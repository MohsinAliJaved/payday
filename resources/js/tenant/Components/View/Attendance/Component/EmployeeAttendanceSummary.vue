<template>
    <div class="row my-5">
        <div class="col-lg-4 mb-4 mb-lg-0" v-if="isChartVisible">
            <div class="row align-items-center justify-content-center">
                <div class="col-6 col-md-5 col-lg-5">
                    <app-chart
                        type="dough-chart"
                        :height="170"
                        :labels="labels"
                        :data-sets="dataSet"
                    />
                </div>
                <div class="col-6 col-md-5 col-lg-7">
                    <div class="chart-data-list">
                        <div class="data-group-item" style="color: #f96868">
                            <span class="square" style="background-color: #f96868"/>
                            {{ $t('regular') }}
                            <span class="value">{{ summaries.regular || 0 }} {{ $t('days') }}</span>
                        </div>
                        <div class="data-group-item" style="color: #4466F2">
                            <span class="square" style="background-color: #4466F2"/>
                            {{ $t('early') }}
                            <span class="value">{{ summaries.early || 0 }} {{ $t('days') }}</span>
                        </div>
                        <div class="data-group-item" style="color: #c22e78">
                            <span class="square" style="background-color: #c22e78"/>
                            {{ $t('late') }}
                            <span class="value">{{ summaries.late || 0 }} {{ $t('days') }}</span>
                        </div>
                        <div class="data-group-item" style="color: #6a008a">
                            <span class="square" style="background-color: #6a008a"/>
                            {{ $t('on_leave') }}
                            <span class="value">{{ numberFormatter(summaries.on_leave) }} {{ $t('days') }}</span>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <div :class="`${isChartVisible ? 'col-lg-8' : 'col-lg-12'}`">
            <div class="row">
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="default-base-color text-center rounded p-3">
                        <h5>{{ summaries.scheduled }} h</h5>
                        <p class="text-muted mb-0">{{ $t('total_schedule_hour') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="default-base-color text-center rounded p-3">
                        <h5>{{ summaries.paid_leave }} {{ $t('hour_short_form') }}</h5>
                        <p class="text-muted mb-0">Leave hour (paid)</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3">
                    <div class="default-base-color text-center rounded p-3">
                        <h5 :class="`text-${summaries.availability_behavior_class}`">{{ summaries.percentage }}%</h5>
                        <p class="text-muted mb-0">{{ $t('total_work_availability') }}</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3 mb-lg-0">
                    <div class="default-base-color text-center rounded p-3">
                        <h5>{{ summaries.worked }} {{ $t('hour_short_form') }}</h5>
                        <p class="text-muted mb-0">
                            {{ $t('total_active_hour') }}
                        </p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4 mb-3 mb-lg-0">
                    <div class="default-base-color text-center rounded p-3">
                        <h5>{{ summaries.balance }} {{ $t('hour_short_form') }}</h5>
                        <p class="text-muted mb-0">{{ $t('balance') }} ({{ summaries.balance_behavior }})</p>
                    </div>
                </div>
                <div class="col-md-6 col-lg-4">
                    <div class="default-base-color text-center rounded p-3">
                        <h5 :class="`text-${summaries.average_class}`">{{ $t(summaries.average) }}</h5>
                        <p class="text-muted mb-0">
                            {{ $t('average_behavior') }}
                        </p>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>

import {numberFormatter} from "../../../../../common/Helper/Support/SettingsHelper";

export default {
    name: "EmployeeAttendanceSummary",
    props: {
        summaries: {
            default: function () {
                return {};
            }
        },
        data: {
            default: function () {
                return [0, 0, 0, 0]
            }
        }
    },
    data() {
        return {
            numberFormatter,
            dataSet: [
                {
                    borderWidth: 0,
                    backgroundColor: [
                        '#f96868',
                        '#4466F2',
                        '#c22e78',
                        '#6a008a'
                    ],
                    data: this.data
                },
            ],
            labels: [this.$t('regular'), this.$t('early'), this.$t('late'), this.$t('on_leave')],
        }
    },
    computed: {
        isChartVisible() {
            return this.data.reduce((sum, current) => parseInt(sum) + parseInt(current))
        }
    }
}
</script>
