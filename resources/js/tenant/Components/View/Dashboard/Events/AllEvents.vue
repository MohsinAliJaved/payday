<template>
    <div class="content-wrapper">
        <app-breadcrumb :page-title="$t('all_events')"/>
        <div class="events-wrapper min-height-400 position-relative"
             :class="{'loading-opacity': preloader && events.length}">
            <app-overlay-loader v-if="preloader"/>
            <div class="event mb-3 cursor-pointer"
                 :class="{'mb-2': index !== events.length - 1}"
                 v-for="(event, index) in events"
                 @click="viewEventDetails(event)">
                <div class="event-date">
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
                    <p class="mb-0">{{ event.event_type.name }}</p>
                    <div class="d-flex align-items-center">
                        <app-icon class="size-13 text-muted mr-2" name="user"></app-icon>
                        <p class="mb-0 text-size-12 text-muted ">{{ event.job_applicant.applied_by.full_name }}</p>
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
            </div>
        </div>
        <app-pagination
            v-show="showPagination"
            :total-row="totalRow"
            :row-limit="rowLimit"
            @submit="paginationSubmit"
        />

        <!--event view modal-->
        <event-view-modal
            v-if="eventViewModalActive"
            :modal-id="eventViewModalId"
            :event="selectedEvent"
            @closeModal="closeEventViewModal"
        />
    </div>
</template>

<script>
import CoreLibrary from '../../../../../core/helpers/CoreLibrary';
import {EVENT} from "../../../../Config/ApiUrl";
import {
    formatDateTime,
    formatDayFromDateTime,
    formatDayNameFromDateTime,
    formatMonthNameFromDateTime,
    onlyTime,
    formatDateTimeDateTime
} from "../../../../Helpers/DateTimeHelper";
import EventViewMixin from "./EventViewMixin";

export default {
    name: 'AllEvents',
    extends: CoreLibrary,
    mixins: [EventViewMixin],
    data() {
        return {
            formatDateTime,
            formatDayFromDateTime,
            formatMonthNameFromDateTime,
            formatDayNameFromDateTime,
            onlyTime,
            formatDateTimeDateTime,
            preloader: false,
            activePage: 1,
            rowLimit: 6,
            totalRow: 0,
            events: []
        }
    },
    computed: {
        showPagination() {
            return this.totalRow > this.rowLimit
        }
    },
    mounted() {
        this.initializeTooltip();
    },
    created() {
        this.getEvents();
    },
    methods: {
        paginationSubmit(data) {
            this.activePage = data;
            this.$hub.$emit('resetPaginationState', this.activePage);
            this.getEvents();
        },
        getEvents() {
            this.preloader = true;
            let reqData = {
                params: {
                    per_page: this.rowLimit,
                    page: this.activePage
                }
            };

            this.axiosGet(EVENT, reqData).then(res => {
                this.events = res.data.data;
                this.totalRow = res.data.total;
                this.preloader = false;
            }).finally(() => {

            })
        },
        isSameDate(startDate, EndDate) {
            return formatDayFromDateTime(startDate) === formatDayFromDateTime(EndDate);
        }
    }
}
</script>