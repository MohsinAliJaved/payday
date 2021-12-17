<template>
    <div class="font-size-default">
        <div id="accordionExample" class="accordion">
            <div class="card card-with-shadow border-0 p-primary">
                <div class="card-header bg-transparent border-0 px-0 pt-0">
                    <a href="#"
                       data-toggle="collapse"
                       data-target="#requirements"
                       aria-expanded="false"
                       aria-controls="permission"
                       class="d-block position-relative text-capitalize text-muted">
                        {{ $t('php') }}
                        <span class="font-size-90">
                            ({{ $t('version_required', { 'number': $optional(available, 'php', 'minimum') }) }})
                        </span>
                        <div class="float-right">
                            <span :class="`${$optional(available, 'php', 'supported') ? 'text-success' : 'text-danger'}`">
                                {{ $optional(available, 'php', 'current') }}
                            </span>
                        </div>
                    </a>
                </div>
                <div class="card-body p-0" v-if="php_requirements.length">
                    <div id="requirements"
                         data-parent="#accordionExample"
                         class="collapse show">
                        <div class="card-body p-0 border-0">
                            <ul class="list-group" >
                                <li v-for="(requirement, index) in php_requirements"
                                    class="border-0 list-group-item d-flex justify-content-between align-items-center px-0"
                                    :class="{'pb-0': index === php_requirements.length - 1}">
                                    {{ requirement.toUpperCase() }}
                                    <app-icon name="x"
                                              class="text-danger"
                                              width="15"
                                              height="15"/>
                                </li>
                            </ul>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </div>
</template>

<script>
    export default {
        name: "Requirements",
        props: {
            php_requirements: {
                default: function () {
                    return [];
                }
            },
            available: {
                default: function () {
                    return []
                }
            }
        },
    }
</script>
