<template>
    <div class="import-steps-page">
        <div class="import-steps-page__header">
            <steps-navigator :total-steps="totalSteps" :current-step="currentStep"></steps-navigator>
        </div>

        <template v-if="currentStep === 1">
            <upload-csv-page @uploaded="storeFileAndGoToMappingsPage"></upload-csv-page>
        </template>

        <template v-if="currentStep === 2">
            <map-fields-page @canceled="removeFileAndGoToUploadPage" :csv-file="csvFile" :csv-filename="csvFilename"></map-fields-page>
        </template>

        <template v-if="currentStep === 3">
            <confirm-mappings-page></confirm-mappings-page>
        </template>

        <div class="import-steps-page__footer">
            <button class="btn btn-light u-margin-right-small" @click="goToPreviousStep()">Go Back</button>
            <button class="btn btn-primary" v-if="!isLastStep" @click="goToNextStep()">Continue</button>
        </div>
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
            csvFilename: ''
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

        storeFileAndGoToMappingsPage(file, filename) {
            this.csvFile = file
            this.csvFilename = filename

            this.goToNextStep()
        },

        removeFileAndGoToUploadPage() {
            this.csvFile = ''
            this.csvFilename = ''

            this.goToPreviousStep()
        }
    }
}
</script>

<style scoped>

</style>
