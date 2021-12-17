import Vue from 'vue'

Vue.prototype.$placeholder = (subject, key = null) => {
    return Vue.prototype.$t('place_holder', {
        subject: Vue.prototype.$t(subject).toLowerCase(),
        type: key ? Vue.prototype.$t(key).toLowerCase() : ''
    });
};

Vue.prototype.$fieldTitle = (subject, title = null, titleCapitalize = false, infix = null) => {
    if (title) {
        let translation = Vue.prototype.$t('field_title', {
            subject: Vue.prototype.$t(subject).toLowerCase(),
            infix: infix ? Vue.prototype.$t(infix).toLowerCase() : '',
            title: titleCapitalize ? Vue.prototype.$t(title) : Vue.prototype.$t(title).toLowerCase()
        })
        return translation.split(' ').filter(a => a).join(' ');
    }
    return Vue.prototype.$t(subject);
};

Vue.prototype.$totalLabel = (name) => {
    return Vue.prototype.$t('total_context', {name: Vue.prototype.$t(name).toLowerCase()})
}
Vue.prototype.$rateLabel = (name) => {
    return Vue.prototype.$t('status_wise_rate', {status: Vue.prototype.$t(name).toLowerCase()})
}
Vue.prototype.$allLabel = (name) => {
    return Vue.prototype.$t('all_feature_name', {name: Vue.prototype.$t(name).toLowerCase()})
}
Vue.prototype.$addLabel = (name) => {
    return Vue.prototype.$t('add_feature_name', {name: Vue.prototype.$t(name).toLowerCase()})
}
Vue.prototype.$createLabel = (name) => {
    return Vue.prototype.$t('create_feature_name', {name: Vue.prototype.$t(name).toLowerCase()})
}
Vue.prototype.$editLabel = (name) => {
    return Vue.prototype.$t('edit_feature_name', {name: Vue.prototype.$t(name).toLowerCase()})
}
Vue.prototype.$copyLabel = (name) => {
    return Vue.prototype.$t('copy_feature_name', {name: Vue.prototype.$t(name).toLowerCase()})
}
Vue.prototype.$chooseLabel = (name) => {
    return Vue.prototype.$t('choose_feature_name', {name: Vue.prototype.$t(name).toLowerCase()})
}
Vue.prototype.$fieldLabel = (subject, key = 'name') => {
    return Vue.prototype.$t('field_label', {
        subject: Vue.prototype.$t(subject).toLowerCase(),
        key: key ? Vue.prototype.$t(key).toLowerCase() : '',
    })
}