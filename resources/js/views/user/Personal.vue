<template>
    <div class="w-96 mx-auto">
        <div>
            <input v-model="title" class="w-96 mb-3 rounded-3xl border p-2 border-slate-300" type="text" placeholder="">
        </div>
        <div>
            <textarea v-model="content" class="w-96 mb-3 rounded-3xl border p-2 border-slate-300"
                      placeholder=""></textarea>
        </div>
        <div>
            <a href="#"
               class="block p-2 w-32 h-10 text-center rounded-3xl bg-green-600 text-white hover:bg-white hover:border hover:border-green-600 hover:text-green-600 box-border">Publish</a>
        </div>
    </div>
</template>


<script>
import Post from "../../components/Post.vue";
import Stat from "../../components/Stat.vue";
export default {
    name: "Personal",
    data() {
        return {

        }
    },
}

</script>

<style scoped>

</style>
