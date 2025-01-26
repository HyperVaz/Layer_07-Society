<template>
    <body class="mb-22">
    <header class="header">
        <div class="logo">
            <img src="../img/209355678.png" class="header__logo" alt="logo">
        </div>
        <div class="container">
            <h1 class="text-center text-3xl pt-2.5" v-if="!token">Please, log in or register</h1>

            <nav class="max-w-100 flex header__router text-amber-300 flex p-8 justify-around">
                <router-link class="nav__link login" v-if="!token" :to="{ name: 'user.login'}">LOGIN</router-link>
                <router-link class="nav__link" v-if="token" :to="{ name: 'user.index'}">Users</router-link>
                <router-link class="nav__link" v-if="token" :to="{ name: 'user.feed'}">Feed</router-link>
                <router-link class="nav__link" v-if="token" :to="{ name: 'user.edit'}">Edit</router-link>
                <router-link class="nav__link" v-if="!token" :to="{name: 'privacy-policy'}">POLICY</router-link>
                <router-link class="nav__link" v-if="token" :to="{ name: 'user.personal'}">Personal</router-link>
                <router-link class="nav__link register" v-if="!token" :to="{ name: 'user.registration'}">REGISTRATION
                </router-link>
                <a class="nav__link" v-if="token" @click.prevent="logout" href="#">Logout</a>
            </nav>

            <router-view></router-view>
        </div>


    </header>

    </body>
    <Footer></Footer>
</template>


<script>
export default {
    name: "App",
    data() {
        return {
            token: null
        }
    },
    mounted() {
        this.getToken()
    },
    watch: {
        $route(to, from) {
            this.getToken()
        }
    },
    methods: {
        getToken() {
            this.token = localStorage.getItem('x_xsrf_token');
        },
        logout() {
            axios.post('/logout')
                .then(res => {
                    localStorage.removeItem('x_xsrf_token');
                    this.$router.push({name: 'user.login'});
                });
        },
    }

}
</script>

<style scoped>

</style>
