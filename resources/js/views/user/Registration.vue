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
            <input v-model="password_confirmation" type="password" placeholder="password_confirmation" class="w-96 p-1 mb-2 border border-inherit rounded-lg">
        </div>
        <input @click.prevent="register" type="submit" value="register" class="block float-right mx-auto w-32 p-1 bg-sky-400 text-white rounded-lg">
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
        }
    },

    methods: {
        register() {
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
        }
    }

}
</script>

<style scoped>

</style>
