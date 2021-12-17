<template>
    <div>
        <app-table
            id="leave-types-table"
            :options="options"
            @action="triggerActions"
        />

        <app-leave-type-create-edit
            v-if="isModalActive"
            v-model="isModalActive"
            :selected-url="selectedUrl"
        />

        <app-confirmation-modal
            v-if="confirmationModalActive"
            icon="trash-2"
            modal-id="app-confirmation-modal"
            @confirmed="confirmed('leave-types-table')"
            @cancelled="cancelled"
        />
    </div>
</template>

<script>

import HelperMixin from "../../../../common/Mixin/Global/HelperMixin";
import LeaveTypeMixins from "../../Mixins/LeaveTypeMixins";
import TriggerActionMixin from "../../../../common/Mixin/Global/TriggerActionMixin";
import DeleteMixin from "../../../../common/Mixin/Global/DeleteMixin";

export default {
    name: "LeaveTypes",
    mixins: [HelperMixin, LeaveTypeMixins, TriggerActionMixin, DeleteMixin],
    props: {
        props: {
            default: ''
        },
        id: {
            type: String
        }
    },
    data() {
        return {
            selectedUrl: '',
            isModalActive: false,
            confirmationModalActive: false
        }
    },
    mounted() {
        this.$hub.$on('headerButtonClicked-' + this.id, (component) => {
            this.isModalActive = true;
            this.selectedUrl = '';
        })
    },
    methods: {
        triggerActions(row, action, active) {
            if (action.name === 'edit') {
                this.selectedUrl = `${this.apiUrl.LEAVE_TYPES}/${row.id}`;
                this.isModalActive = true;
            } else {
                this.getAction(row, action, active)
            }
        },
        openDepartmentModal() {
            this.selectedUrl = '';
            this.isModalActive = true;
        }
    }
}
</script>

<style scoped>

</style>
