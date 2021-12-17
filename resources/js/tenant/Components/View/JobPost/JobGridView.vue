<template>
    <div v-if="dataLoader" class="card border-0 shadow">
        <div class="card-body min-height-400"></div>
    </div>
    <div v-else class="row">
        <div
            v-if="data.length > 0"
            class="col-12 mb-primary"
            v-for="(job, index) in data"
            :key="'job-'+index">
            <job-card
                :id="id"
                :job="job"
                :actions="actions"
                @action-job-table="getAction"
            />
        </div>
    </div>
</template>

<script>
export default {
    name: 'JobGridView',
    props: ['id', 'data', 'actions', 'dataLoader'],
    methods: {
        getAction(row, action, active) {
            this.$emit('getCardAction', row, action, active);
        }
    }
}
</script>