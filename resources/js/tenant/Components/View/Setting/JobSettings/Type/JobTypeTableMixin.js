import {JOB_TYPE} from "../../../../../Config/ApiUrl";
import DeleteMixin from "../../../../Mixins/DeleteMixin";

export default {
    mixins: [DeleteMixin],
    props: ['id','props'],
    data() {
        return {
            selectedUrl: '',
            tableId: 'job-type',
            deleteModalId: 'job-type-delete-modal',
            rowData: {},
            options: {
                url: JOB_TYPE,
                name: 'Job Types',
                datatableWrapper: false,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name'
                    },
                    {
                        title: this.$t('brief'),
                        type: 'text',
                        key: 'brief'
                    }
                ],
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        key: 'edit',
                        // modifier: () => this.$have('PERMISSION_UPDATE_JOB_TYPE')
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        key: 'delete',
                        // modifier: () => this.$have('PERMISSION_DELETE_JOB_TYPE')
                    }
                ],
                // showAction: (this.$have('PERMISSION_UPDATE_JOB_TYPE') || this.$have('PERMISSION_DELETE_JOB_TYPE')),
                rowLimit: 10,
                orderBy: 'desc',
                tableShadow: false,
                actionType: 'default'
            },
            isJobTypeAddEditModalActive: false,
            isJobTypeDeleteModalActive: false,
        }
    },
    beforeMount() {
        // if (this.$have('PERMISSION_UPDATE_JOB_TYPE') || this.$have('PERMISSION_DELETE_JOB_TYPE')) {
            this.options.columns.push({
                title: this.$t('actions'),
                type: 'action'
            })
        // }
    },
    mounted() {
        this.addJobType();
    },
    methods: {
        addJobType() {
            this.$hub.$on(`headerButtonClicked-${this.id}`, ()=> {
                this.openJobTypeAddEditModal();
            })
        },

        getTableAction(rowData, actionObj) {
            this.rowData = rowData;

            if (actionObj.key === 'edit') {
                this.selectedUrl = `${JOB_TYPE}/${rowData.id}`
                this.openJobTypeAddEditModal();
            } else if (actionObj.key === 'delete') {
                this.openJobTypeDeleteModal();
            }
        },
        openJobTypeAddEditModal() {
            this.isJobTypeAddEditModalActive = true;
        },
        closeJobTypeAddEditModal() {
            this.selectedUrl = '';
            this.isJobTypeAddEditModalActive = false;
        },
        openJobTypeDeleteModal() {
            this.isJobTypeDeleteModalActive = true;
        },
        confirmDelete() {
            this.deleteAndReload(`${JOB_TYPE}/${this.rowData.id}`);
        },
        cancelDelete() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.isJobTypeDeleteModalActive = false;
        }
    }
}