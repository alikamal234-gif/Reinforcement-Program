<?php
/**
 * EXERCICE 1 - Gestion d'une pharmacie
 *
 * Une pharmacie gere son stock de medicaments et les ordonnances patients.
 *
 * =====================================================================
 * Classe Medicament :
 *   Proprietes : id, nom, prixUnitaire, stockActuel, stockMinimum, surOrdonnance
 *
 * Methodes :
 *
 *   approvisionner(int $quantite) : void
 *     Augmenter le stock. Quantite doit etre > 0.
 *
 *   estEnRupture() : bool
 *     Retourner true si stockActuel == 0.
 *
 *   estEnStockCritique() : bool
 *     Retourner true si 0 < stockActuel <= stockMinimum.
 *
 *   toString() : string
 *     Format : "Doliprane 500mg | Prix: 12.50 DH | Stock: 45 unites"
 *
 * =====================================================================
 * Classe LignePrescription :
 *   Proprietes : medicament (Medicament), quantitePrescrite, posologie (string)
 *
 * =====================================================================
 * Classe Ordonnance :
 *   Proprietes : id, nomPatient, medecin, date, lignes (LignePrescription[]),
 *                dispensee (bool)
 *
 * Methodes :
 *
 *   ajouterMedicament(Medicament $med, int $quantite, string $posologie) : void
 *     Verifier que le medicament n'est pas deja dans l'ordonnance.
 *     Lever une LogicException si doublon.
 *
 *   calculerCout() : float
 *     Calculer le total brut des medicaments de l'ordonnance.
 *     Appliquer une prise en charge securite sociale : 70% du total.
 *     Retourner le montant restant a charge patient (30%).
 *
 *   dispenser(Pharmacie $pharmacie) : array
 *     Verifier pour chaque medicament :
 *       - Disponible en stock
 *       - Non en rupture
 *     Decremente les stocks si tout est OK.
 *     Retourner ['succes' => bool, 'message' => string, 'manquants' => array]
 *     Marquer l'ordonnance comme dispensee.
 *
 * =====================================================================
 * Classe Pharmacie :
 *   Proprietes : nom, medicaments (Medicament[])
 *
 * Methodes :
 *
 *   ajouterMedicament(Medicament $med) : void
 *
 *   rechercherMedicament(string $nom) : ?Medicament
 *     Recherche insensible a la casse. Retourner null si non trouve.
 *
 *   getMedicamentsEnRupture() : array
 *   getMedicamentsEnStockCritique() : array
 *
 *   valeurStockTotal() : float
 *     Retourner sum(prixUnitaire * stockActuel) pour tous les medicaments.
 */

// Votre implementation ci-dessous
class Medicament {
    private int $id ;
    private string $nom;

    private float $prixUnitaire;
    private int $stockActuel;
    private int $stockMinimum;
    private $surOrdonnance;
    public function __construct($nom,$prixUnitaire,
$stockActuel,
$stockMinimum,
$surOrdonnance ) {
        $this->nom = $nom;
        $this->prixUnitaire = $prixUnitaire;
        $this->stockActuel = $stockActuel;
        $this->stockMinimum = $stockMinimum;
        $this->surOrdonnance = $surOrdonnance;
    }

    public function approvisionner(int $quantite):void{
        if($quantite > 0){
            $this->stockActuel += $quantite;
        }
    }

    public function estEnRupture():bool{
        return $this->stockActuel == 0;
    }
    public function estEnStockCritique(){
        return 0 < $this->stockActuel && $this->stockActuel <= $this->stockMinimum;
    }

    public function __tostring(){
        return "$this->nom | Prix: $this->prixUnitaire DH | Stock: $this->stockActuel unites";
    }
    public function getPrixUnitaire(){
        return $this->prixUnitaire;
    }
    public function getStockActuel(){
        return $this->stockActuel;
    }
    public function setStockActuel($stockActuel){
        $this->stockActuel = $stockActuel;
    }
    public function getstockMinimum(){
        return $this->stockMinimum;
    }
    public function getNom(){
        return $this->nom;
    }

}

class LignePrescription{
    private Medicament $medicament;
    private $quantitePrescrite;
    private string $posologie;

    public function __construct($med , $quantitePrescrite , $posologie) {
        $this->medicament = $med;
        $this->quantitePrescrite = $quantitePrescrite;
        $this->posologie = $posologie;
    }

    public function getMedicament(){
        return $this->medicament;
    }

    public function getQuantitePrescrite(){
        return $this->quantitePrescrite;
    }
    public function getPosologie(){
        return $this->posologie;
    }
}

class Pharmacie{
    private array $medicaments = [];

    private string $nom;

