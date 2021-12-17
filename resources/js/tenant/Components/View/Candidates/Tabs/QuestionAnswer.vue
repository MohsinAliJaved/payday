<template>
    <div v-if="answers.length > 0" class="accordion accordion-question" id="accordionExample">
        <div class="card" v-for="(answer, index) in answers" v-if="answer.answer">
            <div class="card-header"
                 :id="`heading-${index}`">
                <button
                    class="btn btn-block text-left p-0"
                    :class="{'collapsed':visibleIndex!==index}"
                    type="button"
                    @click.prevent="visibleIndex=index"
                    data-toggle="collapse"
                    :data-target="`#collapse-answer-${index}`"
                    :aria-expanded="`${visibleIndex === index ? 'true' : 'false'}`"
                    :aria-controls="`collapse-answer-${index}`">
                    <span class="d-inline-flex align-items-center">
                        <app-icon name="help-circle" class="mr-2"/>
                        {{ questionTitle(answer.question) }}
                    </span>
                    <app-icon name="chevron-down" class="btn-arrow"/>
                </button>
            </div>
            <div :id="`collapse-answer-${index}`"
                 class="collapse"
                 :class="{'show': index === 0}"
                 :aria-labelledby="`collapse-answer-${index}`"
                 data-parent="#accordionExample">
                <div class="card-body">
                    {{ answer.answer }}
                </div>
            </div>
        </div>
    </div>
    <app-empty-data-block
        v-else
        :message="$t('no_question_answer_found')"
    />
</template>

<script>
export default {
    name: "QuestionAnswer",
    props: {
        applyForm: {},
        answers: {}
    },
    data() {
        return {
            visibleIndex: 0
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
    },
    methods: {
        nameGen(name) {
            return _.snakeCase(_.lowerCase(name));
        },
        questionTitle(ques) {
            let question = this.questions.find(item => this.nameGen(item) === ques)
            return question ? question : '';
        },
    }
}
</script>
