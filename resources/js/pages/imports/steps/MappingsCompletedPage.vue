<template>
    <div class="container">
        <h1>Status</h1>

        <div class="row">
            <div class="col-md-6">
                <div v-for="job in jobs" :key="job.id" class="card mt-5 p-1">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title u-margin-right-auto">{{ job.filename }}</h5>
                            <p><span class="fw-bold">Started at:</span> 24 Oct 2022 09:10:22</p>
                        </div>
                        <span class="badge bg-success">Success</span>
                    </div>
                </div>
            </div>
        </div>

        <div class="d-flex mt-5">
            <a href="/">
                <button class="btn btn-primary">Go Home</button>
            </a>
        </div>
    </div>
</template>

<script>
export default {
    name: "MappingsCompletedPage.vue",

    data() {
        return {
            jobs: [
                {
                    id: 987,
                    filename: 'example.csv',
                    status: 'in progress',
                },
                {
                    id: 123,
                    filename: 'testing.csv',
                    status: 'failed',
                }
            ],
        }
    },

    mounted() {
        console.log('component mounted.');
        Echo.channel(`imports`)
            .listen('ContactsImportFailed', (e) => {
                console.log('hey');
                console.log('e', e);
            });
    }
}
</script>
