<template>
    <div class="import-steps-page">
        <div class="import-steps-page__header">
            <steps-navigator :total-steps="totalSteps" :current-step="currentStep"></steps-navigator>
        </div>

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
            totalSteps: 3
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
        }
    }
}
</script>

<style scoped>

</style>
