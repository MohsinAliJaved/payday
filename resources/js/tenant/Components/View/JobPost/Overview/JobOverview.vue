<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-sm-12 col-md-6">
                <app-breadcrumb
                    :page-title="pageTitle"
                />
            </div>
            <div class="col-sm-12 col-md-6 breadcrumb-side-button">
                <div class="float-md-right mb-3 mb-sm-3 mb-md-0">
                    <button
                        type="button"
                        class="btn btn-primary btn-with-shadow"
                        data-toggle="modal"
                        @click="isEmailVerificationModalActive = true">
                        {{ $addLabel('candidate') }}
                    </button>
                </div>
            </div>
        </div>

        <app-tab
            class="job-overview-tab"
            :tabs="tabOptions"
            type="horizontal"
        />

        <!-- Candidate Add/Edit Modal -->
        <candidate-add-edit-modal
            v-if="isCandidateAddEditModalActive"
            :table-id="tableId"
            :verify-email="verifyEmail"
            :selected-job-id="jobPostId"
            @closeModal="closeCandidateAddEditModal"
        />
        <!-- Email Verification Modal -->
        <email-verification-modal
            v-if="isEmailVerificationModalActive"
            @verifiedData="afterVerifiedEmail"
            @closeModal="closeEmailVerificationModal"
        />
    </div>
</template>

<script>
import {JOB_APPLICANT} from "../../../../Config/ApiUrl";
import {axiosPost} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: 'JobOverview',
    props: {
        jobPostId: {},
    },
    data() {
        return {
            pageTitle: this.$t('overview'),
            tabOptions: [
                {
                    icon: 'grid',
                    name: this.$t('overview'),
                    component: 'overview',
                    props: {
                        jobPostId: this.jobPostId
                    },
                    // permission: this.$have('PERMISSION_VIEW_JOB_OVERVIEW')
                },
                {
                    icon: 'users',
                    name: this.$t('candidates'),
                    component: 'job-candidates',
                    props: {
                        jobPostId: this.jobPostId
                    },
                    // permission: this.$have('PERMISSION_VIEW_CANDIDATE_LIST_FROM_JOB_OVERVIEW') && this.$have('PERMISSION_VIEW_CANDIDATE')
                }
            ],
            verifyEmail: '',
            tableId: 'overview-candidates-table',
            isCandidateAddEditModalActive: false,
            isEmailVerificationModalActive: false
        }
    },
    computed: {
        jobTitle() {
            return this.$store.state.jobs.selectedJobTitle
        }
    },
    watch: {
        jobTitle: {
            handler: function (title) {
                this.pageTitle = `${this.$t('overview')} - ${title}`
            }
        }
    },
    created() {
        this.$store.dispatch('getStatuses');
    },
    methods: {
        closeCandidateAddEditModal() {
            this.isCandidateAddEditModalActive = false;
            this.verifyEmail = '';
        },
        closeEmailVerificationModal() {
            this.isEmailVerificationModalActive = false;
        },
        afterVerifiedEmail(email, data) {
            if (data) {
                this.$toastr.w(this.$t('candidate_already_exist_wait_for_assigning_candidate_to_this_job'));
                this.assignCandidate(data)
            }
            else {
                this.verifyEmail = email;
                this.openCandidateAddEditModal();
            }
        },
        assignCandidate(candidateData) {
            let data = {
                applicant_id: candidateData.id,
                job_post_id: this.jobPostId,
            }
            axiosPost(JOB_APPLICANT, data).then((res)=> {
                if(res.data.status == false) {
                    this.$toastr.e(res.data.message);
                    return;
                }
                this.$toastr.s(res.data.message);
                this.$hub.$emit(`reload-${this.tableId}`)
            })
        },
        openCandidateAddEditModal() {
            this.isCandidateAddEditModalActive = true;
            setTimeout(() => {
                $('#candidate-details-modal').css({
                    opacity: '0.5',
                });
            });
        }
    },
}
</script>