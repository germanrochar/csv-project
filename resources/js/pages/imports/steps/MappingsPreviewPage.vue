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
            <p v-show="importingInProgress">Importing contacts...</p>
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
                <button class="btn btn-primary" @click="completeImport" :disabled="importingInProgress">Finish</button>
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
            errors: [],
            importingInProgress: false
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
            let csvFields = this.mappings.getMappingKeys()
            let contactFields = this.mappings.getMappingValues()
            let customCsvFields = this.mappings.getCustomMappingKeys()
            let customContactFields = this.mappings.getCustomMappingValues()

            let formData = new FormData()
            formData.append('csv_file', this.csvFile)
            formData.append('contact_fields', JSON.stringify(contactFields))
            formData.append('custom_contact_fields', JSON.stringify(customContactFields))
            formData.append('csv_fields', JSON.stringify(csvFields))
            formData.append('custom_csv_fields', JSON.stringify(customCsvFields))

            this.importingInProgress = true
            axios.post(
                '/imports/contacts/csv',
                formData
            )
            .then(response => {
                this.importingInProgress = false
                this.$emit('imported')
            }).catch(error => {
                // This can definitely be improved but I'll leave it like this to speed up the delivery of the project.
                if (error.response.status === 400) {
                    this.errors = {'csv_fields': error.response.data}
                } else {
                    this.errors = error.response.data.errors ?? []
                }
            })
        }
    }
}
</script>
