<template>
    <div v-if="preloader" class="height-400 loading-opacity">
        <app-overlay-loader/>
    </div>
    <div v-else class="p-primary">
        <div class="d-flex justify-content-end align-items-center mb-5">
            <a href="#"
               v-if="saveButton"
               class="btn btn-success d-inline-flex align-items-center justify-content-center"
               @click.prevent="saveForm">
                <app-icon name="save" class="size-17 mr-2"/>
                {{ $t('save_changes') }}
            </a>
            <a href="#"
               v-if="viewButton"
               class="btn btn-primary d-inline-flex align-items-center justify-content-center ml-2"
               @click.prevent="viewApplicationForm">
                <app-icon name="eye" class="size-17 mr-2"/>
                {{ $t('view_apply_form') }}
            </a>
        </div>
        <!-- Basic Information -->
        <div class="rounded border mb-5">
            <div class="bg-off-light d-flex align-items-center p-4">
                <app-input type="switch" :disabled="true" v-model="basicInformation.isVisible"/>
                <h6 class="mb-0">
                    <label class="mb-0">
                        {{ basicInformation.title }}
                    </label>
                </h6>
            </div>
            <div class="p-4">
                <div class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-inline-flex align-items-center">
                        <div
                            class="width-30 height-30 text-white rounded d-inline-flex align-items-center justify-content-center bg-primary mr-2">
                            <app-icon :name="basicInformation.icon" class="size-16"/>
                        </div>
                        <p class="text-muted mb-0">
                            <template v-for="(item, fieldIndex) in basicInformation.fields.filter(i=> i.show)">
                                {{ item.name }}<sup v-if="item.require">*</sup>
                                {{ fieldIndex === (basicInformation.fields.filter(i => i.show).length - 1) ? '' : ', ' }}
                            </template>
                        </p>
                    </div>
                    <a href="#"
                       class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                       @click.prevent="openPersonalInfoModal(basicInformation, 'view')">
                        <app-icon name="eye" class="size-14"/>
                    </a>
                </div>
            </div>
        </div>

        <!--Application Form-->

        <div class="rounded border mb-5" v-for="(section, index) in applicationForm">
            <div class="bg-off-light d-flex align-items-center justify-content-between p-4">
                <div class="d-flex align-items-center">
                    <app-input type="switch" v-model="section.isVisible"/>
                    <h6 class="mb-0">
                        <label class="mb-0">
                            {{ section.title }}
                        </label>
                    </h6>
                </div>
                <a href="#"
                   v-if="index > 4"
                   class="text-muted text-left default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                   @click.prevent="deleteSection(index)">
                    <app-icon name="trash-2" class="size-14"/>
                </a>
            </div>
            <div v-if="section.isVisible" class="p-4">
                <!--Custom Field type-->
                <div v-if="section.type==='custom_field'"
                     class="d-flex align-items-center justify-content-between mb-2">
                    <div class="d-inline-flex align-items-center">
                        <div
                            class="width-30 height-30 text-white rounded d-inline-flex align-items-center justify-content-center mr-2"
                            :class="[section.icon === 'bookmark' ? 'bg-info' : 'bg-primary']">
                            <app-icon
                                :name="section.icon"
                                class="size-16"
                            />
                        </div>
                        <p class="text-muted mb-0">
                            <template v-for="(item, fieldIndex) in section.fields.filter(i=> i.show)">
                                {{ item.name }}<sup v-if="item.require">*</sup>
                                {{ fieldIndex === (section.fields.filter(i => i.show).length - 1) ? '' : ', ' }}
                            </template>
                        </p>
                    </div>
                    <a href="#"
                       class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                       @click.prevent="openPersonalInfoModal(section)">
                        <app-icon name="edit" class="size-14"/>
                    </a>
                </div>
                <!--Question type-->
                <template v-if="section.type==='question'">
                    <div class="d-flex align-items-center justify-content-between mb-2"
                         v-for="question in section.fields">
                        <div class="d-inline-flex align-items-center">
                            <div
                                class="width-30 height-30 text-white bg-brand-color rounded d-inline-flex align-items-center justify-content-center mr-2">
                                <app-icon :name="section.icon?section.icon:'align-left'" class="size-14"/>
                            </div>
                            <p class="text-muted mb-0">
                                {{ question.name }}
                            </p>
                        </div>
                        <div class="d-inline-flex align-items-center">
                            <a href="#"
                               class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center mr-2"
                               @click.prevent="openQuestionAddEditModal(question.type, question)">
                                <app-icon name="edit" class="size-14"/>
                            </a>
                            <a href="#"
                               class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                               @click.prevent="deleteQuestion(question, section.key)">
                                <app-icon name="trash-2" class="size-14"/>
                            </a>
                        </div>
                    </div>
                    <div class="dropdown">
                        <button class="btn dropdown-toggle primary-text-color d-inline-flex align-items-center px-0"
                                type="button"
                                id="addQuestion"
                                data-toggle="dropdown"
                                aria-haspopup="true"
                                aria-expanded="false">
                            <app-icon name="plus" class="size-14 mr-2"/>
                            {{ $t('add_new_question') }}
                        </button>
                        <div class="dropdown-menu" aria-labelledby="addQuestion">
                            <a class="dropdown-item text-muted disabled" href="#" disabled="disabled">
                                {{ $t('choose_question_type') }}
                            </a>
                            <a class="dropdown-item"
                               href="#"
                               v-for="questionType in questionTypes"
                               @click.prevent="openQuestionAddEditModal(questionType.type)">
                                {{ questionType.title }}
                            </a>
                        </div>
                    </div>
                </template>
                <!--Assignment & Resume-->
                <div v-if="section.type==='attachment'"
                     class="d-flex align-items-center justify-content-between">
                    <div class="d-inline-flex align-items-center">
                        <div
                            class="width-30 height-30 text-white bg-success rounded d-inline-flex align-items-center justify-content-center mr-2">
                            <app-icon name="file-text" class="size-14"/>
                        </div>
                        <p class="text-muted mb-0">
                            {{ section.fields[0].name }}
                        </p>
                    </div>
                    <div v-if="section.key==='assignment'" class="d-inline-flex align-items-center">
                        <a href="#"
                           class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center"
                           @click.prevent="openAssignmentAddEditModal">
                            <app-icon name="edit" class="size-14"/>
                        </a>
                    </div>
                </div>
            </div>
        </div>
        <button
            v-if="!sectionAdding"
            type="button"
            class="btn primary-text-color d-inline-flex align-items-center px-0" @click.prevent="sectionAdding=true">
            <app-icon name="plus" class="size-14 mr-2"/>
            {{ $t('add_more_section') }}
        </button>
        <div v-if="sectionAdding" class="rounded border mb-5">
            <div class="bg-off-light d-flex align-items-center p-4">
                <app-input
                    type="text"
                    :placeholder="$t('enter_section_name')"
                    v-model="newSectionTitle"
                    @keyup.enter="addNewSection"
                />
                <button type="button"
                        class="btn btn-primary text-capitalize ml-2"
                        @click.prevent="addNewSection">
                    {{ $t('add') }}
                </button>
                <button type="button"
                        class="btn btn-secondary ml-2"
                        @click.prevent="cancelSectionAdding">
                    {{ $t('cancel') }}
                </button>
            </div>
        </div>

        <personal-info-modal
            v-if="isPersonalInfoModalActive"
            :info="selectedInfo"
            :info-modal-action-type="infoModalActionType"
            @openCustomFieldModal="openCustomFieldModal"
            @deleteField="deleteFieldFromInfo"
            @closeModal="closePersonalInfoModal"
        />

        <app-custom-field-modal
            v-if="isCustomFieldModalActive"
            submission-type="event"
            :previous-data="customFieldEditableData"
            @customFieldSubmit="getCustomFieldData"
            @customFieldUpdate="getAndUpdateCustomFieldData"
            @closeModal="closeCustomFieldModal"
        />

        <question-add-edit-modal
            v-if="isQuestionAddEditModalActive"
            :question-type="selectedQuestionType"
            :previous-data="questionEditableData"
            @questionSubmit="addQuestion"
            @questionUpdate="updateQuestion"
            @closeModal="closeQuestionAddEditModal"
        />

        <assignment-add-edit-modal
            v-if="isAssignmentAddEditModalActive"
            :previous-data="assignmentEditableData"
            @assignmentUpdate="updateAssignment"
            @closeModal="closeAssignmentAddEditModal"
        />
    </div>
