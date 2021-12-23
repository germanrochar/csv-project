<template>
    <div>
        <div class="d-flex justify-content-center p-4">
            <steps-navigator :total-steps="totalSteps" :current-step="currentStep"></steps-navigator>
        </div>

<!--        Add footer here-->
    </div>
</template>

<script>
import StepsNavigator from "../components/imports/StepsNavigator";
export default {
    name: "ImportStepsPage.vue",
    components: { StepsNavigator },

    data() {
        return {
            currentStep: 1,
            totalSteps: 3
        }
    }
}
</script>

<style scoped>

</style>
