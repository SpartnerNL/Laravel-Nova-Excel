<template>
    <div class="flex w-full justify-end items-center mx-3">
        <button v-if="importAction" @click="modalOpen = true" class="btn btn-default btn-primary" dusk="import-button">
            <svg width="14" height="14" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24">
                <path fill="var(--white)" d="M13 5.41V17a1 1 0 0 1-2 0V5.41l-3.3 3.3a1 1 0 0 1-1.4-1.42l5-5a1 1 0 0 1 1.4 0l5 5a1 1 0 1 1-1.4 1.42L13 5.4zM3 17a1 1 0 0 1 2 0v3h14v-3a1 1 0 0 1 2 0v3a2 2 0 0 1-2 2H5a2 2 0 0 1-2-2v-3z"></path>
            </svg>
            <span>{{ importAction.name }}</span>
        </button>


        <!-- Action Confirmation Modal -->
        <!-- <portal to="modals"> -->
        <transition name="fade">
            <component
                :is="importAction.component"
                :working="working"
                v-if="modalOpen"
                selected-resources="all"
                :resource-name="resourceName"
                :action="importAction"
                :errors="errors"
                @confirm="executeAction"
                @close="modalOpen = false"
            />
        </transition>
        <!-- </portal> -->
    </div>
</template>
<script>
    import {Errors, InteractsWithResourceInformation} from 'laravel-nova';

    export default {
        mixins: [InteractsWithResourceInformation],

        props: {
            resourceName: String,
        },

        data() {
            return {
                working: false,
                modalOpen: false,
                importAction: null,
                errors: new Errors(),
            };
        },

        async created() {
            await this.getActions();
        },

        methods: {
            async getActions() {
                let {data: {actions}} = await window.Nova.request().get(`/nova-api/${this.resourceName}/actions`);
                this.importAction = actions.filter(action => {
                    return action.uriKey === 'import-excel';
                }).shift();
            },

            async executeAction() {
                this.working = true;

                try {
                    let {data} = await window.Nova.request({
                        method: 'post',
                        url: `/nova-vendor/maatwebsite/laravel-nova-excel/${this.resourceName}/upload`,
                        data: this.form(),
                        headers: {'Content-Type': 'multipart/form-data'}
                    });

                    this.$router.push({name: 'excel-imports-preview', params: {upload: data.upload, meta: data.meta}});
                } catch (error) {
                    if (error.response.status === 422) {
                        this.errors = new Errors(error.response.data.errors)
                    } else {
                        this.$toasted.show(data.message, {type: "error"});
                    }
                }

                this.working = false;
            },

            form() {
                let form = new FormData();

                this.importAction.fields.forEach(field => {
                    field.fill(form);
                });

                return form;
            }
        },
    }
</script>
