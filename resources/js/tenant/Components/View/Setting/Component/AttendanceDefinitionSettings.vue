<template>
    <app-overlay-loader v-if="preloader"/>
    <form v-else method="post" ref="form" :data-url="apiUrl.ATTENDANCE_SETTINGS">
        <app-form-group
            page="page"
            :label="`${$t('punch_in_time_tolerance')}(${$t('minutes')})`"
            :recommendation="$t('punch_in_time_tolerance_recommendation')"
            v-model="formData.punch_in_time_tolerance"
            type="number"
            :error-message="$errorMessage(errors, 'punch_in_time_tolerance')"
            label-alignment="top">
            <template slot="suggestion">
                <div class="d-flex mt-4">
                    <div class="text-center mr-4">
                        <span class="badge badge-warning rounded-pill mb-2">
                            {{ $t('early') }}
                        </span>
                        <p class="text-muted mb-0">
                            {{ $t('before_on_time') }}
                        </p>
                    </div>
                    <div class="text-center mr-4">
                        <span class="badge badge-success rounded-pill mb-2">
                            {{ $t('regular') }}
                        </span>
                        <p class="text-muted mb-0">
                            {{ $t('on_time_tolerance') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <span class="badge badge-danger rounded-pill mb-2">
                            {{ $t('late') }}
                        </span>
                        <p class="text-muted mb-0">
                            {{ $t('after_tolerance') }}
                        </p>
                    </div>
                </div>
            </template>
        </app-form-group>

        <app-form-group
            page="page"
            :label="`${$t('work_availability_definition')}(${$t('percentage')})`"
            :recommendation="$t('work_availability_definition_recommendation')"
            v-model="formData.work_availability_definition"
            type="number"
            :error-message="$errorMessage(errors, 'work_availability_definition')"
            label-alignment="top">
            <template slot="suggestion">
                <div class="d-flex mt-4">
                    <div class="text-center mr-4">
                        <span class="badge badge-success rounded-pill mb-2">
                            {{ $t('good') }}
                        </span>
                        <p class="text-muted mb-0">
                            {{ $t('equal_to_or_above_of_percentage') }}
                        </p>
                    </div>
                    <div class="text-center">
                        <span class="badge badge-danger rounded-pill mb-2">
                            {{ $t('bad') }}
                        </span>
                        <p class="text-muted mb-0">
                            {{ $t('bellow_percentage') }}
                        </p>
                    </div>
                </div>
            </template>
        </app-form-group>

        <div class="row">
            <div class="col-12">
                <app-submit-button :label="$t('save')" :loading="loading" @click="submitData"/>
            </div>
        </div>
    </form>
</template>

<script>
import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
import {axiosGet} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "AttendanceDefinitionSettings",
    mixins: [FormHelperMixins],
    data() {
        return {
            work_availability_definition: 80,
            punch_in_time_tolerance: 15
        }
    },
    methods: {
        afterSuccess(response) {
            this.toastAndReload(response.data.message)
        },
        getAttendanceSettings() {
            this.preloader = true;
            axiosGet(this.apiUrl.ATTENDANCE_SETTINGS).then(({data}) => {
                this.formData = data;
            }).finally(() => this.preloader = false)
        }
    },
    mounted() {
        this.getAttendanceSettings();
    }
}
</script>
