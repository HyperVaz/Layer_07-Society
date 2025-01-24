<template>
    <div class="loader-container" v-if="loading">
        <img src="../../img/Дон%20Дигидончик.png" class="spinner">
        <p class="loader-text">Загрузка...</p>
    </div>
</template>

<script>
export default {
    props: {
        loading: {
            type: Boolean,
            default: false,
        },
    },
};
</script>

<style scoped>
.loader-container {
    position: fixed;
    top: 0;
    left: 0;
    width: 100%;
    height: 100%;
    background-color: rgba(255, 255, 255, 0.8);
    display: flex;
    flex-direction: column;
    justify-content: center;
    align-items: center;
    z-index: 1000;
}

.spinner {
    border-radius: 50%;
    width: 150px;
    height: 150px;
    animation: spin 2s linear infinite;
    margin-bottom: 10px;
}

@keyframes spin {
    0% {
        transform: rotate(0deg);
    }
    100% {
        transform: rotate(360deg);
    }
}
.loader-text{
    font-size: 1.2rem;
    font-weight: bold;
}
</style>
