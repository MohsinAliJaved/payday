<template>
    <div>
        <div class="candidate-application-form">
            <div class="candidate-step-menu custom-scrollbar">
                <div class="toggle-sidebar" @click="toggleSidebar">
                    <div class="bar1"/>
                    <div class="bar2"/>
                    <div class="bar3"/>
                </div>
                <div class="nav flex-column nav-pills" id="v-pills-tab" role="tablist" aria-orientation="vertical">
                    <a class="nav-link"
                       :class="[{'active':checkActiveTab(basicInformation.key)}, {'complete':checkCompleteTab(basicInformation.key)}]"
                       :id="`v-pills-${basicInformation.key}-tab`">
                        <span class="step-number">
                            01
                            <span v-if="checkCompleteTab(basicInformation.key)" class="complete-icon">
                                <app-icon name="check"/>
                            </span>
                            <span class="step-divider"><span class="divider"/></span>
                        </span>
                        <span class="step-name">{{ basicInformation.title }}</span>
                    </a>
                    <a v-for="(item, index) in applyForm" class="nav-link"
                       :class="[{'active':checkActiveTab(item.key)}, {'complete':checkCompleteTab(item.key)}]"
                       :id="`v-pills-${item.key}-tab`">
                        <span class="step-number">
                            {{ `${(index + 2) > 10 ? (index + 2) : `0${index + 2}`}` }}
                            <span v-if="checkCompleteTab(item.key)" class="complete-icon">
                                <app-icon name="check"/>
                            </span>
                            <span class="step-divider"><span class="divider"/></span>
                        </span>
                        <span class="step-name">{{ item.title }}</span>
                    </a>
                    <a class="nav-link"
                       :class="[{'active':checkActiveTab(finalStep.key)}, {'complete':checkCompleteTab(finalStep.key)}]"
                       :id="`v-pills-${finalStep.key}-tab`">
                        <span class="step-number">
                            {{ `${(applyForm.length + 2) > 10 ? (applyForm.length + 2) : `0${applyForm.length + 2}`}` }}
                            <span v-if="checkCompleteTab(finalStep.key)" class="complete-icon">
                                <app-icon name="check"/>
                            </span>
                            <span class="step-divider"><span class="divider"/></span>
                        </span>
                        <span class="step-name">{{ finalStep.title }}</span>
                    </a>
                </div>
            </div>
            <div class="candidate-step-content">
                <div class="tab-content" id="v-pills-tabContent">
                    <div class="tab-pane fade"
                         :class="{'show active':checkActiveTab(basicInformation.key)}"
                         :id="`v-pills-${basicInformation.key}`">
                        <div class="row">
                            <div class="col-lg-9 col-xl-7">
                                <div>
                                    <h4 class="mb-2">
                                        {{ basicInformation.title }}
                                    </h4>
                                    <div v-for="(field, basicIndex) in basicInformation.fields" class="row mb-4">
                                        <div v-if="field.show" class="col-12">
                                            <label :for="nameGen(field.name)">{{ field.name }}<sup v-if="field.require">*</sup></label>
                                            <app-input
                                                :type="field.type"
                                                :id="nameGen(field.name)"
                                                :list="listGen(field.options, field.type)"
                                                :disabled="previousCandidate"
                                                :error-message="$errorMessage(errorObj, nameGen(field.name), false)"
                                                :required="field.require"
                                                v-model="submitData.basic_information[nameGen(field.name)]"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane-action d-flex justify-content-end">
                                    <button type="button" @click.prevent="goToQuestionStep">
                                        {{ $t('next') }}
                                        <app-icon name="chevron-right" class="ml-2"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div v-for="(item, parentIndex) in applyForm" class="tab-pane fade"
                         :class="{'show active':checkActiveTab(item.key)}">
                        <div class="row">
                            <div class="col-lg-9 col-xl-7">
                                <div>
                                    <h4 class="mb-2">
                                        {{ item.title }}
                                    </h4>
                                    <div v-for="(field, childIndex) in item.fields" class="row mb-4">
                                        <div v-if="field.show" class="col-12">
                                            <label :for="nameGen(field.name)">{{ field.name }}<sup v-if="field.require">*</sup></label>
                                            <app-input
                                                :type="field.type"
                                                :id="nameGen(field.name)"
                                                :list="listGen(field.options, field.type)"
                                                :radio-checkbox-name="(field.type === 'radio' || field.type === 'checkbox')? nameGen(field.name) : ''"
                                                :error-message="$errorMessage(errorObj, nameGen(field.name), false)"
                                                :required="field.require"
                                                v-model="submitData[item.key][nameGen(field.name)]"
                                            />
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane-action">
                                    <button
                                        type="button"
                                        @click.prevent="goPrevQuesStep(parentIndex, item.key, parentIndex > 0 ? applyForm[parentIndex-1].key : '')">
                                        <app-icon name="chevron-left" class="mr-2"/>
                                        {{ $t('previous') }}
                                    </button>
                                    <button
                                        type="button"
                                        @click.prevent="goNextQuesStep(parentIndex, item.key, parentIndex < (applyForm.length - 1) ? applyForm[parentIndex+1].key : '')">
                                        {{ $t('next') }}
                                        <app-icon name="chevron-right" class="ml-2"/>
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="tab-pane fade"
                         :class="{'show active':checkActiveTab(finalStep.key)}"
                         :id="`v-pills-${finalStep.key}`">
                        <div class="row">
                            <div class="col-lg-9 col-xl-7">
                                <div>
                                    <h4 class="mb-2">
                                        {{ finalStep.title }} - {{ appliedDone ? $t('submitted') : $t('in_progress') }}
                                    </h4>
                                    <div v-if="activeTab==='submit'" class="row mb-4">
                                        <div class="col-12 min-height-300 py-primary">
                                            <h5 class="mb-3">{{ basicInformation.title }}</h5>
                                            <table class="table table-borderless shadow font-size-90">
                                                <tbody>
                                                <tr>
                                                    <td class="text-muted width-150">{{ $t('first_name') }}</td>
                                                    <td>{{ submitApplicant.first_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted width-150">{{ $t('last_name') }}</td>
                                                    <td>{{ submitApplicant.last_name }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted width-150">{{ $t('email') }}</td>
                                                    <td>{{ submitApplicant.email }}</td>
                                                </tr>
                                                <tr>
                                                    <td class="text-muted width-150">{{ $t('gender') }}</td>
                                                    <td class="text-capitalize">{{ submitApplicant.gender }}</td>
                                                </tr>
                                                <tr v-if="submitApplicant.date_of_birth">
                                                    <td class="text-muted width-150">{{ $t('date_of_birth') }}</td>
                                                    <td>{{ formatDateToLocal(submitApplicant.date_of_birth) }}</td>
                                                </tr>
                                                </tbody>
                                            </table>
                                            <h5 class="mt-3">{{ $t('others_information') }}</h5>
                                            <template v-if="tempFormData.question_answer.length > 0">
                                                <div v-for="(ans, ansIndex) in tempFormData.question_answer"
                                                     v-if="ans.answer"
                                                     class="card border-0 shadow mt-3 p-2">
                                                    <p class="mb-0">{{ questionTitle(ans.question) }}</p>
                                                    <p class="mb-0 text-muted">{{ ans.answer }}</p>
                                                </div>
                                            </template>
                                        </div>
                                    </div>
                                </div>
                                <div class="tab-pane-action" :class="{'justify-content-end':appliedDone}">
                                    <button
                                        v-if="!appliedDone"
                                        type="button" @click.prevent="goToQuestionLastStep">
                                        <app-icon name="chevron-left" class="mr-2"/>
                                        {{ $t('previous') }}
                                    </button>
                                    <app-load-more
                                        :preloader="preloader"
                                        :label="$t('submit')"
                                        :class-name="`shadow-none ${appliedDone ? 'disabled' : ''}`"
                                        @submit="submitApplication"
                                    />
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
        <!-- Email Verification Modal -->
        <candidate-email-verification-modal
            v-if="emailVerificationModal"
            :job-post-id="applyFormData.id"
            @dontVerified="goToJobPostView"
            @verifiedData="afterVerifiedEmail"
        />
    </div>
</template>

<script>
import {FormMixin} from "../../../../core/mixins/form/FormMixin";
import DateFunction from "../../../../core/helpers/date/DateFunction";
import {formatDateToLocal} from "../../../../common/Helper/Support/DateTimeHelper";
import {PUBLIC_JOB_POST} from "../../../Config/ApiUrl";

export default {
    name: 'CandidateApplicationForm',
    mixins: [FormMixin],
    props: {
        applyFormData: {}
    },
    data() {
        return {
            formatDateToLocal,
            emailVerificationModal: false,
            previousCandidate: false,
            // Error Handler
            errorObj: {},

            // Other Data
            finalStep: {
                key: 'submit',
                title: this.$t('submit_application')
            },
            basicInformation: {
                title: this.$t('basic_information'),
                key: 'basic_info',
                type: 'custom_field',
                isVisible: true,
                icon: 'user',
                fields: [
                    {
                        id: 1,
                        name: this.$t('first_name'),
                        type: 'text',
                        show: true,
                        require: true
                    },
                    {
                        id: 2,
                        name: this.$t('last_name'),
                        type: 'text',
                        show: true,
                        require: true
                    },
                    {
                        id: 3,
                        name: this.$t('email'),
                        type: 'email',
                        show: true,
                        require: true
                    },
                    {
                        id: 4,
                        name: this.$t('gender'),
                        type: 'radio',
                        show: true,
                        require: true,
                        options: [this.$t('male'), this.$t('female'), this.$t('other')]
                    },
                    {
                        id: 5,
                        name: this.$t('date_of_birth'),
                        type: 'date',
                        show: true,
                        require: false,
                    },
                ]
            },
            submitData: {
                basic_information: {}
            },
            tempFormData: {
                question_answer: []
            },

            // Styles
            appliedDone: false,
            preloader: false,
            activeTab: 'basic_info',
            completeTabs: [],
        }
    },
    computed: {
        applyForm() {
            let data = typeof this.applyFormData['apply_form_settings'] === 'string' ?
                JSON.parse(this.applyFormData['apply_form_settings']) : this.applyFormData['apply_form_settings'];
            return data.filter(item => item.isVisible);
        },
        questions() {
            let question = []
            this.applyForm.forEach(item => {
                question = [...question, ...item.fields]
            })
            return question.map(el => el.name);
        },
        submitApplicant() {
            return this.submitData.basic_information
        }
    },
    beforeMount() {
        this.applyForm.forEach(item => {
            this.submitData[item.key] = {};
        })
    },
    mounted() {
        this.emailVerificationModal = true;
    },
    methods: {
        afterVerifiedEmail(email, data) {
            if (data) {
                this.submitData.applicant_id = data.id;
                this.submitData.basic_information = data;
                this.previousCandidate = true;
            } else this.submitData.basic_information.email = email
            this.emailVerificationModal = false;
        },
        toggleSidebar() {
            $('.toggle-sidebar').toggleClass('change');
            $('.candidate-step-menu').toggleClass('active');
        },
        checkActiveTab(tab) {
            return this.activeTab === tab
        },
        checkCompleteTab(tab) {
            return this.completeTabs.includes(tab)
        },
        questionTitle(ques) {
            let question = this.questions.find(item => this.nameGen(item) === ques)
            return question ? question : '';
        },

        /***
         * Methods for generating Form
         ***/

        nameGen(name) {
            return _.snakeCase(_.lowerCase(name));
        },
        listGen(options, type) {
            if (!(type === 'radio' || type === 'checkbox' || type === 'select')) return [];
            if (this.isUndefined(options) || options.length < 1) return [];
            return options.map(item => {
                return {
                    id: this.nameGen(item),
                    value: item
                }
            })
        },
        goToQuestionStep() {
            if (this.submitData.applicant_id) {
                this.tempFormData.applicant_id = this.submitData.applicant_id;
                this.readyToGoQuestionStep();
                return;
            }
            this.checkBasicInfoValidation();
        },
        checkBasicInfoValidation() {
            this.errorObj = {};
            this.fieldStatus = {
                isSubmit: true
            };
            this.basicInformation.fields.forEach(item => {
                let name = this.nameGen(item.name);
                if (item.require && !this.submitData.basic_information[name]) {
                    this.errorObj[name] = this.$t('this_field_is_required')
                }
                if (item.type === 'email' && !this.isValidEmail(this.submitData.basic_information[name])) {
                    this.errorObj[name] = this.$t('this_field_is_invalid')
                }
                if (item.type === 'date' && this.submitData.basic_information[name]) {
                    this.submitData.basic_information[name] = DateFunction.getDateFormatForBackend(this.submitData.basic_information[name])
                }
            })
            if (Object.keys(this.errorObj).length < 1) {
                this.tempFormData.basic_information = this.submitData.basic_information;
                this.readyToGoQuestionStep();
            }
        },
        readyToGoQuestionStep() {
            this.completeTabs.push(this.basicInformation.key);
            if (this.applyForm.length > 0)
                this.activeTab = this.applyForm[0].key;
            else {
                this.prepareFormData();
                this.activeTab = this.finalStep.key;
            }
        },

        /***
         * Questions Steps
         ***/
        goNextQuesStep(index, currentKey, nextKey) {
            this.checkQuestionsValidation(index, currentKey, nextKey)
        },
        goPrevQuesStep(index, currentKey, prevKey) {
            if (prevKey) this.activeTab = prevKey;
            else this.activeTab = 'basic_info';
        },

        checkQuestionsValidation(index, key, nextKey) {
            this.errorObj = {};
            this.fieldStatus = {
                isSubmit: true
            };
            this.applyForm[index].fields.forEach(item => {
                if(!item.show) return;
                let name = this.nameGen(item.name);
                if (item.require && !this.submitData[key][name]) {
                    this.errorObj[name] = this.$t('this_field_is_required')
                }
                if (item.type === 'email' && !this.isValidEmail(this.submitData[key][name])) {
                    this.errorObj[name] = this.$t('this_field_is_invalid')
                }
                if (item.type === 'dropzone' && item.require && !this.submitData[key][name]?.length) {
                    this.errorObj[name] = this.$t('this_field_is_required');
                }
                if (item.type === 'dropzone' && this.submitData[key][name]?.length > 1) {
                    this.errorObj[name] = this.$t('this_field_require_only_one_attachment');
                }
                if (item.type === 'dropzone' && this.submitData[key][name]?.length === 1) {
                    let fileName = this.submitData[key][name][0].name.toLowerCase().split('.'),
                        extension = fileName[fileName.length - 1],
                        acceptableTypes = ['pdf', 'docx', 'doc']
                    if (!acceptableTypes.includes(extension)) {
                        this.errorObj[name] = this.$t('this_field_format_should_be_pdf_doc_docx');
                    }
                }
                if (item.type === 'date' && this.submitData[key][name]) {
                    this.submitData[key][name] = DateFunction.getDateFormatForBackend(this.submitData[key][name])
                }
            })
            if (Object.keys(this.errorObj).length < 1) {
                this.completeTabs.push(key);
                if (nextKey) this.activeTab = nextKey;
                else {
                    this.prepareFormData();
                    this.activeTab = this.finalStep.key;
                }
            }
        },
        goToQuestionLastStep() {
            if(this.applyForm.length > 0)
            this.activeTab = this.applyForm[this.applyForm.length - 1].key;
            else this.activeTab = 'basic_info';
        },
        submitApplication() {
            this.completeTabs.push(this.finalStep.key);
            this.preloader = true;
            this.finalSubmit();
        },
        prepareFormData() {
            this.tempFormData.question_answer = [];
            this.applyForm.forEach(item => {
                item.fields.forEach(el => {
                    let ans = this.submitData[item.key][this.nameGen(el.name)];
                    if (ans) {
                        this.tempFormData.question_answer.push({
                            question: this.nameGen(el.name),
                            answer: typeof ans === 'string' ? ans :
                                ((Array.isArray(ans) && typeof ans[0] === 'string') ? ans.toString() : ''),
                            attachment: (Array.isArray(ans) && typeof ans[0] === 'object') ? ans[0] : ''
                        })
                    }
                })
            })
        },
        finalSubmit() {
            this.tempFormData.apply_form_setting = typeof this.applyFormData['apply_form_settings'] === 'string' ?
                JSON.parse(this.applyFormData['apply_form_settings']) : this.applyFormData['apply_form_settings'];
            let url = `${PUBLIC_JOB_POST}/${this.applyFormData.slug}/apply`,
                formData = new FormData;

            for (const [key, value] of Object.entries(this.tempFormData)) {
                if (key === 'question_answer') {
                    let index = 0;
                    value.forEach(item => {
                        formData.append(`question_answer[${index}][question]`, item.question);
                        formData.append(`question_answer[${index}][attachment]`, item.attachment);
                        formData.append(`question_answer[${index}][answer]`, item.answer);
                        index++;
                    })
                } else if (key === 'basic_information') {
                    for (const [field, data] of Object.entries(value)) {
                        formData.append(`basic_information[${field}]`, data)
                    }
                } else if (key === 'applicant_id') {
                    formData.append('applicant_id', value);
                } else if (key === 'apply_form_setting') {
                    formData.append('apply_form_setting', JSON.stringify(value));
                }
            }

            this.axiosPost({url, data: formData}).then((res) => {
                this.preloader = false;
                this.$toastr.s(res.data.message);
                this.appliedDone = true;
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            }).finally(() => {
                this.preloader = false;
            })
        },
        goToJobPostView() {
            window.location = this.getAppUrl(`${PUBLIC_JOB_POST}/${this.applyFormData.slug}/display`);
        }
    }
}
</script>