<template>
    <div class="container">
        <h1>Status</h1>

        <div class="alert alert-danger" role="alert" v-show="errors">
            {{ errors }}
        </div>

        <div class="row">
            <div class="col-md-6">
                <div class="alert alert-warning mt-3" role="alert" v-show="importJobsAreEmpty">
                    No Import Jobs where found.
                </div>

                <div v-for="(importJob, index) in importJobs" :key="importJob.id" class="card mt-5 p-1">
                    <div class="card-body">
                        <div class="d-flex">
                            <h5 class="card-title u-margin-right-auto">Import Job #{{ index + 1 }}</h5>
                            <p><span class="fw-bold">Started at: </span>{{ formattedDate(importJob.created_at) }}</p>
                        </div>

                        <span :class="getImportJobBadgeStyle(importJob.status)" v-text="getImportJobBadgeText(importJob.status)"></span>
                        <p class="mt-3" v-text="getImportJobStatusText(importJob.status)"></p>
                        <p v-if="importJob.status === 'failed'"><span class="fw-bold">Error: </span> {{ importJob.error_message }}</p>
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
import moment from 'moment';
import 'moment-timezone';

export default {
    name: "MappingsCompletedPage.vue",

    data() {
        return {
            importJobs: [],
            errors: null,
        }
    },

    computed: {
        importJobsAreEmpty() {
            return this.importJobs.length === 0;
        }
    },

    methods: {
        getImportJobs() {
            axios.get('/import-jobs').then(({data}) => {
                this.importJobs = data;
            }).catch(error => {
                console.error(error);
                this.errors = error.response.data.message;
            });
        },

        getImportJobBadgeStyle(status) {
            return {
                'badge': true,
                'bg-success': status === 'completed',
                'bg-danger': status === 'failed',
                'bg-secondary': status === 'started',
            };
        },

        getImportJobBadgeText(status) {
            const statuses = {
                'started': 'In Progress',
                'completed': 'Completed',
                'failed': 'Failed',
            };

            return statuses[status];
        },

        getImportJobStatusText(status) {
            const statuses = {
                'started': 'The import job is in progress.',
                'completed': 'The import job has finished successfully.',
                'failed': 'The import job failed.',
            };

            return statuses[status];
        },

        formattedDate(date) {
            return moment(date).tz('America/New_York').format('lll');
        }
    },

    mounted() {
        this.getImportJobs();

        Echo.channel(`imports`)
            .listen('ContactsImportFailed', (e) => {
                this.getImportJobs();
            })
            .listen('ContactsImportedSucceeded', (e) => {
                this.getImportJobs();
            });
    }
}
</script>
