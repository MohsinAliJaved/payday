<template>
    <div class="todo-wrapper">
        <h5 class="mb-3">
            {{ $t('your_to_dos') }}
        </h5>

        <div class="todo" >
            <div class="todo-add">
                <div class="input-group">
                    <input type="text"
                           class="form-control"
                           :placeholder="$t('add_a_todo')"
                           aria-describedby="todoAddButton"
                           v-model="newTodo"
                           @keyup.enter="addItem"/>
                    <div class="input-group-append">
                        <button class="btn" :class="{'disabled': !newTodo}" type="button" id="todoAddButton" @click="addItem">
                            <app-icon name="plus" class="size-18"/>
                        </button>
                    </div>
                </div>
            </div>
            <div class="card border-0 shadow" v-if="preloader">
                <div class="card-body min-height-300">
                    <app-overlay-loader></app-overlay-loader>
                </div>
            </div>
            <template v-else>
                <div class="todo-items">
                    <div v-if="pending.length > 0">
                        <p class="status">
                            {{ $t('you_have') }} {{ pending.length }} {{ $t('pending_item').toLowerCase() }}<span
                            v-if="pending.length>1">s</span>
                        </p>
                        <transition-group name="todo-item" tag="ul" class="todo-list">
                            <li v-for="(item, index) in pending" :key="`pending-${index}`">
                                <input type="checkbox"
                                       :id="`item_${item.id}`"
                                       class="todo-checkbox"
                                       @click="pendingToComplete($event,item)"
                                >
                                <label :for="`item_${item.id}`">
                                    <app-icon name="check" class="size-15 primary-text-color"/>
                                </label>
                                <span class="todo-title">{{ item.name }}</span>
                                <button type="button" class="btn-delete" @click="deleteItem(item)">
                                    <app-icon name="trash-2" class="size-16"/>
                                </button>
                            </li>
                        </transition-group>
                    </div>

                    <transition name="slide-fade">
                        <p v-if="!pending.length" class="status free">
                            <img :src="AppFunction.getAppUrl('images/celebration.svg')" alt="No items">
                            {{ $t('no_todos_title') }}
                        </p>
                    </transition>

                    <div v-if="completed.length > 0 && showComplete">
                        <p class="status">
                            {{ $t('you_have') }} {{ completed.length }} {{ $t('completed_item').toLowerCase() }}<span
                            v-if="completed.length>1">s</span>
                        </p>
                        <transition-group name="todo-item" tag="ul" class="todo-list completed-list">
                            <li v-for="(item, index) in completed" :key="`completed-${index}`">
                                <input type="checkbox"
                                       :id="`item_${item.id}`"
                                       class="todo-checkbox"
                                       @click="completeToPending($event,item)"
                                       :checked="true">

                                <label :for="`item_${item.id}`">
                                    <app-icon name="check" class="size-15 primary-text-color"/>
                                </label>
                                <span class="todo-title">{{ item.name }}</span>
                                <button type="button" class="btn-delete" @click="deleteItem(item)">
                                    <app-icon name="trash-2" class="size-16"/>
                                </button>
                            </li>
                        </transition-group>
                    </div>

                    <div class="d-flex justify-content-end">
                        <button type="button" class="btn btn-sm btn-primary px-2 mr-1" v-if="completed.length > 0"
                                @click="toggleShowComplete">
                            <span v-if="!showComplete">{{ $t('show') }}</span>
                            <span v-else>{{ $t('hide') }}</span> {{ $t('complete') }}
                        </button>
                        <button type="button"
                                class="btn btn-sm btn-danger d-inline-flex align-items-center justify-content-center px-2"
                                v-if="todoList.length > 0"
                                @click="clearAll">
                            <app-icon name="x" class="size-16 mr-2"/>
                            {{ $t('clear_all') }}
                        </button>
                    </div>
                </div>
            </template>

        </div>
    </div>
</template>

<script>

import CoreLibrary from '../../../../../core/helpers/CoreLibrary';
import AppFunction from '../../../../../core/helpers/app/AppFunction';
import {TODO} from "../../../../Config/ApiUrl";
import {mapGetters} from "vuex";


export default {
    name: 'Todo',
    extends: CoreLibrary,
    data() {
        return {
            preloader: true,
            todoList: [],
            newTodo: '',
            showComplete: false,
            status: {},
            AppFunction
        };
    },
    computed: {
        ...mapGetters([
            'statusListForTodo'
        ]),
        pendingId: function () {
            return this.statusListForTodo.find((e) => e.name === 'status_pending').id
        },
        completedId: function () {
            return this.statusListForTodo.find((e) => e.name === 'status_done').id
        },
        pending: function () {
            return this.todoList.filter(function (item) {
                return item.status.name === 'status_pending';
            })
        },
        completed: function () {
            return this.todoList.filter(function (item) {
                return item.status.name === 'status_done';
            });
        },
    },
    created() {
        this.getTodos();
    },
    methods: {
        addItem() {
            let url = `${TODO}`,
                data = {
                    status_id: this.pendingId,
                    name: this.newTodo,
                };

            this.axiosPost({url, data}).then(() => {
                this.getTodos();
                this.newTodo = '';
            })
        },
        deleteItem(item) {
            this.todoList.splice(this.todoList.indexOf(item), 1);
            this.axiosDelete(`${TODO}/${item.id}`).catch(() => {
                this.getTodos();
            })
        },
        toggleShowComplete() {
            this.showComplete = !this.showComplete;
        },
        clearAll() {
            this.todoList = [];
            let url = `${TODO}` + '/clear-all';
            this.axiosPut({url}).catch(() => {
                this.getTodos();
            });
        },
        getTodos() {
            this.preloader = true;
            this.axiosGet(TODO).then(res => {
                this.todoList = res.data.data;
                this.preloader = false;
            });
        },
        pendingToComplete(event, item) {
            if (event.target.checked) {
                let url = `${TODO}/${item.id}/change-status`,
                    data = {
                        status_id: this.completedId
                    };

                this.axiosPatch({url, data}).then(() => {
                    this.getTodos();
                });
            }
        },
        completeToPending(event, item) {

            if (!event.target.checked) {
                let url = `${TODO}/${item.id}/change-status`,
                    data = {
                        status_id: this.pendingId
                    };

                this.axiosPatch({url, data}).then(() => {
                    this.getTodos();
                });
            }
        }
    }
}
</script>