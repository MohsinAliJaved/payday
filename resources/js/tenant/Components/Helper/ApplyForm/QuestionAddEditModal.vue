<template>
  <app-modal
    :modal-id="modalId"
    title="Add/Edit Question"
    :preloader="preloader"
    modal-size="default"
    @submit="submit"
    @closeModal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title text-capitalize">
        Add/Edit Question
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
        <div class="form-group row align-items-center">
          <label for="questionTitle" class="mb-sm-0 col-sm-3">
            {{ $t("question") }}
          </label>
          <div class="col-sm-9">
            <app-input
              type="text"
              id="questionTitle"
              v-model="formData.name"
              :placeholder="$t('write_your_question')"
            />
          </div>
        </div>
        <template v-if="questionType === 'text' || questionType === 'textarea'">
          <div class="form-group row align-items-center">
            <label class="col-sm-3 mb-sm-0">{{ $t("answer_type") }}</label>
            <div class="col-sm-9">
              <p class="mb-0">
                {{ questionType === "text" ? $t("text") : $t("textarea") }}
              </p>
            </div>
          </div>
        </template>
        <template v-if="questionType === 'yes-no'">
          <div class="form-group row align-items-center">
            <label class="col-sm-3 mb-sm-0">{{ $t("answer_type") }}</label>
            <div class="col-sm-9">
              <p class="mb-0">{{ $t("radio") }}</p>
            </div>
          </div>
        </template>
        <template
          v-if="questionType === 'checkbox' || questionType === 'radio'"
        >
          <div class="form-group row align-items-center">
            <label class="col-sm-3 mb-sm-0">{{ $t("answer_type") }}</label>
            <div class="col-sm-9">
              <p class="mb-0">{{ $t(questionType) }}</p>
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-3 mb-sm-0">{{ $t("options") }}</label>
            <div class="col-sm-9">
              <div class="border rounded">
                <ul
                  v-if="formData.options && formData.options.length"
                  class="list-unstyled mb-0"
                >
                  <li
                    class="border-bottom d-flex align-items-center justify-content-between p-3"
                    v-for="(option, index) in formData.options"
                  >
                    <div>{{ option }}</div>
                    <div class="d-inline-flex align-items-center">
                      <a
                        href="#"
                        class="width-20 height-20 d-inline-flex align-items-center justify-content-center"
                        @click.prevent="removeOption(index)"
                      >
                        <app-icon name="x" class="size-12 text-danger" />
                      </a>
                    </div>
                  </li>
                </ul>
                <p v-else class="px-3 pt-3 mb-0">{{ $t("no_items") }}</p>
                <div v-if="showInput" class="p-3">
                  <app-input
                    type="text"
                    v-model="newOption"
                    @keyup.enter="addOption"
                  />
                </div>
                <a
                  v-else
                  href="#"
                  class="d-flex align-items-center p-3"
                  @click.prevent="toggleInput"
                >
                  <app-icon name="plus" class="size-14 mr-2" />
                  {{ $t("add_option") }}
                </a>
              </div>
            </div>
          </div>
        </template>
        <div class="form-group row">
          <label class="mb-sm-0 col-sm-3">
            {{ $t("settings") }}
          </label>
          <div class="col-sm-9">
            <div class="form-group d-flex align-items-center">
              <app-input type="switch" v-model="formData.show" />
              <label class="mb-0">{{ $t("visible_in_apply_form") }}</label>
            </div>
            <div class="form-group d-flex align-items-center">
              <app-input type="switch" v-model="formData.require" />
              <label class="mb-0">{{ $t("require_an_answer") }}</label>
            </div>
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
import { ModalMixin } from "../../../../common/Mixin/Tenant/ModalMixin";
import { FormMixin } from "../../../../core/mixins/form/FormMixin";

export default {
  name: "QuestionAddEditModal",
  mixins: [FormMixin, ModalMixin],
  props: {
    questionType: {},
    previousData: {},
    submitButtonText: {
      default: function() {
        return this.$t("save");
      },
    },
    cancelButtonText: {
      default: function() {
        return this.$t("cancel");
      },
    },
  },
  data() {
    return {
      preloader: false,
      modalId: "question-add-edit-modal",
      newOption: "",
      formData: {
        name: "",
        type: this.questionType,
        show: true,
        require: false,
      },
      showInput: false,
    };
  },
  watch: {
    previousData: {
      handler: function(data) {
        if (data) {
          this.formData = _.cloneDeep(data);
        }
      },
      immediate: true,
    },
  },
  created() {
    if (
      (this.questionType === "checkbox" || this.questionType === "radio") &&
      !this.previousData
    )
      this.formData.options = [];
    if (this.questionType === "yes-no" && !this.previousData) {
      this.formData.type = "radio";
      this.formData.options = ["Yes", "No"];
    }
  },
  methods: {
    closeModal() {
      this.$emit("closeModal");
    },
    submit() {
      if (this.previousData) this.$emit("questionUpdate", this.formData);
      else this.$emit("questionSubmit", this.formData);
      this.afterFinalResponse();
    },
    toggleInput() {
      this.showInput = !this.showInput;
    },
    addOption() {
      this.formData.options.push(this.newOption);
      this.newOption = "";
    },
    removeOption(index) {
      this.formData.options.splice(index, 1);
    },
  },
};
</script>