</template>

<script>
import {FormMixin} from '../../../../core/mixins/form/FormMixin';

export default {
    name: 'ApplyForm',
    mixins: [FormMixin],
    props: {
        formSetting: {},
        viewButton: {
            type: Boolean,
            default: true
        },
        saveButton: {
            type: Boolean,
            default: true
        },
        preloader: {
            type: Boolean,
            default: false
        }
    },
    data() {
        return {
            sectionAdding: false,
            newSectionTitle: '',
            infoModalActionType: '',
            selectedQuestionType: '',
            selectedInfo: '',
            customFieldInfoKey: '',
            customFieldEditableData: '',
            questionEditableData: '',
            assignmentEditableData: '',
            basicInformation: {
                title: this.$t('basic_information'),
                key: 'basic_info',
                type: 'custom_field',
                isVisible: true,
                icon: 'user',
                fields: [
                    {
                        id: 1,
                        name: 'First Name',
                        type: 'text',
                        show: true,
                        require: true
                    },
                    {
                        id: 2,
                        name: 'Last Name',
                        type: 'text',
                        show: true,
                        require: true
                    },
                    {
                        id: 3,
                        name: 'Email',
                        type: 'email',
                        show: true,
                        require: true
                    },
                    {
                        id: 4,
                        name: 'Gender',
                        type: 'radio',
                        show: true,
                        require: true,
                        options: ['Male', 'Female', 'Other']
                    },
                    {
                        id: 5,
                        name: 'Date of birth',
                        type: 'date',
                        show: true,
                        require: false,
                    },
                ]
            },
            applicationForm: [
                {
                    title: this.$t('contact_details'),
                    type: 'custom_field',
                    key: 'contact_details',
                    isVisible: true,
                    icon: 'globe',
                    fields: [
                        {
                            id: 1,
                            name: 'Phone',
                            type: 'tel-input',
                            show: true,
                            require: true
                        },
                        {
                            id: 2,
                            name: 'Address',
                            type: 'textarea',
                            show: true,
                            require: false
                        },
                        {
                            id: 3,
                            name: 'Linkedin',
                            type: 'text',
                            show: true,
                            require: false
                        },
                        {
                            id: 4,
                            name: 'Twitter',
                            type: 'text',
                            show: true,
                            require: false
                        }
                    ]
                },
                {
                    title: this.$t('education_experience'),
                    type: 'custom_field',
                    key: 'education_experience',
                    isVisible: true,
                    icon: 'bookmark',
                    fields: [
                        {
                            id: 1,
                            name: 'Education',
                            type: 'text',
                            show: true,
                            require: false
                        },
                        {
                            id: 2,
                            name: 'Work experience',
                            type: 'text',
                            show: true,
                            require: false,
                        }
                    ]
                },
                {
                    title: this.$t('questions'),
                    type: 'question',
                    key: 'questions',
                    isVisible: true,
                    icon: 'align-left',
                    fields: [
                        {
                            id: 1,
                            name: 'Write something about you...',
                            type: 'text',
                            show: true,
                            require: true
                        },
                        {
                            id: 2,
                            name: 'Why you think you are good for this job?',
                            type: 'text',
                            show: true,
                            require: false,
                        }
                    ]
                },
                {
                    title: this.$t('assignment'),
                    type: 'attachment',
                    key: 'assignment',
                    isVisible: true,
                    icon: 'file-text',
                    fields: [
                        {
                            id: 1,
                            name: 'Write your assignment question',
                            type: 'textarea',
                            show: true,
                            require: true
                        },
                        {
                            id: 2,
                            name: 'Upload your attachment',
                            type: 'dropzone',
                            show: true,
                            require: true
                        }
                    ]
                },
                {
                    title: this.$t('resume_upload'),
                    type: 'attachment',
                    key: 'resume',
                    isVisible: true,
                    icon: 'upload',
                    fields: [
                        {
                            id: 1,
                            name: 'Upload your resume here',
                            type: 'dropzone',
                            show: true,
                            require: true
                        }
                    ]
                }
            ],
            questionTypes: [
                {id: 1, type: 'text', title: 'Text'},
                {id: 2, type: 'textarea', title: 'Textarea'},
                {id: 3, type: 'yes-no', title: 'Yes/No'},
                {id: 4, type: 'radio', title: 'Choose option'},
                {id: 4, type: 'checkbox', title: 'Multiple choice'}
            ],
            isCustomFieldModalActive: false,
            isPersonalInfoModalActive: false,
            isQuestionAddEditModalActive: false,
            isAssignmentAddEditModalActive: false,
        }
    },
    watch: {
        formSetting: {
            handler: function (data) {
                if(data && data.length) this.applicationForm = _.cloneDeep(data);
            },
            immediate: true
        }
    },
    methods: {
        generateId(data) {
            let ids = data.map(i => Number(i.id));
            if (ids.length > 0) return Math.max(...ids) + 1
            else return 1
        },

        getCustomFieldData(arg) {
            let data = _.cloneDeep(arg),
                context = this.applicationForm.find(item => item.key === this.customFieldInfoKey).fields;
            data.show = true;
            data.require = true;
            data.id = this.generateId(context);
            context.push(data);
        },

        getAndUpdateCustomFieldData(arg) {
            let field = this.applicationForm
                .find(item => item.key === this.customFieldInfoKey).fields
                .find(temp => temp.id === this.customFieldEditableData.id);
            field.name = arg.name;
            field.type = arg.type;
            if (arg.options) field.options = arg.options;
        },

        deleteFieldFromInfo(key, field) {
            let information = this.applicationForm.find(item => item.key === key);
            information.fields.splice(information.fields.indexOf(field), 1);
        },

        addQuestion(data) {
            let questions = this.applicationForm
                .find(item => item.key === 'questions').fields;
            data.id = this.generateId(questions);
            questions.push(data);
        },

        updateQuestion(data) {
            let questions = this.applicationForm
                    .find(item => item.key === 'questions').fields,
                question = questions.find(item => item.id === data.id);
            question.name = data.name;
            question.type = data.type;
            question.show = data.show;
            question.require = data.require;
            if (data.options) question.options = data.options;
        },

        deleteQuestion(question, key) {
            let questions = this.applicationForm
                .find(item => item.key === key).fields;
            questions.splice(questions.indexOf(question), 1);
        },

        openPersonalInfoModal(info, type = '') {
            this.infoModalActionType = type;
            this.selectedInfo = info;
            this.isPersonalInfoModalActive = true;
        },

        closePersonalInfoModal() {
            this.infoModalActionType = '';
            this.selectedInfo = '';
            this.isPersonalInfoModalActive = false;
        },

        openCustomFieldModal(key, data) {
            this.customFieldInfoKey = key;
            if (data) this.customFieldEditableData = data;
            this.isCustomFieldModalActive = true;

            setTimeout(() => {
                $('#personal-info-modal').css({
                    opacity: '0.5',
                });
            });
        },

        closeCustomFieldModal() {
            this.customFieldInfoKey = '';
            this.customFieldEditableData = '';
            this.isCustomFieldModalActive = false;
            $('#personal-info-modal').css({
                opacity: '1',
            });
        },

        openQuestionAddEditModal(type, question = null) {
            this.selectedQuestionType = type;
            if (question) this.questionEditableData = question
            this.isQuestionAddEditModalActive = true;
        },

        closeQuestionAddEditModal() {
            this.selectedQuestionType = '';
            this.questionEditableData = '';
            this.isQuestionAddEditModalActive = false;
        },

        openAssignmentAddEditModal() {
            this.assignmentEditableData = this.applicationForm.find(item => item.key === 'assignment').fields[0];
            this.isAssignmentAddEditModalActive = true;
        },

        updateAssignment(data) {
            this.assignmentEditableData.name = data.name;
            this.assignmentEditableData.require = data.require;
        },

        closeAssignmentAddEditModal() {
            this.assignmentEditableData = '';
            this.isAssignmentAddEditModalActive = false;
        },

        addNewSection() {
            if(!this.newSectionTitle) {
                this.$toastr.w(this.$t('section_name_is_required'));
                return;
            }
            let section = {};
            section.title = this.newSectionTitle;
            section.type = 'custom_field';
            section.key = _.snakeCase(_.lowerCase(this.newSectionTitle));
            section.icon = 'box';
            section.isVisible = true;
            section.fields = [];
            this.applicationForm.push(section);
            this.sectionAdding = false;
            this.newSectionTitle = '';
        },

        deleteSection(index) {
            this.applicationForm.splice(index,1);
        },

        cancelSectionAdding() {
            this.sectionAdding = false;
            this.newSectionTitle = '';
        },

        saveForm() {
            this.$emit('update', this.applicationForm);
        },

        viewApplicationForm() {
            this.$emit('viewForm', this.applicationForm);
        },
    }
}
</script>