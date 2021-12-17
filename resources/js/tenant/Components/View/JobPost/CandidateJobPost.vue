<template>
  <div class="content-wrapper">
    <div class="editor-content">
      <div class="preview-content">
        <div class="preview">
          <div
            v-if="pageBlocks[pageView].logo"
            class="d-flex flex-column align-items-center mb-5"
          >
            <img :src="icon" class="candidate-viewable-icon img-fluid" alt="" />
          </div>

          <div v-if="pageBlocks[pageView].header" class="text-center mb-5">
            <h1
              class="mb-4"
              :style="`font-size: ${titleStyle.fontSize}; font-weight: ${titleStyle.fontWeight}; letter-spacing: ${titleStyle.letterSpacing}; color: ${titleStyle.color};`"
            >
              {{ content.title }}
            </h1>

            <p
              class="mb-4"
              :style="`font-size: ${subTitleStyle.fontSize}; font-weight: ${subTitleStyle.fontWeight}; letter-spacing: ${subTitleStyle.letterSpacing}; color: ${subTitleStyle.color};`"
            >
              {{ content.subtitle }}
            </p>

            <p
              :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`"
            >
              {{ content.details }}
            </p>
          </div>

          <div v-if="pageBlocks[pageView].body" class="editor-body">
            <div v-for="(section, index) in content.bodySection" class="mb-5">
              <h5
                :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`"
              >
                {{ section.headings }}
              </h5>
              <p
                :style="`font-size: ${descriptionStyle.fontSize}; font-weight: ${descriptionStyle.fontWeight}; letter-spacing: ${descriptionStyle.letterSpacing}; color: ${descriptionStyle.color};`"
              >
                {{ section.description }}
              </p>
            </div>
          </div>

          <div class="apply-wrapper mb-5">
            <h4>{{ $t("apply_for_the_post") }} {{ content.title }}</h4>
            <a :href="applyLink" class="btn">
              {{ $t("apply_now") }}
            </a>
          </div>

          <div v-if="pageBlocks[pageView].logo" class="text-center py-4">
            <img
              :src="logo"
              class="candidate-viewable-logo img-fluid d-block mx-auto"
              alt=""
            />
          </div>

          <div v-if="pageBlocks[pageView].footer" class="text-center py-4">
            {{ companyText }}
          </div>
        </div>
      </div>
    </div>
  </div>
</template>

<script>
import { mapState, mapActions, mapGetters } from "vuex";
import { axiosGet, urlGenerator } from "../../../../common/Helper/AxiosHelper";
import { APP_GENERAL_SETTINGS } from "../../../../common/Config/apiUrl";
import { BASIC_SETTINGS, LANGUAGE } from "../../../../tenant/Config/ApiUrl";

export default {
  name: "CandidateJobPost",
  props: ["data", "applyLink"],
  data() {
    return {
      viewType: "desktop",
      activePreview: "desktop",
    };
  },
  computed: {
    ...mapState({
      languages: (state) => state.support.languages,
      generalSettings: (state) => state.tenant_settings.tenantSettings,
    }),
    jobPostSetting() {
      if (typeof this.data["job_post_settings"] === "string")
        return JSON.parse(this.data["job_post_settings"]);
      return this.data["job_post_settings"];
    },
    content() {
      return this.jobPostSetting.content;
    },
    pageStyle() {
      return this.jobPostSetting.pageStyle;
    },
    pageBlocks() {
      return this.jobPostSetting.pageBlocks;
    },
    pageView() {
      return this.activePreview === "desktop" ? "defaultView" : "mobileView";
    },
    titleStyle() {
      return this.pageStyle[this.pageView].find((item) => item.key === "title");
    },
    subTitleStyle() {
      return this.pageStyle[this.pageView].find(
        (item) => item.key === "sub-title"
      );
    },
    detailsStyle() {
      return this.pageStyle[this.pageView].find(
        (item) => item.key === "details"
      );
    },
    headingsStyle() {
      return this.pageStyle[this.pageView].find(
        (item) => item.key === "headings"
      );
    },
    descriptionStyle() {
      return this.pageStyle[this.pageView].find(
        (item) => item.key === "description"
      );
    },
    icon() {
    return settings.tenant_icon ? urlGenerator(window.settings.tenant_icon) : urlGenerator('/images/icon.png');      
    },
    logo() {
        return settings.tenant_logo ? urlGenerator(window.settings.tenant_logo) : urlGenerator('/images/logo.png');
    },
    companyText() {
      return `${this.$t("copyright_text")} ${
        window.settings?.company_name ? window.settings.company_name : ""
      }`;
    },
  },
  watch: {
    viewType: {
      handler: function (type) {
        this.toggleResponsivePreview(type);
      },
      immediate: true,
    },
  },
  mounted() {
    this.checkViewType();
    window.onresize = () => {
      this.checkViewType();
    };
    this.getConfig();
    this.getTenantSettings();
  },
  methods: {
    ...mapActions(["getConfig", "getTenantSettings"]),
    checkViewType() {
      this.viewType = window.innerWidth > 575 ? "desktop" : "mobile";
    },
    toggleResponsivePreview(previewType) {
      let preview = $(".preview-content .preview");

      if (previewType === "desktop") {
        this.activePreview = "desktop";
        preview.removeClass("mobile-preview");
        preview.addClass("desktop-preview");
      } else {
        this.activePreview = "mobile";
        preview.removeClass("desktop-preview");
        preview.addClass("mobile-preview");
      }
    },
  },
};
</script>
