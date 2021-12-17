<template>
    <note :title="$t('attention')"
          :description="$t('server_permission_required', {
        replace: collection(permission_requirements).pluck('folder').join(', ')})"/>
</template>

<script>
import {collection} from "../../../Helper/helpers";
    import Note from "../../Helper/Note";
    export default {
        name: "Permissions",
        components: {Note},
        props: {
            permission_requirements: {
                default: []
            }
        },
        data() {
            return {
                collection
            }
        },
        methods: {},
        created() {

        }
    }
</script>
