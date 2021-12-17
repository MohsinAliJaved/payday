import {FormMixin} from '../../../core/mixins/form/FormMixin';
export const FormHelperMixin = {
    mixins: [FormMixin],
    data() {
        return {
            preloader:false,
        }
    },
    methods: {
        beforeSubmit(){
            this.preloader = true;
        },
        afterError(response) {
            this.$toastr.e(response.data.message);
        },
        afterFinalResponse() {
            this.preloader = false;
        }
    }
}