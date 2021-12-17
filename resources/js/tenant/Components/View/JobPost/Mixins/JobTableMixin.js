import {DASHBOARD, JOB, JOB_POST, PUBLIC_JOB_POST} from "../../../../Config/ApiUrl";
import CoreLibrary from '../../../../../core/helpers/CoreLibrary';
import JobTableFilterMixin from "./JobTableFilterMixin";
import DeleteMixin from "../../../../../tenant/Components/Mixins/DeleteMixin";

export default {
    extends: CoreLibrary,
    mixins: [DeleteMixin, JobTableFilterMixin],
    data() {
        return {
            isJobAddEditModalActive: false,
            shareableLinkModalActive: false,
            deleteConfirmationModalActive: false,
            dependenciesCallCount: 0,
            selectedUrl: '',
            tableId: 'job-table',
            deleteModalId: 'job-delete-modal',
            rowData: {},
            options: {
                name: 'JobsTable',
                url: `${DASHBOARD}/job-summery`,
                cardView: true,
                responsive: true,
                enableCookie: false,
                cardViewComponent: 'job-grid-view',
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'text',
                        key: 'name'
                    }
                ],
                actions: [
                    {
                        title: this.$t('preview'),
                        key: 'preview',
                        modifier: (data) => {
                            return data['status'].name === 'status_open'
                        }
                    },
                    {
                        title: this.$t('edit'),
                        key: 'edit',
                        // modifier: () => this.$have('PERMISSION_EDIT_JOB')
                    },
                    {
                        title: this.$t('edit_job_post'),
                        key: 'edit-job-post',
                        // modifier: () => this.$have('PERMISSION_EDIT_JOB_POST')
                    },
                    {
                        title: this.$t('settings'),
                        key: 'job-settings',
                        url: this.getAppUrl('/job-settings'),
                        // modifier: () => this.$have('PERMISSION_VIEW_JOB_POST_SETTING')
                    },
                    {
                        title: this.$t('publish_job'),
                        key: 'status_open',
                        modifier: (data) => {
                            // if(!this.$have('PERMISSION_CHANGE_JOB_POST_STATUS')) return false;
                            return data['status'].name === 'status_draft';
                        }
                    },
                    {
                        title: this.$t('sharable_link'),
                        key: 'job-sharable-link',
                        modifier: (data) => {
                            // if(!this.$have('PERMISSION_SHARABLE_LINK_OF_JOB_POST')) return false;
                            return data['status'].name === 'status_open';
                        }
                    },
                    {
                        title: this.$t('activate'),
                        key: 'status_open',
                        modifier: (data) => {
                            // if(!this.$have('PERMISSION_CHANGE_JOB_POST_STATUS')) return false;
                            return data['status'].name === 'status_closed';
                        }
                    },
                    {
                        title: this.$t('deactivate'),
                        key: 'status_closed',
                        modifier: (data) => {
                            // if(!this.$have('PERMISSION_CHANGE_JOB_POST_STATUS')) return false;
                            return data['status'].name === 'status_open';
                        }
                    },
                    {
                        title: this.$t('delete'),
                        key: 'delete',
                        // modifier: () => this.$have('PERMISSION_DELETE_JOB_POST')

                    }
                ],
                filters: [
                    {
                        title: this.$t('active_jobs'),
                        type: 'dropdown-menu-filter',
                        key: 'status',
                        listValueField: 'translated_name',
                        option: [],
                    }
                ],
                rowLimit: 5,
                afterRequestSuccess: (data) => {
                    this.callDependencies();
                }
            }
        }
    },
    methods: {
        callDependencies() {
            if(this.dependenciesCallCount === 0 ) {
                this.$store.dispatch('getJobTypeList');
                this.$store.dispatch('getDepartmentList');
                this.$store.dispatch('getStagesList');
                this.$store.dispatch('getCompanyLocationList');
                this.dependenciesCallCount++;
            }
        },
        getDashboardAction(rowData, actionObj, active) {
            this.rowData = rowData;
            if (actionObj.key === 'edit') {
                this.selectedUrl = `${JOB}/${this.rowData.id}`;
                this.openJobAddEditModal();
            } else if(actionObj.key === 'delete') {
                this.deleteConfirmationModalActive = true;
            } else if(actionObj.key === 'edit-job-post') {
                window.location = this.getAppUrl(`${JOB_POST}/${rowData.id}/edit-job-post`);
            } else if(actionObj.key === 'job-settings') {
                window.location = this.getAppUrl(`${JOB_POST}/${rowData.id}/settings`)
            } else if (actionObj.key === 'status_open' || actionObj.key === 'status_closed') {
                let changeStatusId = this.statusListForJobPost.find(item => item.name === actionObj.key).id;
                this.changeJobStatus(changeStatusId)
            } else if (actionObj.key === 'job-sharable-link') {
                this.openShareableLinkModal();
            } else if (actionObj.key === 'preview') {
                let link = `${PUBLIC_JOB_POST}/${rowData.slug}/display`;
                window.location = this.getAppUrl(link);
            }
        },
        changeJobStatus(status_id) {
            let url = `${JOB_POST}/${this.rowData.id}/change-status`,
                data = {status_id}
            this.axiosPatch({url, data}).then((res)=> {
                this.$toastr.s(res.data.message);
                this.$hub.$emit(`reload-${this.tableId}`)
            })
        },
        openJobAddEditModal() {
            this.isJobAddEditModalActive = true;
        },
        closeJobAddEditModal() {
            this.selectedUrl = '';
            this.isJobAddEditModalActive = false;
        },
        openShareableLinkModal() {
            this.shareableLinkModalActive = true
        },
        closeShareableLinkModal() {
            this.shareableLinkModalActive = false
        },
        confirmed() {
            this.deleteAndReload(`${JOB}/${this.rowData.id}`)
        },
        cancel() {
            $(`#${this.deleteModalId}`).modal('hide');
            this.closeDeleteModal();
        },
        closeDeleteModal() {
            this.deleteConfirmationModalActive = false;
        },
    }
}