    public function __construct($nom) {
        $this->nom = $nom;
    }
    public function ajouterMedicament(Medicament $med){
        foreach ($this->medicaments as $medicament) {
            if($medicament->getNom() == $med->getNom()){
                throw new Exception("medicament il exist deja ", 1);
            }
        }
        $this->medicaments[] = $med;
    }

    public function rechercherMedicament(string $nom) : ?Medicament{
        foreach($this->medicaments as $medicament){
            if(strcasecmp($medicament->getNom(),$nom) == 0){
                return $medicament;
            }
        }
        return null;
    }
    public function getMedicamentsEnRupture():array{
        $result = [];
        foreach($this->medicaments as $medicament){
            if($medicament->getStockActuel() == 0){
                $result[] = $medicament;
            }
        }
        return $result;
    }

    public function getMedicamentsEnStockCritique(): array{
        $result = [];
        foreach($this->medicaments as $medicament){
            if($medicament->getStockActuel() > 0 && $medicament->getStockActuel() <= $medicament->getstockMinimum()){
                $result[] = $medicament;
            }
        }
        return $result;
    }

    public function valeurStockTotal() : float{
        $results = 0;
        foreach($this->medicaments as $medicament){
            $results += $medicament->getPrixUnitaire() * $medicament->getStockActuel();
        }
        return $results;
    }

}

class Ordonnance{
    private $id;
    private $nomPatient;
    private $medecin;
    private $date;
    private array $lignes = [];
    private bool $dispensee = false;

    public function __construct(
$nomPatient,
$medecin,
) {
    $this->nomPatient = $nomPatient;
    $this->medecin = $medecin;
    }

    public function ajouterMedicament(Medicament $med, int $quantite, string $posologie){
        foreach($this->lignes as $line){
            if($line->getMedicament()->getNom() == $med->getNom()){
                return throw new Exception("this medicament is repleat", 1);
            }
        }
        $this->lignes[] = new LignePrescription($med, $quantite, $posologie);
    }

    public function calculerCout(){
        $total = 0;
        foreach ($this->lignes as $line){
            $total += ($line->getMedicament()->getPrixUnitaire() * $line->getQuantitePrescrite());
        }
        return $total * 0.3;
    }

    public function dispenser(Pharmacie $pharmacie) : array{
        $manquants = [];
        
        foreach($this->lignes as $line){
            if($line->getMedicament()->getStockActuel() == 0 || $line->getMedicament()->getStockActuel() < $line->getQuantitePrescrite()){
                $this->dispensee = false;
                $manquants[] = $line->getMedicament();
            }
        }
        if(count($manquants) > 0){
            return ['succes' => $this->dispensee,'message' => 'manque hhh', 'manquants' => $manquants];
        }else{
            $this->dispensee = true;
            foreach ($this->lignes as $line) {
                $line->getMedicament()->setStockActuel($line->getMedicament()->getStockActuel() - $line->getQuantitePrescrite());
            }
            return ['succes' => $this->dispensee,'message' => 'machi manque hhh', 'manquants' => $manquants];
        }
    }
}


// Tests attendus
$pharmacie = new Pharmacie("Pharmacie Centrale");

$paracetamol = new Medicament("Paracetamol 500mg", 12.50, 80, 15, false);
$amoxicilline = new Medicament("Amoxicilline 500mg", 35.00, 8, 20, true);
$ibuprofene = new Medicament("Ibuprofene 400mg", 18.00, 0, 10, false);
$metformine = new Medicament("Metformine 850mg", 28.00, 3, 15, true);

$pharmacie->ajouterMedicament($paracetamol);
$pharmacie->ajouterMedicament($amoxicilline);
$pharmacie->ajouterMedicament($ibuprofene);
$pharmacie->ajouterMedicament($metformine);

echo "=== Etat du stock ===" . PHP_EOL;
echo "Ruptures : " . count($pharmacie->getMedicamentsEnRupture()) . PHP_EOL;
echo "Critiques : " . count($pharmacie->getMedicamentsEnStockCritique()) . PHP_EOL;
echo "Valeur stock : " . $pharmacie->valeurStockTotal() . " DH" . PHP_EOL;

$ordonnance = new Ordonnance("Alami Hassan", "Dr. Benali");
$ordonnance->ajouterMedicament($paracetamol, 2, "1 comprime toutes les 8h");
$ordonnance->ajouterMedicament($amoxicilline, 1, "1 gelule 3 fois par jour");

echo PHP_EOL . "=== Ordonnance ===" . PHP_EOL;
echo "Reste a charge : " . $ordonnance->calculerCout() . " DH" . PHP_EOL;

$resultat = $ordonnance->dispenser($pharmacie);
echo "Dispensation : " . ($resultat['succes'] ? 'OK' : 'ECHEC') . PHP_EOL;
if (!$resultat['succes']) echo "Message : " . $resultat['message'] . PHP_EOL;
