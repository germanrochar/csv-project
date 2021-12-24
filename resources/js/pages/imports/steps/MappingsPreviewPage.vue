<template>
    <div class="row d-flex justify-content-center mappings-preview-page">
        <div class="col-md-6">
            <h4>Field Mapping Preview</h4>
            <table class="table table-bordered">
                <thead>
                <tr class="table-secondary">
                    <th scope="col">CSV File Field</th>
                    <th scope="col">Contacts Field</th>
                </tr>
                </thead>
                <tbody>
                <tr v-for="(value, key) in mappings">
                    <td>{{ key }}</td>
                    <td>{{ value }}</td>
                </tr>
                </tbody>
            </table>

            <div class="mappings-preview-page__footer">
                <button class="btn btn-light u-margin-right-small">Cancel</button>
                <button class="btn btn-primary">Finish</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MappingsPreviewPage",
    props: ['mappings'],
    data() {
        return {

        }
    },

    mounted() {
        console.log('component mounted')
        console.log(this.mappings);
    }
}
</script>

<style scoped>

</style>
