<template>
    <modal :modal-id="modalId"
           :title="modalTitle"
           :preloader="preloader"
           modal-size="large"
           :submit-button-text="$t('send')"
           @submit="submit"
           @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="''">

                <div class="form-group">
                    <app-input
                        type="text"
                        :placeholder="$t('subject')"
                        v-model="formData.subject"
                        :required="true"
                    />
                </div>
                <div class="form-group">
                    <app-input
                        type="text-editor"
                        :placeholder="$t('write_a_message_here')"
                        v-model="formData.mail"
                        :required="true"
                    />
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import {ModalMixin} from '../../../../../common/Mixin/Tenant/ModalMixin';
import {FormMixin} from "../../../../../core/mixins/form/FormMixin";
import {JOB_APPLICANT} from "../../../../Config/ApiUrl";

export default {
    name: 'MailingModal',
    mixins: [FormMixin , ModalMixin],
    props: {
        jobApplicantId: {},
        selectedCandidate: {}
    },
    data() {
        return {
            modalId: 'mailing-modal',
            modalTitle: `${this.$t('mail_to')} - ${this.selectedCandidate.full_name}`,
            formData: {
                subject: '',
                mail: '',
            }
        }
    },
    methods: {
        submit() {
            let url = `${JOB_APPLICANT}/${this.jobApplicantId}/send-email`,
                data = this.formData;
            this.submitFromFixin('patch', url, data);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
        },
    }
}
</script>