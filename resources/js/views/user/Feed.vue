<template>
    <Loader :loading="loading" />
    <div class="w-96 mx-auto">
        <div v-if="posts">
            <h1 class="mb-8 pb-8 border-b border-gray-400">Posts</h1>
            <Post v-for="post in posts" :post="post"></Post>
        </div>
    </div>
</template>

<script>
import Post from "../../components/Post.vue";
import Loader from "../../components/Loader.vue";

export default {
    name: "Feed",

    data() {
        return {
            posts: [],
            loading: false

        }
    },

    components: {
        Loader,
        Post,
    },
    mounted() {
        this.getPosts()
    },

    methods: {

        async getPosts() {
            this.loading = true; // Запускаем загрузчик
            try {
                const res = await axios.get('/api/users/following_posts');
                this.posts = res.data.data;
                console.log(this.posts);
            } catch (error) {
                console.error("Ошибка при загрузке постов:", error);
                // Здесь можно добавить логику отображения ошибки пользователю
                // Например, вывести уведомление
                this.$notify({
                    type: 'error',
                    title: 'Error',
                    text: 'Failed to load posts'
                })
            } finally {
                this.loading = false; // Останавливаем загрузчик в любом случае
            }
        },


    }
}
</script>

<style scoped>

</style>
