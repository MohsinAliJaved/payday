export default {
    data() {
        return {
            errors: {},
            errorHandle: false
        }
    },
    methods: {
        afterError(res) {
            this.errors = res.data.errors;
            if(this.errorHandle) this.handleFormSubmissionError('formData', res.data.errors);
        },
        afterFinalResponse() {
            this.preloader = false;
        },
        handleFormSubmissionError(objName = 'formData', errorObj = null) {
            this.fieldStatus = {
                isSubmit: true
            };
            if (!errorObj) return;
            let keys = Object.keys(errorObj);
            if (keys.length < 1) return;
            for (let i = 0; i < keys.length; i++) {
                let fieldKey = objName ? `${objName}_${keys[i]}` : keys[i]
                this.fieldStatus[fieldKey] = {
                    isValid: false,
                    message: errorObj[keys[i]][0]
                }
            }
        }
    }
}