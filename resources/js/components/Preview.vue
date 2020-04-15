<template>
    <div>
        <heading class="mb-6">Import</heading>

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

            <table class="table w-full">
                <thead>
                <tr>
                    <th v-for="heading in headings">{{ heading }}</th>
                </tr>
                </thead>
                <tbody>
                <tr>
                    <td v-for="(heading, headingIndex) in headings" :key="headingIndex" class="text-center">
                        <select class="w-full form-control form-select" v-model="mapping[headingIndex]">
                            <option value="">- {{ __('Ignore this column') }} -</option>
                            <option v-for="field in fields" :key="field.attribute" :value="field.attribute">{{ field.name }}</option>
                        </select>
                    </td>
                </tr>
                <tr v-for="row in rows">
                    <td v-for="(col, index) in row" :key="index">{{ col }}</td>
                </tr>
                </tbody>
            </table>

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
            'meta'
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

            this.headings.map((value, key) => {
                this.mapping[key] = '';
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
                        meta: this.meta
                    });

                    this.$toasted.show('All data imported!', {type: "success"});
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
