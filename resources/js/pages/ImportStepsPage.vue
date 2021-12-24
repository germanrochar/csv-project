<template>
    <div class="import-steps-page container">
        <div class="import-steps-page__header">
            <steps-navigator :total-steps="totalSteps" :current-step="currentStep"></steps-navigator>
        </div>

        <template v-if="currentStep === 1">
            <upload-csv-page
                @canceled="goToPreviousStep"
                @uploaded="storeFileAndContinue"
            ></upload-csv-page>
        </template>

        <template v-if="currentStep === 2">
            <mapping-fields-page
                @canceled="removeDataAndGoBack"
                @completed="storeMappingsAndContinue"
                :csv-file="csvFile"
                :csv-filename="csvFilename"
                :old-mappings="mappings"
            ></mapping-fields-page>
        </template>

        <template v-if="currentStep === 3">
            <mappings-preview-page
                @go-back='goToPreviousStep'
                :mappings="mappings"
                :csv-file="csvFile"
            ></mappings-preview-page>
        </template>
    </div>
</template>

<script>
import StepsNavigator from "../components/imports/StepsNavigator";
export default {
    name: "ImportStepsPage",
    components: { StepsNavigator },

    data() {
        return {
            currentStep: 1,
            totalSteps: 3,
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
        goToNextStep() {
            if (this.isLastStep) {
                alert('Something went wrong. Please contact tech support.')
                return
            }

            this.currentStep++
        },

        goToPreviousStep() {
            if (this.isFirstStep) {
                this.$emit('canceled')
                return
            }

            this.currentStep--
        },

        storeFileAndContinue(file, filename) {
            this.csvFile = file
            this.csvFilename = filename

            this.goToNextStep()
        },

        removeDataAndGoBack() {
            this.csvFile = ''
            this.csvFilename = ''
            this.mappings = ''

            this.goToPreviousStep()
        },

        storeMappingsAndContinue(mappings) {
            this.mappings = mappings

            this.goToNextStep()
        }
    }
}
</script>

<style scoped>

</style>
