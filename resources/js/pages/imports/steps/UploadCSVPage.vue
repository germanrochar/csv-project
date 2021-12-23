<template>
    <div class="center-content text-center">
        <h3>Upload CSV File</h3>
        <p>Import your contacts from a csv file</p>
        <div class="mb-3">
            <input class="form-control" type="file" @change="uploadFile" accept=".csv">
        </div>
        <span v-show="isLoading">Loading...</span>
    </div>
</template>

<script>
export default {
    name: "UploadCSVPage",

    data() {
        return {
            isLoading: false
        }
    },

    methods: {
        uploadFile(e) {
            this.isLoading = true;
            let files = e.target.files || e.dataTransfer.files
            let csvFile = files[0]
            let csvFileName = files[0].name

            console.log('File')
            console.log(csvFile)
            console.log('File Name')
            console.log(csvFileName)

            let formData = new FormData()
            formData.append('csv_file', csvFile)
            axios.post(
                '/contacts/upload/csv',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(response => {
                this.isLoading = false;
            }).catch(error => {

            })
        }
    }
}
</script>
