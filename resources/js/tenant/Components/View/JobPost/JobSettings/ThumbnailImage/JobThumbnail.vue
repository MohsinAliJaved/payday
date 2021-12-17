<template>
    <div class="p-primary min-height-300">
        <app-overlay-loader v-if="preloader || loader"/>
        <form class="mb-0"
              :class="{'loading-opacity': preloader || loader}"
              ref="form"
              :data-url="''">

            <app-note
                class="mb-4"
                title="info"
                :show-title="false"
                :notes="$t('sharable_thumbnail_instruction')"
            />

            <div class="form-group mb-0">
                <app-input
                    id="thumbnail_image"
                    class="circle mx-xl-auto"
                    type="custom-file-upload"
                    v-model="formData.thumbnail_image"
                    :required="true"
                    :generate-file-url="false"
                    :label="$t('change')"/>
            </div>

            <button
                type="button"
                class="btn btn-primary mt-primary"
                @click.prevent="submit">
                {{ $t('save') }}
            </button>
        </form>
    </div>
</template>

<script>
import {FormMixin} from "../../../../../../core/mixins/form/FormMixin";
import {FormHelperMixin} from "../../../../../../tenant/Components/Mixins/FormHelperMixin";
import {SOCIAL_SHARABLE_THUMBNAIL} from "../../../../../Config/ApiUrl";
import {mapGetters} from "vuex";
import ErrorHandlerMixin from "../../../../../../common/Mixin/ErrorHandlerMixin";

export default {
    name: "JobThumbnail",
    mixins: [FormMixin, FormHelperMixin, ErrorHandlerMixin],
    props: ['props'],
    data() {
        return {
            SOCIAL_SHARABLE_THUMBNAIL,
            formData: {
                thumbnail_image: '',
            }
        }
    },
    computed: {
        ...mapGetters([
            'selectedJobDetails',
            'loader'
        ]),
    },
    watch: {
        selectedJobDetails: {
            handler: function (data) {
                this.formData.thumbnail_image = data['job_post_thumbnail'] ? data['job_post_thumbnail'].full_url : ''
            }
        }
    },
    created() {
        this.formData.thumbnail_image = this.selectedJobDetails?.job_post_thumbnail ? this.selectedJobDetails['job_post_thumbnail'].full_url : ''
    },
    methods: {
        submit() {
            this.errorHandle = true;
            let formData = new FormData,
            url = `${SOCIAL_SHARABLE_THUMBNAIL}/${this.props.job_post_id}/update`;

            formData.append('_method', 'patch')
            formData.append('thumbnail_image', this.formData.thumbnail_image);

            this.submitFromFixin('post', url, formData);
        },
        afterSuccess(response) {
            this.$toastr.s(response.data.message);
            this.$store.dispatch('getJobDetails', this.selectedJobDetails.id);
        }
    }
}
</script>
