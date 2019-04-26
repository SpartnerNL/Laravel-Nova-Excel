<template>
    <div>
        <span>
            <a class="cursor-pointer text-70 hover:text-primary" data-testid="imports-items-0-view-button" dusk="2001-view-button" title="View">
                <svg class="fill-current" xmlns="http://www.w3.org/2000/svg" viewBox="0 0 24 24" width="22" height="22"><path class="heroicon-ui" d="M6 18.7V21a1 1 0 0 1-2 0v-5a1 1 0 0 1 1-1h5a1 1 0 1 1 0 2H7.1A7 7 0 0 0 19 12a1 1 0 1 1 2 0 9 9 0 0 1-15 6.7zM18 5.3V3a1 1 0 0 1 2 0v5a1 1 0 0 1-1 1h-5a1 1 0 0 1 0-2h2.9A7 7 0 0 0 5 12a1 1 0 1 1-2 0 9 9 0 0 1 15-6.7z"/></svg>
            </a>
        </span>
    </div>
</template>
<script>
    export default {
        mounted() {
            this.$el.parentElement.classList.add('td-fit');
            this.$el.parentElement.classList.add('text-right');
        }
    }
</script>