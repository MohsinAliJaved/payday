<template>
    <div v-if="preloader || loader" class="content-wrapper">
        <div class="card border-0 shadow min-height-350">
            <div class="card-body">
                <app-overlay-loader/>
            </div>
        </div>
    </div>

    <app-editor
        v-else
        :editor-content="jobSetting.content"
        :editor-page-style="jobSetting.pageStyle"
        :editor-page-blocks="jobSetting.pageBlocks"
        :publish-btn="publishBtn"
        @viewPreview="goToPreview"
        @changed="saveChangedData"
        @published="publishData"
    />
</template>

<script>
import {JOB_POST} from "../../../Config/ApiUrl";
import {axiosPatch, urlGenerator} from "../../../../common/Helper/AxiosHelper";
import AppFunction from "../../../../core/helpers/app/AppFunction";
import {mapGetters} from "vuex";

export default {
    name: 'JobEditor',
    props: {
        job: {},
        previewLink: {}
    },
    data() {
        return {
            preloader: false,
            modified: false,
            modifiedData: null,
            logo: urlGenerator(window.settings.tenant_icon),
            icon: urlGenerator(window.settings.tenant_icon),
        }
    },
    computed: {
        ...mapGetters(['statusListForJobPost', 'loader', 'appSettings']),
        publishBtn() {
            // if(!this.$have('PERMISSION_CHANGE_JOB_POST_STATUS')) return false;
            console.log(urlGenerator(window.settings.tenant_icon))
            let draftId = Number(this.statusListForJobPost.find(item => item.name === 'status_draft').id),
                editJobStatusId = Number(this.job.status_id);
            return draftId === editJobStatusId;
        },
        jobSetting() {
            return this.modified ? this.modifiedData : (typeof this.job['job_post_settings'] === 'string' ?
                JSON.parse(this.job['job_post_settings']) : this.job['job_post_settings']);
        }
    },
    methods: {
        changeAndReload(url, data, callback) {
            this.preloader = true;
            let form = {
                job_post_settings: data
            }
            axiosPatch(url, form).then(res => {
                this.$toastr.s(res.data.message)
                if(callback) callback();
                this.preloader = false;
                this.modified = true;
                this.modifiedData = _.cloneDeep(data);
            }).catch(({response}) => {
                this.$toastr.e(response.data.message)
                location.reload();
            })
        },
        saveChangedData(data) {
            let url = `${JOB_POST}/${this.job.id}/update-job-post`;
            this.changeAndReload(url, data);
        },
        publishData(data) {
            let url = `${JOB_POST}/${this.job.id}/publish-job-post`;
            this.changeAndReload(url, data, ()=> location.reload());
        },
        goToPreview() {
            window.open(this.previewLink);
        }
    },
    mounted() {
        this.$store.dispatch('getStatuses');
    }
}
</script>