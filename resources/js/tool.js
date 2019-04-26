import Upload from "./components/Upload";
import Preview from "./components/Preview";
import ImportToolbar from "./components/ImportToolbar";
import ImportRetryButton from "./components/ImportRetryButton";

Nova.booting((Vue, router) => {

    // TODO: only load on /resources/imports
    Vue.component('custom-index-toolbar', ImportToolbar);
    Vue.component('index-import-retry-button', ImportRetryButton);

    router.addRoutes([
        {
            name: 'excel-imports-upload',
            path: '/imports/upload',
            component: Upload,
        },
        {
            name: 'excel-imports-preview',
            path: '/imports/:import/preview',
            component: Preview,
            props: route => {
                return {
                    import: route.params.import,
                }
            },
        },
    ])
});