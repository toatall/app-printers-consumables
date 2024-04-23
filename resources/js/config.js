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
            base: `/consumables/counts`,

            // index: () => `/consumables/counts`,
            index() { return this.base },            
            create: () => `/consumables/counts/create`,
            store () { return this.base },
            show: (id) => `/consumables/counts/${id}`,
            add: (id) => `/consumables/counts/${id}/add`,
            validate: (id) => `/consumables/counts/validate`,
            checkExists: () => `/consumables/counts/check-exists`,
            update(id) { return this.show(id) },
            updateOrganizations(id) { return `${this.base}/${id}/update-organizations` },

            journal: {
                added: {
                    index: (idConsumable, idConsumableCount) => `/consumables/${idConsumable}/counts/${idConsumableCount}/added`,
                    redo: (idConsumable, idConsumableCount, id) => `/consumables/${idConsumable}/counts/${idConsumableCount}/added/${id}`,
                },
                installed: {
                    index: (idConsumable, idConsumableCount) => `/consumables/${idConsumable}/counts/${idConsumableCount}/installed`,
                    redo: (idConsumable, idConsumableCount, id) => `/consumables/${idConsumable}/counts/${idConsumableCount}/installed/${id}`,
                },
            },            
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