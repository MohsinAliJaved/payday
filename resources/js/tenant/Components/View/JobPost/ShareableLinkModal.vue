<template>
  <app-modal
    :modal-id="modalId"
    :title="modalTitle"
    :preloader="preloader"
    modal-size="large"
    :hide-footer="true"
    @closeModal="$emit('closeModal')"
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
      <div v-else>
        <p class="text-muted">
          {{ $t("sharable_link_anyone_with_this_link_can_apply_to_this_job") }}
        </p>
        <div class="form-control d-flex align-items-center">
          <span>{{ shareableLink }}</span>
          <button
            type="button"
            class="btn btn-primary text-white width-90 ml-auto"
            @click.stop.prevent="getShareableLink"
          >
            <span v-if="!successfullyCopied">
              {{ $t("copy") }}
            </span>
            <span v-else class="animate__animated animate__fadeInDown">
              <app-icon name="check" class="size-21" />
            </span>
          </button>
          <input type="hidden" id="shareableLink" :value="shareableLink" />
        </div>
      </div>
    </template>
  </app-modal>
</template>

<script>
import { axiosGet } from "../../../../common/Helper/AxiosHelper";
import { JOB_POST } from "../../../Config/ApiUrl";

export default {
  name: "ShareableLinkModal",
  props: {
    jobPostId: {},
  },
  data() {
    return {
      preloader: false,
      modalId: "shareable-link-modal",
      modalTitle: "Shareable link",
      successfullyCopied: false,
      shareableLink: "http://jobpoint.test/job-overview?tab=Overview",
    };
  },
  created() {
    this.preloader = true;
    axiosGet(`${JOB_POST}/${this.jobPostId}/generate-sharable-link`).then(
      (res) => {
        this.shareableLink = res.data;
        this.preloader = false;
      }
    );
  },
  methods: {
      closeModal() {
      this.$emit("closeModal");
    },
    getShareableLink() {
      let shareableLinkToCopy = document.querySelector("#shareableLink");
      shareableLinkToCopy.setAttribute("type", "text");
      shareableLinkToCopy.select();

      try {
        let successful = document.execCommand("copy"),
          msg = successful
            ? this.$t("shareable_link_was_copied_successfully")
            : this.$t("sorry_you_can_not_copy_the_link");

        this.successfullyCopied = true;
        this.$toastr.s(msg);
      } catch (err) {
        this.successfullyCopied = false;
        this.$toastr.s(this.$t("something_went_wrong"));
      }

      // Unselect the range
      shareableLinkToCopy.setAttribute("type", "hidden");
      window.getSelection().removeAllRanges();
    },
  },
};
</script>