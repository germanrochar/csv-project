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
                <button class="btn btn-light u-margin-right-small" @click="goToPreviousPage" :disabled="importingInProgress">Go Back</button>
                <button class="btn btn-primary" @click="completeImport" :disabled="importingInProgress">Finish</button>
            </div>
        </div>
    </div>
</template>

<script>
import Mappings from "../../../core/mappings/Mappings";

export default {
    name: "MappingsPreviewPage",
    props: {
        mappings: Mappings,
        csvFile: File
    },

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
            const mappingKeys = this.mappings.getMappingKeys()
            const mappingValues = this.mappings.getMappingValues()

            if (mappingKeys.length !== mappingValues.length) {
                this.errors = {'mappings': ['Something went wrong. Please contact tech support.']}
                return;
            }

            const mappings = {};
            mappingKeys.forEach((key, index) => {
                mappings[key] = mappingValues[index];
            })

            let formData = new FormData()
            formData.append('csv_file', this.csvFile)
            formData.append('mappings', JSON.stringify(mappings))

            this.importingInProgress = true
            axios.post(
                '/imports/contacts/csv',
                formData
            ).then(() => {
                this.$emit('imported')
            }).catch(error => {
                if (error.response.status === 400) {
                    this.errors = {'csv_fields': error.response.data}
                } else {
                    this.errors = error.response.data.errors ?? []
                }
            }).finally(() => {
                this.importingInProgress = false
            })
        }
    }
}
</script>
