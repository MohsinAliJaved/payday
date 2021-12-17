<template>
    <div class="content-wrapper">
        <app-breadcrumb
            :page-title="$t('job_settings')"
        />
        <app-tab
            class="job-setting-tab"
            :tabs="tabs"
            :icon="pageIcon"
        />
    </div>
</template>

<script>
export default {
    name: 'JobSetting',
    props: {
        permissions: {}
    },
    data() {
        return {
            pageIcon: 'settings',
            tabs: [
                {
                    name: this.$t('application_form'),
                    title: this.$t('application_form'),
                    component: 'application-form-setting',
                    // permission: this.$have('PERMISSION_MANAGE_GLOBAL_APPLICATION_FORM'),
                },
                {
                    name: this.$t('hiring_stage'),
                    title: this.$t('hiring_stage'),
                    component: 'hiring-stage-setting',
                    // permission: this.$have('PERMISSION_VIEW_STAGE'),
                    headerButton: {
                        label: this.$addLabel('hiring_stage'),
                        class: 'btn btn-primary mr-primary'
                    }
                },
                {
                    name: this.$t('event_type'),
                    title: this.$t('event_type'),
                    component: 'event-type-setting',
                    // permission: this.$have('PERMISSION_VIEW_EVENT_TYPE'),
                    headerButton:{
                        label: this.$addLabel('event_type'),
                        class: 'btn btn-primary mr-primary'
                    }
                },
                {
                    name: this.$t('job_type'),
                    title: this.$t('job_type'),
                    component: 'job-type-setting',
                    headerButton: {
                        label: this.$addLabel('job_type'),
                        class: 'btn btn-primary mr-primary'
                    }
                },
                // {
                //     name: this.$t('department'),
                //     title: this.$t('department'),
                //     component: 'department-setting',
                //     headerButton: {
                //         label: this.$addLabel('department'),
                //         class: 'btn btn-primary mr-primary'
                //     }
                // },
                {
                    name: this.$t('location'),
                    title: this.$t('location'),
                    component: 'location-setting',
                    headerButton:{
                        label: this.$addLabel('location'),
                        class: 'btn btn-primary mr-primary'
                    }
                }
            ]
        }
    }
}
</script>