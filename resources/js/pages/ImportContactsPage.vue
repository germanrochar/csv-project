<template>
    <div>
        <template v-if="!showImportSteps">
            <div class="center-content">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h3>Import CSV File</h3>
                    <p>Import your contacts from a csv file</p>
                    <button type="button" class="btn btn-primary flex-grow-*" @click="showSteps">Import</button>
                </div>
            </div>
        </template>

        <template v-if="showImportSteps">
            <div class="import-steps-page container">
                <div class="import-steps-page__header">
                    <steps-navigator :total-steps="totalSteps" :current-step="currentStep"></steps-navigator>
                </div>

                <template v-if="currentStep === 1">
                    <upload-csv-page
                        @canceled="hideImportSteps"
                        @uploaded="storeFileAndContinue"
                    ></upload-csv-page>
                </template>

                <template v-if="currentStep === 2">
                    <mapping-fields-page
                        @canceled="removeMappingsAndFileAndGoBack"
                        @completed="storeMappingsAndContinue"
                        :csv-file="csvFile"
                        :csv-filename="csvFilename"
                        :old-mappings="mappings"
                    ></mapping-fields-page>
                </template>

                <template v-if="currentStep === 3">
                    <mappings-preview-page
                        @go-back='goToPreviousStep'
                        @imported="goToNextStep"
                        :mappings="mappings"
                        :csv-file="csvFile"
                    ></mappings-preview-page>
                </template>

                <template v-if="currentStep === 4">
                    <mappings-completed-page></mappings-completed-page>
                </template>
            </div>
        </template>
    </div>

</template>

<script>
import StepsNavigator from "../components/imports/StepsNavigator";
import MappingFieldsPage from "../pages/imports/steps/MappingFieldsPage.vue";
import MappingsCompletedPage from "../pages/imports/steps/MappingsCompletedPage.vue";
import MappingsPreviewPage from "../pages/imports/steps/MappingsPreviewPage.vue";
import UploadCsvPage from "../pages/imports/steps/UploadCSVPage.vue";

export default {
    name: "ImportContactsPage",
    components: {
        StepsNavigator,
        UploadCsvPage,
        MappingFieldsPage,
        MappingsPreviewPage,
        MappingsCompletedPage
    },

    data() {
        return {
            showImportSteps: true,
            currentStep: 4,
            totalSteps: 4,
            csvFile: '',
            csvFilename: '',
            mappings: {}
        }
    },

    computed: {
        isLastStep() {
            return this.currentStep === this.totalSteps
        },
        isFirstStep() {
            return this.currentStep === 1
        }
    },

    methods: {
        showSteps() {
            this.showImportSteps = true;
        },
        hideImportSteps() {
            this.showImportSteps = false;
        },

        goToNextStep() {
            if (this.isLastStep) {
                alert('Something went wrong. Please contact tech support.')
                return
            }

            this.currentStep++
        },

        goToPreviousStep() {
            if (this.isFirstStep) {
                alert('Something went wrong. Please contact tech support.')
                return
            }

            this.currentStep--
        },

        storeFileAndContinue(file, filename) {
            this.csvFile = file
            this.csvFilename = filename

            this.goToNextStep()
        },

        removeMappingsAndFileAndGoBack() {
            this.csvFile = ''
            this.csvFilename = ''
            this.mappings = {}

            this.goToPreviousStep()
        },

        storeMappingsAndContinue(mappings) {
            this.mappings = mappings

            this.goToNextStep()
        }
    }
}
</script>
