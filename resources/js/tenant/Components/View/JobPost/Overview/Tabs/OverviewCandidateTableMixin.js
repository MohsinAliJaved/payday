import {CANDIDATE, DASHBOARD, JOB_APPLICANT} from "../../../../../Config/ApiUrl";
import DeleteMixin from "../../../../../../tenant/Components/Mixins/DeleteMixin";
import CandidateModalActionMixin from "../../../../../../tenant/Components/Mixins/app/CandidateModalActionMixin";

export default {
    mixins: [CandidateModalActionMixin, DeleteMixin],
    props: ['props'],
    data() {
        return {
            tableId: 'overview-candidates-table',
            deleteModalId: 'candidate-delete-modal',
            selectedUrl: '',
            jobApplicantId: '',
            rowData: null,
            options: {
                url: `${DASHBOARD}/${this.props.jobPostId}/applicants`,
                name: 'Overview Candidates',
                columns: [
                    {
                        title: this.$t('profile'),
                        type: 'component',
                        key: 'full_name',
                        componentName: 'app-table-media',
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
                    }
                ],
                actions: [
                    {
                        title: this.$t('edit'),
                        key: 'edit',
                        icon: 'edit',
                        // modifier: () => this.$have('PERMISSION_UPDATE_CANDIDATE')
                    },
                    {
                        title: this.$t('unassigned_job'),
                        key: 'unassigned',
                        icon: 'x-circle',
                        // modifier: (data) => this.$have('PERMISSION_UN_ASSIGN_JOB') ? data['total_application'][0].count > 1 : this.$have('PERMISSION_UN_ASSIGN_JOB')
                    },
                    {
                        title: this.$t('delete'),
                        key: 'delete',
                        icon: 'trash-2',
                        // modifier: (data) => this.$have('PERMISSION_DELETE_CANDIDATE') ? data['total_application'][0].count < 2 : this.$have('PERMISSION_DELETE_CANDIDATE')
                    }
                ],
                // showAction: (this.$have('PERMISSION_ASSIGN_JOB') || this.$have('PERMISSION_UPDATE_CANDIDATE') || this.$have('PERMISSION_DELETE_CANDIDATE')),
                managePagination: false,
                tableShadow: false,
                actionType: 'dropdown',
                datatableWrapper: false,
                rowLimit: 10
            },
            isCandidateDetailsModalActive: false,
            isCandidateAddEditModalActive: false,
            isCandidateDeleteModalActive: false,
            isUnassignedModalActive: false
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
    },
    methods: {
        getTableMediaAction() {
            this.$hub.$on(`getTableMediaAction-${this.tableId}`, (data) => {
                this.jobApplicantId = data['job_applicants'][0].id;
                this.openCandidateDetailsModal();
            })
        },
        getTableAction(rowData, actionObj) {
            this.rowData = rowData;
            if (actionObj.key === 'edit') {
                this.editCandidate();
            } else if (actionObj.key === 'delete') {
                this.isCandidateDeleteModalActive = true
            } else if (actionObj.key === 'unassigned') {
                this.isUnassignedModalActive = true;
            }
        },
        openCandidateDetailsModal() {
            this.isCandidateDetailsModalActive = true;
        },
        closeCandidateDetailsModal() {
            this.jobApplicantId = '';
            this.isCandidateDetailsModalActive = false;
        },
        editCandidate() {
            this.selectedUrl = `${CANDIDATE}/${this.rowData.id}`;
            this.openCandidateAddEditModal();
        },
        openCandidateAddEditModal() {
            this.isCandidateAddEditModalActive = true;
        },
        closeCandidateAddEditModal() {
            this.isCandidateAddEditModalActive = false;
            this.selectedUrl = '';
        },
        confirmDelete() {
            this.deleteAndReload(`${CANDIDATE}/${this.rowData.id}`);
        },
        confirmUnassigned() {
            this.deleteAndReload(`${JOB_APPLICANT}/${this.rowData['job_applicants'][0].id}`);
        },
        cancel() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.isCandidateDeleteModalActive = false;
            this.isUnassignedModalActive = false;
        }
    },
}