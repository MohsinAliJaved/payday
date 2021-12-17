<template>
    <div class="min-height-200">
        <template v-if="attachments.length > 0">
            <div v-if="assignment" class="default-base-color rounded p-4 mb-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0 text-primary">{{ $t('assignment') }} - {{ applicant.full_name }}</p>
                    <div class="d-flex justify-content-start">
                        <a :href="urlGenerator(assignment.attachment)"
                           target="_blank"
                           class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center">
                            <app-icon name="download" class="size-14"/>
                        </a>
                    </div>
                </div>
            </div>
            <div v-if="resume" class="default-base-color rounded p-4 mb-primary">
                <div class="d-flex justify-content-between align-items-center">
                    <p class="mb-0 text-primary">{{ $t('resume') }} - {{ applicant.full_name }}</p>
                    <div class="d-flex justify-content-start">
                        <a :href="urlGenerator(resume.attachment)"
                           target="_blank"
                           class="text-muted default-base-color width-30 height-30 rounded d-inline-flex align-items-center justify-content-center">
                            <app-icon name="download" class="size-14"/>
                        </a>
                    </div>
                </div>
            </div>
        </template>
        <app-empty-data-block
            v-else
            :message="$t('this_candidate_have_no_attachment')"
        />
    </div>
</template>

<script>
import {urlGenerator} from "../../../../../common/Helper/AxiosHelper";

export default {
    name: "CandidateAttachments",
    props: {
        applyForm: {},
        applicant: {},
        answers: {}
    },
    data() {
        return {
            urlGenerator
        }
    },
    computed: {
        questions() {
            let question = []
            this.applyForm.forEach(item => {
                question = [...question, ...item.fields]
            })
            return question.map(el => el.name);
        },
        attachments() {
            return this.answers.filter(item => item.attachment);
        },
        resume() {
            return this.attachments.find(item => item.question.includes('resume'));
        },
        assignment() {
            return this.attachments.find(item => !item.question.includes('resume'));
        }
    },
    methods: {
        nameGen(name) {
            return _.snakeCase(_.lowerCase(name));
        },
        questionTitle(ques) {
            let question = this.questions.find(item => this.nameGen(item) === ques)
            return question ? question : '';
        }
    }
}
</script>