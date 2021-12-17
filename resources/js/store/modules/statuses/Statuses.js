import {axiosGet} from "../../../common/Helper/AxiosHelper";
import {GET_STATUSES} from "../../../tenant/Config/ApiUrl";

export default {

    state: {
        statuses: [],
    },

    getters: {
        statuses: state => state.statuses,
        statusListForUser: state => state.statuses.filter(item => item.type === 'user'),
        statusListForJobApplicant: state => state.statuses.filter(item => item.type === 'job_applicant'),
        statusListForJobPost: state => state.statuses.filter(item => item.type === 'job_post')
            .map(e => {
                return {
                    id: e.id,
                    name: e.name,
                    translated_name: e.name === 'status_open' ? 'Active Jobs' : (e.name === 'status_closed' ? 'Inactive Jobs' : 'Draft Jobs')
                }
            }),
        statusListForTodo: state => state.statuses.filter(item => item.type === 'todo')
    },

    mutations: {
        SET_STATUS_LIST(state, data) {
            state.statuses = data;
        }

    },

    actions: {
        getStatuses({commit, rootState}, payload = '') {
            rootState.loader.loader = true;
            axiosGet(`${GET_STATUSES}?type=${payload}`).then((res) => {
                commit('SET_STATUS_LIST', res.data);
                rootState.loader.loader = false;
            })
        }
    },
}
