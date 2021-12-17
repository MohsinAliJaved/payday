<template>
    <div v-if="multiDay">
        <div>{{ `${this.$t('from')} ${leaveDateAndTimeFormat(start_at)}` }}</div>
        <div>{{ `${this.$t('to')} ${leaveDateAndTimeFormat(end_at)}` }}</div>
    </div>
    <div v-else-if="hours">
        <div>{{ `${this.$t('from')} ${onlyTime(start_at)}` }}</div>
        <div>{{ `${this.$t('to')} ${leaveDateAndTimeFormat(end_at)}` }}</div>
    </div>
    <div v-else>
        <div>{{ `${calenderTime(start_at, false)}` }}</div>
    </div>
</template>

<script>
import {calenderTime, onlyTime} from "../../../../../common/Helper/Support/DateTimeHelper";
import {leaveDateAndTimeFormat} from "../Helper/Helper";

export default {
    name: "LeaveDateAndTime",
    props: {
        value: {},
        rowData: {}
    },
    data(){
        return {
            calenderTime,
            leaveDateAndTimeFormat,
            onlyTime,
        }
    },
    computed: {
        start_at(){
            return this.rowData.start_at;
        },
        end_at(){
            return this.rowData.end_at;
        },
        multiDay(){
            return this.rowData.duration_type === 'multi_day';
        },
        hours(){
            return this.rowData.duration_type === 'hours';
        }
    }
}
</script>

<style scoped>

</style>