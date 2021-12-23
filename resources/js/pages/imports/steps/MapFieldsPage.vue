<template>
    <div>
        <span>Map Columns</span>
    </div>
</template>

<script>
export default {
    name: "MapFieldsPage",
    props: ['csvFile', 'csvFilename'],

    data() {
        return {

        }
    },

    methods: {
        scanCSVFile() {
            let formData = new FormData()
            formData.append('csv_file', this.csvFile)
            axios.post(
                '/contacts/upload/csv',
                formData,
                {
                    headers: {
                        'Content-Type': 'multipart/form-data'
                    },
                }
            ).then(response => {
                // Set csv file fields
                // Set contacts columns fields
            }).catch(error => {

            })
        }
    },

    mounted() {
        this.scanCSVFile()
    }
}
</script>
