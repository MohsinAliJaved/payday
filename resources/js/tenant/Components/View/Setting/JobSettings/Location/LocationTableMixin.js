import {COMPANY_LOCATION} from "../../../../../Config/ApiUrl";
import DeleteMixin from "../../../../../../tenant/Components/Mixins/DeleteMixin";

export default {
    mixins: [DeleteMixin],
    props: ['id', 'props'],
    data() {
        return {
            selectedUrl: '',
            tableId: 'company-location',
            deleteModalId: 'company-location-delete-modal',
            rowData: {},
            options: {
                url: COMPANY_LOCATION,
                name: 'CompanyLocations',
                datatableWrapper: false,
                columns: [
                    {
                        title: this.$t('address'),
                        type: 'text',
                        key: 'address'
                    }
                ],
                actions: [
                    {
                        title: this.$t('edit'),
                        icon: 'edit',
                        key: 'edit',
                        // modifier: () => this.$have('PERMISSION_UPDATE_COMPANY_LOCATION')
                    },
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                        key: 'delete',
                        // modifier: () => this.$have('PERMISSION_DELETE_COMPANY_LOCATION')
                    }
                ],
                // showAction: (this.$have('PERMISSION_UPDATE_COMPANY_LOCATION') || this.$have('PERMISSION_DELETE_COMPANY_LOCATION')),
                rowLimit: 10,
                orderBy: 'desc',
                tableShadow: false,
                actionType: 'default'
            },
            isLocationAddEditModalActive: false,
            isLocationDeleteModalActive: false,
        }
    },
    beforeMount() {
        // if (this.$have('PERMISSION_UPDATE_COMPANY_LOCATION') || this.$have('PERMISSION_DELETE_COMPANY_LOCATION')) {
            this.options.columns.push({
                title: this.$t('actions'),
                type: 'action'
            })
        // }
    },
    mounted() {
        this.addCompanyLocation();
    },
    methods: {
        addCompanyLocation() {
            this.$hub.$on(`headerButtonClicked-${this.id}`, () => {
                this.openLocationAddEditModal();
            })
        },
        getTableAction(rowData, actionObj) {
            this.rowData = rowData;

            if (actionObj.title === this.$t('edit')) {
                this.openLocationAddEditModal();
                this.selectedUrl = `${COMPANY_LOCATION}/${rowData.id}`;
            } else if (actionObj.title === this.$t('delete')) {
                this.openLocationDeleteModal();
            }
        },
        openLocationAddEditModal() {
            this.isLocationAddEditModalActive = true;
        },
        closeLocationAddEditModal() {
            this.selectedUrl = '';
            this.isLocationAddEditModalActive = false;
        },
        openLocationDeleteModal() {
            this.isLocationDeleteModalActive = true;
        },
        confirmDelete() {
            this.deleteAndReload(`${COMPANY_LOCATION}/${this.rowData.id}`);
        },
        cancelDelete() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.isLocationDeleteModalActive = false
        }
    }
}