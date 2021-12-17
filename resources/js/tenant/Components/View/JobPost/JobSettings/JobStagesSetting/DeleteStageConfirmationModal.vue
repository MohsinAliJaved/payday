<template>
    <modal
        :modal-id="modalId"
        :title="$t('stage_delete_confirmation')"
        :preloader="preloader"
        :modal-scroll="false"
        :submit-button-text="$t('move_and_delete')"
        @submit="submit"
        @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form
                class="mb-0"
                :class="{'loading-opacity': preloader}"
                ref="form"
                :data-url="''">
                <app-note
                    :notes="noteInstruction"
                    :show-title="false"
                    padding-class="p-2"
                />
                <div class="form-group mt-3 mb-0">
                    <app-input
                        id="move-stage"
                        type="search-select"
                        list-value-field="name"
                        :placeholder="$t('click_to_choose_stage')"
                        :required="true"
                        :list="selectableStage"
                        v-model="moveStageId"
                    />
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import {ModalMixin} from '../../../../../Mixins/ModalMixin';
import {FormMixin} from '../../../../../../core/mixins/form/FormMixin';
import {JOB_STAGE} from "../../../../../Config/ApiUrl";

export default {
    name: 'DeleteStageConfirmationModal',
    mixins: [ModalMixin, FormMixin],
    props: {
        stages: {},
        deletableStage: {}
    },
    data() {
        return {
            modalId: 'stage-delete-modal',
            moveStageId: '',
        }
    },
    computed: {
        selectableStage() {
            return this.stages.filter(item => Number(item.id) !== Number(this.deletableStage.id));
        },
        noteInstruction() {
            return `${this.$t('already')} ${this.deletableStage['job_applicant_count'][0].count}
             ${this.$t('candidate').toLowerCase()} ${this.$t('in')} ${this.deletableStage.name}
              ${this.$t('stage').toLowerCase()}. ${this.$t('choose_stage_before_delete')}.`
        }
    },
    methods: {
        submit() {
            let url = `${JOB_STAGE}/${this.deletableStage.id}/move_applicant`,
                data = {
                    'next_stage_id': this.moveStageId
                }
            this.submitFromFixin('patch', url, data)
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$emit('afterMoveAndDelete')
        }
    }
}
</script>