<template>
    <div class="p-primary">
        <h6>{{ $t('job_stages') }}</h6>
        <div class="d-flex align-items-center justify-content-between mb-4">
            <p class="text-muted mb-0">
                {{ $t('job_stages_setting_subtitle') }}
            </p>
            <button
                type="button"
                class="btn btn-primary"
                @click.prevent="toggleInput">
                {{ $t('add_a_stage') }}
            </button>
        </div>
        <div v-if="loader" class="min-height-200">
            <app-overlay-loader/>
        </div>
        <div v-else class="border rounded">
            <draggable
                tag="div"
                v-model="draggableStages"
                v-bind="dragOptions"
                :move="checkMove"
                @start="dragstart"
                @end="dragend"
                @change="sortStages">
                <div class="d-flex align-items-center justify-content-between border-bottom p-3"
                     v-for="(stage, index) in draggableStages"
                     :key="`stage-${index}`">
                    <div class="d-inline-flex align-items-center">
                        <app-icon name="menu" class="size-18 text-muted mr-2"/>
                        <p v-if="editStageIndex !== index" class="mb-0 text-capitalize">{{ stage.name }}</p>
                        <app-input
                            v-else
                            type="text"
                            :id="`edit-stage-${index}`"
                            v-model="stage.name"
                            @keyup.enter="editJobStage(stage)"
                        />
                    </div>
                    <div v-if="!predefinedStages.includes(stage.name.toLowerCase())"
                         class="d-inline-flex align-items-center">
                        <a
                           href="#"
                           @click.prevent="editStageIndex = index"
                           class="width-20 height-20 d-inline-flex align-items-center justify-content-center mr-1">
                            <app-icon name="edit" class="size-14 text-muted"/>
                        </a>
                        <a
                           href="#"
                           class="width-20 height-20 d-inline-flex align-items-center justify-content-center"
                           @click.prevent="deleteStage(stage)">
                            <app-icon name="x-circle" class="size-14 text-danger"/>
                        </a>
                    </div>
                </div>
            </draggable>
            <div
                 class="p-3">
                <app-input
                    v-if="showInput"
                    id="new-stage-input"
                    type="text"
                    :placeholder="$t('type_and_enter')"
                    v-model="formData.new_stages"
                    @keyup.enter="addJobStage"
                />
                <a v-else
                   href="#"
                   class="d-flex align-items-center"
                   @click.prevent="toggleInput">
                    <app-icon name="plus" class="size-14 mr-2"/>
                    {{ $t('add_a_stage') }}
                </a>
            </div>
        </div>
        <delete-stage-confirmation
            v-if="deleteStageModalActive"
            :stages="draggableStages"
            :deletableStage="deletableStage"
            @afterMoveAndDelete="reloadStageData"
            @closeModal="deleteStageModalActive = false"
        />
    </div>
</template>

<script>
import draggable from 'vuedraggable';
import {mapGetters} from "vuex";
import {FormMixin} from '../../../../../../core/mixins/form/FormMixin';
import {axiosPatch, axiosDelete} from "../../../../../../common/Helper/AxiosHelper";
import {JOB_POST, JOB_STAGE} from "../../../../../Config/ApiUrl";

export default {
    name: 'JobStages',
    mixins: [FormMixin],
    components: {draggable},
    data() {
        return {
            predefinedStages: ['new', 'hired', 'disqualified'],
            stages: [],
            draggableStages: [],
            formData: {
                new_stages: '',
            },
            editStageIndex: -1,
            deletableStage: null,
            showInput: false,
            deleteStageModalActive: false,
        }
    },
    watch: {
        selectedJobDetails: {
            handler: function (data) {
                this.stages = data.job_stages ? data.job_stages : []
            }
        },
        stages: {
            handler: function (data) {
                if (data.length) {
                    this.draggableStages = [];
                    let sortedStr = _.cloneDeep(this.selectedJobDetails.stages),
                        sortedName = sortedStr.toLowerCase().split(',');
                    for (let i = 0; i < sortedName.length; i++) {
                        let stage = data.find(item => item.name === sortedName[i]);
                        if (stage) this.draggableStages.push(stage);
                    }
                }
            }
        }
    },
    created() {
        this.stages = this.selectedJobDetails?.job_stages ? this.selectedJobDetails.job_stages : []
    },
    computed: {
        ...mapGetters([
            'selectedJobDetails',
            'loader'
        ]),
        dragOptions() {
            return {
                animation: 300
            };
        }
    },
    methods: {
        toggleInput() {
            this.showInput = !this.showInput
            if (this.showInput) {
                setTimeout(() => {
                    $(`#new-stage-input`).focus();
                })
            }
        },
        reloadStageData() {
            this.$store.dispatch('getJobDetails', this.selectedJobDetails.id);
        },
        formatStages() {
            let stages = this.draggableStages.map(item => item.name),
                hiredIndex = stages.indexOf('hired'),
                lastStages = stages.splice(hiredIndex, 2);
            return [...stages, this.formData.new_stages, ...lastStages].toString();
        },
        addJobStage() {
            if (!this.formData.new_stages) {
                this.$toastr.w(this.$t('please_fill_the_field'));
                return;
            }
            this.formData.new_stages = this.formData.new_stages.toLowerCase();
            this.formData.final_sorted_stages = this.formatStages();
            let url = `${JOB_POST}/${this.selectedJobDetails.id}/add-stages`
            axiosPatch(url, this.formData).then((res) => {
                this.$toastr.s(res.data.message);
                this.reloadStageData();
                this.formData = {};
                this.showInput = false;
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            })
        },
        sortStages() {
            let url = `${JOB_POST}/${this.selectedJobDetails.id}/add-stages`,
                data = {
                    final_sorted_stages: this.draggableStages.map(item => item.name).toString().toLowerCase()
                };
            axiosPatch(url, data).then((res) => {
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
                this.reloadStageData();
            })
        },
        editJobStage(stage) {
            let url = `${JOB_STAGE}/${stage.id}`,
                data = {
                    name: stage.name
                }
            axiosPatch(url, data).then((res) => {
                this.$toastr.s(res.data.message);
                this.editStageIndex = -1;
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
                this.reloadStageData();
            })
        },
        deleteStage(stage) {
            if (stage['job_applicant_count']?.length && (stage['job_applicant_count'][0].count > 0)) {
                this.deletableStage = stage;
                this.deleteStageModalActive = true;
                return;
            }
            axiosDelete(`${JOB_STAGE}/${stage.id}`).then((res) => {
                this.$toastr.s(res.data.message);
                this.reloadStageData();
                this.deletableStage = null;
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            })
        },
        checkMove(ev) {
            // if (!this.$have('PERMISSION_UPDATE_JOB_POST_STAGE')) return false;
            if (this.predefinedStages.includes(ev.draggedContext.element.name.toLowerCase())) return false;
            if (ev.draggedContext.futureIndex === 0 || (ev.draggedContext.futureIndex > this.draggableStages.length - 3)) return false;
        },
        dragstart(ev) {
            this.drag = true;
            ev.item.classList.add('catch-item');
            ev.target.classList.add('catch-container');
        },
        dragend(ev) {
            this.drag = false;
            ev.item.classList.remove('catch-item');
            ev.target.classList.remove('catch-container');
        }
    }
}
</script>