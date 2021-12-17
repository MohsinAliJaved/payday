<template>
  <app-modal
    :modal-id="modalId"
    :title="modalTitle"
    :modal-scroll="false"
    :preloader="preloader"
    modal-size="large"
    @submit="submit"
    @closeModal="closeModal"
  >
    <template slot="header">
      <h5 class="modal-title text-capitalize">
        {{ modalTitle }}
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
        :data-url="HIRING_TEAM"
      >
        <div class="form-group row">
          <label for="users" class="mb-sm-0 col-sm-3">
            {{ $t("member") }}
          </label>
          <div class="col-sm-9">
            <app-input
              id="users"
              type="multi-select"
              v-model="formData.recruiters"
              :list="userList"
              list-value-field="name"
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
import { ModalMixin } from "../../../../../../common/Mixin/Tenant/ModalMixin";
import { FormMixin } from "../../../../../../core/mixins/form/FormMixin";
import { mapGetters } from "vuex";
import { HIRING_TEAM } from "../../../../../Config/ApiUrl";

export default {
  name: "HiringTeamAddEditModal",
  mixins: [FormMixin, ModalMixin],
  props: {
    tableId: { type: String },
    jobPostId: { type: Number },
    teamMember: {},
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
      formData: {
        recruiters: [],
        job_post_id: "",
      },
      HIRING_TEAM,
      preloader: false,
      modalId: "hiring-team-add-edit-modal",
      modalTitle: this.$t("assign_team_member"),
    };
  },
  computed: {
    ...mapGetters(["selectableUsers"]),
    userList() {
      return this.selectableUsers.filter(
        (item) => !this.teamMember.includes(item.id)
      );
    },
  },
  methods: {
      closeModal() {
      this.$emit("closeModal");
    },
    submit() {
      this.formData.job_post_id = this.jobPostId;
      this.formData.recruiters = this.formData.recruiters.map((item) => {
        return {
          recruiter_id: item,
        };
      });

      this.save(this.formData);
    },
    afterSuccess(response) {
      this.$toastr.s(response.data.message);
      this.$hub.$emit(`reload-${this.tableId}`);
    },
  },
};
</script>