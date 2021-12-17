<template>
  <app-modal
    :modal-id="modalId"
    :title="$t('verify_email')"
    :preloader="preloader"
    :modal-backdrop="modalBackdrop"
    modal-size="default"
    :submit-button-text="$t('next')"
    @submit="submit"
    @closeModal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title text-capitalize">{{ $t("verify_email") }}</h5>
      <button
        type="button"
        class="close outline-none"
        data-dismiss="modal"
        aria-label="Close"
        @click.prevent="closeModal"
      >
        <span>
          <app-icon :name="'x'"></app-icon>
        </span>
      </button>
    </template>
    <template slot="body">
      <app-overlay-loader v-if="preloader" />
      <form
        class="mb-0"
        :class="{ 'loading-opacity': preloader }"
        ref="form"
        :data-url="VERIFY_EMAIL"
      >
        <div class="form-group row align-items-center">
          <label for="email" class="mb-sm-0 col-sm-3">
            {{ $t("email") }}
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
    <template slot="footer">
      <div :class="{ 'loading-opacity': preloader }">
        <button
          type="button"
          class="btn btn-secondary mr-2"
          data-dismiss="modal"
          @click.prevent="closeModal"
        >
          {{ cancelButtonText }}
        </button>
        <button type="submit" class="btn btn-primary" @click.prevent="submit">
          {{ submitButtonText }}
        </button>
      </div>
    </template>
  </app-modal>
</template>

<script>
import { FormMixin } from "../../../core/mixins/form/FormMixin";
import { ModalMixin } from "../../../common/Mixin/Tenant/ModalMixin";
import { VERIFY_EMAIL } from "../../../tenant/Config/ApiUrl";

export default {
  name: "EmailVerificationModal",
  mixins: [FormMixin, ModalMixin],
  props: {
    modalBackdrop: {},
    submitButtonText: {
      default: function () {
        return this.$t("next");
      },
    },
    cancelButtonText: {
      default: function () {
        return this.$t("cancel");
      },
    },
  },
  data() {
    return {
      modalId: "email-verification-modal",
      VERIFY_EMAIL,
      formData: {
        email: "",
      },
    };
  },
  mounted() {
    console.log("abc");
  },
  methods: {
    closeModal() {
      this.$emit("closeModal");
    },
    submit() {
      this.save(this.formData);
    },
    afterSuccess(response) {
      this.$emit("verifiedData", this.formData.email, response.data);
    },
  },
};
</script>