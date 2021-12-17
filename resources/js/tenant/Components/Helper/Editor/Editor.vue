<template>
    <div class="content-wrapper p-0">
        <div class="editor-wrapper">
            <div class="editor-navbar">
                <ul class="nav nav-left">
                    <li class="nav-item">
                        <a class="nav-link"
                           :class="{'active': activePreview === 'desktop'}"
                           href="#"
                           @click="toggleResponsivePreview('desktop')">
                            <app-icon name="monitor" class="size-20 mr-2"/>
                            {{ $t('desktop') }}
                        </a>
                    </li>
                    <li class="nav-item">
                        <a class="nav-link"
                           :class="{'active': activePreview === 'mobile'}"
                           href="#"
                           @click="toggleResponsivePreview('mobile')">
                            <app-icon name="smartphone" class="size-20 mr-2"/>
                            {{ $t('mobile') }}
                        </a>
                    </li>
                </ul>
                <nav class="navbar-expand-md position-relative">
                    <ul class="nav nav-right collapse navbar-collapse" id="navbarToggle">
                        <li class="nav-item mr-md-1">
                            <a class="nav-link change-toggler" href="#" @click.prevent="toggleEditor">
                                <app-icon name="maximize-2" class="size-20 mr-2 mr-md-0"/>
                                <span class="d-md-none">{{ $t('toggle_editor') }}</span>
                            </a>
                        </li>
                        <li class="nav-item mr-md-2">
                            <a class="nav-link view-section" href="#" @click.prevent="viewPreview">
                                <app-icon name="eye" class="size-20 mr-2 mr-md-0"/>
                                <span class="d-md-none">{{ $t('view_job') }}</span>
                            </a>
                        </li>
                        <li v-if="saveBtn"
                            class="nav-item"
                            :class="{'mr-md-2':publishBtn}">
                            <a href="#"
                               class="nav-link save-changes"
                               @click.prevent="saveChanges">
                                {{ $t('save_changes') }}
                            </a>
                        </li>
                        <li v-if="publishBtn" class="nav-item">
                            <a href="#"
                               class="nav-link publish-job"
                               @click.prevent="publishChanges">
                                {{ $t('publish_job') }}
                            </a>
                        </li>
                    </ul>
                    <button
                        class="navbar-toggler"
                        type="button"
                        data-toggle="collapse"
                        data-target="#navbarToggle"
                        aria-controls="navbarToggle"
                        aria-expanded="false"
                        aria-label="Toggle navigation">
                        <app-icon name="more-vertical"/>
                    </button>
                </nav>
            </div>
            <div class="editor-content">
                <div class="preview-content">
                    <div class="preview">
                        <div v-if="pageBlocks[pageView].logo" class="d-flex flex-column align-items-center mb-5">
                            <img :src="icon"
                                 class="candidate-viewable-icon img-fluid"
                                 alt=""
                            />
                            <!--
                            <div v-if="editJobLogo" class="form-group">
                                <app-input
                                    type="custom-file-upload"
                                    :label="$t('change_image')"
                                    v-model="icon"
                                />
                            </div>
                            -->
                        </div>

                        <div v-if="pageBlocks[pageView].header" class="text-center mb-5">
                            <button
                                v-if="!title && !editTitle"
                                @click.prevent="editTitle = true"
                                class="btn btn-primary mb-4">
                                {{$addLabel('title')}}
                            </button>
                            <h1 v-if="!editTitle"
                                class="mb-4"
                                @click.prevent="editTitle = true"
                                :style="`font-size: ${titleStyle.fontSize}; font-weight: ${titleStyle.fontWeight}; letter-spacing: ${titleStyle.letterSpacing}; color: ${titleStyle.color};`">
                                {{ title }}
                            </h1>
                            <div v-else class="time-picker-input mb-4">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="title"
                                    />
                                    <div class="input-group-append" @click.prevent="editTitle = false">
                                        <span class="input-group-text">
                                            <app-icon name="save"/>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <button
                                v-if="!subtitle && !editSubTitle"
                                @click.prevent="editSubTitle = true"
                                class="btn btn-primary mb-4">
                                {{$addLabel('subtitle')}}
                            </button>
                            <p v-if="!editSubTitle"
                               class="mb-4"
                               @click.prevent="editSubTitle = true"
                               :style="`font-size: ${subTitleStyle.fontSize}; font-weight: ${subTitleStyle.fontWeight}; letter-spacing: ${subTitleStyle.letterSpacing}; color: ${subTitleStyle.color};`">
                                {{ subtitle }}
                            </p>
                            <div v-else class="time-picker-input mb-4">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="subtitle"
                                    />
                                    <div class="input-group-append" @click.prevent="editSubTitle = false">
                                        <span class="input-group-text">
                                            <app-icon name="save"/>
                                        </span>
                                    </div>
                                </div>
                            </div>

                            <button
                                v-if="!details && !editDetails"
                                @click.prevent="editDetails = true"
                                class="btn btn-primary mb-4">
                                {{$addLabel('details')}}
                            </button>
                            <p v-if="!editDetails"
                               @click.prevent="editDetails = true"
                               :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">
                                {{ details }}
                            </p>
                            <div v-else class="time-picker-input">
                                <div class="input-group">
                                    <input
                                        type="text"
                                        class="form-control"
                                        v-model="details"
                                    />
                                    <div class="input-group-append" @click.prevent="editDetails = false">
                                        <span class="input-group-text">
                                            <app-icon name="save"/>
                                        </span>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-if="pageBlocks[pageView].body" class="editor-body">
                            <div class="editor-group-action mb-2">
                                <button
                                    v-if="editBody"
                                    type="button"
                                    class="btn"
                                    @click.prevent="addMoreRow">
                                    <app-icon name="plus"/>
                                </button>
                                <button
                                    type="button"
                                    class="btn"
                                    @click.prevent="editBody = true">
                                    <app-icon name="edit"/>
                                </button>
                                <button
                                    type="button"
                                    class="btn"
                                    @click.prevent="editDone">
                                    <app-icon name="check"/>
                                </button>
                            </div>
                            <template v-if="!editBody">
                                <div v-for="(section,index) in bodySection"
                                     class="mb-5">
                                    <h5 :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`">
                                        {{ section.headings }}
                                    </h5>
                                    <p :style="`font-size: ${descriptionStyle.fontSize}; font-weight: ${descriptionStyle.fontWeight}; letter-spacing: ${descriptionStyle.letterSpacing}; color: ${descriptionStyle.color};`">
                                        {{ section.description }}
                                    </p>
                                </div>
                            </template>
                            <template v-else>
                                <div class="mb-3" v-for="(section,index) in bodySection">
                                    <div class="d-flex align-items-center justify-content-between mb-2">
                                        <input
                                            type="text"
                                            class="editor-body-input form-control"
                                            v-model="section.headings"
                                        />
                                        <div @click.prevent="deleteSection(section)"
                                            class="width-30 height-30 text-white bg-success rounded d-inline-flex align-items-center justify-content-center cursor-pointer">
                                            <app-icon name="trash-2" class="size-14"/>
                                        </div>
                                    </div>
                                    <textarea
                                        class="custom-scrollbar"
                                        rows="8"
                                        v-model="section.description">
                                    </textarea>
                                </div>
                            </template>
                        </div>

                        <div v-if="isCareer" class="mb-5">
                            <h5 :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`">
                                {{ $t('job_openings') }}
                            </h5>
                            <hr/>
                            <div class="job-openings">
                                <div class="row">
                                    <div class="col-12 mb-primary"
                                         :class="{'col-md-6 col-xl-4' : (activePreview === 'desktop')}"
                                         v-for="(job, index) in jobList">
                                        <div class="job-card">
                                            <a :href="job.url" class="text-size-18">{{ job.title }}</a>
                                            <p class="mb-0">{{ job.type }}</p>
                                            <div class="text-muted text-size-13 d-flex align-items-center">
                                                <app-icon name="map-pin" class="size-14 mr-2"/>
                                                {{ job.location }}
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>

                        <div v-else class="apply-wrapper mb-5">
                            <h4>{{$t('apply_for_the_post')}} {{this.title}}</h4>
                            <a href="#" class="btn">
                                {{ $t('apply_now') }}
                            </a>
                        </div>

                        <div v-if="pageBlocks[pageView].logo" class="text-center py-4">
                            <img :src="logo" class="candidate-viewable-logo img-fluid d-block mx-auto" alt="">
                        </div>

                        <div v-if="pageBlocks[pageView].footer" class="text-center py-4">
                            {{ companyText }}
                        </div>
                    </div>
                </div>
                <div class="preview-content-editor custom-scrollbar">
                    <div class="editing-options">
                        <div class="mb-4">
                            <h6 class="mb-3">{{ $t('page_styling') }}</h6>
                            <div class="accordion" id="accordionExample">
                                <div class="accordion-item"
                                     v-for="(item, index) in pageStyle[pageView]">
                                    <div class="accordion-header" id="headingOne">
                                        <button
                                            class="btn btn-block text-left"
                                            type="button"
                                            data-toggle="collapse"
                                            :data-target="`#collapse-${index}`"
                                            aria-expanded="true"
                                            :aria-controls="`collapse-${index}`">
                                            {{ item.name }}
                                        </button>
                                    </div>
                                    <div :id="`collapse-${index}`" class="collapse" aria-labelledby="headingOne"
                                         data-parent="#accordionExample">
                                        <div class="accordion-content">
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label for="titleFontSize">
                                                    Font size
                                                </label>
                                                <input
                                                    type="number"
                                                    id="titleFontSize"
                                                    class="form-control"
                                                    v-model="item.fontSize"
                                                />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label for="titleFontWeight">
                                                    Font weight
                                                </label>
                                                <input
                                                    type="number"
                                                    id="titleFontWeight"
                                                    class="form-control"
                                                    v-model="item.fontWeight"
                                                />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between mb-2">
                                                <label for="titleLetterSpacing">
                                                    Letter spacing
                                                </label>
                                                <input
                                                    type="number"
                                                    id="titleLetterSpacing"
                                                    class="form-control"
                                                    v-model="item.letterSpacing"
                                                />
                                            </div>
                                            <div class="d-flex align-items-center justify-content-between">
                                                <label for="titleColor">
                                                    Color
                                                </label>
                                                <input
                                                    type="color"
                                                    id="titleColor"
                                                    class="form-control"
                                                    v-model="item.color"
                                                />
                                            </div>
                                        </div>
                                    </div>
                                </div>
                            </div>
                        </div>
                        <div class="mb-4">
                            <h6 class="mb-3">{{ $t('page_blocks') }}</h6>
                            <div class="page-blocks">
                                <div class="block-item" v-for="(key, index) in Object.keys(pageBlocks[pageView])">
                                    <div class="d-flex align-items-center">
                                        <label class="custom-control d-inline border-switch mb-0 mr-3">
                                            <input type="checkbox"
                                                   :id="`headerSwitch-${index}`"
                                                   class="border-switch-control-input"
                                                   v-model="pageBlocks[pageView][key]">
                                            <span class="border-switch-control-indicator"></span>
                                        </label>
                                        <label :for="`headerSwitch-${index}`" class="text-capitalize mb-0">
                                            {{ key }}
                                        </label>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    import {FormMixin} from '../../../../core/mixins/form/FormMixin';
    import AppFunction from '../../../../core/helpers/app/AppFunction';
    import {urlGenerator} from "../../../../common/Helper/AxiosHelper";

    export default {
        name: 'Editor',
        mixins: [FormMixin],
        props: {
            editorLogo: {
                type: String
            },
            editorIcon: {
                type: String
            },
            editorContent: {
                type: Object,
                require: true
            },
            editorIsCareer: {
                type: Boolean,
                default: false
            },
            editorApplyLink: {
                type: String
            },
            editorJobList: {
                type: Array
            },
            editorPageStyle: {
                type: Object,
            },
            editorPageBlocks: {
                type: Object,
            },
            saveBtn: {
                type: Boolean,
                default: true
            },
            publishBtn: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                activePreview: 'desktop',

                // Common
                logo: urlGenerator(window.settings.tenant_icon),
                icon: urlGenerator(window.settings.tenant_icon),
                title: this.editorContent.title,
                subtitle: this.editorContent.subtitle,
                details: this.editorContent.details,
                bodySection: this.editorContent.bodySection,

                // Specific
                isCareer: this.editorIsCareer,
                applyLink: this.editorApplyLink,
                jobList: this.editorJobList,

                // Styles
                pageStyle: this.editorPageStyle,
                pageBlocks: this.editorPageBlocks,

                // Edit
                editJobLogo: false,
                editTitle: false,
                editSubTitle: false,
                editDetails: false,
                editBody: false,
            }
        },
        mounted() {
            console.log('this.editorIcon')
            console.log(this.editorIcon)
            let navbarRight = document.querySelector('.navbar-nav-right'),
                navbarButtons = navbarRight.querySelectorAll('.nav-item');
            navbarButtons[0].classList.add('d-none');
            setTimeout(()=>{
                document.documentElement.setAttribute('theme', 'light')
            })
        },
        computed: {
            pageView() {
                return this.activePreview === 'desktop' ? 'defaultView' : 'mobileView'
            },
            titleStyle() {
                return this.pageStyle[this.pageView].find(item => item.key === 'title')
            },
            subTitleStyle() {
                return this.pageStyle[this.pageView].find(item => item.key === 'sub-title')
            },
            detailsStyle() {
                return this.pageStyle[this.pageView].find(item => item.key === 'details')
            },
            headingsStyle() {
                return this.pageStyle[this.pageView].find(item => item.key === 'headings')
            },
            descriptionStyle() {
                return this.pageStyle[this.pageView].find(item => item.key === 'description')
            },
            companyText() {
                return `${this.$t('copyright_text')} ${window.settings?.company_name ? window.settings.company_name : ''}`;
            }
        },
        methods: {
            viewPreview() {
                this.$emit('viewPreview');
            },
            preparedChangedData() {
                let content = {}, data = {};
                content.title = this.title;
                content.subtitle = this.subtitle;
                content.details = this.details;
                content.bodySection = this.bodySection;
                data.content = content;
                data.pageStyle = this.pageStyle;
                data.pageBlocks = this.pageBlocks;
                return data;
            },
            saveChanges() {
                this.$emit('changed', this.preparedChangedData());
            },
            publishChanges() {
                this.$emit('published', this.preparedChangedData());
            },
            addMoreRow() {
                this.bodySection.push({
                    headings: '',
                    description: '',
                })
            },
            deleteSection(section) {
                this.bodySection.splice(this.bodySection.indexOf(section), 1);
            },
            editDone() {
                this.bodySection = this.bodySection.filter(item => item.headings !== '');
                this.editBody = false
            },
            toggleEditor() {
                $('.editor-content').toggleClass('hide-editor');
            },
            toggleJobLogoUploader() {
                if (!this.editorIsCareer) {
                    this.editJobLogo = true;
                }
            },
            toggleResponsivePreview(previewType) {
                let preview = $('.preview-content .preview')

                if (previewType === 'desktop') {
                    this.activePreview = 'desktop';
                    preview.removeClass('mobile-preview');
                    preview.addClass('desktop-preview');
                } else {
                    this.activePreview = 'mobile';
                    preview.removeClass('desktop-preview');
                    preview.addClass('mobile-preview');
                }
            }
        }
    }
</script>

<style lang="scss">
.editor-body-input {
    width: calc(100% - 35px) !important;
}
</style>