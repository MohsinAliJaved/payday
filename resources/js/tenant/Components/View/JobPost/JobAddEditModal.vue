<template>
  <app-modal
    :modal-id="modalId"
    :title="selectedUrl ? $editLabel('job') : $createLabel('new_job')"
    :preloader="preloader"
    :modal-scroll="false"
    modal-size="large"
    @submit="submit"
    @closeModal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title text-capitalize">
        {{ selectedUrl ? $editLabel("department") : $addLabel("department") }}
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
        :data-url="selectedUrl ? selectedUrl : JOB"
      >
        <div class="form-group row align-items-center">
          <label for="jobTitle" class="col-sm-3 mb-0">
            {{ $t("job_title") }}
          </label>
          <div class="col-sm-9">
            <app-input
              id="jobTitle"
              type="text"
              v-model="formData.name"
              :placeholder="$placeholder('title')"
              :required="true"
            />
          </div>
        </div>
        <div class="form-group row align-items-center">
          <label for="jobType" class="col-sm-3 mb-sm-0">
            {{ $t("job_type") }}
          </label>
          <div class="col-sm-9">
            <app-input
              id="jobType"
              type="search-select"
              :placeholder="$chooseLabel('job_type')"
              :list="jobTypeList"
              list-value-field="name"
              :required="true"
              v-model="formData.job_type_id"
            />
          </div>
        </div>
        <div class="form-group row align-items-center">
          <label for="department" class="col-sm-3 mb-sm-0">
            {{ $t("department") }}
          </label>
          <div class="col-sm-9">
            <app-input
              id="department"
              type="search-select"
              :placeholder="$chooseLabel('department')"
              :list="departmentList"
              list-value-field="name"
              :required="true"
              v-model="formData.department_id"
            />
          </div>
        </div>
        <div class="form-group row align-items-center">
          <label for="locations" class="col-sm-3 mb-sm-0">
            {{ $t("location") }}
          </label>
          <div class="col-sm-9">
            <app-input
              id="locations"
              type="search-select"
              :placeholder="$chooseLabel('location')"
              :list="companyLocationList"
              list-value-field="address"
              :required="true"
              v-model="formData.company_location_id"
            />
          </div>
        </div>
        <div class="form-group row">
          <label for="description" class="col-sm-3 mb-0">
            {{ $t("description") }}
          </label>
          <div class="col-sm-9">
            <app-input
              id="description"
              type="textarea"
              v-model="formData.description"
              :placeholder="$placeholder('description')"
            />
          </div>
        </div>
        <div class="form-group row align-items-center">
          <label for="salary" class="col-sm-3 mb-0">
            {{ $t("salary") }}
          </label>
          <div class="col-sm-9">
            <app-input
              id="salary"
              type="text"
              v-model="formData.salary"
              :placeholder="$placeholder('salary')"
            />
          </div>
        </div>
        <div class="form-group row align-items-center">
          <label for="sub-date" class="col-sm-3 mb-0">
            {{ $t("last_submission") }}
          </label>
          <div class="col-sm-9">
            <app-input
              id="sub-date"
              type="date"
              :required="true"
              v-model="formData.last_submission_date"
              :placeholder="$t('last_submission_date')"
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
import { FormMixin } from "../../../../core/mixins/form/FormMixin";
import { ModalMixin } from "../../../../common/Mixin/Tenant/ModalMixin";
import EditorDemoMixin from "../../Helper/Editor/EditorDemoMixin";
import { mapGetters } from "vuex";
import { JOB } from "../../../Config/ApiUrl";
import DateFunction from "../../../../core/helpers/date/DateFunction";
import { BASIC_SETTINGS } from "../../../../tenant/Config/ApiUrl";
import { axiosGet } from "../../../../common/Helper/AxiosHelper";

export default {
  name: "JobAddEditModal",
  mixins: [FormMixin, ModalMixin, EditorDemoMixin],
  props: {
    tableId: {},
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
      modalId: "job-add-edit-Modal",
      JOB,
      formData: {
        name: "",
        job_type_id: "",
        department_id: "",
        company_location_id: "",
        salary: "",
        description: "",
        last_submission_date: new Date(),
      },
    };
  },
  computed: {
    ...mapGetters([
      "jobTypeList",
      "departmentList",
      "stageList",
      "companyLocationList",
      "applicationForm",
    ]),
  },
  methods: {
    closeModal() {
      this.$emit("closeModal");
    },
    generateSettingData() {
      this.content.title = this.formData.name;
      this.content.subtitle = this.formData.description
        ? this.formData.description
        : `${this.formData.name} - Description Here`;
      this.content.details = `${
        this.jobTypeList.find((item) => item.id === this.formData.job_type_id)
          ?.name
      } - ${
        this.companyLocationList.find(
          (item) => item.id === this.formData.company_location_id
        )?.address
      }`;
      let data = {};
      data.content = this.content;
      data.pageStyle = this.pageStyle;
      data.pageBlocks = this.pageBlocks;
      return data;
    },
    generateStages() {
      let stages = this.stageList.filter(
          (item) =>
            !(
              item.name.toLowerCase() === "new" ||
              item.name.toLowerCase() === "hired" ||
              item.name.toLowerCase() === "disqualified"
            )
        ),
        stageName = stages.map((item) => item.name.toLowerCase());
      return ["new", ...stageName, "hired", "disqualified"].toString();
    },
    async submit() {
      if (!this.selectedUrl) {
        await axiosGet(BASIC_SETTINGS).then(({ data }) => {
          this.formData.apply_form_settings = JSON.parse(data.application_form);
        });
        this.formData.stages = this.generateStages();
        this.formData.job_post_settings = this.generateSettingData();
      }
      if (this.formData.last_submission_date) {
        this.formData.last_submission_date =
          DateFunction.getDateFormatForBackend(
            this.formData.last_submission_date
          );
      }
      this.save(this.formData);
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$hub.$emit(`reload-${this.tableId}`);
    },
    afterSuccessFromGetEditData({ data }) {
      this.formData.name = data.name;
      this.formData.job_type_id = data.job_type_id;
      this.formData.department_id = data.department_id;
      this.formData.company_location_id = data.company_location_id;
      this.formData.salary = data.salary;
      this.formData.description = data.description;
      this.formData.last_submission_date = data.last_submission_date;
      this.preloader = false;
    },
  },
};
</script>