<template>
    <div>
        <heading class="mb-6">Import</heading>

        <card class="flex flex-col">
            <div class="p-8">
                <h2 class="pb-4">Preview</h2>
                <p class="pb-4">
                    We were able to discover <b>0</b> column(s) and <b>0</b>
                    row(s) in your data.
                </p>
                <p class="pb-4">
                    Choose a resource to import them into and match up the headings from the CSV to the
                    appropriate fields of the resource.
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
    }
</script>