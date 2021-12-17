<template>
    <app-modal
        :modal-id="modalId"
        modal-size="extra-large"
        :modal-scroll="true"
        :title="modalTitle"
        :preloader="preloader"
        :hide-header="true"
        :hide-footer="true"
        modal-body-class="p-0"
        @closeModal="closeModal">
        <template slot="body">
            <div class="candidate-details-wrapper min-height-400">
                <app-overlay-loader v-if="preloader"/>
                <template v-else>
                    <template v-if="jobApplication">
                        <div class="candidate-details-header">
                            <div class="row">
                                <div class="col-lg-6 col-xl-7 mb-4 mb-lg-0">
                                    <div class="d-flex align-items-center">
                                        <div class="candidate-profile-avatar mr-3">
                                            <div class="no-profile-image">
                                                {{ shortTitle(applicant.full_name) }}
                                            </div>
                                        </div>
                                        <div>
                                            <h4>{{ applicant.full_name }}</h4>
                                            <p class="text-muted mb-0">
                                                {{ jobPost.name }}
                                            </p>
                                            <small class="text-muted">
                                                {{ `${$t('applied')} ${getDateFromNow(jobApplication.created_at).toLowerCase()}` }}
                                            </small>
                                        </div>
                                    </div>
                                </div>
                                <div class="col-lg-6 col-xl-5">
                                    <div class="default-base-color rounded p-3 mb-3">
                                        <star-rating
                                            :grade="review"
                                            @rateUpdate="updateRating"
                                        />
                                    </div>
                                    <div class="d-flex align-items-center justify-content-between">
                                        <div class="d-flex align-items-center default-actions">
                                            <a v-if="currentStage.name !== 'disqualified'"
                                               class="action-button"
                                               href="#"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               :title="$t('disqualify')"
                                               @click="openCandidateDisqualifyModal">
                                                <app-icon name="minus-circle" class="size-26"/>
                                            </a>
                                            <a
                                               href="#"
                                               class="action-button"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               :title="$t('compose_mail')"
                                               @click="openCandidateMailingModal">
                                                <app-icon name="mail" class="size-26"/>
                                            </a>
                                            <a
                                               href="#"
                                               class="action-button"
                                               data-toggle="tooltip"
                                               data-placement="top"
                                               :title="$createLabel('event')"
                                               @click="openEventAddEditModal('')">
                                                <app-icon name="calendar" class="size-26"/>
                                            </a>
                                        </div>
                                        <div class="d-flex align-items-center justify-content-end">
                                            {{ $t('stage') }}
                                            <div class="dropdown dropdown-with-animation stage-dropdown ml-2">
                                                <button class="btn btn-primary dropdown-toggle text-capitalize"
                                                        :class="{'disabled': !(true)}"
                                                        type="button"
                                                        data-toggle="dropdown"
                                                        aria-expanded="false">
                                                    {{ currentStage.name }}
                                                </button>
                                                <ul class="dropdown-menu dropdown-menu-right">
                                                    <li>
                                                        <a class="dropdown-item text-muted disabled"
                                                           href="#">
                                                            {{ $t('move_to_stage') }}
                                                        </a>
                                                    </li>
                                                    <li v-for="(stage, index) in jobStageList"
                                                        v-if="stage.name !== 'disqualified'">
                                                        <a class="dropdown-item text-capitalize d-flex align-items-center justify-content-between"
                                                           :class="{'text-primary disabled': stage.id === currentStage.id}"
                                                           :key="`${stage.name}-${index}`"
                                                           @click.prevent="updateStage(stage)"
                                                           href="#">
                                                            <span>{{ stage.name }}</span>
                                                            <span v-if="stage.id === currentStage.id">
                                                            <app-icon name="check"/>
                                                        </span>
                                                        </a>
                                                    </li>
                                                </ul>
                                            </div>
                                            <app-note-editor
                                                v-if="isNoteAvailable"
                                                :table-id="tableId"
                                                :row-data="jobApplication"
                                                icon-class="size-36"
                                                :note-title="$t('disqualification_reason')"
                                                :note-description="jobApplication.disqualification_reason ? jobApplication.disqualification_reason : $t('default_disqualification_reason_note')"
                                                :url="`${JOB_APPLICANT}/${jobApplication.id}/update-disqualification-note`"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="candidate-details-content">
                            <div class="horizontal-tab">
                                <div class="card border-0">
                                    <nav class="candidate-tab-nav">
                                        <div class="nav nav-tabs">
                                            <a v-for="(tab,index) in visibleTabs"
                                               class="nav-item p-primary text-capitalize"
                                               :class="{'active': tab.target === activeTabTarget}"
                                               :key="`${tab.icon}-${index}`"
                                               data-toggle="tab"
                                               @click.prevent="activeTabTarget = tab.target"
                                               href="#">
                                                <app-icon :name="tab.icon" class="mr-2"/>
                                                {{ tab.name }}
                                            </a>
                                        </div>
                                    </nav>
                                    <div class="tab-content">
                                        <div v-if="activeTabTarget==='timeline'"
                                             class="tab-pane fade show active"
                                             id="timeline">
                                            <candidate-logs
                                                :job-applicant-id="jobApplicantId"
                                            />
                                        </div>
                                        <div v-if="activeTabTarget==='notes'"
                                             class="tab-pane fade show active"
                                             id="notes">
                                            <candidate-notes
                                                :job-applicant-id="jobApplicantId"
                                            />
                                        </div>
                                        <div
                                            v-if="activeTabTarget==='events'"
                                            class="tab-pane fade show active"
                                            id="events">
                                            <candidate-events
                                                :job-applicant-id="jobApplicantId"
                                                @viewEvent="$emit('viewEvent', $event)"
                                                @editCandidateEvent="openEventAddEditModal"
                                            />
                                        </div>
                                        <div v-if="activeTabTarget==='applicantDetails'"
                                             class="tab-pane fade show active"
                                             id="applicantDetails">
                                            <candidate-details
                                                :applicant="applicant"
                                            />
                                        </div>
                                        <div v-if="activeTabTarget==='questions'"
                                             class="tab-pane fade show active"
                                             id="questions">
                                            <question-answer
                                                :apply-form="applyForm"
                                                :answers="answers"
                                            />
                                        </div>
                                        <div v-if="activeTabTarget==='attachments'"
                                             class="tab-pane fade show active"
                                             id="attachments">
                                            <candidate-attachments
                                                :apply-form="applyForm"
                                                :applicant="applicant"
                                                :answers="answers"
                                            />
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </template>
                    <div v-else class="h-100 d-flex flex-column justify-content-center">
                        <app-empty-data-block
                            :message="$t('you_dont_have_permission_to_view_details')"
                        />
                    </div>
                </template>
            </div>
        </template>
    </app-modal>
