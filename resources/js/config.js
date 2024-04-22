// export const ConfigUrl = {

import { prefix } from "@fortawesome/free-brands-svg-icons"

    
//     consumables: {
//         edit: `/printers/{printer-id}/consumables/{consumable-id}/edit`,
//     },
//     move: {
//         add: `/printers/{printer-id}/consumables-move/{consumable-id}/add`,
//         take: `/printers/{printer-id}/consumables-move/{consumable-id}/take`,
//         moveToLocal: `/printers/{printer-id}/consumables-move/{consumable-id}/move-to-local`,
//         history: `/printers/{printer-id}/consumables-move/{consumable-id}/history`,
//     }
// }

// export const urlDictionary = {
//     printers: {
//         index: `/dictionary/printers`,
//         create: `/dictionary/printers/create`,
//         edit: `/dictionary/printers/{id}/edit`,
//         update: `/dictionary/printers/{id}`,
//     }
// }

export const urls = {
    home: '/',
    users: {
        index: () => `/users`,
        organizations: {
            index: () => `/users/organizations`,
            change: (id) => `/users/organizations/${id}`,
        }
    },
    // chart: {
    //     index: `/chart`,
    // },    
    printers: {
        prefix: `/printers/workplace`,

        // index: () => `/printers/workplace`,
        index() { return this.prefix },
        // create: () => `/printers/workplace/create`,
        create() { return `${this.prefix}/create` },
        store() { return this.index() },
        show: (id) => `/printers/workplace/${id}`,
        edit: (id) => `/printers/workplace/${id}/edit`,
        update(id) { return this.show(id) },
        delete(id) { return this.show(id) },
    },

    consumables: {
        counts: {
            index: () => `/consumables/counts`,
            create: () => `/consumables/counts/create`,
            save: () => `/consumables/counts/save`,
            show: (id) => `/consumables/counts/${id}`,
            add: (id) => `/consumables/counts/${id}/add`,
            validate: (id) => `/consumables/counts/validate/${id}`,
            checkExists: () => `/consumables/counts/check-exists`,
        },
    },    
    
    dictionary: {
        prefix: 'dictionary',
        printers: {
            index: () => `/dictionary/printers`,
            create: () => `/dictionary/printers/create`,
            show: (id) => `/dictionary/printers/${id}`,
            edit: (id) =>  `/dictionary/printers/${id}/edit`,
            update: (id) => `/dictionary/printers/${id}`,
            delete: (id) => `/dictionary/printers/${id}`,

            consumables: {
                index: (id) => `/dictionary/printers/${id}/consumables`,
                add: (idPrinter, idConsumable) => `/dictionary/printers/${idPrinter}/consumables/${idConsumable}/add`,
                delete: (idPrinter, idConsumable) => `/dictionary/printers/${idPrinter}/consumables/${idConsumable}`,
            },
        },
        consumables: {
            index: () => `/dictionary/consumables`,
            store() { return this.index() },
            create: () => `/dictionary/consumables/create`,
            show: (id) => `/dictionary/consumables/${id}`,
            edit: (id) => `/dictionary/consumables/${id}/edit`,           
            update(id) { return this.show(id) },
            delete(id) { return this.show(id) },

            printers: {
                index: (id) => `/dictionary/consumables/${id}/printers`,
                add: (idConsumable, idPrinter) => `/dictionary/consumables/${idConsumable}/printers/${idPrinter}/add`,
                delete: (idConsumable, idPrinter) => `/dictionary/consumables/${idConsumable}/printers/${idPrinter}`,
            },
        },
        organizations: {
            
            index: () => `/dictionary/organizations`,
            store() { return this.index() },
            create: () => `/dictionary/organizations/create`,
            show: (id) => `/dictionary/organizations/${id}`,
            edit: (id) => `/dictionary/organizations/${id}/edit`,           
            update(id) { return this.show(id) },
            delete(id) { return this.show(id) },
        },
    },
}