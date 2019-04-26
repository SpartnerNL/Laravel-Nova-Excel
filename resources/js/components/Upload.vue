<template>
    <div>
        <heading class="mb-6">{{ __('New Import') }}</heading>

        <card class="flex flex-col p-6 justify-center" style="min-height: 300px">

            <div class="mb-4">
                <h2 class="py-4">Resource</h2>
                <p class="pb-4">Choose which resource to import your data into:</p>
                <div>
                    <select name="resource" class="block form-control form-select" v-model="resource">
                        <option value="">- Select a resource -</option>
                        <option v-for="(label, index) in resources" :value="index">{{ label }}</option>
                    </select>
                </div>
            </div>

            <div class="mb-6">
                <h2 class="py-4">File</h2>
                <p class="pb-4">Choose the file you want to import:</p>
                <input type="file" name="file" @change="handleFile">
            </div>

            <button type="submit" class="btn btn-default btn-primary" @click="upload">{{ __('Import') }}</button>
        </card>
    </div>
</template>

<script>
    export default {
        mounted() {

        },
        data() {
            return {
                form: new FormData(),
            };
        },
        methods: {
            handleFile(e) {
                this.form.append('file', e.target.files[0]);
            },
            upload() {
                window.Nova.request()
                    .post('/nova-vendor/maatwebsite/laravel-nova-excel/upload',
                        this.form,
                        {headers: {'Content-Type': 'multipart/form-data'}}
                    )
                    .then(({data}) => {
                        this.$router.push({name: 'excel-imports-preview', params: {import: data.import}})
                    })
                    .catch(({response: {data}}) => {
                        this.$toasted.show(data.message, {type: "error"});
                    });
            }
        }
    }
</script>