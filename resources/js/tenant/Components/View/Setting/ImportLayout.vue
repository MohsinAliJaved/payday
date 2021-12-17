<template>
    <div class="content-wrapper">
        <app-page-top-section :title="$t('import')"/>
        <app-tab
            :tabs="tabs"
            icon="settings"
        />
    </div>
</template>

<script>
export default {
    name: "ImportLayout",
    data() {
        return {
            tabs: [
                {
                    name: this.$t('employees'),
                    title: this.$t('import_employee'),
                    component: "app-import-employees",
                    permission: this.$can('import_employees')
                },
            ]
        }
    }
}
</script>

<style scoped>

</style>