<template>
    <div class="w-96 mx-auto">
        <Stat :stats="stats"></Stat>
        <div>
            <input v-model="title" class="w-96 mb-5 rounded-3xl border p-2 border-slate-300" type="text"
                   placeholder="Write your post title!">
            <div v-if="errors.title">
                <p v-for="error in errors.title" class="text-red-500">{{ error }}</p>
            </div>

        </div>
        <div>
            <textarea v-model="content" class="w-96 mb-3 rounded-3xl border p-2 border-slate-300"
                      placeholder="What can you say to us?"></textarea>
            <div v-if="errors.content">
                <p v-for="error in errors.content" class="text-red-500">{{ error }}</p>
            </div>

        </div>
        <div class="flex mb-3 justify-between">
            <div>
                <input @change="storeImage" ref="file" type="file" class="hidden">
                <a href="#"
                   class="block p-2 w-32 h-10 text-center rounded-3xl bg-orange-600 text-white hover:bg-white hover:border hover:border-green-600 hover:text-black-600 box-border"
                   @click.prevent="selectFile()">Image</a>
            </div>
            <div>

            </div>



            <div>
                <a @click.prevent="store" href="#"
                   class="block p-2 w-32 h-10 text-center rounded-3xl bg-black-600 text-white hover:bg-white hover:border hover:border-green-600 hover:text-green-600 box-border">Publish</a>
            </div>
        </div>
        <div v-if="image">
            <img :src="image.url" alt="preview">
            <a class="text-red-600" v-if="image" @click.prevent="image = null" href="#">Cancel/back</a>
        </div>
    </div>

    <div v-if="posts">
        <h1 class="mb-8 pb-8 border-b border-gray-400">Posts</h1>
        <Post v-for="post in posts" :post="post"></Post>
    </div>


</template>


<script>
import Post from "../../components/Post.vue";
import Stat from "../../components/Stat.vue";

export default {
    name: "Personal",
    data() {
        return {
            title: '',
            content: '',
            image: null,
            posts: [],
            errors: [],
            stats: [],

        }
    },
    components: {
        Post, Stat
    },
    mounted() {
        this.getPosts();
        this.getStats();
    },
    methods: {
        getStats() {
            axios.post('/api/users/stats', { id: null })
                .then(res => {
                    console.log(res);
                    this.stats = res.data.data;
                })
        },
        getPosts() {
            axios.get('/api/posts')
                .then(res => {
                    this.posts = res.data.data
                })
        },

        store() {
            const id = this.image ? this.image.id : null
            axios.post('/api/posts', {title: this.title, content: this.content, image_id: id})
                .then(res => {
                    this.title = ''
                    this.content = ''
                    this.image = null
                    this.posts.unshift(res.data.data)
                })
                .catch(e => {
                    this.errors = e.response.data.errors;

                })

        },
        selectFile() {
            this.fileInput = this.$refs.file;
            this.fileInput.click();
        },
        storeImage(e) {
            const file = e.target.files[0]
            const formData = new FormData()
            formData.append('image', file)

            axios.post('/api/post_images', formData)
                .then(res => {
                    this.image = res.data.data;
                })
        },


    }
}

</script>

<style scoped>

</style>
