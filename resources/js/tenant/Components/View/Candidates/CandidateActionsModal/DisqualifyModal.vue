<template>
    <modal :modal-id="modalId"
           :title="modalTitle"
           :preloader="preloader"
           modal-size="large"
           @submit="submit"
           @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form v-else class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="''">
                <div class="form-group row align-items-center">
                    <label for="reason" class="col-sm-3 mb-sm-0">
                        {{$t('reason')}}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            id="reason"
                            type="textarea"
                            :text-area-rows="1"
                            :placeholder="$placeholder('reason')"
                            v-model="formData.disqualification_reason"
                        />
                    </div>
                </div>
                <div class="form-group row">
                    <label for="notify" class="col-sm-3 mb-sm-0">
                        {{$t('notify')}}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            id="notify"
                            type="radio"
                            custom-radio-type="d-block customized-radio radio-default mb-2"
                            :list="notifyList"
                            v-model="formData.notify"
                        />
                    </div>
                </div>
                <div v-show="parseInt(formData.notify) === 1" class="form-group row">
                    <label for="notifyMail" class="col-sm-3 mb-sm-0">
                        {{$t('compose')}}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            id="notifyMail"
                            type="text-editor"
                            v-model="formData.mail"
                        />
                    </div>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
    import {ModalMixin} from '../../../../../common/Mixin/Tenant/ModalMixin';
    import {FormMixin} from '../../../../../core/mixins/form/FormMixin';
    import {JOB_APPLICANT} from "../../../../Config/ApiUrl";
    import {mapGetters} from "vuex";
    import {axiosGet, urlGenerator} from "../../../../../common/Helper/AxiosHelper";

    export default {
        name: 'DisqualifyModal',
        mixins: [FormMixin, ModalMixin],
        props: {
            tableId: String,
            jobApplicantId: {},
            selectedCandidate: {}
        },
        data() {
            return {
                preloader: false,
                parseInt,
                modalId: 'disqualify-reason-modal',
                modalTitle: `${this.$t('disqualify')} - ${this.selectedCandidate.full_name}`,
                formData: {
                    disqualification_reason: '',
                    notify: 2,
                    mail: '',
                    subject:''
                },
                notifyList: [
                    {id: 1, value: this.$t('notify_by_email')},
                    {id: 2, value: this.$t('do_not_notify')}
                ],
            }
        },
        computed: {
            ...mapGetters([
                'statusListForJobApplicant'
            ]),
            disqualifyStatusId: function () {
                return this.statusListForJobApplicant.find((e) => e.name === 'status_disqualified').id
            },
        },
        created() {
            this.getDisqualifyTemplateForCandidate()
        },
        methods: {
            submit() {
                this.preloader = true;
                let url = `${JOB_APPLICANT}/${this.jobApplicantId}/disqualify`,
                    data = this.formData;
                this.formData.status_id = this.disqualifyStatusId;
                this.axiosPatch({url , data}).then((res) => {
                    this.preloader = false;
                    this.$toastr.s(res.data.message);
                    this.$hub.$emit('getSelectedJobApplicantTimelineData');
                    this.$hub.$emit('resetCandidateDetails');
                    this.$hub.$emit(`reload-${this.tableId}`, false);
                    this.afterFinalResponse();
                }).catch(({response}) => {
                    this.afterError(response);
                })
            },
            getDisqualifyTemplateForCandidate(){
                this.preloader = true;
                let url = `${JOB_APPLICANT}/${this.jobApplicantId}/disqualify-template`;

                axiosGet(url).then((res) => {
                    this.formData.mail = res.data.template;
                    this.formData.mail = this.formData.mail.replace('{app_logo}', urlGenerator(window.settings.company_logo));
                    this.formData.subject = res.data.subject;
                    this.preloader = false;
                })
            }
        },
    }
</script>