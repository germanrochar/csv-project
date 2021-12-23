<template>
    <div class="upload-csv-page">
        <div class="center-content text-center">
            <h3>Upload CSV File</h3>
            <p>Import your contacts from a csv file</p>
            <div class="mb-3">
                <input class="form-control" type="file" @change="uploadFile" accept=".csv">
            </div>

        </div>

        <div class="upload-csv-page__footer">
            <button class="btn btn-light u-margin-right-small" @click="cancelImport()">Cancel</button>
            <button class="btn btn-primary" @click="goToMapFieldsPage()" :disabled="!csvIsUploaded">Continue</button>
        </div>
    </div>
</template>

<script>
export default {
    name: "UploadCSVPage",

    data() {
        return {
            csvFile: '',
            csvFilename: ''
        }
    },

    computed: {
        csvIsUploaded() {
            return this.csvFile.length > 0 && this.csvFilename.length > 0
        }
    },

    methods: {
        uploadFile(e) {
            let files = e.target.files || e.dataTransfer.files
            this.csvFile = files[0]
            this.csvFilename = files[0].name

            this.emitUploadedEvent()
        },

        goToMapFieldsPage() {
            if (this.csvFile.length <= 0 || this.csvFilename <= 0) {
                alert('Something went wrong. Please contact tech support.')
                return
            }

            this.emitUploadedEvent()
        },

        cancelImport() {
            this.$emit('canceled')
        },

        emitUploadedEvent() {
            this.$emit('uploaded', this.csvFile, this.csvFilename)
        }
    }
}
</script>
