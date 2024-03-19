import { createApp, h } from 'vue'
import { InertiaProgress } from '@inertiajs/progress'
import { createInertiaApp } from '@inertiajs/inertia-vue3'

/* import the fontawesome core */
import { dom, library } from '@fortawesome/fontawesome-svg-core'

/* import font awesome icon component */
import { FontAwesomeIcon } from '@fortawesome/vue-fontawesome'

/* import specific icons */
import { fas } from '@fortawesome/free-solid-svg-icons'
import { fab } from '@fortawesome/free-brands-svg-icons'
import { far } from '@fortawesome/free-regular-svg-icons'
import CircleIcon from 'vue-material-design-icons/Circle'


import Lara from '@/presets/lara'
import PrimeVue from 'primevue/config'
import DialogService from 'primevue/dialogservice'
import DynamicDialog from 'primevue/dynamicdialog'
import ToastService from 'primevue/toastservice'
import Tooltip from 'primevue/tooltip'
import 'primeicons/primeicons.css'
import moment from 'moment/moment'


/* add icons to the library */
library.add(fas, far, fab)
dom.watch()

InertiaProgress.init()

createInertiaApp({
    resolve: name => require(`./Pages/${name}`),
    title: title => title,// ? `${title} - Система учета картриджей` : 'Система учета картриджей',
    setup({ el, App, props, plugin }) {
        createApp({ render: () => h(App, props), moment })
            .component('font-awesome-icon', FontAwesomeIcon)
            .component('circle-icon', CircleIcon)            
            .use(plugin)            
            .use(PrimeVue, {
                unstyled: true,
                pt: Lara,
                ripple: true,
            })
            .use(ToastService)
            .use(DialogService)
            .component('DynamicDialog', DynamicDialog)       
            .directive('tooltip', Tooltip)
            .mount(el)
    },
})