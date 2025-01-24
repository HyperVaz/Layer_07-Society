<template>
    <div class="flex flex-col items-center space-y-4 p-4">
        <input
            type="file"
            @change="handleFileUpload"
            accept="image/*"
            ref="fileInput"
            class="hidden"
            id="fileInput"
        />
        <label
            for="fileInput"
            class="cursor-pointer px-4 py-2 bg-blue-500 text-white rounded-md hover:bg-blue-600 focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-opacity-50"
        >
            Выбрать аватар
        </label>
        <button
            @click="uploadAvatar"
            :disabled="!selectedFile"
            class="px-4 py-2 bg-green-500 text-white rounded-md hover:bg-green-600 focus:outline-none focus:ring-2 focus:ring-green-500 focus:ring-opacity-50 disabled:opacity-50 disabled:cursor-not-allowed"
        >
            Загрузить
        </button>

        <div v-if="previewImage" class="mt-4">
            <img :src="previewImage" alt="Предпросмотр аватара" class="max-w-xs rounded-lg shadow-md">
        </div>

        <div v-if="uploadedImage && !previewImage" class="mt-4">
            <img :src="uploadedImage" alt="Загруженный аватар" class="max-w-xs rounded-lg shadow-md" />
        </div>

        <div v-if="successMessage" class="text-green-600 font-semibold">{{ successMessage }}</div>
        <div v-if="errorMessage" class="text-red-600 font-semibold">{{ errorMessage }}</div>
    </div>
</template>

<script>
import axios from 'axios';
export default {
    data() {
        return {
            selectedFile: null,
            uploadedImage: null,
            previewImage: null,
            successMessage: '',
            errorMessage: '',
        };
    },
    methods: {
        handleFileUpload(event) {
            this.selectedFile = event.target.files[0];
            if (this.selectedFile) {
                this.previewImage = URL.createObjectURL(this.selectedFile);
            }
            else{
                this.previewImage = null;
            }
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
                // Очищаем предпросмотр и выбранный файл
                this.clearFileInput();

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
        clearFileInput() {
            if (this.previewImage) {
                URL.revokeObjectURL(this.previewImage);
                this.previewImage = null;
            }
            this.$refs.fileInput.value = null;
            this.selectedFile = null;

        }
    },
    beforeUnmount() {
        // Очищаем URL при размонтировании компонента
        this.clearFileInput();
    }
};
</script>
