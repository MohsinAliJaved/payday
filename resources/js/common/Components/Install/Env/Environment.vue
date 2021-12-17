<template>
    <div class="py-5">
        <h2 class="text-center text-capitalize mb-primary">
            {{ $t('install') }} {{ appName }}
        </h2>
        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center">
                    <app-icon name="database" class="primary-text-color mr-2"/>
                    {{ $t('database_configuration') }}
                </h5>
            </div>
            <div class="card-body">
                <app-form-group
                    page="page"
                    :label="$t('db_connection')"
                    type="select"
                    v-model="environment.database_connection"
                    :placeholder="$placeholder('db_connection', '')"
                    :list="[
                        {id: 'mysql', value: $t('mysql')},
                        {id: 'pgsql', value: $t('pgsql')},
                        {id: 'sqlsrv', value: $t('sqlsrv')},
                    ]"
                    :error-message="$errorMessage(errors, 'database_connection')"
                />
                <app-form-group
                    page="page"
                    :label="$t('database_hostname')"
                    type="text"
                    v-model="environment.database_hostname"
                    :placeholder="$placeholder('database_hostname', '')"
                    :error-message="$errorMessage(errors, 'database_hostname')"
                />
                <app-form-group
                    page="page"
                    :label="$t('database_port')"
                    type="text"
                    v-model="environment.database_port"
                    :placeholder="$placeholder('database_port', '')"
                    :error-message="$errorMessage(errors, 'database_port')"
                />
                <app-form-group
                    page="page"
                    :label="$t('database_name')"
                    type="text"
                    v-model="environment.database_name"
                    :placeholder="$placeholder('database_name', '')"
                    :error-message="$errorMessage(errors, 'database_name')"
                />
                <app-form-group
                    page="page"
                    :label="$t('database_username')"
                    type="text"
                    v-model="environment.database_username"
                    :placeholder="$placeholder('database_username', '')"
                    :error-message="$errorMessage(errors, 'database_username')"
                />

                <app-form-group
                    page="page"
                    :label="$t('database_password')"
                    type="password"
                    v-model="environment.database_password"
                    :placeholder="$placeholder('database_password', '')"
                    :error-message="$errorMessage(errors, 'database_password')"
                    :show-password="true"
                />

            </div>
        </div>
        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="user" class="primary-text-color mr-2"/>
                    {{ $t('admin_login_details') }}
                </h5>
            </div>
            <div class="card-body">
                <div class="form-group row">
                    <div class="col-12">
                        <app-note :title="$t('password_requirements')"
                                  :notes="$t('password_requirements_message')" />
                    </div>
                </div>
                <app-form-group
                    page="page"
                    :label="$t('name')"
                    type="text"
                    v-model="environment.full_name"
                    :placeholder="$placeholder('name', '')"
                    :error-message="$errorMessage(errors, 'full_name')"
                />
                <app-form-group
                    page="page"
                    :label="$t('email')"
                    type="text"
                    v-model="environment.email"
                    :placeholder="$placeholder('email', '')"
                    :error-message="$errorMessage(errors, 'email')"
                />
                <app-form-group
                    page="page"
                    :label="$t('password')"
                    type="password"
                    v-model="environment.password"
                    :placeholder="$placeholder('password', '')"
                    :error-message="$errorMessage(errors, 'password')"
                    :show-password="true"
                />
                <app-form-group
                    page="page"
                    :label="$t('confirm_password')"
                    type="password"
                    v-model="environment.password_confirmation"
                    :placeholder="$placeholder('password', '')"
                    :error-message="$errorMessage(errors, 'password_confirmation')"
                    :show-password="true"
                />
            </div>
        </div>

        <div class="card card-with-shadow border-0 mb-primary">
            <div class="card-header bg-transparent p-primary">
                <h5 class="card-title mb-0 d-flex align-items-center ">
                    <app-icon name="key" class="primary-text-color mr-2"/>
                    {{ $t('purchase_code') }}
                </h5>
            </div>
            <div class="card-body">
                <app-form-group
                    page="page"
                    :label="$t('code')"
                    type="text"
                    v-model="environment.code"
                    :placeholder="$placeholder('code', '')"
                    :error-message="$errorMessage(errors, 'code')"
                />
            </div>
        </div>


        <div class="form-group mt-5">
            <app-submit-button :loading="loading"
                               @click="submitData"
                               btn-class="btn-block btn-primary"
                               :label="$t('install')"/>
        </div>
    </div>
</template>

<script>
import FormHelperMixins from "../../../Mixin/Global/FormHelperMixins";
import {urlGenerator} from "../../../Helper/AxiosHelper";

export default {
    name: "Environment",
    mixins: [FormHelperMixins],
    props: {
        appName: {}
    },
    data() {
        return {
            environment: {
                app_name: this.appName,
                environment: 'production',
                app_debug: 'true',
                app_url: window.localStorage.getItem('base_url') ?? window.origin,
                cache_driver: 'file',
                queue_connection: 'database',
                session_driver: 'cookie',
                database_connection: 'mysql',
                database_hostname: 'localhost',
                database_port: '3306',
                name: 'Default tenant',
                short_name: 'default-tenant',
                full_name: ''
            },
        }
    },
    methods: {
        submitData() {
            this.loading = true;
            const {first_name, last_name} = this.names;
            const formData = {...this.environment, first_name, last_name}
            this.submitFromFixin('post', 'app/set-environment', formData)
        },
        afterSuccess(response) {
            this.$toastr.s('', response.data.message);
            window.location = urlGenerator('/admin/users/login')
        },
        afterFinalResponse(){}
    },
    created() {
        this.environment.database_connection = 'mysql';
    },
    computed: {
        names() {
            const full_name_spited = this.environment.full_name.split(' ').filter(name => name);
            if (full_name_spited.length) {
                if (full_name_spited.length === 2) {
                    return {
                        first_name: full_name_spited[0],
                        last_name: full_name_spited[1]
                    }
                }else if (full_name_spited.length === 1) {
                    return {
                        first_name: full_name_spited[0],
                        last_name: ''
                    }
                }else if (full_name_spited.length === 3) {
                    return {
                        first_name: `${full_name_spited[0]} ${full_name_spited[1]}`,
                        last_name: full_name_spited[2]
                    }
                }else {
                    return {
                        first_name: full_name_spited[0],
                        last_name: full_name_spited.slice(1, full_name_spited.length).join(' ')
                    }
                }
            }
            return {
                first_name: '',
                last_name: ''
            }
        }
    }
}
</script>

