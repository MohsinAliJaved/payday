<template>
    <div class="default-base-color candidate-notes min-height-200 p-primary"
         :class="{'loading-opacity':preloader}">

        <!--Compose Note-->
        <div v-if="$have('PERMISSION_CREATE_NOTE')"
             class="card shadow border-0"
             :class="{'loading-opacity':addLoader}">

            <app-overlay-loader v-if="addLoader"/>
            <div class="card-body">
                <div class="d-flex align-items-center mb-4">
                    <app-avatar
                        avatar-class="avatars-w-40"
                        :title="activeUser.full_name"
                        :border="true"
                        :img="activeUser.img"
                    />
                    <div class="ml-3">
                        <h6 class="mb-1">{{ activeUser.full_name }}</h6>
                    </div>
                </div>
                <app-input
                    type="text-editor"
                    v-model="noteContent"
                    :height="100"
                    :minimal="true"
                />
                <div class="d-flex justify-content-end mt-2">
                    <button
                        :disabled="!noteContent"
                        class="btn btn-primary"
                        @click.prevent="addNote">
                        {{ $t('submit') }}
                    </button>
                </div>
            </div>
        </div>

        <!--Note and body-->
        <template v-if="!preloader && $have('PERMISSION_VIEW_NOTES_OWN_CREATED')">
            <div v-for="(note,index) in notes"
                 class="card shadow border-0 mt-3"
                 :class="{'loading-opacity':(actionLoader && (Number(note.id) === Number(editNoteId)))}">
                <app-overlay-loader
                    v-if="actionLoader && (Number(note.id) === Number(editNoteId))"
                />
                <div class="card-body">
                    <div class="d-flex align-items-center justify-content-between mb-4">
                        <div class="d-flex align-items-center">
                            <app-avatar
                                avatar-class="avatars-w-40"
                                :title="note['noted_by'].full_name"
                                :border="true"
                                :img="note['noted_by'].profile_picture ? note['noted_by'].profile_picture.full_url : ''"
                            />
                            <div class="ml-3">
                                <h6 class="mb-1">{{ note['noted_by'].full_name }}</h6>
                                <p class="text-muted font-size-90 mb-0">
                                    {{ formatDateTimeDateTime(note.updated_at) }}
                                </p>
                            </div>
                        </div>
                        <div v-if="$have('PERMISSION_UPDATE_NOTE') || $have('PERMISSION_DELETE_NOTE')"
                             class="d-flex align-items-center justify-content-start">
                            <a v-if="$have('PERMISSION_UPDATE_NOTE') && ((note['noted_by'].id) === Number(activeUser.id))"
                               href="#"
                               @click.prevent="makeNoteEditable(note)">
                                <app-icon class="size-18" name="edit"/>
                            </a>
                            <a v-if="$have('PERMISSION_DELETE_NOTE') && ((note['noted_by'].id) === Number(activeUser.id))"
                               href="#" @click.prevent="deleteNote(note.id)"
                               class="ml-2">
                                <app-icon class="size-18" name="trash-2"/>
                            </a>
                        </div>
                    </div>
                    <template v-if="Number(note.id) === Number(editNoteId)">
                        <app-input
                            type="text-editor"
                            v-model="editNote"
                            :height="100"
                            :minimal="true"
                        />
                        <div class="d-flex justify-content-end mt-2">
                            <button
                                class="btn btn-secondary"
                                @click.prevent="cancelEdit">
                                {{ $t('cancel') }}
                            </button>
                            <button
                                class="btn btn-primary ml-3"
                                @click.prevent="updateNote">
                                {{ $t('update') }}
                            </button>
                        </div>
                    </template>
                    <template v-else>
                        <p class="text-muted mb-0" v-html="note.description"></p>
                    </template>
                </div>
            </div>
            <app-empty-data-block
                v-if="notes.length === 0"
                :message="$t('no_comments_available')"
            />
        </template>

        <div v-else
             class="height-250 card shadow mt-3">
            <app-overlay-loader/>
        </div>
    </div>
</template>

<script>
import {axiosDelete, axiosGet, axiosPatch, axiosPost} from "../../../../Helpers/AxiosHelper";
import {NOTE} from "../../../../Config/ApiUrl";
import {formatDateTimeDateTime} from "../../../../Helpers/DateTimeHelper";
import {mapGetters} from "vuex";

export default {
    name: "CandidateNotes",
    props: {
        jobApplicantId: {}
    },
    data() {
        return {
            formatDateTimeDateTime,
            preloader: false,
            addLoader: false,
            actionLoader: false,
            editNoteId: 0,
            notes: [],
            editNote: '',
            edit: false,
            noteContent: ''
        }
    },
    created() {
        this.getNotes();
    },
    computed: {
        ...mapGetters(['activeUser']),
    },
    methods: {
        getNotes() {
            this.preloader = true;
            let url = `${NOTE}/job-applicant/${this.jobApplicantId}/get-notes`;
            axiosGet(url).then(res => {
                this.notes = res.data;
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
            }).finally(() => {
                this.preloader = false;
            })
        },
        addNote() {
            this.addLoader = true;
            let data = {
                job_applicant_id: this.jobApplicantId,
                description: this.noteContent,
            }
            axiosPost(NOTE, data).then(res => {
                this.addLoader = false;
                this.successAndReload(res);
                this.noteContent = '';
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
                this.getNotes();
            })
        },
        makeNoteEditable(note) {
            this.editNoteId = Number(note.id);
            this.editNote = note.description;
        },
        updateNote() {
            this.actionLoader = true;
            let url = `${NOTE}/${this.editNoteId}`,
                data = {
                    description: this.editNote
                }
            axiosPatch(url, data).then(res => {
                this.actionLoader = false;
                this.cancelEdit();
                this.successAndReload(res);
            }).catch(({response}) => {
                this.$toastr.e(response.data.message);
                this.getNotes();
            })
        },
        cancelEdit() {
            this.editNoteId = 0;
            this.editNote = '';
        },
        deleteNote(id) {
            this.actionLoader = true;
            axiosDelete(`${NOTE}/${id}`).then(res => {
                this.actionLoader = false;
                this.successAndReload(res);
            })
        },
        successAndReload({data}) {
            this.$toastr.s(data.message);
            this.getNotes();
        }
    }
}
</script>

<style lang="scss">
.candidate-notes {
    margin: -2rem !important;
}
</style>