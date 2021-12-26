<template>
    <div class="mapping-fields-page">
        <div class="col-md-12">
            <div>
                <h3>Map Fields</h3>
                <p>Map fields in your csv file to contacts table fields</p>
            </div>

            <!--   Feedback     -->
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
                            <th class="mapping-fields-page__table-headings" scope="col">CSV File Field</th>
                            <th class="mapping-fields-page__table-headings" scope="col">Contacts Field</th>
                        </tr>
                    </thead>
                    <tbody>
                    <tr v-for="(mapping, index) in mappings.getAll()">
                        <td>{{ mapping.key }}</td>
                        <td>
                            <div v-if="!mapping.isCustom">
                                <multiselect
                                v-model="mapping.value"
                                :options="options"
                                track-by="humanReadableName"
                                placeholder="Select field"
                                label="humanReadableName"
                                @select="checkIfIsCustomOption($event, index)"
                                ></multiselect>
                            </div>
                            <div v-if="mapping.isCustom" class="d-flex align-items-center">
                                <input type="text"
                                   v-model="mapping.value"
                                   v-if="mapping.isCustom"
                                   class="form-control u-margin-right-small"
                                   placeholder="Type custom field"
                                >
                                <button class="btn btn-secondary" @click="markAsNotCustomField(index)">Cancel</button>
                            </div>
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
                this.mappings = Object.keys(this.oldMappings).length > 0 ? this.oldMappings : new Mappings(this.csvFields)
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
            if (selectedOption.key !== 'custom') {
                return
            }

            this.$nextTick(() => this.mappings.markAsCustom(index))
        },

        markAsNotCustomField(index) {
            this.$nextTick(() => this.mappings.markAsNotCustom(index))
        }
    },

    mounted() {
        this.scanCSVFile()
    }
}
</script>

<style src="vue-multiselect/dist/vue-multiselect.min.css"></style>
