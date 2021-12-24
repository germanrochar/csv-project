<template>
    <div class="mapping-fields-page">
        <div class="col-md-12">
            <div>
                <h3>Map Fields</h3>
                <p>Map fields in your csv file to contacts table fields</p>
            </div>

            <!--   Scan feedback     -->
            <div class="mapping-fields-page__feedback">
                <div class="alert alert-danger" role="alert" v-if="!scanErrorsAreEmpty">
                    <span class="sr-only">The following errors were found in your csv file:</span>

                    <!--       List Scan Errors         -->
                    <ul>
                        <li v-for="scanError in scanErrors">{{ scanError }}</li>
                    </ul>

                    <div class="mapping-fields-page__filename-box mapping-fields-page__filename-box--danger">
                    <span>
                        <span class="fw-bold">File name</span>: {{ csvFilename}}
                    </span>
                        <a href="#" @click="cancelMapping">Upload a different file</a>
                    </div>
                </div>

                <div class="alert alert-success" role="alert" v-else>
                    <p class="fw-bold">Found 1000 contacts in:</p>
                    <div class="mapping-fields-page__filename-box mapping-fields-page__filename-box--success">
                    <span>
                        <span class="fw-bold">File name</span>: {{ csvFilename}}
                    </span>
                    </div>
                </div>
            </div>

            <!--   Mappings table     -->
            <div v-if="scanErrorsAreEmpty">
                <p class="fw-bold"> Map your fields to Contacts' fields</p>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">CSV File Field</th>
                        <th scope="col">Contacts Field</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(csvField, index) in csvFields">
                        <td>{{ csvField }}</td>
                        <td>
                            <multiselect v-model="mappedValues[index]" :options="contactsFields" placeholder="Select field"></multiselect>
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!--    Footer    -->
            <div class="mapping-fields-page__footer">
                <button class="btn btn-light u-margin-right-small" @click="cancelMapping">Cancel</button>
                <button class="btn btn-primary" @click="setMappingsAndContinue" :disabled="!scanErrorsAreEmpty">Continue</button>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
export default {
    name: "MappingFieldsPage",
    props: ['csvFile', 'csvFilename', 'mappings'],
    components: { Multiselect },

    data() {
        return {
            scanErrors: [],
            csvFields: [],
            contactsFields: [],
            mappedValues: []
        }
    },

    computed: {
      scanErrorsAreEmpty() {
          return this.scanErrors.length === 0;
      }
    },

    methods: {
        scanCSVFile() {
            let formData = new FormData()
            formData.append('csv_file', this.csvFile)

            axios.post(
                '/scan/csv',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(response => {
                this.csvFields = response.data.csvFields
                this.contactsFields = response.data.contactsFields

                if (Object.keys(this.mappings).length > 0) {
                    this.mappedValues = Object.values(this.mappings)
                } else {
                    // Create an empty array with same length than csv fields
                    this.mappedValues = this.csvFields.map(field => '')
                }
            }).catch(error => {
                this.scanErrors = error.response.data.errors['csv_file'] ?? ['Something went wrong. Please contact tech support.']
            })
        },

        cancelMapping() {
            this.$emit('canceled')
        },

        setMappingsAndContinue() {
            let mappings = {}
            this.csvFields.forEach((field, index) => {
                mappings[field] = this.mappedValues[index]
            })

            this.$emit('completed', mappings)
        }
    },

    mounted() {
        this.scanCSVFile()
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
