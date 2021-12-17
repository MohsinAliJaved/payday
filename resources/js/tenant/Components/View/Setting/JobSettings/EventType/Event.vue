<template>
    <div>
        <app-table
            :id="tableId"
            :options="options"
            @action="getTableAction"
        />

        <event-add-edit-modal
            v-if="isEventTypeAddEditModalActive"
            :selected-url="selectedUrl"
            :table-id="tableId"
            @closeModal="closeEventTypeAddEditModal"
        />

        <app-delete-modal
            v-if="isEventTypeDeleteModalActive"
            :modal-id="deleteModalId"
            :preloader="deleteLoader"
            @confirmed="confirmDelete"
            @cancelled="cancelDelete"
        />
    </div>
</template>

<script>
    import EventTypeTableMixin from './EventTypeTableMixin';

    export default {
        name: 'Event',
        mixins: [EventTypeTableMixin]
    }
</script>