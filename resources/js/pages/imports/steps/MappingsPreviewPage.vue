<template>
    <div class="row d-flex justify-content-center mappings-preview-page">
        <div class="col-md-6">
            <div class="">
                <div class="alert alert-danger" role="alert" v-if="!errorsAreEmpty">
                    <span class="sr-only">Error:</span>

                    <ul>
                        <template v-for="errorKey in errors">
                            <li v-for="error in errorKey">
                                {{ error }}
                            </li>
                        </template>
                    </ul>
                </div>
            </div>

            <h4>Field Mapping Preview</h4>
            <table class="table table-bordered">
                <thead>
                    <tr class="table-secondary">
                        <th scope="col">CSV File Field</th>
                        <th scope="col">Contacts Field</th>
                    </tr>
                </thead>
                <tbody>
                <tr v-for="mapping in mappings.getAll()">
                    <td>{{ mapping.key }}</td>
                    <td>
                        <template v-if="typeof mapping.value === 'object'">
                            {{ mapping.value.humanReadableName }}
                        </template>
                        <template v-else>
                            {{ mapping.value }}
                        </template>
                    </td>
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
            errors: []
        }
    },

    computed: {
        errorsAreEmpty() {
            return this.errors.length === 0
        }
    },

    methods: {
        goToPreviousPage() {
            this.$emit('go-back')
        },

        completeImport() {
            let mappingKeys = this.mappings.getMappingKeys()
            let mappingValues = this.mappings.getMappingValues()

            let formData = new FormData()
            formData.append('csv_file', this.csvFile)
            formData.append('mapping_keys', JSON.stringify(mappingKeys))
            formData.append('mapping_values', JSON.stringify(mappingValues))

            axios.post(
                '/imports/contacts/csv',
                formData
            )
            .then(response => {
                console.log(response.data);
            }).catch(error => {
                this.errors = error.response.data.errors ?? []
            })
        }
    }
}
</script>
