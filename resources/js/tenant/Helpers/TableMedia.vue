<template>
    <div class="d-flex align-items-center">
        <div class="avatars-w-50">
            <app-avatar
                :title="rowData.full_name"
                :img="''"
                :alt-text="rowData.full_name"
                :shadow="true"
            />
        </div>
        <div class="ml-3">
            <a
               href="#"
               @click.prevent="passEvent">
                {{ rowData.full_name }}
            </a>
            <!-- <p v-else class="mb-0">
                {{ rowData.full_name }}
            </p> -->
            <p class="mb-0 font-size-90 text-muted">
                {{ rowData.email }}
            </p>
        </div>
    </div>
</template>

<script>
export default {
    name: 'TableMedia',
    props: {
        value: {},
        rowData: {},
        tableId: {},
    },
    methods: {
        passEvent() {
            this.$hub.$emit(`getTableMediaAction-${this.tableId}`, this.rowData)
        }
    }
}
</script>