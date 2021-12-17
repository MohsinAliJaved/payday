<template>
    <div>
        <p class="font-size-90 mb-0">
            <a v-if="this.$can('view_employees')" :href="profileUrl(details.assign_by ? details.assign_by : user)">
                {{ details.assign_by ? details.assign_by.full_name : user.full_name}}
            </a>
            <a v-else class="cursor-default" @click.prevent="" href="#">{{ details.assign_by ? details.assign_by.full_name : user.full_name}}</a>
            <span class="text-muted font-size-90">
                {{ details.attendance_details_id ? $t('has_changed') : $t('has_added') }}
            </span>
            {{ $t(punchTitle) }}
            <span class="text-muted font-size-90">
                {{ $t('entry') }}
            </span>
        </p>
        <p class="mb-0 font-size-90 text-muted">
            {{ $t('to') }}
            <span :class="timeClass">
                {{ onlyTime(dateTime) }}
            </span>
            {{ calenderTime(dateTime, false) }}
        </p>
        <p v-if="getComment.comment" class="font-size-80 text-muted mb-0">
            <app-icon name="file-text" class="size-15"/>
            {{ getComment.comment }}
        </p>
    </div>
</template>

<script>
import {calenderTime, onlyTime} from "../../../../../common/Helper/Support/DateTimeHelper";
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";
import {EMPLOYEES_PROFILE} from "../../../../Config/ApiUrl";

export default {
    name: "LogDateTimeWithNote",
    props: {
        details: {},
        user: {},
        type: {},
        className: {},
    },
    data() {
        return {
            onlyTime,
            calenderTime,
        }
    },
    computed: {
        getComment(){
            const field = {'punch-in': 'in-note', 'punch-out': 'out-note'}[this.type];
            return this.collection(this.details.comments.filter(comment => comment.type === field)).first();
        },
        dateTime(){
            return this.type === 'punch-out' ? this.details.out_time : this.details.in_time
        },
        timeClass(){
            return `text-${this.className}`;
        },
        punchTitle(){
            return {'punch-in': 'punch_in', 'punch-out': 'punch_out'}[this.type];
        }
    },
    methods: {
        profileUrl(user) {
            return urlGenerator(`${EMPLOYEES_PROFILE}/${user.id}/profile`)
        }
    }
}
</script>

<style scoped>

</style>