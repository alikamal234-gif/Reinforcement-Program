/**
 * EXERCICE 2 - Analyse d'un catalogue produits
 *
 * Contexte :
 * Un magasin vous confie son catalogue sous forme de tableau d'objets.
 * Vous devez produire plusieurs rapports pour le responsable des stocks.
 *
 * Travail demande :
 *
 * 1. produitsEnRuptureOuCritique(produits)
 *    Retourner les produits dont le stock <= 5. Trier par stock croissant.
 *
 * 2. valeurTotaleParCategorie(produits)
 *    Retourner un objet { categorie: valeurTotaleStock } ou
 *    valeurTotaleStock = sum(stock * prix) pour cette categorie.
 *
 * 3. produitLePlusCherParCategorie(produits)
 *    Retourner un objet { categorie: produitLePlusCher } (l'objet produit complet).
 *
 * 4. appliquerRemise(produits, categorie, pourcentage)
 *    Retourner un nouveau tableau avec le prix reduit pour la categorie donnee.
 *    Arrondir a 2 decimales. Ne pas muter le tableau original.
 */

const produits = [
  { id: 1, nom: 'Laptop Pro',      prix: 8500, stock: 12, categorie: 'Informatique' },
  { id: 2, nom: 'Souris sans fil', prix: 150,  stock: 3,  categorie: 'Informatique' },
  { id: 3, nom: 'Clavier mecanique',prix: 420, stock: 8,  categorie: 'Informatique' },
  { id: 4, nom: 'Bureau debout',   prix: 2200, stock: 5,  categorie: 'Mobilier'     },
  { id: 5, nom: 'Chaise ergonomique',prix:1800,stock: 2,  categorie: 'Mobilier'     },
  { id: 6, nom: 'Lampe LED',       prix: 180,  stock: 20, categorie: 'Mobilier'     },
  { id: 7, nom: 'Tapis de souris', prix: 80,   stock: 0,  categorie: 'Accessoires'  },
  { id: 8, nom: 'Support laptop',  prix: 350,  stock: 7,  categorie: 'Accessoires'  },
  { id: 9, nom: 'Webcam HD',       prix: 550,  stock: 4,  categorie: 'Informatique' },
];

function produitsEnRuptureOuCritique(produits) {
  // TODO
  const product = produits.filter(produit => produit.stock <= 5)
  return product.sort((a,b) => a-b)
}

function valeurTotaleParCategorie(produits) {
  // TODO
  const categories = []
  produits.forEach(produit => {
        const categorie = produit.categorie

        if(!categories[categorie]){
            categories[categorie] = ({
                ...produit,
                valeurTotaleStock : 0
            })
        }

        categories[categorie].valeurTotaleStock += (produit.prix * produit.stock)
  });
  return categories
}

function produitLePlusCherParCategorie(produits) {
  // TODO
  const max = produits[0]
  produits.forEach(produit => {
    if(produit.prix > max.prix){
        max = produit
    }
  })
  return max
}

function appliquerRemise(produits, categorie, pourcentage) {
  // TODO
  const newProduits = produits.filter(produit => produit.categorie == categorie)
  return newProduits.map(produit => {
    return ({
        ...produit,
        prix : produit.prix - (produit.prix * pourcentage / 100)
    })
  })
}

// Tests
console.log("=====================================")
console.log(produitsEnRuptureOuCritique(produits));
console.log("=====================================")
console.log(valeurTotaleParCategorie(produits));
console.log("=====================================")
console.log(produitLePlusCherParCategorie(produits));
console.log("=====================================")
console.log(appliquerRemise(produits, 'Informatique', 10));