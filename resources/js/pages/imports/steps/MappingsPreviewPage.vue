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
                <button class="btn btn-light u-margin-right-small" @click="goToPreviousPage">Go Back</button>
                <button class="btn btn-primary" @click="completeImport">Finish</button>
            </div>
        </div>
    </div>
</template>

<script>
export default {
    name: "MappingsPreviewPage",
    props: ['mappings', 'csvFile'],

    data() {
        return {

        }
    },

    methods: {
        goToPreviousPage() {
            this.$emit('go-back')
        },

        completeImport() {
            let formData = new FormData()
            formData.append('csv_file', this.csvFile)
            for(let key in this.mappings) {
                formData.append(key, this.mappings[key])
            }
            axios.post(
                '/imports/contacts/csv',
                formData
            )
            .then(response => {
                console.log(response.data);
            }).catch(error => {
                console.log(error.response);
            })
        }
    },

    created() {
        console.log(this.csvFile);
        console.log(this.mappings);
    }
}
</script>
