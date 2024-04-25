import { createApp, h } from 'vue';
import { InertiaProgress } from '@inertiajs/progress';
import { createInertiaApp } from '@inertiajs/inertia-vue3';

/* import the fontawesome core */
import { dom, library } from '@fortawesome/fontawesome-svg-core';

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome';

/* import specific icons */
import { fas } from '@fortawesome/free-solid-svg-icons';
import { fab } from '@fortawesome/free-brands-svg-icons';
import { far } from '@fortawesome/free-regular-svg-icons';
import CircleIcon from 'vue-material-design-icons/Circle';

import Lara from '@/presets/lara';
import PrimeVue from 'primevue/config';
import DialogService from 'primevue/dialogservice';
import DynamicDialog from 'primevue/dynamicdialog';
import ToastService from 'primevue/toastservice';
import ConfirmationService from 'primevue/confirmationservice';
import ConfirmDialog from 'primevue/confirmdialog';
import Tooltip from 'primevue/tooltip';
import 'primeicons/primeicons.css';
import moment from 'moment/moment';
import { urls, config } from '@/config';

moment.locale('ru');

/* add icons to the library */
library.add(fas, far, fab)
dom.watch()

InertiaProgress.init()

createInertiaApp({
    resolve: name => require(`./Pages/${name}`),
    title: title => title,// ? `${title} - Система учета картриджей` : 'Система учета картриджей',
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props) })
            .component('font-awesome-icon', FontAwesomeIcon)
            .component('circle-icon', CircleIcon)            
            .use(plugin)            
            .use(PrimeVue, {
                unstyled: true,
                pt: Lara,
                ripple: true,
                locale: {
                    accept: 'OK',
                    reject: 'Отмена',
                },
            })
            .use(ToastService)
            .use(DialogService)   
            .use(ConfirmationService)
            .component('DynamicDialog', DynamicDialog)       
            .component('ConfirmDialog', ConfirmDialog)       
            .directive('tooltip', Tooltip)
            .provide('moment', moment)            
            .provide('urls', urls) 
            .provide('config', config)      
            .mount(el)
    },
})
