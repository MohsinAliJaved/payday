<template>
  <app-modal
    :modal-id="modalId"
    :title="
      selectedUrl ? $editLabel('hiring_stage') : $addLabel('hiring_stage')
    "
    :preloader="preloader"
    @submit="submit"
    @closeModal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title text-capitalize">
        {{
          selectedUrl ? $editLabel("hiring_stage") : $addLabel("hiring_stage")
        }}
      </h5>
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
        :data-url="selectedUrl ? selectedUrl : STAGE"
      >
        <div class="form-group mb-0">
          <label for="name">
            {{ $t("name") }}
          </label>
          <app-input
            id="name"
            type="text"
            :required="true"
            :placeholder="$placeholder('name')"
            v-model="formData.name"
          />
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
import { ModalMixin } from "../../../../../../common/Mixin/Tenant/ModalMixin";
import { FormMixin } from "../../../../../../core/mixins/form/FormMixin";
import { STAGE } from "../../../../../Config/ApiUrl";
import ErrorHandlerMixin from "../../../../../../common/Mixin/ErrorHandlerMixin";

export default {
  name: "StageAddEditModal",
  mixins: [ModalMixin, FormMixin, ErrorHandlerMixin],
  props: {
    tableId: { type: String },
    submitButtonText: {
      default: function () {
        return this.$t("save");
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
      STAGE,
      modalId: "stage-add-edit-modal",
      formData: {},
    };
  },
  methods: {
    submit() {
      this.errorHandle = true;
      if (this.formData.name)
        this.formData.name = this.formData.name.toLowerCase();
      this.save(this.formData);
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$hub.$emit(`reload-${this.tableId}`);
      this.closeModal();
    },
    afterSuccessFromGetEditData(response) {
      this.formData = response.data;
      this.preloader = false;
    },
    closeModal() {
      this.$emit("closeModal");
    },
  },
};
</script>