</template>

<script>
import {ModalMixin} from '../../../../common/Mixin/Tenant/ModalMixin';
import {FormMixin} from '../../../../core/mixins/form/FormMixin';
import {JOB_APPLICANT} from "../../../../common/Config/apiUrl";
import {shortTitle} from "../../../../common/Helper/Support/TextHelper";
import {getDateFromNow} from "../../../../common/Helper/Support/DateTimeHelper";

export default {
    name: "CandidateDetailsModal",
    mixins: [ModalMixin, FormMixin],
    props: {
        tableId: String,
        jobApplicantId: {}
    },
    data() {
        return {
            shortTitle,
            getDateFromNow,
            JOB_APPLICANT,
            modalId: 'candidate-details-modal',
            modalTitle: this.$t('overview'),
            jobApplication: null,
            currentStageData: null,
            currentReview: 0,
            tabs: [
                {
                    name: this.$t('timeline'),
                    icon: 'clock',
                    target: 'timeline',
                    // permission: this.$have('PERMISSION_VIEW_TIMELINE')
                },
                {
                    name: this.$t('notes'),
                    icon: 'book-open',
                    target: 'notes',
                    // permission: this.$have('PERMISSION_VIEW_NOTE')
                },
                {
                    name: this.$t('events'),
                    icon: 'calendar',
                    target: 'events',
                    // permission: this.$have('PERMISSION_VIEW_EVENTS_FOR_JOB_APPLICANT')
                },
                {
                    name: this.$t('applicant_details'),
                    icon: 'user',
                    target: 'applicantDetails',
                    permission: true
                },
                {
                    name: this.$t('questions_answers'),
                    icon: 'help-circle',
                    target: 'questions',
                    permission: true
                },
                {
                    name: this.$t('attachments'),
                    icon: 'file-text',
                    target: 'attachments',
                    permission: true
                },
            ],
            activeTabTarget: 'timeline',
        }
    },
    created() {
        this.initializeCandidateData();
    },
    mounted() {
        this.$hub.$on('resetCandidateDetails', (targetTab = 'timeline') => {
            if (targetTab) this.activeTabTarget = targetTab;
            this.initializeCandidateData();
        });
        this.$hub.$on('activeTargetTab', (targetTab) => {
            if (targetTab) this.activeTabTarget = targetTab;
        });

        this.activeTabTarget = this.visibleTabs[0].target;
        // For Event Modal
        this.$store.dispatch('getEventTypes');
    },
    computed: {
        visibleTabs() {
            return this.tabs.filter(item => item.permission)
        },
        applicant() {
            return this.jobApplication['applied_by']
        },
        jobPost() {
            return this.jobApplication['job_post']
        },
        answers() {
            return this.jobApplication['answers']
        },
        applyForm() {
            return typeof this.jobApplication['apply_form_setting'] === 'string' ?
                JSON.parse(this.jobApplication['apply_form_setting']) : this.jobApplication['apply_form_setting'];
        },
        review() {
            return Number(this.currentReview)
        },
        jobStageList() {
            return this.jobPost['job_stages'].map(({id, name}) => {
                return {id: Number(id), name}
            })
        },
        currentStage() {
            return {
                id: Number(this.currentStageData.id),
                name: this.currentStageData.name
            }
        },
        isNoteAvailable() {
            return this.jobApplication?.status?.name === 'status_disqualified' && this.jobApplication?.disqualification_reason
        }
    },
    methods: {
        initializeCandidateData() {
            this.preloader = true;
            this.axiosGet(`${JOB_APPLICANT}/${this.jobApplicantId}`).then((res) => {
                this.jobApplication = res.data;
                this.currentStageData = this.jobApplication['current_stage'];
                this.currentReview = this.jobApplication.review;
                this.preloader = false;
                this.initializeTooltip();
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            }).finally(() => {
                this.preloader = false;
            })
        },
        updateRating(rate) {
            let data = {review: rate.toString()}
            this.axiosPatch({
                url: `${JOB_APPLICANT}/${this.jobApplicantId}/change-review`,
                data
            }).then((res) => {
                this.$toastr.s(res.data.message);
                this.$hub.$emit('getSelectedJobApplicantTimelineData');
                this.$hub.$emit(`reload-${this.tableId}`, false);
                this.currentReview = rate;
            })
        },
        updateStage(stage) {
            if (stage.name === 'disqualified') {
                this.openCandidateDisqualifyModal();
                return;
            }
            let data = {next_stage_id: stage.id}
            this.axiosPatch({
                url: `${JOB_APPLICANT}/${this.jobApplicantId}/change-stage`,
                data
            }).then((res) => {
                this.$toastr.s(res.data.message);
                this.$hub.$emit('getSelectedJobApplicantTimelineData');
                this.$hub.$emit(`reload-${this.tableId}`, false);
                if (this.currentStageData.name === 'disqualified') this.initializeCandidateData();
                else this.currentStageData = stage;
            })
        },
        openCandidateDisqualifyModal() {
            this.$emit('openCandidateDisqualifyModal', this.applicant);
        },
        openCandidateMailingModal() {
            this.$emit('openCandidateMailingModal', this.applicant);
        },
        openEventAddEditModal(editableEvent = '') {
            this.$emit('openEventAddEditModal', this.jobPost, this.applicant, editableEvent);
        }
    },
}
</script>