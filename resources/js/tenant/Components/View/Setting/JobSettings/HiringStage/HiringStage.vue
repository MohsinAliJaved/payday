<template>
    <div>
        <app-table
            :id="tableId"
            :options="options"
            @action="getTableAction"
        />

        <hiring-stage-add-edit-modal
            v-if="isStageAddEditModalActive"
            :selected-url="selectedUrl"
            :table-id="tableId"
            @closeModal="closeStageAddEditModal"
        />

        <app-delete-modal
            v-if="isStageDeleteModalActive"
            :modal-id="deleteModalId"
            :preloader="deleteLoader"
            @confirmed="confirmDelete"
            @cancelled="cancelDelete"
        />
    </div>
</template>

<script>
import HiringStageTableMixin from "./HiringStageTableMixin";

export default {
    name: "HiringStage",
    mixins: [HiringStageTableMixin]
}
</script>