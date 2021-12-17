<template>
    <div>
        <app-table
            :id="tableId"
            :options="options"
            @action="getTableAction"
        />

        <hiring-team-add-edit-modal
            v-if="isHiringTeamModalActive"
            :table-id="tableId"
            :job-post-id="props.job_post_id"
            :team-member="teamMember"
            @closeModal="closeHiringTeamAddEditModal"
        />
    </div>
</template>

<script>
import {HIRING_TEAM, HIRING_TEAM_LIST} from "../../../../../Config/ApiUrl";
import DeleteMixin from "../../../../../Components/Mixins/DeleteMixin";

export default {
    mixins: [DeleteMixin],
    props: ['props', 'id'],
    name: 'HiringTeamSetting',
    data() {
        return {
            selectedUrl: '',
            isHiringTeamModalActive: false,
            tableId: 'hiring-team',
            rowData: {},
            options: {
                url: `${HIRING_TEAM_LIST}/${this.props.job_post_id}`,
                name: this.$t('hiring_team'),
                datatableWrapper: false,
                columns: [
                    {
                        title: this.$t('name'),
                        type: 'object',
                        key: 'user',
                        modifier: (value) => value ? value.full_name : '-',
                    },
                    {
                        title: this.$t('email'),
                        type: 'object',
                        key: 'user',
                        modifier: (value) => value ? value.email : '-',
                    }
                ],
                actions: [
                    {
                        title: this.$t('delete'),
                        icon: 'trash-2',
                    }
                ],
                afterRequestSuccess: (data) => {
                    this.getRequiters(data);
                },
                // showAction: this.$have('PERMISSION_DELETE_HIRING_TEAM'),
                rowLimit: 10,
                orderBy: 'desc',
                tableShadow: false,
                actionType: 'default'
            },
            isStageAddEditModalActive: false,
            teamMember: []
        }
    },
    beforeMount() {
        // if (this.$have('PERMISSION_DELETE_HIRING_TEAM')) {
            this.options.columns.push({
                    title: this.$t('actions'),
                    type: 'action'
                })
        // }
    },
    mounted() {
        this.addTeamMember();
    },
    methods: {
        addTeamMember() {
            this.$hub.$on(`headerButtonClicked-${this.id}`, () => {
                this.openHiringTeamModal();
            })
        },
        openHiringTeamModal() {
            this.isHiringTeamModalActive = true;
        },
        closeHiringTeamAddEditModal() {
            this.selectedUrl = '';
            this.isHiringTeamModalActive = false;
        },
        getTableAction(rowData, actionObj) {
            this.rowData = rowData;
            if (actionObj.title === this.$t('delete')) {
                this.deleteAndReload(`${HIRING_TEAM}/${rowData.id}`);
            }
        },
        getRequiters({data}) {
            this.teamMember = data.data.map(item => Number(item.user.id))
        }
    }
}
</script>