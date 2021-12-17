<template>
    <apply-form-settings
        :form-setting="applyForm"
        :preloader="loader || preloader"
        @update="updateSetting"
        @viewForm="viewForm"
    />
</template>

<script>

import {mapGetters} from "vuex";
import {JOB_POST, PUBLIC_JOB_POST} from "../../../../../Config/ApiUrl";
import {axiosPatch, urlGenerator} from "../../../../../../common/Helper/AxiosHelper";


export default {
    name: 'ApplicationForm',
    data() {
        return {
            axiosPatch,
            applyForm: '',
            preloader: false,
        }
    },
    computed: {
        ...mapGetters([
            'applicationForm',
            'selectedJobDetails',
            'loader'
        ])
    },
    watch: {
        selectedJobDetails: {
            handler: function (data) {
                this.applyForm = data.apply_form_settings ?
                    (typeof data.apply_form_settings === 'string' ? JSON.parse(data.apply_form_settings) : data.apply_form_settings)
                    : this.applicationForm;
            }
        }
    },
    methods: {
        updateSetting(data) {
            this.preloader = true;
            let url = `${JOB_POST}/${this.selectedJobDetails.id}/edit-apply-form-setting`,
                form = {apply_form_settings: data}
            axiosPatch(url, form).then(res => {
                this.$toastr.s(res.data.message);
                this.$store.dispatch('getJobDetails', this.selectedJobDetails.id);
            }).finally(() => {
                this.preloader = false;
            })
        },
        viewForm() {
            window.open(urlGenerator(`${PUBLIC_JOB_POST}/${this.selectedJobDetails.slug}/apply`));
        }
    }
}
</script>
