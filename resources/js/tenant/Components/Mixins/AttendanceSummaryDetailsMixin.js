import SelectAbleUserFilterMixin from "./SelectAbleUserFilterMixin";
import NavFilterMixin from "./NavFilterMixin";
import {UserFilterMixin} from "./FilterMixin";

export default {
    mixins: [SelectAbleUserFilterMixin, NavFilterMixin, UserFilterMixin],
    data() {
        return {
            loading: false,
            options: {
                showSearch: false,
                showFilter: true,
                filters: [
                    {
                        title: this.$t('department'),
                        type: 'multi-select-filter',
                        key: 'departments',
                        listValueField: 'name',
                        option: [],
                        permission: !!this.$can('view_departments')
                    },
                    {
                        title: this.$t('work_shift'),
                        type: 'multi-select-filter',
                        key: 'working_shifts',
                        listValueField: 'name',
                        option: [],
                        permission: !!this.$can('view_working_shifts')
                    },
                    {
                        title: this.$t('users'),
                        type: "multi-select-filter",
                        key: "users",
                        option: [],
                        listValueField: 'full_name',
                        permission: !!this.$can('view_all_attendance')  && !!this.$can('view_users')
                    },
                ]

            },
            attendances: [],
            ranges: [],
            defaultWorkingShift: {}
        }
    },
    computed: {
        queryString() {
            return this.$store.getters.getFilterUrls;
        }
    },
}