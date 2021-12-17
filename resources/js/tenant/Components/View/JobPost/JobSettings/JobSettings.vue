<template>
    <div class="content-wrapper">
        <app-breadcrumb
            :page-title="pageTitle"
        />

        <app-tab
            class="job-setting-tab"
            :tabs="tabsVertical"
            :icon="icon"
        />
    </div>
</template>

<script>
import {mapGetters} from "vuex";

export default {
    name: 'JobSettings',
    props: ['jobPostId'],
    data() {
        return {
            pageTitle: this.$t('settings'),
            icon: 'settings',
            tabsVertical: [
                {
                    name: this.$t('apply_form'),
                    title: this.$t('apply_form'),
                    component: 'job-application-form',
                    // permission: this.$have('PERMISSION_MANAGE_JOB_POST_APPLICATION_FORM')
                },
                {
                    name: this.$t('job_stages'),
                    title: this.$t('job_stages'),
                    component: 'job-stages-setting',
                    // permission: this.$have('PERMISSION_VIEW_JOB_POST_STAGE')
                },
                {
                    name: this.$t('hiring_team'),
                    title: this.$t('hiring_team'),
                    headerButton: 
                    // this.$have('PERMISSION_CREATE_HIRING_TEAM') ?
                        {
                            label: this.$t('assign_team_member'),
                            class: 'btn btn-primary mr-primary'
                        },
                    props: {
                        job_post_id: this.jobPostId
                    },
                    component: 'hiring-team-setting',
                    // permission: this.$have('PERMISSION_VIEW_HIRING_TEAM')
                },
                {
                    name: this.$t('thumbnail_image'),
                    title: this.$t('thumbnail_image'),
                    component: 'thumbnail-image-setting',
                    props: {
                        job_post_id: this.jobPostId
                    },
                    // permission: this.$have('PERMISSION_MANAGE_SHARABLE_THUMBNAIL')
                },
            ],
        }
    },
    created() {
        this.$store.dispatch('getJobDetails', this.jobPostId);
        this.$store.dispatch('getUsers');
    },
    computed: {
        ...mapGetters(['selectedJobDetails'])
    },
    watch: {
        selectedJobDetails: {
            handler: function (data) {
                this.pageTitle = `${this.$t('settings')} - ${data.name}`
            }
        }
    }
}
</script>
