<template>
    <apply-form-settings
        :form-setting="applicationForm"
        :preloader="preloader"
        :view-button="false"
        @update="updateSetting"
    />
</template>

<script>

import CoreLibrary from "../../../../../../core/helpers/CoreLibrary";
import {GLOBAL_APPLICATION_FORM} from "../../../../../Config/ApiUrl";

export default {
    name: 'ApplicationFormSetting',
    extends: CoreLibrary,
    data() {
        return {
            applicationForm: [],
            preloader: false,
        }
    },
    created() {
        this.getSetting();
    },
    methods: {
        getSetting() {
            this.preloader = true;
            this.axiosGet(GLOBAL_APPLICATION_FORM).then(res => {
                this.applicationForm = res.data?.application_form ? res.data?.application_form : '';
            }).finally(()=> {
                this.preloader = false
            })
        },
        updateSetting(data) {
            this.preloader = true;
            this.axiosPatch({
                url: GLOBAL_APPLICATION_FORM,
                data: {
                    application_form: data
                }
            }).then(res => {
                this.$toastr.s(res.data.message);
                this.getSetting();
            })
        }
    }
}
</script>