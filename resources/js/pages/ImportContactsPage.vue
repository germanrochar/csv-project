<template>

    <div>
        <template v-if="!showImportSteps">
            <div class="center-content">
                <div class="d-flex flex-column align-items-center justify-content-center">
                    <h3>Import CSV File</h3>
                    <p>Import your contacts from a csv file</p>
                    <button type="button" class="btn btn-success flex-grow-*" @click="goToImportSteps">Import</button>
                </div>
            </div>
        </template>

        <template v-if="showImportSteps">
            <import-steps-page @canceled="hideImportSteps"></import-steps-page>
        </template>
    </div>

</template>

<script>
export default {
    name: "ImportContactsPage",
    data() {
        return {
            showImportSteps: false
        }
    },

    methods: {
        goToImportSteps() {
            this.showImportSteps = true;
        },
        hideImportSteps() {
            this.showImportSteps = false;
        }
    }
}
</script>
