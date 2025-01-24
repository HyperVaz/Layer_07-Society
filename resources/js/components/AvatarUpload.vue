<template>
    <div>
        <input type="file" @change="handleFileUpload" accept="image/*" ref="fileInput" />
        <button @click="uploadAvatar" :disabled="!selectedFile">Загрузить</button>
        <div v-if="uploadedImage">
            <img :src="uploadedImage" alt="Загруженный аватар" style="max-width: 200px;">
        </div>
        <div v-if="successMessage" style="color: green;">{{successMessage}}</div>
        <div v-if="errorMessage" style="color: red;">{{ errorMessage }}</div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            selectedFile: null,
            uploadedImage: '',
            successMessage: '',
            errorMessage: ''
        };
    },
    methods: {
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0];
        },
        async uploadAvatar() {
            this.successMessage = '';
            this.errorMessage = '';
            if (!this.selectedFile) {
                this.errorMessage = 'Выберите файл для загрузки';
                return;
            }
            const formData = new FormData();
            formData.append('avatar', this.selectedFile);

            try {
                const response = await axios.post('/api/avatars', formData, {
                    headers: {
                        'Content-Type': 'multipart/form-data',
                        'Authorization': `Bearer ${localStorage.getItem('authToken')}`
                    }
                });

                this.uploadedImage = response.data.path;
                this.successMessage = response.data.message;
                this.$refs.fileInput.value = null;
                this.selectedFile = null;


            } catch (error) {
                if (error.response && error.response.data && error.response.data.errors) {
                    this.errorMessage = Object.values(error.response.data.errors).flat().join(', ');
                }
                else
                {
                    this.errorMessage = 'Ошибка при загрузке аватара.';
                }
                console.error('Ошибка загрузки аватара:', error);
            }
        },
    }
};
</script>
