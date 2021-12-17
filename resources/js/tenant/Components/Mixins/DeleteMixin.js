import AppFunction from "../../../core/helpers/app/AppFunction";
import AxiosFunction from "../../../core/helpers/axios/AxiosFunction";

export default {
    data() {
        return {
            deleteLoader: false
        }
    },
    methods: {
        deleteAndReload(url) {
            this.deleteLoader = true;
            let deleteUrl = AppFunction.getAppUrl(url);
            AxiosFunction.axiosDelete(deleteUrl)
                .then((response) => {
                    if (this.deleteModalId) $(`#${this.deleteModalId}`).modal('hide');
                    if (typeof this.closeDeleteModal === 'function') this.closeDeleteModal();
                    if (this.tableId) this.$hub.$emit(`reload-${this.tableId}`);
                    this.$toastr.s(response.data.message);
                })
                .catch(({response}) => {
                    this.$toastr.e(response.data.message);
                })
                .finally(() => {
                    this.deleteLoader = false;
                })
        }
    }
}