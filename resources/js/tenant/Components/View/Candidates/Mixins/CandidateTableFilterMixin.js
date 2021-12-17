import JobFilterMixin from "../../../Mixins/app/JobFilterMixin";
import {mapGetters} from "vuex";

export default {
    mixins: [JobFilterMixin],
    created() {
        this.$store.dispatch('getStatuses');
    },
    computed: {
        ...mapGetters([
            'statusListForJobApplicant'
        ])
    },
    watch: {
        statusListForJobApplicant: {
            handler: function (data) {
                this.options.filters.find(item => item.key === 'status').option = data
            }
        }
    },
}