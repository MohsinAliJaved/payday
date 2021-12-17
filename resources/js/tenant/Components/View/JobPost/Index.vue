<template>
    <div class="content-wrapper">
        <div class="row">
            <div class="col-lg-7 col-xl-8 mb-4 mb-lg-0">
                <div class="d-flex align-items-center justify-content-between">
                    <app-breadcrumb :page-title="$t('job_board')"/>
                    <button
                        type="button"
                        class="btn btn-success btn-with-shadow mb-4"
                        data-toggle="modal"
                        @click="openJobAddEditModal">
                        <app-icon name="plus" class="size-20 mr-2"/>
                        {{ $createLabel('new_job') }}
                    </button>
                </div>

                <app-table
                    id="job-table"
                    :options="options"
                    :table-view="false"
                    :card-view="true"
                    @action="getDashboardAction"
                />

                <job-add-edit-modal
                    v-if="isJobAddEditModalActive"
                    :table-id="tableId"
                    :selected-url="selectedUrl"
                    @closeModal="closeJobAddEditModal"
                />

                <app-delete-modal
                    v-if="deleteConfirmationModalActive"
                    :preloader="deleteLoader"
                    :modal-id="deleteModalId"
                    @confirmed="confirmed"
                    @cancelled="cancel"
                />

                <!--Job Shareable Link Modal -->
                <shareable-link-modal
                    v-if="shareableLinkModalActive"
                    :job-post-id="rowData.id"
                    @closeModal="closeShareableLinkModal"
                />
            </div>
            <div class="col-lg-5 col-xl-4">
                <div class="mb-primary">
                    <events/>
                </div>
                <div>
                    <to-do/>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
import JobTableMixin from './Mixins/JobTableMixin';

export default {
    name: 'JobList',
    mixins: [JobTableMixin]
}
</script>