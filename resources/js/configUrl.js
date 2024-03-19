export const ConfigUrl = {
    
    consumables: {
        edit: `/printers/{printer-id}/consumables/{consumable-id}/edit`,
    },
    move: {
        add: `/printers/{printer-id}/consumables-move/{consumable-id}/add`,
        take: `/printers/{printer-id}/consumables-move/{consumable-id}/take`,
        moveToLocal: `/printers/{printer-id}/consumables-move/{consumable-id}/move-to-local`,
        history: `/printers/{printer-id}/consumables-move/{consumable-id}/history`,
    }
}