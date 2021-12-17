<template>
    <modal
        modal-size="default"
        :modal-id="modalId"
        :title="eventType.name"
        :hide-submit-button="true"
        :cancel-button-text="$t('close')"
        @closeModal="$emit('closeModal')">
        <template slot="body">
            <div class="">
                <div class="d-flex align-items-center mb-2">
                    <app-icon class="mr-2" name="user"></app-icon>
                    <p class="mb-0">{{ event.job_applicant.applied_by.full_name }}</p>
                </div>
                <div class="d-flex align-items-center mb-3">
                    <app-icon name="clock" class="mr-2"/>
                    <span v-if="isSameDate(event.start_at,event.end_at )"
                          class="">
                        {{ onlyTime(event.start_at) }} - {{ onlyTime(event.end_at) }}
                    </span>
                    <span v-else class="">
                        {{ formatDateTimeDateTime(event.start_at) }} - {{ formatDateTimeDateTime(event.end_at) }}
                    </span>
                </div>
                <hr>
                <p class="mb-0">{{$t('attendees')}}:</p>
                <app-avatars-group
                    :border="true"
                    :avatars="attendees"
                />
            </div>
        </template>
    </modal>
</template>

<script>
import {
    formatDateTimeDateTime,
    formatDayFromDateTime,
    onlyTime
} from "../../../Helpers/DateTimeHelper";

export default {
    name: 'EventViewModal',
    props: {
        event: {},
        modalId: {},
    },
    data() {
        return {
            formatDayFromDateTime,
            formatDateTimeDateTime,
            onlyTime,

        }
    },
    computed: {
        eventType() {
            return this.event['event_type'];
        },
        attendees() {
            return this.event['attendees']
                .map(item => item.hiring_team.user)
                .map(el => {
                    return{
                        id: el.id,
                        title: el.full_name,
                        img: el.profile_picture?.full_url
                    }
                });
        },
    },
    methods: {
        isSameDate(startDate, EndDate) {
            return formatDayFromDateTime(startDate) === formatDayFromDateTime(EndDate);
        }
    }
}
</script>