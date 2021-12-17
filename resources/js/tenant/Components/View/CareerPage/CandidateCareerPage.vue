<template>
    <div class="content-wrapper">
        <div class="editor-content">
            <div class="preview-content">
                <div class="preview">
                    <div v-if="pageBlocks[pageView].logo" class="d-flex flex-column align-items-center mb-5">
                        <img :src="icon"
                             class="candidate-viewable-icon img-fluid"
                             alt=""
                        />
                    </div>

                    <div v-if="pageBlocks[pageView].header" class="text-center mb-5">
                        <h1 class="mb-4"
                            :style="`font-size: ${titleStyle.fontSize}; font-weight: ${titleStyle.fontWeight}; letter-spacing: ${titleStyle.letterSpacing}; color: ${titleStyle.color};`">
                            {{ content.title }}
                        </h1>

                        <p class="mb-4"
                           :style="`font-size: ${subTitleStyle.fontSize}; font-weight: ${subTitleStyle.fontWeight}; letter-spacing: ${subTitleStyle.letterSpacing}; color: ${subTitleStyle.color};`">
                            {{ content.subtitle }}
                        </p>

                        <p :style="`font-size: ${detailsStyle.fontSize}; font-weight: ${detailsStyle.fontWeight}; letter-spacing: ${detailsStyle.letterSpacing}; color: ${detailsStyle.color};`">
                            {{ content.details }}
                        </p>
                    </div>

                    <div v-if="pageBlocks[pageView].body" class="editor-body">
                        <div v-for="(section,index) in content.bodySection"
                             class="mb-5">
                            <h5 :style="`font-size: ${headingsStyle.fontSize}; font-weight: ${headingsStyle.fontWeight}; letter-spacing: ${headingsStyle.letterSpacing}; color: ${headingsStyle.color};`">
                                {{ section.headings }}
                            </h5>
                            <p :style="`font-size: ${descriptionStyle.fontSize}; font-weight: ${descriptionStyle.fontWeight}; letter-spacing: ${descriptionStyle.letterSpacing}; color: ${descriptionStyle.color};`">
                                {{ section.description }}
                            </p>
                        </div>
                    </div>

                    <div class="mb-5">
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

                    <div v-if="pageBlocks[pageView].logo" class="text-center py-4">
                        <img :src="logo" class="candidate-viewable-logo img-fluid d-block mx-auto" alt="">
                    </div>

                    <div v-if="pageBlocks[pageView].footer" class="text-center py-4">
                        {{ companyText }}
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import {urlGenerator} from "../../../../common/Helper/AxiosHelper";
import {PUBLIC_JOB_POST} from "../../../Config/ApiUrl";

export default {
    name: 'CandidateCareerPage',
    props: {
        careerPage: {},
        jobPosts: {}
    },
    data() {
        return {
            urlGenerator,
            viewType: 'desktop',
            activePreview: 'desktop'
        }
    },
    computed: {
        jobList() {
            return this.jobPosts.map(item => {
                return {
                    title: item.name,
                    type: item['job_type'].name,
                    location: item['location'].address,
                    url: urlGenerator(`${PUBLIC_JOB_POST}/${item.slug}/display`)
                }
            })
        },
        careerPageData() {
            if (typeof this.careerPage === 'string')
                return JSON.parse(this.careerPage);
            return this.careerPage;
        },
        content() {
            return this.careerPageData.content;
        },
        pageStyle() {
            return this.careerPageData.pageStyle;
        },
        pageBlocks() {
            return this.careerPageData.pageBlocks;
        },
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
        icon() {
            //  return this.careerPageData.icon ? this.careerPageData.icon : urlGenerator(window.settings.tenant_icon)
        },
        logo() {
        //    return urlGenerator(window.settings.tenant_icon);
        },
        companyText() {
            return `${this.$t('copyright_text')} ${window.settings?.company_name ? window.settings.company_name : ''}`;
        }
    },
    watch: {
        viewType: {
            handler: function (type) {
                this.toggleResponsivePreview(type)
            },
            immediate: true
        }
    },
    mounted() {
        this.checkViewType();
        window.onresize = () => {
            this.checkViewType();
        }
    },
    methods: {
        checkViewType() {
            this.viewType = window.innerWidth > 575 ? 'desktop' : 'mobile';
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
