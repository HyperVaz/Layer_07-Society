<template>
    <div v-if="isOpen" class="gallery-modal-overlay" @click.self="closeModal">
        <div class="gallery-modal">
            <img :src="imageUrl" :alt="imageTitle" class="gallery-image" @click.stop/>
            <div class="gallery-caption">
                <h2 class="gallery-title">{{ imageTitle }}</h2>
                <p class="gallery-user">By: {{ userName }}</p>
            </div>
            <button class="gallery-close-button" @click="closeModal">
                <i class="fas fa-times"></i>
            </button>
        </div>
    </div>
</template>

<script>
export default {
    name: 'GalleryModal',
    props: {
        imageUrl: {
            type: String,
            required: true
        },
        imageTitle: {
            type: String,
            required: true
        },
        userName: {
            type: String,
            required: true
        },
        isOpen: {
            type: Boolean,
            default: false
        }
    },
    methods: {
        closeModal(){
            this.$emit('close')
        }
    }
};
</script>

<style scoped>
.gallery-modal-overlay {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(0, 0, 0, 0.8);
    display: flex;
    align-items: center;
    justify-content: center;
    z-index: 1000; /* Чтобы модальное окно было сверху всего */
}

.gallery-modal {
    background-color: white;
    padding: 20px;
    border-radius: 8px;
    max-width: 80%; /* Максимальная ширина модального окна */
    max-height: 90%; /* Максимальная высота модального окна */
    overflow: auto;
    position: relative;
}

.gallery-image {
    max-width: 100%;
    display: block;
    margin: 0 auto;
    max-height: 80vh;
    object-fit: contain;
}

.gallery-caption {
    margin-top: 10px;
    text-align: center;
}

.gallery-title {
    font-size: 1.5rem;
    margin-bottom: 5px;
}

.gallery-user {
    font-size: 1rem;
    color: #888;
}
.gallery-close-button{
    position: absolute;
    top: 10px;
    right: 10px;
    background-color: transparent;
    border: none;
    font-size: 24px;
    cursor: pointer;
    color: #333;
    z-index: 10;
}
</style>
