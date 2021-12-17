import {DEPARTMENT} from "../../../../../Config/ApiUrl";
import DeleteMixin from "../../../../../../tenant/Components/Mixins/DeleteMixin";

export default {
    mixins: [DeleteMixin],
    props: ['id', 'props'],
    data() {
        return {
            selectedUrl: '',
            tableId: 'department',
            deleteModalId: 'department-delete-modal',
            rowData: {},
            options: {
                url: DEPARTMENT,
                name: 'Departments',
                datatableWrapper: false,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name'
                    }
                ],
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        key: 'edit',
                        // modifier: () => this.$have('PERMISSION_UPDATE_DEPARTMENT')
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        key: 'delete',
                        // modifier: () => this.$have('PERMISSION_DELETE_DEPARTMENT')
                    }
                ],
                // showAction: (this.$have('PERMISSION_UPDATE_DEPARTMENT') || this.$have('PERMISSION_DELETE_DEPARTMENT')),
                rowLimit: 10,
                orderBy: 'desc',
                tableShadow: false,
                actionType: 'default'
            },
            isDepartmentAddEditModalActive: false,
            isDepartmentDeleteModalActive: false,
        }
    },
    beforeMount() {
        // if (this.$have('PERMISSION_UPDATE_DEPARTMENT') || this.$have('PERMISSION_DELETE_DEPARTMENT')) {
            this.options.columns.push({
                title: this.$t('actions'),
                type: 'action'
            })
        // }
    },
    mounted() {
        this.addDepartment();
    },
    methods: {
        addDepartment() {
            this.$hub.$on(`headerButtonClicked-${this.id}`, () => {
                this.openDepartmentAddEditModal();
            })
        },
        getTableAction(rowData, actionObj) {
            this.rowData = rowData;

            if (actionObj.key === 'edit') {
                this.openDepartmentAddEditModal();
                this.selectedUrl = `${DEPARTMENT}/${rowData.id}`;
            } else if (actionObj.key === 'delete') {
                this.openDepartmentDeleteModal();
            }
        },
        openDepartmentAddEditModal() {
            this.isDepartmentAddEditModalActive = true;
        },
        closeDepartmentAddEditModal() {
            this.selectedUrl = '';
            this.isDepartmentAddEditModalActive = false;
        },
        openDepartmentDeleteModal() {
            this.isDepartmentDeleteModalActive = true;
        },
        confirmDelete() {
            this.deleteAndReload(`${DEPARTMENT}/${this.rowData.id}`);
        },
        cancelDelete() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.isDepartmentDeleteModalActive = false
        }
    }
}