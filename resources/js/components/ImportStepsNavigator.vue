<template>
    <div class="steps-navigator">
        <template v-for="step in totalSteps">
            <div :class="getBubbleClasses(step)">
                <span>{{ step }}</span>
            </div>

            <!-- Arrow -->
            <span v-show="step !== totalSteps" class="steps-navigator__arrow">&rarr;</span>
        </template>
    </div>
</template>

<script>
export default {
    name: "ImportStepsNavigator",
    props: ['totalSteps', 'currentStep'],

    data() {
        return {

        }
    },

    methods: {
        getBubbleClasses(step) {
            let classes = ['steps-navigator__bubble']

            if (step === this.currentStep) {
                classes.push('steps-navigator__bubble--active')
            } else {
                classes.push('steps-navigator__bubble--inactive')
            }

            return classes
        }
    }
}
</script>
