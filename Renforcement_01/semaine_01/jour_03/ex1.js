/**
 * EXERCICE 1 - Gestionnaire d'inventaire d'entrepot
 *
 * Contexte :
 * Un entrepot est organise en zones, chaque zone contient des rayons,
 * chaque rayon contient des produits. Vous devez implementer des fonctions
 * de navigation et de manipulation de cette structure.
 *
 * Travail demande :
 *
 * 1. trouverProduit(entrepot, idProduit)
 *    Retourner { produit, zone, rayon } ou null si introuvable.
 *
 * 2. produitsStockCritique(entrepot, seuilMinimum)
 *    Retourner la liste de tous les produits (toutes zones confondues)
 *    dont le stock <= seuilMinimum. Ajouter les champs `zone` et `rayon`.
 *
 * 3. valeurTotaleEntrepot(entrepot)
 *    Retourner la somme totale : sum(stock * prixUnitaire) pour tous les produits.
 *
 * 4. deplacerProduit(entrepot, idProduit, nouvelleZone, nouveauRayon)
 *    Retourner un NOUVEL entrepot (sans muter l'original) ou le produit
 *    a ete retire de sa position actuelle et ajoute a la nouvelle position.
 *    Si la zone ou le rayon n'existent pas, les creer.
 *
 * 5. rapportParZone(entrepot)
 *    Retourner [{ zone, nombreProduits, nombreReferences, valeurTotale }]
 *    nombreReferences = nombre de produits distincts
 *    nombreProduits = somme des stocks
 */

const entrepot = {
    'Zone-A': {
        'Rayon-A1': [
            { id: 'P001', nom: 'Clavier', stock: 45, prixUnitaire: 120 },
            { id: 'P002', nom: 'Souris', stock: 3, prixUnitaire: 85 },
            { id: 'P003', nom: 'Webcam', stock: 12, prixUnitaire: 220 },
        ],
        'Rayon-A2': [
            { id: 'P004', nom: 'Ecran 24"', stock: 8, prixUnitaire: 1500 },
            { id: 'P005', nom: 'Ecran 27"', stock: 2, prixUnitaire: 2200 },
        ]
    },
    'Zone-B': {
        'Rayon-B1': [
            { id: 'P006', nom: 'Cable HDMI', stock: 100, prixUnitaire: 30 },
            { id: 'P007', nom: 'Hub USB', stock: 25, prixUnitaire: 95 },
        ],
        'Rayon-B2': [
            { id: 'P008', nom: 'Casque BT', stock: 4, prixUnitaire: 350 },
            { id: 'P009', nom: 'Enceinte', stock: 0, prixUnitaire: 280 },
        ]
    },
    'Zone-C': {
        'Rayon-C1': [
            { id: 'P010', nom: 'Tapis souris', stock: 60, prixUnitaire: 40 },
            { id: 'P011', nom: 'Repose-poignet', stock: 15, prixUnitaire: 55 },
        ]
    }
};

function trouverProduit(entrepot, idProduit) {
    for (let zone in entrepot) {
        for (let rayon in entrepot[zone]) {
            let produits = entrepot[zone][rayon]
            let p = produits.find(e => e.id === idProduit)
            if (p) return { produit: p, zone, rayon }
        }
    }
    return null
}

function produitsStockCritique(entrepot, seuilMinimum) {
    let res = []

    for (let zone in entrepot) {
        for (let rayon in entrepot[zone]) {
            let produits = entrepot[zone][rayon]
            produits
                .filter(p => p.stock <= seuilMinimum)
                .forEach(p => res.push({ ...p, zone, rayon }))
        }
    }

    return res
}

function valeurTotaleEntrepot(entrepot) {
    let total = 0

    for (let zone in entrepot) {
        for (let rayon in entrepot[zone]) {
            entrepot[zone][rayon].forEach(p => {
                total += p.stock * p.prixUnitaire
            })
        }
    }

    return total
}

function deplacerProduit(entrepot, idProduit, nouvelleZone, nouveauRayon) {
    let copy = JSON.parse(JSON.stringify(entrepot))
    let produit = null

    for (let zone in copy) {
        for (let rayon in copy[zone]) {
            let arr = copy[zone][rayon]
            let i = arr.findIndex(p => p.id === idProduit)
            if (i !== -1) produit = arr.splice(i, 1)[0]
        }
    }

    if (!copy[nouvelleZone]) copy[nouvelleZone] = {}
    if (!copy[nouvelleZone][nouveauRayon]) copy[nouvelleZone][nouveauRayon] = []

    if (produit) copy[nouvelleZone][nouveauRayon].push(produit)

    return copy
}

function rapportParZone(entrepot) {
    let res = []

    for (let zone in entrepot) {
        let totalStock = 0
        let refs = 0
        let totalValeur = 0

        for (let rayon in entrepot[zone]) {
            entrepot[zone][rayon].forEach(p => {
                totalStock += p.stock
                refs++
                totalValeur += p.stock * p.prixUnitaire
            })
        }

        res.push({
            zone,
            nombreProduits: totalStock,
            nombreReferences: refs,
            valeurTotale: totalValeur
        })
    }

    return res
}

// Tests
console.log(trouverProduit(entrepot, 'P008'));
console.log(produitsStockCritique(entrepot, 5));
console.log('Valeur totale:', valeurTotaleEntrepot(entrepot));
console.log(rapportParZone(entrepot));