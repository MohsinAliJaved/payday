<template>
    <modal :modal-id="modalId"
           :title="$t('assign_job')"
           :preloader="preloader"
           :modal-scroll="false"
           modal-size="default"
           @submit="submit"
           @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="JOB_APPLICANT">
                <div class="form-group row align-items-center">
                    <label for="name" class="mb-sm-0 col-sm-3">
                        {{ $t('name') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            type="text"
                            id="name"
                            :disabled="true"
                            v-model="candidateName"
                        />
                    </div>
                </div>
                <div class="form-group row align-items-center">
                    <label for="job" class="mb-sm-0 col-sm-3">
                        {{ $t('job') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            type="search-select"
                            :placeholder="$t('select_a_job')"
                            id="job"
                            list-value-field="name"
                            :list="jobs"
                            :required="true"
                            v-model="formData.job_post_id"
                        />
                    </div>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>

import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {ModalMixin} from "../../../../../common/Mixin/Tenant/ModalMixin";
import {JOB_APPLICANT} from "../../../../../common/Config/apiUrl";
import {mapGetters} from "vuex";

export default {
    name: 'AssignJobModal',
    mixins: [FormMixin, ModalMixin],
    props: {
        tableId: {},
        candidate: {}
    },
    data() {
        return {
            JOB_APPLICANT,
            modalId: 'assign-job-modal',
            candidateName: this.candidate.full_name,
            formData: {
                applicant_id: this.candidate.id,
                job_post_id: '',
            }
        }
    },
    computed: {
        ...mapGetters(['jobList']),
        jobs() {
            let candidateJobsId = this.candidate['job_applicants'].map(item => Number(item.job_post_id));
            return this.jobList.filter(item => !candidateJobsId.includes(Number(item.id)))
        }
    },
    methods: {
        submit() {
            this.save(this.formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit(`reload-${this.tableId}`)
        },
        afterSuccessFromGetEditData(response) {

        }
    }
}
</script>