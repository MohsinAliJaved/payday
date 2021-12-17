<template>
    <div>
        <app-table
            :id="tableId"
            :options="options"
            @action="getTableAction"
        />

        <job-type-add-edit-modal
            v-if="isJobTypeAddEditModalActive"
            :selected-url="selectedUrl"
            :table-id="tableId"
            @closeModal="closeJobTypeAddEditModal"
        />

        <app-delete-modal
            v-if="isJobTypeDeleteModalActive"
            :modal-id="deleteModalId"
            :preloader="deleteLoader"
            @confirmed="confirmDelete"
            @cancelled="cancelDelete"
        />
    </div>
</template>

<script>
import JobTypeTableMixin from './JobTypeTableMixin';

export default {
    name: 'JobTypes',
    mixins: [JobTypeTableMixin]
}
</script>