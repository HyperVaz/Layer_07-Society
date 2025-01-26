<template>
    <footer class="footer text-center flex justify-between">
            <div class="footer-content size-full flex justify-between mt-7 mb-55 ">
                <div class="footer-section text-center">
                    <h4>Контакты</h4>
                    <a class="hover:underline" href="mailto:DeBlank@yandex.ru">Email: DeBlank@yandex.ru</a>
                </div>
                <div class="footer-section">
                    <h4>Ссылки</h4>
                    <ul>
                        <router-link class="hover:underline" v-if="!token" :to="{name: 'privacy-policy'}">Политика
                            конфиденциальности</router-link>
                    </ul>
                </div>
                <div class="copyright">
                    © 2025 Contora Corp. <br>
                    Все права защищены.
                </div>
            </div>

    </footer>
</template>


<script>
export default {
    name: "Footer",
    data() {

    },
    mounted() {

    },
    methods: {

    }

}
</script>

<style scoped>

</style>
