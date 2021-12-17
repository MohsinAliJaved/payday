<template>
    <div class="row mx-0 justify-content-center">
        <div class="col-11 col-md-10 col-lg-9 col-xl-8 px-primary margin-top-30 margin-bottom-30">
            <div class="d-flex align-items-center justify-content-start mb-primary">
                <app-icon name="layers" class="text-secondary size-36"/>
                <div class="ml-3">
                    <p class="mb-0 font-size-90">{{ `${$t('this_candidate_has_applied')} ${jobCount} ${$t('jobs').toLowerCase()}` }}</p>
                    <p class="mb-0 font-size-90 text-muted">{{ `${$t('current_job')}: ${currentJob.name}` }}</p>
                </div>
            </div>
            <div v-for="(applicant, index) in data['job_applicants']"
                 class="d-flex align-items-center justify-content-start mb-primary">
                <div class="width-150 d-flex align-items-center">
                    <app-icon name="corner-down-right" class="text-info"/>
                    <p class="mb-0 text-info">
                        {{ index === 0 ? $t('last') : ordinal(data['job_applicants'].length - index) }}
                    </p>
                </div>
                <div class="ml-3 width-350">
                    <p class="mb-0">
                        <span class="text-muted">{{ `${$t('job')}: ` }}</span>
                        <span class="font-size-90">{{ applicant['job_post'].name }}</span>
                    </p>
                    <p class="mb-0">
                        <span class="text-muted">{{ `${$t('current_stage')}: ` }}</span>
                        <span class="font-size-90 text-capitalize">
                          {{ applicant['current_stage'] ? applicant['current_stage'].name : '-' }}
                        </span>
                    </p>
                </div>
                <div class="ml-3 width-350">
                    <p class="mb-0">
                        <span class="text-muted">{{ `${$t('status')}: ` }}</span>
                        <template v-if="applicant['status']">
                            <span :class="`badge badge-pill badge-${applicant['status'].class}`">
                                {{ applicant['status'].translated_name }}
                            </span>
                            <app-note-editor
                                v-if="isNoteAvailable(applicant)"
                                :table-id="tableId"
                                :row-data="data"
                                icon-class="size-24"
                                :note-title="$t('disqualification_reason')"
                                :note-description="applicant.disqualification_reason ? applicant.disqualification_reason : $t('default_disqualification_reason_note')"
                                :url="`${JOB_APPLICANT}/${applicant.id}/update-disqualification-note`"
                            />
                        </template>
                        <span v-else class="font-size-90">-</span>
                    </p>
                    <div class="d-flex align-items-center">
                        <span class="text-muted">{{ `${$t('reviews')}: ` }}</span>
                        <ul v-if="applicant['review']" class="rated list list-unstyled p-0 m-0 min-width-120">
                            <li class="d-inline-block star"
                                v-for="star in 5"
                                :class="{'active': star <= Number(applicant['review'])}"
                                :key="star">
                                <app-icon name="star" class="size-14 ml-1"/>
                            </li>
                        </ul>
                    </div>
                </div>
                <div class="ml-3">
                    <default-action
                        :actions="actions"
                        :row-data="applicant"
                        @action="getActions"
                    />
                </div>
            </div>
        </div>
    </div>

</template>

<script>
import {ordinal} from "../../../../Helpers/TextHelper";
import DefaultAction from "../../../../../core/components/datatable/DefaultAction";
import {JOB_APPLICANT} from "../../../../Config/ApiUrl";

export default {
    name: "CandidateJobsExpandableView",
    components: {
        DefaultAction
    },
    props: {
        data: {},
        tableId: {}
    },
    data() {
        return {
            ordinal,
            JOB_APPLICANT,
            actions: [
                {
                    title: this.$t('view'),
                    key: 'view',
                    icon: 'eye',
                    modifier: () => this.$have('PERMISSION_VIEW_JOB_APPLICANT')
                },
                {
                    title: this.$t('unassigned_job'),
                    key: 'unassigned',
                    icon: 'x-circle',
                    modifier: () => this.$have('PERMISSION_UN_ASSIGN_JOB')
                }
            ]
        }
    },
    computed: {
        jobCount() {
            return this.data['job_applicants'].length
        },
        currentJob() {
            return this.data['job_applicants'][0].job_post
        }
    },
    methods: {
        getActions(rowData, actionObj, active) {
            this.$hub.$emit(`getTableExpandColumnAction-${this.tableId}`, rowData, actionObj)
        },
        isNoteAvailable(applicant) {
            return applicant?.status?.name === 'status_disqualified' && applicant?.disqualification_reason
        }
    }
}
</script>