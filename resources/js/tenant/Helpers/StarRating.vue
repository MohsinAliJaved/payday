<template>
    <div class="text-size-25 d-flex align-items-center justify-content-between">
        <span v-if="hasCounter">
            <template v-if="stars < 1">Not reviewed yet</template>
            <template v-else>{{ stars }} of {{ maxStars }}</template>
        </span>
        <ul class="rating list list-unstyled p-0 m-0"
            :class="{'disabled': !(true)}">
            <li class="d-inline-block cursor-pointer star"
                :class="{'active': star <= stars}"
                v-for="star in maxStars"
                :key="star"
                @click="rate(star)">
                <app-icon name="star"/>
            </li>
        </ul>
    </div>
</template>

<script>
    export default {
        name: 'StarRating',
        props: {
            grade: {
                type: Number,
                required: true
            },
            maxStars: {
                type: Number,
                default: 5
            },
            hasCounter: {
                type: Boolean,
                default: true
            }
        },
        data() {
            return {
                stars: this.grade
            }
        },
        methods: {
            rate(star) {
                if (typeof star === 'number' && star <= this.maxStars && star >= 0) {
                    this.stars = this.stars === star ? star - 1 : star
                    this.$emit('rateUpdate', this.stars);
                }
            }
        }
    }
</script>