<template>
    <app-modal
        :modal-id="modalId"
        :title="(selectedUrl || previousData) ? $editLabel('custom_field') : $addLabel('custom_field')"
        :preloader="preloader"
        :hide-cancel-button="Boolean(previousData)"
        :submit-button-text="previousData ? $t('update') : $t('save')"
        modal-size="default"
        @submit="submit"
        @closeModal="closeModal">

        <template slot="header">
        <h5 class="modal-title text-capitalize">
            {{ (selectedUrl || previousData) ? $editLabel("custom_field") : $addLabel("custom_field") }}
        </h5>
        <button
            type="button"
            class="close outline-none"
            data-dismiss="modal"
            aria-label="Close"
            @click.prevent="closeModal"
        >
            <span>
            <app-icon :name="'x'"></app-icon>
            </span>
        </button>
        </template>
        <template slot="body">
            <app-overlay-loader v-if="preloader"/>
            <form :class="{'loading-opacity': preloader}"
                  ref="form"
                  :data-url="selectedUrl ? selectedUrl : (contextUrl ? contextUrl : '')">
                <div class="form-group">
                    <label for="name">{{ $t('name') }}</label>
                    <app-input
                        id="name"
                        type="text"
                        :placeholder="$placeholder('custom_field', 'name')"
                        v-model="formData.name"
                        :required="true"
                    />
                </div>
                <div class="form-group">
                    <label for="type">{{ $t('type') }}</label>
                    <app-input
                        id="type"
                        type="select"
                        :list="availableInputTypes"
                        v-model="formData.type"
                        :required="true"
                    />
                </div>
                <div v-if="haveMultiOptions" class="form-group">
                    <label for="option">{{ $t('options') }}</label>
                    <div class="custom-input-group mb-2">
                        <input
                            id="option"
                            type="text"
                            class="form-control"
                            :placeholder="$t('type_option_name')"
                            v-model="newOption"
                            @keyup.enter="addOption"
                        />
                        <div class="input-group-append">
                            <button type="button" @click="addOption">
                                <app-icon name="plus" class="size-18"/>
                            </button>
                        </div>
                    </div>
                    <div v-if="formData.options && formData.options.length && haveMultiOptions">
                        <div
                            class="default-base-color rounded d-flex align-items-center justify-content-between px-3 py-2 mb-1"
                            v-for="(option, index) in formData.options"
                            :key="`multi-option-${index}`">
                            <span>{{ option }}</span>
                            <button type="button" class="btn bg-danger padding-5" @click="deleteOption(option)">
                                <app-icon name="trash-2" class="text-white size-12"/>
                            </button>
                        </div>
                    </div>
                </div>
            </form>
        </template>
    <template slot="footer">
      <div :class="{ 'loading-opacity': preloader }">
        <button type="submit" class="btn btn-primary" @click.prevent="submit">
          {{ submitButtonText }}
        </button>
      </div>
    </template>
    </app-modal>
</template>

<script>
import {ModalMixin} from '../../../common/Mixin/Tenant/ModalMixin';
import {FormMixin} from '../../../core/mixins/form/FormMixin';

export default {
    name: 'CustomFieldModal',
    mixins: [ModalMixin, FormMixin],
    props: {
        tableId: {
            type: String
        },
        contextUrl: {
            type: String
        },
        submissionType: {
            type: String,
            default: 'form'
        },
        previousData: {},
        submitButtonText: {
        default: function () {
            return this.$t("update");
        },
        }
    },
    watch: {
        previousData: {
            handler: function (data) {
                if (this.submissionType !== 'form' && data) {
                    this.formData = _.cloneDeep(data)
                }
            },
            immediate: true
        },
        inputType: {
            handler: function (data) {
                if (this.haveMultiOptions) {
                    if (this.formData.options?.length < 1) this.formData.options = [];
                }
            }
        }
    },
    data() {
        return {
            modalId: 'custom-field-modal',
            formData: {
                name: '',
                type: '',
                options: []
            },
            availableInputTypes: [
                {id: '', value: this.$chooseLabel('option'), disabled: true},
                {id: 'text', value: this.$t('text')},
                {id: 'email', value: this.$t('email')},
                {id: 'number', value: this.$t('number')},
                {id: 'textarea', value: this.$t('textarea')},
                {id: 'tel-input', value: this.$t('tel_input')},
                {id: 'date', value: this.$t('date')},
                {id: 'radio', value: this.$t('radio')},
                {id: 'checkbox', value: this.$t('checkbox')},
                {id: 'select', value: this.$t('select')}
            ],
            newOption: ''
        }
    },
    computed: {
        haveMultiOptions() {
            return this.formData.type === 'radio' || this.formData.type === 'checkbox' || this.formData.type === 'select'
        }
    },
    methods: {
        closeModal() {
      this.$emit("closeModal");
    },
        addOption() {
            if (this.newOption) {
                this.formData.options.push(this.newOption);
            }

            this.newOption = '';
        },
        deleteOption(item) {
            this.formData.options.splice(this.formData.options.indexOf(item), 1);
        },
        submit() {
            let field = {};
            if (this.submissionType === 'form') {
                this.save(this.formData);
            } else {
                if (this.haveMultiOptions && (this.formData.options.length < 1)) {
                    this.$toastr.w(this.$t('option_should_not_be_empty'));
                    return;
                }
                if (this.formData.name && this.formData.type) {
                    field.name = this.formData.name;
                    field.type = this.formData.type;

                    if (this.formData.options && this.formData.options.length)
                        field.options = this.formData.options;

                    if (!this.previousData) this.$emit('customFieldSubmit', field);
                    else this.$emit('customFieldUpdate', field);

                    this.afterFinalResponse();
                } else this.$toastr.w(this.$t('please_fill_all_fields'));
            }
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$hub.$emit(`reload-${this.tableId}`)
        },
        afterSuccessFromGetEditData(response) {
            this.formData = response.data;
            this.preloader = false;
        }
    }
}
</script>