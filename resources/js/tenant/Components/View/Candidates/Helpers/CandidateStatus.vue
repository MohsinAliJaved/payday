<template>
    <div class="d-flex align-items-center" :class="alignmentClass">
        <span
            :class="`badge badge-pill badge-${rowData.job_applicants.length> 0 ? rowData.job_applicants[0].status.class : ''} text-capitalize`">
            {{ rowData.job_applicants.length > 0 ? rowData.job_applicants[0].status.translated_name : '-' }}
        </span>

        <app-note-editor
            v-if="isNoteAvailable"
            icon-class="size-26"
            :row-data="rowData"
            :note-title="$t('disqualification_reason')"
            :note-description="disqualification_reason"
            :url="`${JOB_APPLICANT}/${rowData.job_applicants[0].id}/update-disqualification-note`"
        />
    </div>

</template>

<script>

import {FormMixin} from '../../../../../core/mixins/form/FormMixin';
import {JOB_APPLICANT} from "../../../../Config/ApiUrl";

export default {
    name: "CandidateStatus",
    mixins: [FormMixin],
    props: {
        rowData: {},
        alignmentClass: {}
    },
    data() {
        return {
            JOB_APPLICANT,
        }
    },
    computed: {
        disqualification_reason() {
            if (this.rowData?.job_applicants[0].disqualification_reason) {
                return this.rowData.job_applicants[0].disqualification_reason;
            }
            return this.$t('default_disqualification_reason_note');
        },
        isNoteAvailable() {
            return this.rowData?.job_applicants[0]?.status?.name === 'status_disqualified' && this.rowData?.job_applicants[0]?.disqualification_reason
        }
    }
}
</script>
