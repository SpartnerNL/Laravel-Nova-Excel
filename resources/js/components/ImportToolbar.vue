<template>
    <div class="flex-no-shrink ml-auto">
        <router-link :to="{name: 'excel-imports-upload'}" class="btn btn-default btn-primary" dusk="create-button">
            {{ __('New Import') }}
        </router-link>
    </div>
</template>
<script>
    export default {}
</script>