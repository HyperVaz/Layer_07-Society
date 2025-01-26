<template>
    <Loader :loading="loading" />
    <div class="w-96 mx-auto">
        <div v-if="users">
            <div
                class="flex justify-between items-center mb-6 pb-6 border-b border-gray-400"
                v-for="user in users"
                :key="user.id"
            >

                <router-link class="flex w-full" :to="{ name: 'user.show', params: { id: user.id } }">
                    <div class="flex">
                        <div class="user_avatar" v-if="user.avatar">
                            <img class="rounded-full"
                                 :src="user.avatar.path"
                                 alt="Аватар пользователя"
                                 style="max-width: 100px"
                            />
                        </div>
                        <div class="user_avatar" v-else>
                            <img width="100" class="rounded-full" src="../../../../public/storage/404.jpg" alt="">
                        </div>
                    </div>
                    <p class="m-auto">{{ user.name }}</p>
                </router-link>
                <a
                    @click.prevent="toggleFollowing(user)"
                    :class="[
            'block p-2 w-32 text-center text-sm rounded-3xl',
            user.is_followed
              ? 'bg-white text-sky-500 border border-sky-500'
              : 'bg-sky-500 text-white',
          ]"
                    href="#"
                >
                    {{ user.is_followed ? "Unfollowed" : "Follow" }}
                </a>
            </div>
        </div>
    </div>
</template>

<script>
import AvatarUpload from "../../components/AvatarUpload.vue";
import Loader from "../../components/Loader.vue";

export default {
    name: "Index",

    data() {
        return {
            users: [],
            loading: false,
        };
    },
    components: {
        Loader,
        AvatarUpload,
    },
    mounted() {
        this.getUsers();
    },

    methods: {
        async getUsers() {
            this.loading = true;
            try {
                const response = await axios.get("/api/users/");
                this.users = response.data.data;
            } catch (error) {
                console.error("Ошибка загрузки данных пользователей:", error);
                // Обработка ошибок, если необходимо
            } finally {
                this.loading = false;
            }
        },
        toggleFollowing(user) {
            axios
                .post(`/api/users/${user.id}/toggle_following`)
                .then((res) => {
                    user.is_followed = res.data.is_followed;
                });
        },
    },
};
</script>

<style scoped>
</style>
