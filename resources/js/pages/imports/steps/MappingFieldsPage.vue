<template>
    <div class="mapping-fields-page">
        <div class="col-md-12">
            <div>
                <h3>Map Fields</h3>
                <p>Map fields in your csv file to contacts table fields</p>
            </div>

            <!--   Scan feedback     -->
            <div class="mapping-fields-page__feedback">
                <div class="alert alert-danger" role="alert" v-if="!errorsAreEmpty">
                    <span class="sr-only">The following errors were found in your csv file:</span>

                    <!--       List Scan Errors         -->
                    <ul>
                        <li v-for="error in errors">{{ error }}</li>
                    </ul>

                    <div class="mapping-fields-page__filename-box mapping-fields-page__filename-box--danger">
                    <span>
                        <span class="fw-bold">File name</span>: {{ csvFilename}}
                    </span>
                        <a href="#" @click="cancelMapping">Upload a different file</a>
                    </div>
                </div>

                <div class="alert alert-success" role="alert" v-else>
<!--                    <p class="fw-bold">Found 1000 contacts in:</p>-->
                    <div class="mapping-fields-page__filename-box mapping-fields-page__filename-box--success">
                    <span>
                        <span class="fw-bold">File name</span>: {{ csvFilename}}
                    </span>
                    </div>
                </div>
            </div>

            <!--   Mappings table     -->
            <div v-if="errorsAreEmpty">
                <p class="fw-bold"> Map your fields to Contacts' fields</p>
                <table class="table table-bordered">
                    <thead>
                    <tr>
                        <th scope="col">CSV File Field</th>
                        <th scope="col">Contacts Field</th>
                    </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(mapping, index) in mappings">
                        <td>{{ mapping.key }}</td>
                        <td>
                            <multiselect
                                v-model="mapping.value"
                                :options="options"
                                track-by="humanReadableName"
                                placeholder="Select field"
                                label="humanReadableName"
                                @select="checkIfIsCustomOption($event, index)"
                                v-if="!mapping.isCustom"
                            ></multiselect>
                            <input type="text"
                                   v-model="mapping.value"
                                   v-if="mapping.isCustom"
                                   class="form-control"
                                   placeholder="Type custom field"
                            >
                        </td>
                    </tr>
                    </tbody>
                </table>
            </div>

            <!--    Footer    -->
            <div class="mapping-fields-page__footer">
                <button class="btn btn-light u-margin-right-small" @click="cancelMapping">Cancel</button>
                <button class="btn btn-primary" @click="goToNextStep" :disabled="!errorsAreEmpty">Continue</button>
            </div>
        </div>
    </div>
</template>

<script>
import Multiselect from 'vue-multiselect'
import Mappings from "../../../core/mappings/Mappings";
import ContactMappingOptions from "../../../core/mappings/ContactMappingOptions"
export default {
    name: "MappingFieldsPage",
    props: ['csvFile', 'csvFilename', 'oldMappings'],
    components: { Multiselect },

    data() {
        return {
            errors: [],
            csvFields: [],
            contactsFields: [],
            mappings: {},
            options: {}
        }
    },

    computed: {
      errorsAreEmpty() {
          return this.errors.length === 0;
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

                this.options = new ContactMappingOptions(this.contactsFields, this.contactsFields).getAll()
                this.mappings = Object.keys(this.oldMappings).length > 0 ? this.oldMappings : new Mappings(this.csvFields).getAll()
            }).catch(error => {
                this.errors = error.response.data.errors['csv_file'] ?? ['Something went wrong. Please contact tech support.']
            })
        },

        cancelMapping() {
            this.$emit('canceled')
        },

        goToNextStep() {
            this.$emit('completed', this.mappings)
        },

        checkIfIsCustomOption(selectedOption, index) {
            console.log('selected option');
            console.log(selectedOption);
            if (selectedOption.key !== 'custom') {
                return
            }

            this.mappings[index].isCustom = true
            this.$nextTick(() => this.mappings[index].value = '')
        }
    },

    mounted() {
        this.scanCSVFile()
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
