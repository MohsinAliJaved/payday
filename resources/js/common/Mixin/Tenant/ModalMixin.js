export const ModalMixin = {
    props: {
        data: {
            default: null
        }
    },
    data() {
        return {
            preloader: false,
        }
    },
    created() {
        if (this.selectedUrl) this.preloader = true;
    },
    methods: {
        closeModal() {
            if (this.modalId) $(`#${this.modalId}`).modal('hide');
            this.$emit('closeModal');
        },
        beforeSubmit() {
            this.preloader = true;
        },
        afterError(res) {
            this.$toastr.e(res.data.message);
        },
        afterFinalResponse() {
            this.preloader = false;
            this.closeModal();
        }
    }
};
