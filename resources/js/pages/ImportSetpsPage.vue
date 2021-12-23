<template>
    <div>
        <div class="d-flex justify-content-center p-4">
            <import-steps-navigator :total-steps="totalSteps" :current-step="currentStep"></import-steps-navigator>
        </div>

<!--        Add footer here-->
    </div>
</template>

<script>
import ImportStepsNavigator from "../components/ImportStepsNavigator";
export default {
    name: "ImportStepsPage.vue",
    components: { ImportStepsNavigator },

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
