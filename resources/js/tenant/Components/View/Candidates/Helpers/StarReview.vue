<template>
    <ul v-if="value.length" class="rated list list-unstyled p-0 m-0 min-width-120">
        <li class="d-inline-block star"
            v-for="star in maxStars"
            :class="{'active': star<=reviewStars}"
            :key="star">
            <app-icon name="star" class="size-14 ml-1"/>
        </li>
    </ul>
    <p v-else class="mb-0">-</p>
</template>

<script>
export default {
    name: "StarReview",
    props: {
        data: {},
        rowData: {},
        tableId: {},
        value: {}
    },
    data() {
        return {
            maxStars: 5
        }
    },
    computed: {
        reviewStars() {
            let data = this.value.length > 0 ? this.value[0] : null;
            if(!data) return 0
            return Number(data['review']);
        }
    }
}
</script>
