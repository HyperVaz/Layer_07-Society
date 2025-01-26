<template>
    <div class="w-96 mx-auto">
        <div>
            <input v-model="name" type="name" placeholder="name" class="w-96 p-1 mb-2 border border-inherit rounded-lg">
        </div>
        <div>
            <input v-model="email" type="email" placeholder="email" class="w-96 p-1 mb-2 border border-inherit rounded-lg">
        </div>
        <div>
            <input v-model="password" type="password" placeholder="password" class="w-96 p-1 mb-2 border border-inherit rounded-lg">
        </div>
        <div>
            <input v-model="password_confirmation" type="password" placeholder="password confirmation"
                   class="w-96 p-1 mb-2 border border-inherit rounded-lg">
        </div>
        <button @click.prevent="showModal"
                class="btn cursor-pointer text-center block float-right mx-auto w-32 p-1 bg-indigo-600 text-white rounded-lg border">Register</button>

        <!-- Модальное окно -->
        <div v-if="modalVisible" class="fixed inset-0 bg-gray-600 bg-opacity-50 flex justify-center items-center">
            <div class="bg-white p-6 rounded-lg w-96">
                <p>Продолжая, вы соглашаетесь с нашей <router-link :to="{name: 'privacy-policy'}" class="text-blue-500 underline">политикой</router-link> сбора и обработки данных.</p>
                <div class="mt-4 flex items-center">
                    <input type="checkbox" v-model="agreed" id="agreement-checkbox" class="mr-2">
                    <label for="agreement-checkbox">Я согласен</label>
                </div>
                <div class="mt-4 flex justify-end">
                    <button @click.prevent="closeModal" class="btn cursor-pointer text-center mx-2 w-24 p-1 bg-gray-400 text-white rounded-lg border">Отмена</button>
                    <button @click.prevent="register" :disabled="!agreed"
                            class="btn cursor-pointer text-center mx-2 w-24 p-1 bg-indigo-600 text-white rounded-lg border">Подтвердить</button>
                </div>
            </div>
        </div>
    </div>

</template>

<script>
export default {
    name: "Registration",

    data() {
        return {
            name: null,
            email: null,
            password: null,
            password_confirmation: null,
            modalVisible: false,
            agreed: false,
        }
    },

    methods: {
        showModal() {
            this.modalVisible = true;
        },
        closeModal(){
            this.modalVisible = false;
            this.agreed = false;
        },
        register() {
            if (!this.agreed) return
            axios.get('/sanctum/csrf-cookie')
                .then(() => {
                    axios.post('/register', {
                        name: this.name,
                        email: this.email,
                        password: this.password,
                        password_confirmation: this.password_confirmation
                    })
                        .then( res => {
                            localStorage.setItem('x_xsrf_token', res.headers['x-xsrf-token']);
                            this.$router.push({name: 'user.personal'})
                        })
                        .catch(error => {
                            // Обработка ошибки при регистрации
                            console.error("Registration failed:", error);
                            //Можно показать пользователю сообщение об ошибке
                        });
                })
                .catch(error => {
                    // Обработка ошибки при получении куки
                    console.error("Error getting csrf-cookie:", error);
                    // Можно показать пользователю сообщение об ошибке
                });
            this.modalVisible = false;
        }
    }
}
</script>

<style scoped>

</style>
