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
                    Match up the headings from the CSV to the appropriate fields of the resource.
                </p>
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
            }
        },
        mounted() {
            window.Nova.request()
                .get(`/nova-vendor/maatwebsite/laravel-nova-excel/uploads/${this.upload}/preview`)
                .then(({data}) => {
                    this.rows = data.rows;
                    this.headings = data.headings;
                    this.rowCount = data.totalRows;
                    this.columnCount = data.headings.length;
                });
        }
    }
</script>