import {EVENT_TYPE} from "../../../../../Config/ApiUrl";
import DeleteMixin from "../../../../../../tenant/Components/Mixins/DeleteMixin";

export default {
    mixins: [DeleteMixin],
    props: ['id', 'props'],
    data() {
        return {
            selectedUrl: '',
            tableId: 'event-type',
            deleteModalId: 'event-type-delete-modal',
            rowData: {},
            options: {
                url: EVENT_TYPE,
                name: 'event_types',
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
                        // modifier: () => this.$have('PERMISSION_UPDATE_EVENT_TYPE')
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        key: 'delete',
                        // modifier: () => this.$have('PERMISSION_DELETE_EVENT_TYPE')
                    }
                ],
                // showAction: (this.$have('PERMISSION_UPDATE_EVENT_TYPE') || this.$have('PERMISSION_DELETE_EVENT_TYPE')),
                rowLimit: 10,
                orderBy: 'desc',
                tableShadow: false,
                actionType: 'default'
            },
            isEventTypeAddEditModalActive: false,
            isEventTypeDeleteModalActive: false,
        }
    },
    beforeMount() {
        // if (this.$have('PERMISSION_UPDATE_EVENT_TYPE') || this.$have('PERMISSION_DELETE_EVENT_TYPE')) {
            this.options.columns.push({
                title: this.$t('actions'),
                type: 'action'
            })
        // }
    },
    mounted() {
        this.addEventType();
    },
    methods: {
        addEventType() {
            this.$hub.$on(`headerButtonClicked-${this.id}`, () => {
                this.openEventTypeAddEditModal();
            })
        },
        getTableAction(rowData, actionObj) {
            this.rowData = rowData;

            if (actionObj.key === 'edit') {
                this.openEventTypeAddEditModal();
                this.selectedUrl = `${EVENT_TYPE}/${rowData.id}`;
            } else if (actionObj.key === 'delete') {
                this.openEventTypeDeleteModal();
            }
        },
        openEventTypeAddEditModal() {
            this.isEventTypeAddEditModalActive = true;
        },
        closeEventTypeAddEditModal() {
            this.selectedUrl = '';
            this.isEventTypeAddEditModalActive = false;
        },
        openEventTypeDeleteModal() {
            this.isEventTypeDeleteModalActive = true;
        },
        confirmDelete() {
            this.deleteAndReload(`${EVENT_TYPE}/${this.rowData.id}`);
        },
        cancelDelete() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.isEventTypeDeleteModalActive = false
        }
    }
}