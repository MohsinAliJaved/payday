<template>
    <div class="card card-with-shadow border-0 h-100">
        <div class="card-header bg-transparent p-primary">
            <h5 class="card-title text-capitalize mb-0">{{ $t('monthly_time_log') }}</h5>
        </div>
        <app-overlay-loader v-if="preloader"/>
        <div class="card-body" v-else>
            <div class="d-flex align-items-center mb-5">
                <div>
                    <div
                        class="width-55 height-55 bg-brand-color d-flex align-items-center justify-content-center rounded mr-3">
                        <app-icon name="clock" class="text-white size-22"/>
                    </div>
                </div>
                <div>
                    <h5 class="mb-0">{{ totalScheduled }}</h5>
                    <p class="text-muted text-size-12 mb-0">{{ $t('total_schedule_time') }}</p>
                </div>
            </div>
            <div>
                <div class="mb-primary">
                    <p class="text-size-12 mb-1">{{ $t('worked_time') }} - {{ totalWorked }}</p>
                    <div class="progress animate-progress height-10 default-base-color radius-20">
                        <div class="progress-bar bg-brand-color"
                             role="progressbar"
                             :style="`width: ${workedPercentage}%`"/>
                    </div>
                </div>
                <div class="mb-primary">
                    <p class="text-size-12 mb-1">{{ $t('shortage_time') }} - {{ totalShortage }}</p>
                    <div class="progress animate-progress height-10 default-base-color radius-20">
                        <div class="progress-bar bg-brand-color"
                             role="progressbar"
                             :style="`width: ${shortagePercentage}%`"/>
                    </div>
                </div>
                <div>
                    <p class="text-size-12 mb-1">{{ $t('over_time') }} - {{ overTime }}</p>
                    <div class="progress animate-progress height-10 default-base-color radius-20">
                        <div class="progress-bar bg-brand-color"
                             role="progressbar"
                             :style="`width: ${overtimePercentage}%`"/>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";
import {APP_DASHBOARD} from "../../../../Config/ApiUrl";
import {getHoursAndMinutesInString} from "../../../../../common/Helper/Support/DateTimeHelper";

export default {
    name: "EmployeeMonthlyTimeLog",
    data() {
        return {
            preloader: false,
            logs: {},
        }
    },
    created() {
        this.getMonthlyLog();
    },
    mounted() {
        this.$hub.$on('reload-dashboard', () => this.getMonthlyLog())
    },
    methods: {
        getMonthlyLog() {
            this.preloader = true;
            axiosGet(`${APP_DASHBOARD}/employee/attendance-log`).then(({data}) => {
                this.logs = data;
            }).finally(() => {
                this.preloader = false;
            })
        }
    },
    computed: {
        totalScheduled() {
            return getHoursAndMinutesInString(this.logs.total_scheduled, true);
        },
        totalWorked() {
            return getHoursAndMinutesInString(this.logs.total_worked, true);
        },
        overTime() {
            return getHoursAndMinutesInString(this.logs.over_time, true);
        },
        totalShortage() {
            return getHoursAndMinutesInString(this.logs.shortage, true);
        },
        workedPercentage(){
            return (this.logs.total_worked / this.logs.total_scheduled) * 100;
        },
        shortagePercentage(){
            return (this.logs.shortage / this.logs.total_scheduled) * 100;
        },
        overtimePercentage(){
            return (this.logs.over_time / this.logs.total_scheduled) * 100;
        },
    }
}
</script>

<style scoped>

</style>