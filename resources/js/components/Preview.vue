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
                <button class="btn btn-link mr-4">Cancel</button>
                <button class="btn btn-default btn-primary" @click="importRows" :disabled="importing" id="run-import">Import</button>
            </div>
        </card>
    </div>
</template>

<script>
    export default {
        props: [
            'upload'
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
            }
        },
        async mounted() {
            let {data: {rows, headings, totalRows, fields}} = await window.Nova.request()
                .get(`/nova-vendor/maatwebsite/laravel-nova-excel/uploads/${this.upload}/preview`);

            this.rows = rows;
            this.fields = fields;
            this.headings = headings;
            this.rowCount = totalRows;
            this.columnCount = headings.length;
        },
        methods: {
            async importRows() {
                this.importing = true;

                await window.Nova.request().post(`/nova-vendor/maatwebsite/laravel-nova-excel/uploads/${this.upload}/import`, {
                    mapping: this.mapping,
                });

                this.importing = false;
            }
        },
    }
</script>