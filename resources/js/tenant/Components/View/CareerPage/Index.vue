<template>

    <app-editor
        v-if="!preloader"
        :editor-content="pageData.content"
        :editor-logo="urlGenerator(logo)"
        :editor-icon="urlGenerator(icon)"
        :editor-is-career="isCareer"
        :editor-job-list="jobList"
        :editor-page-style="pageData.pageStyle"
        :editor-page-blocks="pageData.pageBlocks"
        :publish-btn="false"
        :save-btn="true"
        @viewPreview="goToPreview"
        @changed="saveChangedData"
    />

    <div v-else class="content-wrapper">
        <div class="card border-0 shadow min-height-350">
            <div class="card-body">
                <app-overlay-loader/>
            </div>
        </div>
    </div>

</template>

<script>
import {axiosPatch, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {CAREER_PAGE, PUBLIC_CAREER_PAGE, PUBLIC_JOB_POST} from "../../../Config/ApiUrl";

export default {
    name: 'CareerPage',
    props: {
        careerPage: {},
        jobPosts: {}
    },
    data() {
        return {
            urlGenerator,
            preloader: false,
            modified: false,
            modifiedData: null,
            logo: window.settings.tenant_icon,
            icon: window.settings.tenant_icon,
            isCareer: true
        }
    },
    computed: {
        pageData() {
            return this.modified ? this.modifiedData : (typeof this.careerPage === 'string' ?
                JSON.parse(this.careerPage) : this.careerPage);
        },
        jobList() {
            return this.jobPosts.map(item => {
                return {
                    title: item.name,
                    type: item['job_type'].name,
                    location: item['location'].address,
                    url: urlGenerator(`${PUBLIC_JOB_POST}/${item.slug}/display`)
                }
            })
        }
    },
    methods: {
        changeAndReload(url, data) {
            this.preloader = true;
            let form = {
                career_page: data
            }
            axiosPatch(url, form).then(res => {
                this.$toastr.s(res.data.message)
                this.preloader = false;
                this.modified = true;
                this.modifiedData = _.cloneDeep(data);
            }).catch(({response}) => {
                this.$toastr.e(response.data.message)
                location.reload();
            })
        },
        saveChangedData(data) {
            let url = `${CAREER_PAGE}`;
            this.changeAndReload(url, data);
        },
        goToPreview() {
            window.open(urlGenerator(PUBLIC_CAREER_PAGE));
        }
    }
}
</script>