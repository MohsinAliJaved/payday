<template>
    <div class="content-wrapper">
        <app-page-top-section :title="$t('attendance_request')">
            <app-attendance-top-buttons :tableId="id" :request-button="true"/>
        </app-page-top-section>

        <app-table
            :id="id"
            :options="options"
            @action="triggerActions"
        />

        <app-attendance-edit-request-modal
            v-if="isEditModalActive"
            v-model="isEditModalActive"
            :selectedUrl="selectedUrl"
            :tableId="id"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            :message="modalSubtitle"
            :modal-class="modalClass"
            :icon="modalIcon"
            modal-id="app-confirmation-modal"
            @confirmed="updateStatus()"
            @cancelled="cancelled"
        />

        <app-attendance-log-modal
            v-if="isAttendanceLogModalActive"
            v-model="isAttendanceLogModalActive"
            :url="changeLogUrl"
            :tableId="id"
        />
    </div>
</template>

<script>
import AttendanceRequestMixin from "../../Mixins/AttendanceRequestMixin";
import AttendanceRequestActionMixin from "../../Mixins/AttendanceRequestActionMixin";
import AppAttendanceTopButtons from "./Component/AppAttendanceTopButtons";

export default {
    mixins: [AttendanceRequestMixin, AttendanceRequestActionMixin],
    components: {AppAttendanceTopButtons},
    props: {
        detailsId: {},
        attendanceId: {},
    },
    data() {
        return {
            id: 'attendance-request-table',
            isAttendanceModalActive: false,
            selectedUrl: '',
        }
    },
    methods: {},
    created() {
        if (this.detailsId) {
            this.changeLogUrl = `${this.apiUrl.ATTENDANCES}/${this.detailsId}/log`;
            this.isAttendanceLogModalActive = true;
        }
    }
}
</script>