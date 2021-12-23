<template>
    <div>
        <span>Confirm Mappings</span>

        <div class="map-fields-page__footer">
            <button class="btn btn-light u-margin-right-small">Cancel</button>
            <button class="btn btn-primary">Continue</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "MappingsPreviewPage",
    props: ['mappedKeys', 'mappedValues'],
    data() {
        return {

        }
    },

    mounted() {
        console.log('component mounted')
        console.log(this.mappedKeys);
        console.log(this.mappedValues);
    }
}
</script>

<style scoped>

</style>
