<template>
    <div class="container ">
        <div>
            <h3>Map Fields</h3>
            <p>Map fields in your csv file to contacts table fields</p>
        </div>

        <!--   Scan feedback     -->
        <div class="map-fields-page__feedback">
            <div class="alert alert-danger" role="alert" v-if="!scanErrorsAreEmpty">
                <span class="sr-only">The following errors were found in your csv file:</span>

                <!--       List Scan Errors         -->
                <ul>
                    <li v-for="scanError in scanErrors">{{ scanError }}</li>
                </ul>

                <div class="map-fields-page__filename-box map-fields-page__filename-box--danger">
                    <span>
                        <span class="fw-bold">File name</span>: {{ csvFilename}}
                    </span>
                    <a href="#" @click="cancelMapping()">Upload a different file</a>
                </div>
            </div>

            <div class="alert alert-success" role="alert" v-else>
                <p class="fw-bold">Found 1000 contacts in:</p>
                <div class="map-fields-page__filename-box map-fields-page__filename-box--success">
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
                    <tr v-for="(csvField) in csvFields">
                        <td>{{ csvField }}</td>
                        <td>
                            <multiselect v-model="values[csvField]" :options="contactsFields" placeholder="Select field"></multiselect>
                        </td>
                    </tr>
                </tbody>
            </table>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
export default {
    name: "MapFieldsPage",
    props: ['csvFile', 'csvFilename'],
    components: { Multiselect },

    data() {
        return {
            scanErrors: [],
            csvFields: [],
            contactsFields: [],
            values: []
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
                console.log(response.data);
                this.csvFields = response.data.csvFields
                this.contactsFields = response.data.contactsFields
            }).catch(error => {
                this.scanErrors = error.response.data.errors['csv_file'] // TODO: Assert this param exists.
            })
        },

        cancelMapping() {
            this.$emit('canceled')
        }
    },

    mounted() {
        this.scanCSVFile()
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
