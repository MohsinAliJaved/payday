<template>
    <modal
        :modal-id="modalId"
        :title="$t('verify_email')"
        :preloader="preloader"
        :modal-backdrop="modalBackdrop"
        modal-size="default"
        :submit-button-text="$t('next')"
        @submit="submit"
        @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="VERIFY_EMAIL">
                <div class="form-group row align-items-center">
                    <label for="email" class="mb-sm-0 col-sm-3">
                        {{ $t('email') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            type="email"
                            :placeholder="$placeholder('email')"
                            id="email"
                            :required="true"
                            v-model="formData.email"
                        />
                    </div>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>

import {FormMixin} from "../../../../core/mixins/form/FormMixin";
import {ModalMixin} from "../../../Mixins/ModalMixin";
import {VERIFY_EMAIL} from "../../../Config/ApiUrl";

export default {
    name: 'EmailVerificationModal',
    mixins: [FormMixin, ModalMixin],
    props: {
        modalBackdrop:{}
    },
    data() {
        return {
            modalId: 'email-verification-modal',
            VERIFY_EMAIL,
            formData: {
                email: ''
            }
        }
    },
    mounted(){
        console.log(this.preloader);
    },
    methods: {
        submit() {
            this.save(this.formData);
        },
        afterSuccess(response) {
            this.$emit('verifiedData', this.formData.email, response.data);
        }
    }
}
</script>