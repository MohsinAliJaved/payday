<template>
    <div>
        <app-table
            :id="tableId"
            :options="options"
            @action="getTableAction"
        />

        <department-add-edit-modal
            v-if="isDepartmentAddEditModalActive"
            :selected-url="selectedUrl"
            :table-id="tableId"
            @closeModal="closeDepartmentAddEditModal"
        />

        <app-delete-modal
            v-if="isDepartmentDeleteModalActive"
            :modal-id="deleteModalId"
            :preloader="deleteLoader"
            @confirmed="confirmDelete"
            @cancelled="cancelDelete"
        />
    </div>
</template>

<script>
    import DepartmentTableMixin from './DepartmentTableMixin';

    export default {
        name: 'Departments',
        mixins: [DepartmentTableMixin]
    }
</script>