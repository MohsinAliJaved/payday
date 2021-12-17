import {mapGetters} from "vuex";

export default {
    watch: {
        jobList: {
            handler: function (data) {
                this.options.filters.find(item => item.key === 'job').option = data
            }
        }
    },
    computed: {
        ...mapGetters([
            'jobList'
        ])
    },
    created() {
        this.$store.dispatch('getJobList');
    }
}