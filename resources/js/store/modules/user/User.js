import Vue from 'vue';
import {axiosGet, urlGenerator} from '../../../common/Helper/AxiosHelper';
import DateFunction from "../../../core/helpers/date/DateFunction";
import * as actions from '../../../tenant/Config/ApiUrl';
import * as dateTimeFunc from '../../../common/Helper/Support/DateTimeHelper';
import axios from 'axios';

export default {
    state: {
        loggedInUser: {},
        profile_picture: '',
        notificationList: [],
        unreadNotificationCount: 0,
        users: [],
    },

    getters: {
        activeUser: state => state.loggedInUser,
        notificationList: state => state.notificationList,
        totalUnreadCount: state => state.unreadNotificationCount,
        getUsers: state => state.users,
        selectableUsers: state => state.users.map(item => {
            return {
                id: Number(item.id),
                name: item.full_name
            }
        })
    },

    mutations: {
        SET_USERS(state, data) {
            state.users = data
        },
        GET_USER(state, obj) {
            const {rootState, user} = obj;
            state.loggedInUser = user;

            if (user.profile) {
                state.loggedInUser.date_of_birth = user.profile.date_of_birth ? new Date(user.profile.date_of_birth) : null;
                state.loggedInUser.gender = user.profile?.gender;
                state.loggedInUser.contact = user.profile?.contact;
                state.loggedInUser.address = user.profile?.address;
            }

            state.loggedInUser.created_at = DateFunction.getDateFormat(user.created_at, rootState.settings.dateFormat);
            state.loggedInUser.role = user.roles && user.roles.length ? user.roles[0].name : '';
            state.profile_picture = user.profile_picture?.full_url ? user.profile_picture.full_url : urlGenerator('/images/avatar-demo.jpg');
            state.loggedInUser.img = user.profile_picture?.full_url ? user.profile_picture.full_url : urlGenerator('/images/avatar-demo.jpg');
        },
        GET_NOTIFICATIONS(state, obj) {
            const {rootState, notifications, count} = obj,
                timeFormat = rootState.settings.timeFormat,
                dateFormat = rootState.settings.dateFormat;

            state.unreadNotificationCount = Number(count);
            state.notificationList = notifications.map(item => {
                const {data, id, notifier, created_at, read_at} = item,
                    profile_picture = notifier?.profile_picture;

                return {
                    img: profile_picture?.full_url,
                    id: id,
                    name: data.name,
                    title: data.message,
                    description: '',
                    time: dateTimeFunc.getTimeFromDateTime(created_at, timeFormat),
                    date: dateTimeFunc.getDateFromNow(created_at, dateFormat),
                    status: read_at == null ? Vue.prototype.$t('new') : Vue.prototype.$t('old'),
                    url: ''
                }
            });
            state.notificationList['total_unread'] = notifications.filter(notification => {

                return notification.status == null;
            }).length;
        },
    },

    actions: {
        getUser({commit, rootState}) {
            axiosGet(actions.LOGIN_USER).then(res => {
                const obj = {
                    user: res.data,
                    rootState
                };
                commit('GET_USER', obj);
            });
        },
        addUser({commit, state}, user) {
            commit('SET_USERS', state.users.concat([user]));
        },
        getNotifications({commit, rootState}, nextPage = actions.NOTIFICATIONS) {
            const url = nextPage === actions.NOTIFICATIONS ? `${urlGenerator(nextPage)}?per_page=4&unread=1` : `${nextPage}&per_page=4&unread=1`;
            axios.get(url).then(({data}) => {
                let obj = {
                    count: data.total,
                    notifications: data.data,
                    rootState
                };
                commit('GET_NOTIFICATIONS', obj);
            })
        },
        getUsers({commit, state}, payload = {}) {
            const url = payload.users ? `${actions.GET_USERS}?existing=${payload.users}` : actions.GET_USERS;
            axiosGet(url).then(({data}) => {
                commit('SET_USERS', data)
            });
        },
    }
}
