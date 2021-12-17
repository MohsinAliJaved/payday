<template>
    <div>
        <app-table
            :id="tableId"
            :options="options"
            @action="getTableAction"
        />

        <!-- Candidate Details Modal -->
        <candidate-details-modal
            v-if="isCandidateDetailsModalActive"
            :table-id="tableId"
            :job-applicant-id="jobApplicantId"
            @openCandidateDisqualifyModal="openCandidateDisqualifyModal"
            @openCandidateMailingModal="openCandidateMailingModal"
            @openEventAddEditModal="openEventAddEditModal"
            @viewEvent="viewEventDetails"
            @closeModal="closeCandidateDetailsModal"
        />

        <!-- Candidate Add/Edit Modal -->
        <candidate-add-edit-modal
            v-if="isCandidateAddEditModalActive"
            :table-id="tableId"
            :selected-url="selectedUrl"
            @closeModal="closeCandidateAddEditModal"
        />

        <!-- Candidate Disqualify Modal -->
        <candidate-disqualify-modal
            v-if="isCandidateDisqualifyModalActive"
            :table-id="tableId"
            :job-applicant-id="jobApplicantId"
            :selected-candidate="selectedCandidate"
            @closeModal="closeCandidateDisqualifyModal"
        />

        <!-- Candidate Mailing Modal -->
        <candidate-mailing-modal
            v-if="isCandidateMailingModalActive"
            :selected-candidate="selectedCandidate"
            :job-applicant-id="jobApplicantId"
            @closeModal="closeCandidateMailingModal"
        />

        <!-- Candidate Event Modal -->
        <candidate-event-modal
            v-if="isCandidateEventAddEditModalActive"
            :selected-job-post="selectedJobPost"
            :selected-applicant="selectedApplicant"
            :job-applicant-id="jobApplicantId"
            :selected-url="eventSelectedUrl"
            @closeModal="closeEventAddEditModal"
        />

        <!-- Candidate Delete Modal -->
        <app-delete-modal
            v-if="isCandidateDeleteModalActive"
            :modal-id="deleteModalId"
            :message="$t('delete_candidate_notice')"
            :preloader="deleteLoader"
            @confirmed="confirmDelete"
            @cancelled="cancel"
        />

        <!-- Unassigned Job Modal -->
        <app-delete-modal
            v-if="isUnassignedModalActive"
            :modal-id="deleteModalId"
            :message="$t('job_unassigned_notice')"
            :preloader="deleteLoader"
            @confirmed="confirmUnassigned"
            @cancelled="cancel"
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
    import OverviewCandidateTableMixin from './OverviewCandidateTableMixin';

    export default {
        name: 'Candidates',
        mixins: [OverviewCandidateTableMixin]
    }
</script>