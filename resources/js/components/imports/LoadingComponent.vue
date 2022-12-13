<template>
    <div class="d-flex">
        <img src="/img/loading-gif.gif" alt="loading gif"  :width="this.width" />

        <span class="ms-2">{{ text }}</span>
    </div>
</template>

<script>
export default {
    name: "LoadingComponent.vue",

    props: {
        text: String,
        width: Number,
    }
}
</script>

<style scoped>

</style>
