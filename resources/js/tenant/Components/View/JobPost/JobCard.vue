<template>
    <div class="card card-with-shadow border-0 h-100 job-card">
        <div class="card-header bg-transparent d-flex justify-content-between align-items-center p-4">
            <div class="d-flex flex-column">
                <h5 class="text-capitalize mr-2">{{ job.name }}</h5>
                <p v-if="job['last_submission_date']"
                   class="text-secondary mb-1">
                    <app-icon name="calendar" class="size-13"/>
                    {{ $t('last_submission_date') }}: {{ formatDateToLocal(job['last_submission_date']) }}
                </p>
                <div v-if="job['job_type'] || job['location'] || job['salary'] || job['department']"
                     class="text-secondary small d-inline-flex align-items-center">
                    <template v-if="job['job_type']">
                        <app-icon name="clock" class="size-12 mr-1"/>
                        <span class="text-size-13 mr-2">{{ job['job_type'].name }} </span>
                    </template>
                    <template v-if="job['location']">
                        <app-icon name="map-pin" class="size-12 mx-1"/>
                        <span class="text-size-13 mr-2">{{ job['location'].address }}</span>
                    </template>
                    <template v-if="job['department']">
                        <app-icon name="home" class="size-12 mx-1"/>
                        <span class="text-size-13 mr-2">{{ job['department'].name }}</span>
                    </template>
                    <template v-if="job['salary']">
                        <app-icon name="dollar-sign" class="size-12 mx-1"/>
                        <span class="text-size-13 mr-2">{{ job['salary'] }}</span>
                    </template>
                </div>
            </div>
            <div v-if="showAction && visibleActions.length > 0" class="dropdown options-dropdown">
                <button type="button"
                        class="btn-option btn d-flex align-items-center justify-content-center"
                        data-toggle="dropdown">
                    <app-icon name="more-vertical"/>
                </button>
                <div class="dropdown-menu dropdown-menu-right py-2 mt-1">
                    <a class="dropdown-item px-4 py-2"
                       href="#"
                       v-for="(action,index) in visibleActions"
                       :key="index"
                       @click.prevent="callAction(action)">
                        {{ action.title }}
                    </a>
                </div>
            </div>
        </div>
        <div class="card-body p-4">
            <div class="d-flex overflow-auto custom-scrollbar">
                <div v-for="(stage, index) in jobStages"
                     :class="colorClasses[index%5]"
                     class="card-widget mb-3 mr-3">
                    <p class="text-size-15 default-font-color mb-0">{{ numberOfCandidate(stage) }}</p>
                    <p class="text-size-13 text-muted mb-0 text-capitalize">{{ stage.name }}</p>
                </div>
            </div>
            <div class="mt-2">
                <button type="button" class="text-size-13 btn btn-sm btn-outline-primary px-3" @click="goToOverview">
                    {{ $t('overview') }}
                </button>
            </div>
        </div>
    </div>
</template>

<script>
import AppFunction from "../../../../core/helpers/app/AppFunction";
import {JOB_POST} from "../../../Config/ApiUrl";
import {formatDateToLocal} from "../../../../common/Helper/Support/DateTimeHelper";

export default {
    name: "JobCard",
    props: {
        job: {
            type: Object,
            required: true
        },
        actions: {
            type: Array,
            required: true
        },
        showAction: {
            type: Boolean,
            default: true
        },
        id: {
            required: true
        }
    },
    data() {
        return {
            formatDateToLocal,
            colorClasses: ['candidates', 'new-candidates', 'qualified-candidates', 'interview-candidates', 'hired-candidates'],
            jobStages: []
        }
    },
    watch: {
        job: {
            handler: function (data) {
                if (data['job_stages']) {
                    this.jobStages = [];
                    let sortedStr = _.cloneDeep(data.stages),
                        sortedName = sortedStr.toLowerCase().split(',');
                    for (let i = 0; i < sortedName.length; i++) {
                        let stage = data['job_stages'].find(item => item.name === sortedName[i]);
                        if (stage) this.jobStages.push(stage);
                    }
                }
            },
            immediate: true,
            deep: true
        }
    },
    computed: {
        visibleActions() {
            return this.actions.filter(action => {
                if (typeof action.title === 'undefined') return false
                if (typeof action.modifier === 'undefined') return true
                return action.modifier(this.job)
            })
        }
    },
    methods: {
        callAction(action) {
            this.$emit('action-' + this.id, this.job, action, true)
        },
        numberOfCandidate(stage) {
            return (stage['job_applicant_count'] && stage['job_applicant_count'][0]?.count) ? stage['job_applicant_count'][0].count : 0
        },
        goToOverview() {
            window.location = AppFunction.getAppUrl(`${JOB_POST}/${this.job.id}/overview`);
        }
    },
}
</script>