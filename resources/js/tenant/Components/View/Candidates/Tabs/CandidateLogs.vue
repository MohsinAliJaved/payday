<template>
    <div class="min-height-250">
        <app-overlay-loader v-if="preloader"/>
        <div v-if="!preloader && timeLineData.length > 0" class="timeline">
            <div v-for="(log, index) in timeLineData" class="timeline-step">
                <div class="number"/>
                <div class="timeline-info">
                    <p class="time">
                        {{ formatDateTimeDateTime(log.created_at) }}
                    </p>
                    <p class="description" v-html="log.description"></p>
                </div>
            </div>
        </div>
        <app-empty-data-block
            v-if="timeLineData.length === 0 && !preloader"
            :message="$t('no_logs_available')"
        />
    </div>
</template>

<script>
import {axiosGet} from "../../../../Helpers/AxiosHelper";
import {JOB_APPLICANT} from "../../../../Config/ApiUrl";
import {formatDateTimeDateTime, getDateFromNow} from "../../../../Helpers/DateTimeHelper";

export default {
    name: "CandidateLogs",
    props: {
        jobApplicantId: {},
    },
    data() {
        return {
            getDateFromNow,
            formatDateTimeDateTime,
            timeLineData: [],
            preloader: false
        }
    },
    created() {
        this.getTimelineData();
    },
    mounted() {
        this.$hub.$on('getSelectedJobApplicantTimelineData', () => {
            this.getTimelineData();
        })
    },
    methods: {
        getTimelineData() {
            this.preloader = true;
            let url = `${JOB_APPLICANT}/${this.jobApplicantId}/get-timeline`;
            axiosGet(url).then(res => {
                this.timeLineData = res.data;
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            }).finally(() => {
                this.preloader = false;
            })
        }
    },
    destroyed() {
        this.$hub.$off('getSelectedJobApplicantTimelineData');
    }
}
</script>
