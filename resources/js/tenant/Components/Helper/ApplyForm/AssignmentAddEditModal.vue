<template>
    <modal
        :modal-id="modalId"
        :title="$t('assignment')"
        :preloader="preloader"
        modal-size="default"
        @submit="submit"
        @closeModal="closeModal">
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form class="mb-0"
                  :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="''">
                <div class="form-group row">
                    <label for="assignment" class="mb-sm-0 col-sm-3">
                        {{ $t('assignment') }}
                    </label>
                    <div class="col-sm-9">
                        <app-input
                            class="mb-2"
                            type="textarea"
                            id="assignment"
                            :text-area-rows="5"
                            v-model="formData.name"
                        />
                        <small class="text-muted">{{ $t('assignment_input_hint') }}</small>
                    </div>
                </div>
                <div class="form-group mb-0 row">
                    <label class="mb-sm-0 col-sm-3">
                        {{ $t('settings') }}
                    </label>
                    <div class="col-sm-9">
                        <div class="form-group d-flex align-items-center">
                            <app-input
                                type="switch"
                                v-model="formData.require"
                            />
                            <label class="mb-0">{{ $t('required') }}</label>
                        </div>
                    </div>
                </div>
            </form>
        </template>
    </modal>
</template>

<script>
import {FormMixin} from "../../../../core/mixins/form/FormMixin";
import {ModalMixin} from "../../../Mixins/ModalMixin";

export default {
    name: 'AssignmentAddEditModal',
    mixins: [FormMixin, ModalMixin],
    props: {
        previousData: {}
    },
    data() {
        return {
            modalId: 'assignment-add-edit-modal',
            formData: {}
        }
    },
    watch: {
        previousData: {
            handler: function (data) {
                if (data) {
                    this.formData = _.cloneDeep(data)
                }
            },
            immediate: true
        }
    },
    created() {

    },
    methods: {
        submit() {
            this.$emit('assignmentUpdate', this.formData);
            this.afterFinalResponse();
        },
        afterSuccess(response) {

        },
        afterSuccessFromGetEditData(response) {

        }
    }
}
</script>