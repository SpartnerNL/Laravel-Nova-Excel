<template>
    <div>
        <heading class="mb-6">{{ action.name }}</heading>

        <card class="flex flex-col">
            <div class="p-8">
                <h2 class="pb-4">Preview</h2>
                <p class="pb-4">
                    We were able to discover <b>{{ columnCount }}</b> column(s) and <b>{{ rowCount }}</b>
                    row(s) in your data.
                </p>
                <p class="pb-4">
                    Match up the headings from the file to the appropriate fields of the resource.
                </p>

                <div v-if="errorMessage" class="bg-danger-light border border-danger-dark text-danger-dark px-4 py-3 rounded relative" role="alert">
                    <div class="font-bold mb-4">{{ errorMessage }}</div>
                    <ul class="pl-6" v-if="errors.length > 0">
                        <li v-for="error in errors" :key="error.row">
                            {{ __('Error on row :row.', {row: error.row})}}&nbsp;{{ error.message }}
                        </li>
                    </ul>
                </div>
            </div>

            <div class="bg-30 flex px-8 py-4">
                <button class="btn btn-link mr-4">{{ __('Cancel')}}</button>
                <button class="btn btn-default btn-primary" @click="importRows" :disabled="importing" id="run-import">
                    {{ __('Import') }}
                </button>
            </div>

            <div style="overflow-x: scroll" class="Flipped">
                <table class="table w-full Content">
                    <thead>
                        <tr>
                            <th v-for="heading in headings">{{ heading }}</th>
                        </tr>
                        <tr>
                            <th v-for="(heading, headingIndex) in headings" :key="headingIndex" class="text-center">
                                <select class="w-full form-control form-select" v-model="mapping[headingIndex]">
                                    <option value="">- {{ __('Ignore this column') }} -</option>
                                    <option v-for="field in fields" :key="field.attribute" :value="field.attribute">{{ field.name }}</option>
                                </select>
                            </th>
                        </tr>
                    </thead>
                    <tbody>
                        <tr v-for="row in rows">
                            <td v-for="(col, index) in row" :key="index">{{ col }}</td>
                        </tr>
                    </tbody>
                    <tfoot v-if="!action || action.uriKey === 're-import-excel'">
                        <tr>
                            <td v-for="(heading, headingIndex) in headings" :key="headingIndex" class="text-center">
                                <input type="checkbox" v-model.number="matchOn" :value="headingIndex"></input>
                            </td>
                        </tr>
                    </tfoot>
                </table>
            </div>

            <div class="bg-30 flex px-8 py-4">
                <button class="btn btn-link mr-4">{{ __('Cancel')}}</button>
                <button class="btn btn-default btn-primary" @click="importRows" :disabled="importing" id="run-import">
                    {{ __('Import') }}
                </button>
            </div>
        </card>
    </div>
</template>

<script>
    export default {
        props: [
            'upload',
            'meta',
            'action',
            'resourceName',
            'resourceId'
        ],
        data() {
            return {
                columnCount: 0,
                rowCount: 0,
                headings: [],
                rows: [],
                fields: [],
                mapping: {},
                importing: false,
                errors: [],
                errorMessage: null,
                matchOn: []
            }
        },
        async mounted() {
            let {data: {rows, headings, totalRows, fields}} = await window.Nova.request()
                .get(`/nova-vendor/maatwebsite/laravel-nova-excel/uploads/${this.upload}/preview`);

            this.rows = rows;
            this.fields = fields;
            this.headings = headings;
            this.rowCount = totalRows;
            this.columnCount = rows[0].length;

            const keyMapping = this.fields.reduce((carry, field) => {
                carry[field.attribute] = field;
                return carry;
            }, {});

            console.log('Setting up mapping');
            this.headings.map((value, key) => {
                // Automatically map if it exists
                if (keyMapping.hasOwnProperty(value)) {
                    console.log('Found corresponding column', {
                        key,
                        value,
                        attribute: keyMapping[value].attribute
                    });
                    this.mapping[key] = keyMapping[value].attribute;
                }
                else this.mapping[key] = '';
            })
        },
        methods: {
            async importRows() {
                this.importing = true;
                this.errors = [];
                this.errorMessage = null;



                try {
                    await window.Nova.request().post(`/nova-vendor/maatwebsite/laravel-nova-excel/uploads/${this.upload}/import`, {
                        mapping: this.mapping,
                        meta: this.meta,
                        resourceName: this.resourceName,
                        resourceId: this.resourceId,
                        action: this.action,
                        matchOn: this.matchOn.map(x => this.mapping[x]).filter(Boolean)
                    });

                    this.$toasted.show('All data imported!', {type: "success"});
                    if (this.resourceName && !this.resourceId) this.$router.push({
                        name: 'index',
                        params: {
                            resourceName: this.resourceName
                        }
                    });
                    else if (this.resourceName && this.resourceId) this.$router.push({
                        name: 'detail',
                        params: {
                            resourceName: this.resourceName,
                            resourceId: this.resourceId
                        }
                    });
                    this.importing = false;
                } catch ({response: {data: {errors, message}}}) {
                    this.importing = false;
                    this.$toasted.show(message, {type: "error"});
                    this.errorMessage = message;
                    this.errors = typeof errors !== 'undefined' ? errors : [];
                }
            }
        },
    }
</script>

<style>
.Flipped, .Flipped .Content
{
    transform:rotateX(180deg);
    -ms-transform:rotateX(180deg); /* IE 9 */
    -webkit-transform:rotateX(180deg); /* Safari and Chrome */
}
</style>
