/**
 * EXERCICE 3 - Systeme de tournoi et classement
 *
 * Contexte :
 * Vous developpez le module de gestion des resultats d'une ligue de football
 * regionale. Le systeme doit calculer automatiquement les classements selon
 * les regles officielles.
 *
 * Regles de calcul :
 * - Victoire : 3 points
 * - Match nul : 1 point
 * - Defaite : 0 points
 * - Egalite de points : departager par difference de buts, puis buts marques,
 *   puis confrontation directe, puis ordre alphabetique
 *
 * Travail demande :
 *
 * 1. calculerClassement(matchs)
 *    A partir du tableau de matchs joues, retourner le classement complet.
 *    Chaque entree du classement contient :
 *    { rang, equipe, joues, victoires, nuls, defaites, bpour, bcontre, diff, points }
 *
 * 2. meilleureAttaque(classement)
 *    Retourner l'equipe ayant marque le plus de buts (objet complet du classement).
 *
 * 3. meilleureDefense(classement)
 *    Retourner l'equipe ayant encaisse le moins de buts.
 *
 * 4. serieInvaincue(matchs, equipe)
 *    Retourner le nombre de matchs consecutifs sans defaite (en partant du match le plus recent).
 */

const matchs = [
    { journee: 1, domicile: 'FUS Rabat', bDomicile: 2, bExterieur: 1, exterieur: 'WAC' },
    { journee: 1, domicile: 'Raja', bDomicile: 1, bExterieur: 1, exterieur: 'MAS' },
    { journee: 1, domicile: 'FAR', bDomicile: 3, bExterieur: 0, exterieur: 'HUSA' },
    { journee: 2, domicile: 'WAC', bDomicile: 2, bExterieur: 2, exterieur: 'Raja' },
    { journee: 2, domicile: 'MAS', bDomicile: 1, bExterieur: 0, exterieur: 'FAR' },
    { journee: 2, domicile: 'HUSA', bDomicile: 1, bExterieur: 3, exterieur: 'FUS Rabat' },
    { journee: 3, domicile: 'Raja', bDomicile: 2, bExterieur: 0, exterieur: 'FAR' },
    { journee: 3, domicile: 'FUS Rabat', bDomicile: 1, bExterieur: 1, exterieur: 'MAS' },
    { journee: 3, domicile: 'WAC', bDomicile: 4, bExterieur: 1, exterieur: 'HUSA' },
    { journee: 4, domicile: 'FAR', bDomicile: 2, bExterieur: 2, exterieur: 'WAC' },
    { journee: 4, domicile: 'MAS', bDomicile: 0, bExterieur: 1, exterieur: 'FUS Rabat' },
    { journee: 4, domicile: 'HUSA', bDomicile: 2, bExterieur: 3, exterieur: 'Raja' },
];

function calculerClassement(matchs) {
    let equipes = {}

    matchs.forEach(match => {
        let eq1 = match.domicile
        let eq2 = match.exterieur

        if (!equipes[eq1]) {
            equipes[eq1] = {
                equipe: eq1, joues: 0, victoires: 0, nuls: 0, defaites: 0,
                bpour: 0, bcontre: 0, diff: 0, points: 0
            }
        }

        if (!equipes[eq2]) {
            equipes[eq2] = {
                equipe: eq2, joues: 0, victoires: 0, nuls: 0, defaites: 0,
                bpour: 0, bcontre: 0, diff: 0, points: 0
            }
        }

        equipes[eq1].joues++
        equipes[eq2].joues++

        equipes[eq1].bpour += match.bDomicile
        equipes[eq1].bcontre += match.bExterieur

        equipes[eq2].bpour += match.bExterieur
        equipes[eq2].bcontre += match.bDomicile

        if (match.bDomicile > match.bExterieur) {
            equipes[eq1].victoires++
            equipes[eq1].points += 3
            equipes[eq2].defaites++
        } else if (match.bDomicile < match.bExterieur) {
            equipes[eq2].victoires++
            equipes[eq2].points += 3
            equipes[eq1].defaites++
        } else {
            equipes[eq1].nuls++
            equipes[eq2].nuls++
            equipes[eq1].points += 1
            equipes[eq2].points += 1
        }
    })

    let classement = Object.values(equipes)
    classement.forEach(e => {
        e.diff = e.bpour - e.bcontre
    })

    classement.sort((a, b) => {
        if (b.points !== a.points) return b.points - a.points
        if (b.diff !== a.diff) return b.diff - a.diff
        if (b.bpour !== a.bpour) return b.bpour - a.bpour
        return a.equipe.localeCompare(b.equipe)
    })

    classement.forEach((e, i) => {
        e.rang = i + 1
    })

    return classement
}

function meilleureAttaque(classement) {
    let max = classement[0]

    classement.forEach(e => {
        if (e.bpour > max.bpour) {
            max = e
        }
    })

    return max
}

function meilleureDefense(classement) {
    let min = classement[0]

    classement.forEach(e => {
        if (e.bcontre < min.bcontre) {
            min = e
        }
    })

    return min
}

function serieInvaincue(matchs, equipe) {
    let count = 0

    for (let i = matchs.length - 1; i >= 0; i--) {
        let m = matchs[i]

        if (m.domicile === equipe) {
            if (m.bDomicile >= m.bExterieur) {
                count++
            } else {
                break
            }
        } else if (m.exterieur === equipe) {
            if (m.bExterieur >= m.bDomicile) {
                count++
            } else {
                break
            }
        }
    }

    return count
}

const classement = calculerClassement(matchs);
console.log('--- Classement ---');
classement.forEach(e => console.log(
    `${e.rang}. ${e.equipe.padEnd(12)} | J:${e.joues} V:${e.victoires} N:${e.nuls} D:${e.defaites} | ${e.bpour}:${e.bcontre} (${e.diff > 0 ? '+' : ''}${e.diff}) | ${e.points} pts`
));
console.log('Meilleure attaque:', meilleureAttaque(classement).equipe);
console.log('Meilleure defense:', meilleureDefense(classement).equipe);
console.log('Serie WAC:', serieInvaincue(matchs, 'WAC'));