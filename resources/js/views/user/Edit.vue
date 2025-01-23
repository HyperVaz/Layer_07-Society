<template>
    <div class="avatar-upload">
        <h2>Загрузите ваш аватар</h2>

        <div class="avatar-preview" v-if="avatarPreview">
            <img :src="avatarPreview" alt="Предпросмотр аватара" />
        </div>
        <div v-else class="avatar-preview">
            <p>Предпросмотр аватара</p>
        </div>

        <input
            type="file"
            id="avatar"
            accept="image/*"
            @change="handleFileChange"
        />
        <label for="avatar" class="upload-button">Выбрать файл</label>

        <button @click="uploadAvatar" :disabled="!selectedFile || isUploading">
            {{ isUploading ? 'Загрузка...' : 'Загрузить' }}
        </button>

        <div v-if="uploadSuccessMessage" class="success-message">
            {{ uploadSuccessMessage }}
            <img v-if="newAvatarUrl" :src="newAvatarUrl" alt="Новый аватар" />
        </div>

        <div v-if="uploadErrorMessage" class="error-message">
            {{ uploadErrorMessage }}
        </div>
    </div>
</template>

<script>
import axios from 'axios'; // Установите axios: npm install axios

export default {
    data() {
        return {
            selectedFile: null,
            avatarPreview: null,
            isUploading: false,
            uploadSuccessMessage: '',
            uploadErrorMessage: '',
            newAvatarUrl: null, // Для хранения URL нового аватара
            userId: null // Получите ID текущего пользователя (замените на способ, описанный ранее)
        };
    },
    async mounted() {
        // Получите ID пользователя при монтировании компонента
        // Пример:  если у вас this.auth.user.id
        // Важно! Проверьте, что this.auth существует и что user.id тоже.
        // Например:
        try{
            const user = await axios.get('/api/user');
            this.userId = user.data.id
        } catch(err) {
            console.log('Ошибка получения текущего пользователя',err)
        }


        // Пример: Если userID хранится в Vuex
        // this.userId = this.$store.getters.userId;

        // Пример:  Если userID хранится в Cookie
        // this.userId = Cookies.get('userId');

        if (!this.userId) {
            console.error("ID пользователя не найден!");
        }
    },
    methods: {
        handleFileChange(event) {
            this.selectedFile = event.target.files[0];

            // Создаем URL для предпросмотра
            if (this.selectedFile) {
                this.avatarPreview = URL.createObjectURL(this.selectedFile);
            } else {
                this.avatarPreview = null;
            }
        },
        async uploadAvatar() {
            this.isUploading = true;
            this.uploadSuccessMessage = '';
            this.uploadErrorMessage = '';
            this.newAvatarUrl = null;

            try {
                if (!this.userId) {
                    this.uploadErrorMessage = "Не удалось получить ID пользователя";
                    return;
                }

                const formData = new FormData();
                formData.append('avatar', this.selectedFile);

                //  Используем шаблонные строки
                const response = await axios.get(`/api/users/${this.userId}/avatar`, formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                    },
                });

                if (response.status === 200) {
                    this.uploadSuccessMessage = 'Аватар успешно загружен!';
                    this.newAvatarUrl = response.data.avatar_url;  // Получаем URL нового аватара
                    this.selectedFile = null;
                    this.avatarPreview = null;
                } else {
                    this.uploadErrorMessage = response.data.message || 'Произошла ошибка при загрузке аватара.';
                }
            } catch (error) {
                this.uploadErrorMessage = 'Произошла ошибка при загрузке аватара.';
                console.error('Ошибка загрузки аватара:', error);
            } finally {
                this.isUploading = false;
            }
        },
    },
};
</script>

<style scoped>
.avatar-upload {
    max-width: 400px;
    margin: 0 auto;
    padding: 20px;
    border: 1px solid #ccc;
    border-radius: 5px;
}

.avatar-upload h2 {
    text-align: center;
    margin-bottom: 20px;
}

.avatar-preview {
    width: 150px;
    height: 150px;
    border: 1px solid #ddd;
    margin-bottom: 15px;
    display: flex;
    justify-content: center;
    align-items: center;
}

.avatar-preview img {
    max-width: 100%;
    max-height: 100%;
    object-fit: cover; /*  Важно, чтобы изображение не искажалось */
}

input[type="file"] {
    display: none; /* Скрываем стандартный input type="file" */
}

.upload-button {
    background-color: #4CAF50;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
    display: inline-block;
    margin-bottom: 10px;
}

.upload-button:hover {
    background-color: #3e8e41;
}

button {
    background-color: #007bff;
    color: white;
    padding: 10px 15px;
    border: none;
    border-radius: 4px;
    cursor: pointer;
}

button:hover {
    background-color: #0056b3;
}

button:disabled {
    background-color: #ccc;
    cursor: not-allowed;
}

.success-message {
    color: green;
    margin-top: 10px;
}

.success-message img {
    width: 50px;
    height: 50px;
    margin-left: 10px;
    vertical-align: middle;
}

.error-message {
    color: red;
    margin-top: 10px;
}
</style>
