<template>
    <div class="w-96 mx-auto">
        <div>
            <input v-model="email" type="email" placeholder="email" class="w-96 p-1 mb-2 border border-inherit rounded-lg">
        </div>
        <div>
            <input v-model="password" type="password" placeholder="password" class="w-96 p-1 mb-2 border border-inherit rounded-lg">
        </div>
        <button @click.prevent="login"
                class="btn cursor-pointer block float-right mx-auto w-32 p-1 bg-indigo-600 text-white rounded-lg border">Log in
        </button>
    </div>

</template>

<script>
export default {
    name: "Login",

    data() {
        return {
            email: null,
            password: null,
        }
    },

    methods: {
        login() {
            axios.get('/sanctum/csrf-cookie')
                .then(response => {
                    axios.post('/login', {email: this.email, password: this.password})
                        .then(r => {
                            localStorage.setItem('x_xsrf_token', r.config.headers['X-XSRF-TOKEN'])
                            this.$router.push({name: 'user.personal'})
                        })
                        .catch(err => {
                        })
                })
        }
    }
}
</script>

<style scoped>

</style>
