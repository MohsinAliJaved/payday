<template>
    <div class="row justify-content-center">
        <div class="col-sm-8 py-5">
            <h3 class="text-center text-capitalize mb-primary">
                {{ `${appName} ${$t('required_environments')}` }}
            </h3>
            <requirements :php_requirements="php_requirements" :available="available"/>
            <permissions v-if="permission_requirements.length" :permission_requirements="permission_requirements"/>
            <span class="text-danger" v-if="errors">{{ errors }}</span>
        </div>
    </div>
</template>

<script>
    import {axiosGet} from "../../../Helpers/AxiosHelper";
    import Requirements from "./Server/Requirements";
    import Permissions from "./Server/Permissions";

    export default {
        name: "Layout",
        components: {Permissions, Requirements},
        props: {
            errors: {
                default: false
            },
            appName: {}
        },
        data(){
            return {
                available: {},
                loading: true
            }
        },
        computed: {
            php_requirements() {
                if (!this.available.requirements) {
                    return [];
                }
                return Object.keys(this.available.requirements.php).filter(r => !this.available.requirements.php[r])
            },
            permission_requirements() {
                if (!this.available.permissions) {
                    return [];
                }
                return this.available.permissions.permissions.filter(permission => !permission.isSet)
            }

        },
        created() {
            axiosGet('app/requirements').then(response => {
                this.available = response.data;
            }).catch(error => {
                this.$toastr.e(error.response.data.message);
            }).finally(response => {
                this.loading = false;
            })
        }
    }
</script>
