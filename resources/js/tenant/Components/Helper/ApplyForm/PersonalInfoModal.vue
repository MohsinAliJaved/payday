<template>
  <app-modal
    :modal-id="modalId"
    :title="infoData.title"
    :preloader="preloader"
    :hide-submit-button="true"
    :hide-footer="!editable"
    modal-size="large"
    :cancel-button-text="$t('close')"
    @submit="submit"
    @closeModal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title text-capitalize">
        {{ infoData.title }}
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
        :data-url="''"
      >
        <p v-if="editable">
          {{
            $t("select_what_should_be_included_or_required_in_the_apply_form")
          }}
        </p>
        <app-note
          v-else
          class="mb-4"
          title="info"
          :show-title="false"
          :notes="$t('view_only_you_can_not_modify_basic_information_setting')"
        />
        <div class="table-responsive mb-2">
          <table class="table table-fixed">
            <thead>
              <tr>
                <th class="w-50">{{ $t("fields") }}</th>
                <th class="">{{ $t("type") }}</th>
                <th class="">{{ $t("require_an_answer") }}</th>
                <th v-if="editable" class="">{{ $t("actions") }}</th>
              </tr>
            </thead>
            <tbody>
              <tr v-for="(field, index) in infoData.fields">
                <td class="w-50">
                  <div class="d-inline-flex align-items-center">
                    <app-input
                      type="switch"
                      :id="`field-name-${index}`"
                      :disabled="!editable"
                      v-model="field.show"
                    />
                    <label :for="`field-name-${index}`" class="mb-0">
                      {{ field.name }}
                    </label>
                  </div>
                </td>
                <td class="">
                  <p class="mb-0 text-capitalize">
                    {{ field.type ? $t(field.type) : "N/A" }}
                  </p>
                </td>
                <td class="">
                  <app-input
                    type="single-checkbox"
                    :id="`field-require-${index}`"
                    :disabled="!editable"
                    :placeholder="
                      `${field.require
                        .toString()
                        .charAt(0)
                        .toUpperCase()}${field.require.toString().slice(1)}`
                    "
                    v-model="field.require"
                  />
                </td>
                <td class="" v-if="editable">
                  <div class="d-inline-flex align-items-center">
                    <a
                      href="#"
                      class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center mr-2"
                      @click="editField(field)"
                    >
                      <app-icon name="edit" class="size-14" />
                    </a>
                    <a
                      href="#"
                      class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                      @click="deleteField(field)"
                    >
                      <app-icon name="trash-2" class="size-14" />
                    </a>
                  </div>
                </td>
              </tr>
            </tbody>
          </table>
        </div>
        <button
          v-if="editable"
          type="button"
          class="btn primary-text-color d-inline-flex align-items-center px-0"
          @click="openCustomFieldModal"
        >
          <app-icon name="plus" class="size-14 mr-2" />
          {{ $t("add_more_fields") }}
        </button>
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
      </div>
    </template>
  </app-modal>
</template>

<script>
import { ModalMixin } from "../../../../common/Mixin/Tenant/ModalMixin";
import { FormMixin } from "../../../../core/mixins/form/FormMixin";

export default {
  name: "PersonalInfoModal",
  mixins: [FormMixin, ModalMixin],
  props: {
    info: {},
    cancelButtonText: {
      default: function() {
        return this.$t("close");
      },
    },
    infoModalActionType: {
      type: String,
      default: "edit",
    },
  },
  data() {
    return {
      modalId: "personal-info-modal",
      infoData: null,
    };
  },
  watch: {
    info: {
      handler: function(value) {
        this.infoData = value;
      },
      immediate: true,
      deep: true,
    },
  },
  computed: {
    editable() {
      return this.infoModalActionType !== "view";
    },
  },
  methods: {
    closeModal() {
      this.$emit("closeModal");
    },
    openCustomFieldModal() {
      this.$emit("openCustomFieldModal", this.infoData.key, false);
    },
    deleteField(field) {
      this.$emit("deleteField", this.infoData.key, field);
    },
    editField(field) {
      this.$emit("openCustomFieldModal", this.infoData.key, field);
    },
    submit() {
      this.afterFinalResponse();
    },
    afterSuccess(response) {},
    afterSuccessFromGetEditData(response) {},
  },
};
</script>
