<template>
    <table class="table table-card">
        <tbody>
        <tr>
            <td class="text-muted width-150">{{ $t('name') }}</td>
            <td>{{ applicant.full_name }}</td>
        </tr>
        <tr>
            <td class="text-muted width-150">{{ $t('email') }}</td>
            <td>{{ applicant.email }}</td>
        </tr>
        <tr>
            <td class="text-muted width-150">{{ $t('gender') }}</td>
            <td class="text-capitalize">{{ applicant.gender }}</td>
        </tr>
        <tr v-if="applicant.date_of_birth">
            <td class="text-muted width-150">{{ $t('date_of_birth') }}</td>
            <td>{{ formatDateToLocal(applicant.date_of_birth) }}</td>
        </tr>
        </tbody>
    </table>
</template>

<script>
import {formatDateToLocal} from "../../../../Helpers/Helpers";

export default {
    name: "CandidateDetails",
    props: {
        applicant: {}
    },
    data() {
        return {
            formatDateToLocal
        }
    }
}
</script>