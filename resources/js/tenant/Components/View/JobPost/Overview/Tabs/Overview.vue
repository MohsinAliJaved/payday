<template>
    <div class="p-primary">
        <div class="overview-wrapper min-height-400">
            <app-overlay-loader v-if="kanbanViewLoader"/>
            <div v-else
                 class="kanban-wrapper custom-scrollbar overflow-auto pb-3"
                 :class="{'justify-content-center align-items-center': !stagesWithSequence.length}">
                <template v-if="stagesWithSequence.length">
                    <div
                        v-for="(stage, index) in stagesWithSequence"
                        :key="'kanban-view-'+index"
                        class="kanban-column">
                        <div class="d-flex align-items-center kanban-stage-header">
                            <span class="width-15 height-15 bg-primary rounded mr-2"></span>
                            <h6 class="text-capitalize mb-0">
                                {{ stage.name }}
                                <span
                                    class="default-base-color rounded text-secondary px-2 py-1 d-inline-flex align-items-center justify-content-center">
                                    {{ stage['job_applicants'].length }}
                                </span>
                            </h6>
                        </div>
                        <draggable
                            class="kanban-draggable-column"
                            :list="stage['job_applicants']"
                            :move="checkMove"
                            @change="changedPosition($event, stage.id)"
                            group="deals">
                            <div class="card draggable-item"
                                 v-for="(applicant, index) in stage['job_applicants']"
                                 @click.prevent="openCandidateDetailsModal(applicant.id)"
                                 :key="index">
                                <div class="card-body">
                                    <div class="d-flex align-items-center">
                                        <app-avatar
                                            :title="applicant['applied_by'].full_name"
                                            class="mr-2"
                                            avatar-class="avatars-w-50"
                                            :img="''"
                                        />
                                        <div>
                                            <p class="mb-0 text-primary">{{ applicant['applied_by'].full_name }}</p>
                                            <small class="text-muted">{{ applicant['applied_by'].email }}</small>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </draggable>
                    </div>
                </template>
                <template v-else>
                    <app-empty-data-block
                        :message="$t('nothing_to_show_here')"
                    />
                </template>
            </div>
        </div>

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
import draggable from 'vuedraggable';
import {axiosGet, axiosPatch} from "../../../../../../common/Helper/AxiosHelper";
import {DASHBOARD, JOB_APPLICANT} from "../../../../../Config/ApiUrl";
import CandidateModalActionMixin from "../../../../../../tenant/Components/Mixins/app/CandidateModalActionMixin";

export default {
    name: 'Overview',
    components: {draggable},
    mixins: [CandidateModalActionMixin],
    props: ['props'],
    data() {
        return {
            tableId: 'overview-candidates-table',
            jobApplicantId: '',
            kanbanViewData: [],
            stagesWithSequence: [],
            kanbanViewLoader: false,
            isCandidateDetailsModalActive: false,
            isCandidateAddEditModalActive: false
        };
    },
    created() {
        this.getDataForKanbanView();
    },
    mounted() {
        this.$hub.$on(`reload-${this.tableId}`, () => {
            this.getDataForKanbanView();
        })
    },
    methods: {
        checkMove() {
            if (!this.$have('PERMISSION_CHANGE_STAGE')) return false;
        },
        getDataForKanbanView() {
            this.kanbanViewLoader = true;
            axiosGet(`${DASHBOARD}/${this.props.jobPostId}/overview`).then((res) => {
                this.kanbanViewData = res.data;
                this.prepareDataForView();
                this.$store.commit('SET_JOB_TITLE', res.data.name);
                this.kanbanViewLoader = false;
            })
        },
        prepareDataForView() {
            this.stagesWithSequence = [];
            if (this.kanbanViewData.stages) {
                let sortedNames = this.kanbanViewData.stages.split(',');
                for (let i = 0; i < sortedNames.length; i++) {
                    let stage = this.kanbanViewData['job_stages'].find(item => item.name === sortedNames[i]);
                    if (stage) this.stagesWithSequence.push(stage);
                }
            } else this.stagesWithSequence = this.kanbanViewData['job_stages'];
        },
        changedPosition(event, id) {
            if (!event['added']) return;
            let applicantId = event['added'].element.id,
                url = `${JOB_APPLICANT}/${applicantId}/change-stage`,
                data = {next_stage_id: id}
            axiosPatch(url,data).then((res) => {
                this.$toastr.s(res.data.message);
            }).catch(({response})=>{
                this.$toastr.e(response.data.message);
                this.getDataForKanbanView();
            })
        },
        openCandidateDetailsModal(id) {
            if(this.$have('PERMISSION_VIEW_JOB_APPLICANT')) {
                this.jobApplicantId = id;
                this.isCandidateDetailsModalActive = true;
            }
        },
        closeCandidateDetailsModal() {
            this.jobApplicantId = '';
            this.isCandidateDetailsModalActive = false;
        }
    }
}
</script>