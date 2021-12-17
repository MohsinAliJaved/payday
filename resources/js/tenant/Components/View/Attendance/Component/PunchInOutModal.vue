<template>
    <div>
        <modal id="punch-in-modal"
               size="large"
               v-model="showModal"
               @submit="submit"
               :preloader="preloader"
               :loading="loading"
               :title="!punch ? $t('punch_in') : $t('punch_out')"
               :submit-btn-class="!punch ? 'btn-success' : 'btn-warning'"
               :btn-label="!punch ? $t('punch_in') : $t('punch_out')">
            <div class="d-flex justify-content-between">
                <div v-if="punch" class="d-flex align-items-center mb-4">
                    <div class="icon-box mr-2">
                        <app-icon name="log-out" class="text-warning"/>
                    </div>
                    <div>
                        <p class="text-secondary mb-1">{{ $t('punch_out_date_and_time') }}</p>
                        <h5 class="mb-1">{{ nowDateTime }}</h5>
                        <span class="">{{ nowDate }}</span>
                    </div>
                </div>
                <div class="d-flex align-items-center mb-4">
                    <div class="icon-box mr-2">
                        <app-icon name="log-in" class="text-success"/>
                    </div>
                    <div>
                        <p class="text-secondary mb-1">{{ punch ? $t('punched_in_date_and_time') : $t('punch_in_date_and_time') }}</p>
                        <h5 class="mb-1">{{ punchInDateTime ? punchInDateTime : nowDateTime}}</h5>
                        <span class="">{{ punchInDate ? punchInDate : nowDate }}</span>
                    </div>
                </div>
            </div>
            <form class="mb-0"
                  ref="form"
            >
                <label for="description">{{ !punch ? $t('punch_in_note') : $t('punch_out_note') }}</label>
                <app-input
                        id="description"
                        type="textarea"
                        v-model="formData.note"
                        :text-area-rows="4"
                        :placeholder="!punch ? $t('punch_in_note_here') : $t('punch_out_note_here')"
                />
            </form>
        </modal>
    </div>
</template>

<script>
    import ModalMixin from "../../../../../common/Mixin/Global/ModalMixin";
    import FormHelperMixins from "../../../../../common/Mixin/Global/FormHelperMixins";
    import {TENANT_BASE_URL} from "../../../../../common/Config/UrlHelper";
    import {axiosGet} from "../../../../../common/Helper/AxiosHelper";
    import {
        formatDateForServer,
        formatUtcToLocal,
        formatDateToLocal,
        calenderTime, onlyTime
    } from "../../../../../common/Helper/Support/DateTimeHelper";
    import {ucFirst} from "../../../../../common/Helper/Support/TextHelper";

    export default {
        name: "PunchInOutModal",
        props: {
            punch: {}
        },
        data() {
            return {
                formData: {},
                nowDate: '',
                nowDateTime: '',
                punchInDate: '',
                punchInDateTime: '',
                url: `${TENANT_BASE_URL}employees`,
                preloader: true,
            }
        },
        mixins: [ModalMixin, FormHelperMixins],
        mounted() {
            this.nowTimes();
        },
        created() {
            if (this.punch) {
                this.getPunchInTime();
                this.url = `${this.url}/punch-out`;
            } else {
                this.url = `${this.url}/punch-in`;
                this.preloader = false;
            }
        },
        methods: {
            submit() {
                this.loading = true;
                this.formData.today = formatDateForServer(new Date());
                this.submitFromFixin(`patch`, this.url, this.formData);
            },
            afterSuccess({data}) {
                this.$toastr.s('', data.message);
                this.reloadPunchInOut();
            },
            afterError({data}) {
                this.$toastr.e('', data?.errors?.out_time[0] ? data?.errors?.out_time[0] :
                    data?.errors?.in_time[0] ? data?.errors?.in_time[0]: data.message);
                this.reloadPunchInOut();
            },
            dateNameWithTime(date) {
                let dateName = calenderTime(date, false);
                let time = onlyTime(date);
                return `${ucFirst(dateName)} (${time})`;
            },
            nowTimes() {
                this.nowDate = formatDateToLocal(new Date());
                this.nowDateTime = this.dateNameWithTime(new Date());
                setInterval(this.nowTimes, 30 * 1000);
            },
            getPunchInTime() {
                axiosGet(`${this.url}/punch-in-time`).then(({data}) => {
                    this.punchInDate = formatDateToLocal(data.in_time);
                    this.punchInDateTime = this.dateNameWithTime(data.in_time);
                    this.preloader = false;
                }).catch(({response}) => {
                    this.$toastr.e('', response.data?.errors?.in_time[0]);
                    setTimeout(() => location.reload())
                })
            },
            reloadPunchInOut(){
                this.$hub.$emit('reload-punch-in-out-button');
                this.$hub.$emit('reload-dashboard');
                $("#punch-in-modal").modal('hide');
            }
        }
    }
</script>

<style scoped lang="scss">
    .icon-box {
        width: 90px;
        height: 90px;

        svg {
            width: 26px;
            height: 26px;
        }
    }
</style>