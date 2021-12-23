<template>
    <div class="center-content text-center">
        <h3>Upload CSV File</h3>
        <p>Import your contacts from a csv file</p>
        <div class="mb-3">
            <input class="form-control" type="file" @change="uploadFile" accept=".csv">
        </div>
    </div>
</template>

<script>
export default {
    name: "UploadCSVPage",

    methods: {
        uploadFile(e) {
            let files = e.target.files || e.dataTransfer.files
            let csvFile = files[0]
            let csvFileName = files[0].name

            this.$emit('uploaded', csvFile, csvFileName)
        }
    }
}
</script>
