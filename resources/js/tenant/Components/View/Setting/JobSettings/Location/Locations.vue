<template>
    <div>
        <app-table
            :id="tableId"
            :options="options"
            @action="getTableAction"
        />

        <location-add-edit-modal
            v-if="isLocationAddEditModalActive"
            :table-id="tableId"
            :selected-url="selectedUrl"
            @closeModal="closeLocationAddEditModal"
        />

        <app-delete-modal
            v-if="isLocationDeleteModalActive"
            :modal-id="deleteModalId"
            :preloader="deleteLoader"
            @confirmed="confirmDelete"
            @cancelled="cancelDelete"
        />
    </div>
</template>

<script>
    import LocationTableMixin from './LocationTableMixin';

    export default {
        name: 'Locations',
        mixins: [LocationTableMixin]
    }
</script>
