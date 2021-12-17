<template>
    <div class="default-base-color candidate-events min-height-200 p-primary">
        <!--Events-->
        <div class="events-wrapper min-height-400 position-relative"
             :class="{'loading-opacity': preloader && events.length}">
            <app-overlay-loader v-if="preloader"/>
            <div class="event mb-3"
                 :class="{'mb-2': index !== events.length - 1}"
                 v-for="(event, index) in events">
                <div class="event-date cursor-pointer"
                     @click="viewEventDetails(event)">
                    <div class="month">
                        {{ formatMonthNameFromDateTime(event.start_at) }}
                    </div>
                    <div class="date-day">
                        <h4 class="text-size-16 mb-0">
                            {{ formatDayFromDateTime(event.start_at) }}
                        </h4>
                        <small class="text-muted10">
                            {{ formatDayNameFromDateTime(event.start_at) }}
                        </small>
                    </div>
                </div>
                <div class="event-details">
                    <div class="d-flex justify-content-between">
                        <div class="cursor-pointer" @click="viewEventDetails(event)">
                            <p class="mb-0">{{ event.event_type.name }}</p>
                            <div class="d-flex align-items-center">
                                <app-icon class="size-13 text-muted mr-2" name="user"></app-icon>
                                <p class="mb-0 text-size-12 text-muted ">
                                    {{ event.job_applicant.applied_by.full_name }}</p>
                            </div>

                            <div class="d-flex align-items-center mb-2">
                                <app-icon name="clock" class="size-13 text-muted mr-2"/>
                                <span v-if="isSameDate(event.start_at,event.end_at )"
                                      class="text-size-12 text-muted">
                                {{ onlyTime(event.start_at) }} - {{ onlyTime(event.end_at) }}
                            </span>
                                <span v-else class="text-size-12 text-muted">
                                {{ formatDateTimeDateTime(event.start_at) }} -
                            {{ formatDateTimeDateTime(event.end_at) }}
                            </span>
                            </div>
                        </div>
                        <div v-if="$have('PERMISSION_UPDATE_EVENT') || $have('PERMISSION_DELETE_EVENT')"
                             class="d-flex align-items-center justify-content-start">
                            <a v-if="$have('PERMISSION_UPDATE_EVENT')"
                               href="#"
                               @click.prevent="editEvent(event)">
                                <app-icon class="size-18" name="edit"/>
                            </a>
                            <a v-if="$have('PERMISSION_DELETE_EVENT')"
                               href="#"
                               @click.prevent="deleteEvent(event)"
                               class="ml-2">
                                <app-icon class="size-18" name="trash-2"/>
                            </a>
                        </div>
                    </div>
                </div>
            </div>
            <app-empty-data-block
                v-if="events.length === 0 && !preloader"
                :message="$t('this_candidate_have_no_event_yet')"
            />
        </div>
    </div>
</template>

<script>
import {EVENT} from "../../../../Config/ApiUrl";
import {axiosGet, axiosDelete} from "../../../../Helpers/AxiosHelper";
import {
    formatDateTime, formatDateTimeDateTime,
    formatDayFromDateTime,
    formatDayNameFromDateTime,
    formatMonthNameFromDateTime, onlyTime
} from "../../../../Helpers/DateTimeHelper";

export default {
    name: "CandidateEvents",
    props: {
        jobApplicantId: {}
    },
    data() {
        return {
            formatDateTime,
            formatDayFromDateTime,
            formatMonthNameFromDateTime,
            formatDayNameFromDateTime,
            onlyTime,
            formatDateTimeDateTime,
            events: [],
            preloader: false
        }
    },
    created() {
        this.getEvents();
    },
    mounted() {
        this.$hub.$on('getSelectedJobApplicantEvents', () => {
            this.getEvents();
        })
    },
    methods: {
        viewEventDetails(event) {
            this.$emit('viewEvent', event)
        },
        getEvents() {
            this.preloader = true;
            let url = `${EVENT}/job-applicant-event-list/${this.jobApplicantId}`;
            axiosGet(url).then(res => {
                this.events = res.data;
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            }).finally(() => {
                this.preloader = false;
            })
        },
        isSameDate(startDate, EndDate) {
            return formatDayFromDateTime(startDate) === formatDayFromDateTime(EndDate);
        },
        editEvent(event) {
            this.$emit('editCandidateEvent', event);
        },
        deleteEvent(event) {
            this.preloader = true;
            let url = `${EVENT}/${event.id}`;
            axiosDelete(url).then(res => {
                this.$toastr.s(res.data.message);
                this.getEvents();
            })
        }
    },
    destroyed() {
        this.$hub.$off('getSelectedJobApplicantEvents');
    }
}
</script>

<style lang="scss">
.candidate-events {
    margin: -2rem !important;
}
</style>