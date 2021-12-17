import {CANDIDATE, JOB_APPLICANT} from "../../../../Config/ApiUrl";
import DeleteMixin from "../../../Mixins/DeleteMixin";
import CandidateTableFilterMixin from "./CandidateTableFilterMixin";
import CandidateModalActionMixin from "../../../Mixins/app/CandidateModalActionMixin";

export default {
    mixins: [CandidateModalActionMixin, DeleteMixin, CandidateTableFilterMixin],
    data() {
        return {
            tableId: 'candidates-table',
            deleteModalId: 'candidate-delete-modal',
            selectedUrl: '',
            verifyEmail: '',
            jobApplicantId: '',
            rowData: null,
            options: {
                url: CANDIDATE,
                name: 'Candidates Table',
                filters: [
                    {
                        title: this.$t('applied_date'),
                        type: 'range-picker',
                        key: 'applied_date_range',
                        option: ['today', 'last7Days', 'lastMonth', 'thisMonth']
                    },
                    {
                        title: this.$t('job'),
                        type: 'drop-down-filter',
                        key: 'job',
                        option: [],
                        listValueField: 'name',
                    },
                    {
                        title: this.$t('status'),
                        type: 'drop-down-filter',
                        key: 'status',
                        option: [],
                        listValueField: 'translated_name',
                    },
                    {
                        title: this.$t('review'),
                        type: 'radio',
                        key: 'review',
                        header: {
                            title: this.$t('want_to_filter_your_list'),
                            description: this.$t('you_can_filter_your_candidates_based_on_reviews')
                        },
                        option: [
                            {id: '0', name: this.$t('not_yet')},
                            {id: '1', name: this.$t('review_one')},
                            {id: '2', name: this.$t('review_two')},
                            {id: '3', name: this.$t('review_three')},
                            {id: '4', name: this.$t('review_four')},
                            {id: '5', name: this.$t('review_five')},
                        ],
                        listValueField: 'name',
                    },
                    {
                        title: this.$t('gender'),
                        type: 'radio',
                        key: 'gender',
                        header: {
                            title: this.$t('want_to_filter_through_gender'),
                            description: this.$t('you_can_filter_your_candidates_based_on_gender')
                        },
                        option: [
                            {id: 'male', name: this.$t('male')},
                            {id: 'female', name: this.$t('female')},
                            {id: 'other', name: this.$t('other')}
                        ],
                        listValueField: 'name',
                    },
                ],
                columns: [
                    {
                        title: this.$t('profile'),
                        type: 'component',
                        key: 'full_name',
                        componentName: 'app-table-media',
                    },
                    {
                        title: this.$t('applied_job'),
                        type: 'custom-html',
                        key: 'job_applicants',
                        modifier: (jobApplicant) => {
                            let data = jobApplicant.length > 0 ? jobApplicant[0] : null;
                            if (!data || !data['job_post']) return '-'
                            return data['job_post'].name
                        }
                    },
                    {
                        title: this.$t('status'),
                        key: 'job_applicants',
                        type: 'component',
                        componentName: 'candidate-status',
                        isVisible: true,
                    },
                    {
                        title: this.$t('reviews'),
                        type: 'component',
                        key: 'job_applicants',
                        componentName: 'candidate-table-star-review'
                    },
                    {
                        title: this.$t('current_stage'),
                        type: 'custom-html',
                        key: 'job_applicants',
                        modifier: (jobApplicant) => {
                            let data = jobApplicant.length > 0 ? jobApplicant[0] : null;
                            if (!data || !data['current_stage']) return '-'
                            return `<span class="text-capitalize">${data['current_stage'].name}</span>`;
                        }
                    },
                    {
                        title: this.$t('job_application'),
                        type: 'expandable-column',
                        key: 'job_applicants',
                        className: 'success',
                        componentName: 'candidate-jobs-expandable-view',
                        modifier: (value, rowData) => {
                            let returnObj = {},
                                jobCount = rowData['job_applicants'].length;
                            returnObj.prefixData = jobCount > 0 ? '' : '-';
                            returnObj.title = jobCount > 1 ? this.$t('multi') : this.$t('single');
                            returnObj.className = jobCount > 1 ? 'warning' : 'success';
                            returnObj.expandable = jobCount > 1;
                            returnObj.visible = jobCount > 0;
                            return returnObj;
                        }
                    }
                ],
                actions: [
                    {
                        title: this.$t('assign_job'),
                        key: 'assign',
                        icon: 'link',
                        // modifier: () => this.$have('PERMISSION_ASSIGN_JOB')
                    },
                    {
                        title: this.$t('unassigned_job'),
                        key: 'unassigned',
                        icon: 'x-circle',
                        // modifier: (rowData) => {
                        //     if(!this.$have('PERMISSION_UN_ASSIGN_JOB')) return false;
                        //     return rowData['total_application'][0].count > 1
                        // }
                    },
                    {
                        title: this.$t('edit'),
                        key: 'edit',
                        icon: 'edit',
                        // modifier: () => this.$have('PERMISSION_UPDATE_CANDIDATE')
                    },
                    {
                        title: this.$t('delete'),
                        key: 'delete',
                        icon: 'trash-2',
                        // modifier: () => this.$have('PERMISSION_DELETE_CANDIDATE')
                    }
                ],
                // showAction: (this.$have('PERMISSION_ASSIGN_JOB') || this.$have('PERMISSION_UPDATE_CANDIDATE') || this.$have('PERMISSION_DELETE_CANDIDATE')),
                rowLimit: 10,
                actionType: 'dropdown',
            },
            isCandidateDetailsModalActive: false,
            isEmailVerificationModalActive: false,
            isCandidateAddEditModalActive: false,
            isCandidateDeleteModalActive: false,
            isUnassignedModalActive: false,
            isCandidateAssignJobModalActive: false
        }
    },
    beforeMount() {
        // if (this.$have('PERMISSION_ASSIGN_JOB') || this.$have('PERMISSION_UPDATE_CANDIDATE') || this.$have('PERMISSION_DELETE_CANDIDATE')) {
            this.options.columns.push({
                title: this.$t('actions'),
                type: 'action'
            })
        // }
    },
    mounted() {
        this.getTableMediaAction();
        this.getExpandColumnAction();
    },
    methods: {
        getTableMediaAction() {
            this.$hub.$on(`getTableMediaAction-${this.tableId}`, (data) => {
                this.jobApplicantId = data['job_applicants'][0].id;
                this.openCandidateDetailsModal();
            })
        },
        getExpandColumnAction() {
            this.$hub.$on(`getTableExpandColumnAction-${this.tableId}`, (data, action) => {
                this.jobApplicantId = data.id;
                if (action.key === 'view') this.openCandidateDetailsModal();
                else if (action.key === 'unassigned') this.isUnassignedModalActive = true;
            })
        },
        getTableAction(rowData, actionObj) {
            this.rowData = rowData;
            if (actionObj.key === 'edit') {
                this.editCandidate();
            } else if (actionObj.key === 'delete') {
                this.isCandidateDeleteModalActive = true
            } else if (actionObj.key === 'assign') {
                this.openCandidateAssignJobModal();
            } else if(actionObj.key === 'unassigned') {
                this.jobApplicantId = rowData['job_applicants'][0].id
                this.isUnassignedModalActive = true;
            }
        },
        editCandidate() {
            this.selectedUrl = `${CANDIDATE}/${this.rowData.id}`;
            this.openCandidateAddEditModal();
        },
        openCandidateAssignJobModal() {
            this.isCandidateAssignJobModalActive = true;
        },
        closeCandidateAssignJobModal() {
            this.isCandidateAssignJobModalActive = false;
        },
        openCandidateDetailsModal() {
            this.isCandidateDetailsModalActive = true;
        },
        closeCandidateDetailsModal() {
            this.jobApplicantId = '';
            this.isCandidateDetailsModalActive = false;
        },
        closeEmailVerificationModal() {
            this.isEmailVerificationModalActive = false;
        },
        afterVerifiedEmail(email, data) {
            if (data) this.$toastr.w(this.$t('candidate_already_added'));
            else {
                this.verifyEmail = email;
                this.openCandidateAddEditModal();
            }
        },
        openCandidateAddEditModal() {
            this.isCandidateAddEditModalActive = true;
        },
        closeCandidateAddEditModal() {
            this.isCandidateAddEditModalActive = false;
            this.selectedUrl = '';
            this.verifyEmail = '';
        },
        confirmDelete() {
            this.deleteAndReload(`${CANDIDATE}/${this.rowData.id}`);
        },
        confirmUnassigned() {
            this.deleteAndReload(`${JOB_APPLICANT}/${this.jobApplicantId}`);
        },
        cancel() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.isCandidateDeleteModalActive = false;
            this.isUnassignedModalActive = false;
        }
    }
}