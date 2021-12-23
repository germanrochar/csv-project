<template>
    <div class="import-steps-page">
        <div class="import-steps-page__header">
            <steps-navigator :total-steps="totalSteps" :current-step="currentStep"></steps-navigator>
        </div>

        <template v-if="currentStep === 1">
            <upload-csv-page @canceled="goToPreviousStep" @uploaded="storeFileAndContinue"></upload-csv-page>
        </template>

        <template v-if="currentStep === 2">
            <map-fields-page @canceled="removeFileAndGoBack" @completed="storeMappingsAndContinue"  :csv-file="csvFile" :csv-filename="csvFilename"></map-fields-page>
        </template>

        <template v-if="currentStep === 3">
            <mappings-preview-page :mappedKeys="mappedKeys" :mappedValues="mappedValues"></mappings-preview-page>
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
            mappedKeys: [],
            mappedValues: []
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

        removeFileAndGoBack() {
            this.csvFile = ''
            this.csvFilename = ''

            this.goToPreviousStep()
        },

        storeMappingsAndContinue(mappedKeys, mappedValues) {
            this.mappedKeys = mappedKeys
            this.mappedValues = mappedValues

            this.goToNextStep()
        }
    }
}
</script>

<style scoped>

</style>
