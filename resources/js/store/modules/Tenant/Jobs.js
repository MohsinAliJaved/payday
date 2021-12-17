import {axiosGet} from "../../../common/Helper/AxiosHelper";
import * as URL from "../../../common/Config/apiUrl";

export default {

    state: {
        selectedJobTitle: '',
        selectedJob: null,
        jobList: [],
        jobTypeList: [],
        departmentList: [],
        stageList: [],
        companyLocationList: [],
        eventTypeList:[]
    },

    getters: {
        selectedJobDetails: state => state.selectedJob,
        jobList: state => state.jobList,
        jobTypeList: state => state.jobTypeList,
        departmentList: state => state.departmentList,
        stageList: state => state.stageList,
        companyLocationList: state => state.companyLocationList,
        eventTypeList: state => state.eventTypeList,
    },

    mutations: {
        SET_JOB_TITLE(state, data= '') {
            state.selectedJobTitle = data;
        },
        SET_JOB_DETAILS(state, data) {
            state.selectedJob = data;
        },
        SET_JOB_LIST(state, data) {
            state.jobList = data;
        },
        SET_JOB_TYPE(state, data) {
            state.jobTypeList = data;
        },
        SET_DEPARTMENT_LIST(state, data) {
            state.departmentList = data;
        },
        SET_STAGE_LIST(state, data) {
            state.stageList = data;
        },
        SET_COMPANY_LOCATION(state, data) {
            state.companyLocationList = data;
        },
        SET_EVENT_TYPES(state, data) {
            state.eventTypeList = data;
        },
    },

    actions: {
        getJobDetails({commit, rootState}, payload) {
            // rootState.loader.loader = true;
            axiosGet(`${URL.JOB}/${payload}`).then((res)=> {
                commit('SET_JOB_DETAILS', res.data);
                // rootState.loader.loader = false;
            })
        },
        getJobList({commit, rootState}) {
            // rootState.loader.loader = true;
            axiosGet(URL.SELECTABLE_JOBS).then((res)=> {
                commit('SET_JOB_LIST', res.data);
                // rootState.loader.loader = false;
            })
        },
        getJobTypeList({commit, rootState}) {
            // rootState.loader.loader = true;
            axiosGet(URL.SELECTABLE_JOB_TYPES).then((res)=> {
                commit('SET_JOB_TYPE', res.data);
                // rootState.loader.loader = false;
            })
        },
        getDepartmentList({commit, rootState}) {
            // rootState.loader.loader = true;
            axiosGet(URL.SELECTABLE_DEPARTMENTS).then((res)=> {
                commit('SET_DEPARTMENT_LIST', res.data);
                // rootState.loader.loader = false;
            })
        },
        getStagesList({commit, rootState}) {
            // rootState.loader.loader = true;
            axiosGet(URL.SELECTABLE_STAGES).then((res)=> {
                commit('SET_STAGE_LIST', res.data);
                // rootState.loader.loader = false;
            })
        },
        getCompanyLocationList({commit, rootState}) {
            // rootState.loader.loader = true;
            axiosGet(URL.SELECTABLE_COMPANY_LOCATIONS).then((res)=> {
                commit('SET_COMPANY_LOCATION', res.data);
                // rootState.loader.loader = false;
            })
        },
        getEventTypes({commit, rootState}) {
            // rootState.loader.loader = true;
            axiosGet(URL.SELECTABLE_EVENT_TYPES).then((res)=> {
                commit('SET_EVENT_TYPES', res.data);
                // rootState.loader.loader = false;
            })
        },
    },
}
