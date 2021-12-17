<template>
    <div class="events-wrapper">
        <div class="d-flex align-items-center justify-content-between mb-3">
            <h5 class="mb-0">
                {{ $t('your_events') }}
            </h5>
            <a v-if="totalEventCount > 0" :href="getAppUrl('/all-events')" class="btn btn-sm btn-primary px-3">
                {{ $t('view_all') }}
            </a>
        </div>

        <div class="card border-0 shadow" v-if="preloader">
            <div class="card-body min-height-300">
                <app-overlay-loader/>
            </div>
        </div>

        <template v-else>
            <div class="event cursor-pointer"
                 v-for="(event, index) in events"
                 v-if="index < 3"
                 :class="{'mb-2': index !== events.length - 1}"
                 @click="viewEventDetails(event)">
                <div class="event-date">
                    <div class="month">
                        {{ formatMonthNameFromDateTime(event.start_at) }}
                    </div>
                    <div class="date-day">
                        <h4 class="text-size-16 mb-0">{{ formatDayFromDateTime(event.start_at) }}</h4>
                        <small class="text-muted">{{ formatDayNameFromDateTime(event.start_at) }}</small>
                    </div>
                </div>

                <div class="event-details">
                    <p class="mb-0">{{ event.event_type.name }}</p>
                    <div class="d-flex align-items-center">
                        <app-icon class="size-13 text-muted mr-2" name="user"></app-icon>
                        <p class="mb-0 text-size-13 text-muted ">{{ event.job_applicant.applied_by.full_name }}</p>
                    </div>
                    <div class="d-flex align-items-center mb-2">
                        <app-icon name="clock" class="size-13 text-muted mr-2"/>
                        <span v-if="isSameDate(event.start_at,event.end_at )"
                              class="text-size-13 text-muted">
                            {{ onlyTime(event.start_at) }} - {{ onlyTime(event.end_at) }}
                        </span>
                        <span v-else class="text-size-13 text-muted">
                            {{ formatDateTimeDateTime(event.start_at) }} - {{ formatDateTimeDateTime(event.end_at) }}
                        </span>
                    </div>
                </div>
            </div>
            <transition name="slide-fade">
                <div v-if="events.length < 1" class="card border-0 shadow">
                    <div class="card-body min-height-150">
                        <p class="status free">
                            <img :src="getAppUrl('images/celebration.svg')" alt="No items">
                            {{ $t('no_events_title') }}
                        </p>
                    </div>
                </div>
            </transition>
        </template>

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
    formatDayFromDateTime,
    formatMonthNameFromDateTime,
    formatDayNameFromDateTime,
    onlyTime,
    formatDateTime,
    formatDateTimeDateTime,
} from "../../../../Helpers/DateTimeHelper";
import EventViewMixin from "./EventViewMixin";

export default {
    name: 'Events',
    extends: CoreLibrary,
    mixins: [EventViewMixin],
    data() {
        return {
            formatDayFromDateTime,
            formatMonthNameFromDateTime,
            formatDayNameFromDateTime,
            onlyTime,
            formatDateTime,
            formatDateTimeDateTime,
            preloader: false,
            events: [],
            totalEventCount: 0
        }
    },
    created() {
        this.getEvents();
    },
    mounted() {
        this.initializeTooltip();
    },
    methods: {
        getEvents() {
            this.preloader = true;
            this.axiosGet(`${EVENT}?per_page=3`).then(res => {
                this.events = res.data.data;
                this.totalEventCount = res.data.total;
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