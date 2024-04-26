export const config = {
    // Настройки всплывающего сообщения
    toast: {
        // Время отображения всплывающего сообщения (мс)
        timeLife: 5000,
    }
}

export const urls = {
    home: '/',

    // пользователи
    users: {
        index: () => `/users`,
        organizations: {
            index: () => `/users/organizations`,
            change: (id) => `/users/organizations/${id}`,
        }
    },    

    // принтеры
    printers: {
        base: `/printers/workplace`,
        
        index() { return this.base },
        create() { return `${this.base}/create` },
        store() { return this.index() },
        show: (id) => `/printers/workplace/${id}`,
        edit: (id) => `/printers/workplace/${id}/edit`,
        update(id) { return this.show(id) },
        delete(id) { return this.show(id) },
        list (idConsumable) { return `${this.base}/list/${idConsumable}` },
        all() { return `${this.base}/all` },
    },

    // расходные материалы
    consumables: {        
        counts: {
            base: `/consumables/counts`,
            
            index() { return this.base },            
            create: () => `/consumables/counts/create`,
            store () { return this.base },
            show: (id) => `/consumables/counts/${id}`,
            add: (id) => `/consumables/counts/${id}/add`,
            subtract: (consumable, count) => `/consumables/${consumable}/counts/${count}/installed`,
            validate: () => `/consumables/counts/validate`,
            checkExists: () => `/consumables/counts/check-exists`,
            update(id) { return this.show(id) },
            updateOrganizations(id) { return `${this.base}/${id}/update-organizations` },

            installed() { return `${this.base}/installed/last` },
            installMaster() { return `${this.base}/installed/master` },

            listByPrinter(idPrinter) { return `${this.base}/list-by-printer/${idPrinter}` },

            // 
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

    // справочник
    dictionary: {
        prefix: 'dictionary',

        // справочник принтеров
        printers: {
            index: () => `/dictionary/printers`,
            create: () => `/dictionary/printers/create`,
            show: (id) => `/dictionary/printers/${id}`,
            edit: (id) =>  `/dictionary/printers/${id}/edit`,
            update: (id) => `/dictionary/printers/${id}`,
            delete: (id) => `/dictionary/printers/${id}`,
            // привязанные расходные материалы к принтеру
            consumables: {
                index: (id) => `/dictionary/printers/${id}/consumables`,
                add: (idPrinter, idConsumable) => `/dictionary/printers/${idPrinter}/consumables/${idConsumable}/add`,
                delete: (idPrinter, idConsumable) => `/dictionary/printers/${idPrinter}/consumables/${idConsumable}`,
            },
        },

        // справочник расходных материалов
        consumables: {
            index: () => `/dictionary/consumables`,
            store() { return this.index() },
            create: () => `/dictionary/consumables/create`,
            show: (id) => `/dictionary/consumables/${id}`,
            edit: (id) => `/dictionary/consumables/${id}/edit`,           
            update(id) { return this.show(id) },
            delete(id) { return this.show(id) },
            // привязанные принтеры к расходному материалу
            printers: {
                index: (id) => `/dictionary/consumables/${id}/printers`,
                add: (idConsumable, idPrinter) => `/dictionary/consumables/${idConsumable}/printers/${idPrinter}/add`,
                delete: (idConsumable, idPrinter) => `/dictionary/consumables/${idConsumable}/printers/${idPrinter}`,
            },
        },

        // справочник организаций
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