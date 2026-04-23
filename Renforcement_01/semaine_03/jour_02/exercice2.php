<?php
/**
 * EXERCICE 2 - Gestion d'une flotte de vehicules
 *
 * Une entreprise gere sa flotte et les missions de ses chauffeurs.
 *
 * =====================================================================
 * Classe Vehicule :
 *   Proprietes : id, immatriculation, marque, modele, kilometrage,
 *                disponible, dernierControle (date), tarifJourHT
 *
 */
    class Vehicule {
        public int $id;
        public string $immatriculation;
        public $marque;
        public $modele;
        public $kilometrage;
        public  $disponible;
        public $dernierControle;
        public $tarifJourHT;

        public function __construct(
            $immatriculation,
            $marque,
            $modele,
            $kilometrage,
            $dernierControle,
            $disponible,
        ) {
            $this->immatriculation = $immatriculation;
            $this->marque = $marque;
            $this->modele = $modele;
            $this->kilometrage = $kilometrage;
            $this->disponible = $disponible;
            $this->dernierControle = $dernierControle;
        }

        public function effectuerEntretient(int $kmActuels, string $dateControle) : void{
            if($this->kilometrage > $kmActuels){
            $this->kilometrage = $kmActuels;
            }
            $this->dernierControle = $dateControle;
        }
        public function necessite_Controle(){
            if((int)date('m',strtotime(date('Y-m-d') - $this->dernierControle)) > 6){
                return true;
            }
        }

        public function __tostring(){
        return "[$this->dernierControle] $this->modele | $this->kilometrage km | Disponible $this->disponible";
        }

    }
/** 
 * Methodes :
 *
 *   effectuerEntretien(int $kmActuels, string $dateControle) : void
 *     Mettre a jour le kilometrage si kmActuels > kilometrage actuel.
 *     Mettre a jour la date de dernier controle.
 *
 *   necessite_Controle() : bool
 *     Retourner true si plus de 180 jours depuis le dernier controle
 *     OU si (kmActuels - kmDernierControle) > 15000.
 *     Pour simplifier : retourner true si date controle > 6 mois.
 *
 *   toString() : string
 *     Format : "[AA-123-AA] Renault Clio | 45 200 km | Disponible"
 *
 * =====================================================================
 * Classe Mission :
 *   Proprietes : id, vehicule, chauffeur (string), depart, destination,
 *                dateDebut, dateFin (nullable), kmDebut, kmFin (nullable),
 *                statut ('planifiee'|'en_cours'|'terminee'|'annulee')
 */

class Mission {
    private int $id;
    private Vehicule $vehicle;
    private string $chauffeur;
    private $depart;
    private $destination;
    private string $dateDebut;
    private ?string $dateFin;
    private $kmDebut;
    private ?int $kmFin;
    public string $status;
    
    public function __construct($vehicle,$chauffeur,$depart,$destination,$dateDebut){
    $this->vehicle = $vehicle;
    $this->depart = $depart;
    $this->chauffeur = $chauffeur;
    $this->dateDebut = $dateDebut;
    $this->destination = $destination;
    $this->status = 'planifiee';
}
    
    public function demarrer(int $kmActuels): void{
        if($this->status == 'planifiee' && $this->vehicle->disponible){
            $this->status = 'en_cours';
            $this->vehicle->disponible = false;
        }
    }
    public function terminer(int $kmFinaux, string $dateFin){
        if($this->status == 'en_cours'){
            $this->kmFin = $kmFinaux;
            $this->dateFin = $dateFin;
            $this->status = 'terminee';
            $this->vehicle->disponible = true;
        }
    }
    public function calculerCout(){
        $debut = new DateTime($this->dateDebut);
        $fin = new DateTime($this->dateFin);
        $jours = $debut->diff($fin)->days;
        $jours = max(1, $jours);
        $prixjours = $jours * $this->vehicle->tarifJourHT;
        $km = $this->kmFin - $this->kmDebut;
        $supplement = 0;
        if($km > 300){
            $supplement = ($km - 300) * 2.5;
        }
        $totalHT = $prixjours + $supplement;
        return $totalHT * 1.20;
    }

    public function getVehicule(){
        return $this->vehicle;
    }
    public function getStatus(){
        return $this->status;
    }
    public function getKmFin(){
        return $this->kmFin;
    }
    public function getKmDebut(){
        return $this->kmDebut;
    }
}
/** 
 * Methodes :
 *
 *   demarrer(int $kmActuels) : void
 *     Verifier que la mission est 'planifiee'.
 *     Verifier que le vehicule est disponible.
 *     Passer le statut a 'en_cours', marquer vehicule indisponible.
 *
 *   terminer(int $kmFinaux, string $dateFin) : void
 *     Verifier que la mission est 'en_cours'.
 *     Mettre a jour kmFin, dateFin, statut.
 *     Liberer le vehicule (disponible = true).
 *     Mettre a jour le kilometrage du vehicule.
 *
 *   calculerCout() : float
 *     (dateFin - dateDebut en jours, minimum 1) * tarifJourHT du vehicule.
 *     Ajouter supplement kilometrique : si (kmFin - kmDebut) > 300 km,
 *     chaque km supplementaire coute 2.50 DH.
 *     Retourner le cout TTC (HT * 1.20).
 *
 * =====================================================================
 * Classe Flotte :
 *   Proprietes : nom (string), vehicules (Vehicule[]), missions (Mission[])
 */
class Flotte{
    public string $nom;
    public array $vehicule = [];
    public array $missions = [];
    public function __construct(string $nom) {
        $this->nom = $nom;
    }
    public function ajouterVehicule(Vehicule $v){
        foreach($this->vehicule as $vehicle){
            if($vehicle == $v){
                return throw new Exception("this vehicle is exist", 1);
            }
        }
        $this->vehicule[] = $v;
    }

    public function creerMission(
    string $vehiculeId,
    string $chauffeur,
    string $depart,
    string $destination,
    string $dateDebut
): Mission {

    $vehiculeTrouve = null;

    foreach ($this->vehicule as $vehicule) {
        if ($vehicule->immatriculation === $vehiculeId) {
            $vehiculeTrouve = $vehicule;
            break;
        }
    }

    if ($vehiculeTrouve === null) {
        throw new RuntimeException("Vehicule introuvable");
    }

    if (!$vehiculeTrouve->disponible) {
        throw new RuntimeException("Vehicule non disponible");
    }

    $mission = new Mission(
        $vehiculeTrouve,
        $chauffeur,
        $depart,
        $destination,
        $dateDebut
    );

    $this->missions[] = $mission;

    return $mission;
}

    public function vehiculesDisponibles(){
        $results = [];
        foreach ($this->vehicule as $vehicle) {
            if($vehicle->disponible){
                $results[] = $vehicle;
            }
        }
        return $results;
    }
    public function coutTotalMissions(){
        $count = 0;
        foreach ($this->missions as $mission) {
            if($mission->status == "terminee"){
                $count++;
            }
        }

        return $count;
    }

    public function rapportMissions(): array {
    $rapport = [];

    foreach ($this->vehicule as $vehicule) {

        $nbMissions = 0;
        $kmParcourus = 0;
        $coutTotal = 0;

        foreach ($this->missions as $mission) {

            if ($mission->getVehicule()->immatriculation === $vehicule->immatriculation
                && $mission->getStatus() === 'terminee') {

                $nbMissions++;

                $kmParcourus += ($mission->getKmFin() - $mission->getKmDebut());

                $coutTotal += $mission->calculerCout();
            }
        }

        $rapport[] = [
            'immatriculation' => $vehicule->immatriculation,
            'nbMissions' => $nbMissions,
            'kmParcourus' => $kmParcourus,
            'coutTotal' => $coutTotal
        ];
    }

    return $rapport;
}

}

/**
 * Methodes :
 *
 *   ajouterVehicule(Vehicule $v) : void
 *
 *   creerMission(string $vehiculeId, string $chauffeur, string $depart,
 *                string $destination, string $dateDebut) : Mission
 *     Verifier que le vehicule existe et est disponible.
 *     Lever RuntimeException sinon.
 *
 *   vehiculesDisponibles() : array
 *
 *   coutTotalMissions() : float
 *     Somme des couts de toutes les missions terminees.
 *
 *   rapportMissions() : array
 *     Pour chaque vehicule : { immatriculation, nbMissions, kmParcourus, coutTotal }
 */

// Votre implementation ci-dessous

// Tests
$flotte = new Flotte("Transport Express Maroc");

$v1 = new Vehicule("1-AB-2345", "Renault", "Clio", 45200, "2023-06-15", 350);
$v2 = new Vehicule("2-CD-6789", "Peugeot", "308",  32100, "2024-01-10", 420);
$v3 = new Vehicule("3-EF-1122", "Ford",    "Transit", 98000, "2022-08-20", 580);

$flotte->ajouterVehicule($v1);
$flotte->ajouterVehicule($v2);
$flotte->ajouterVehicule($v3);

echo "Vehicules disponibles : " . count($flotte->vehiculesDisponibles()) . PHP_EOL;

$m1 = $flotte->creerMission("1-AB-2345", "Rachid B.", "Casablanca", "Rabat", "2024-04-01");
$m1->demarrer(45200);
$m1->terminer(45285, "2024-04-01");
echo "Cout mission 1 : " . $m1->calculerCout() . " DH TTC" . PHP_EOL;

$m2 = $flotte->creerMission("3-EF-1122", "Khalid M.", "Casablanca", "Marrakech", "2024-04-02");
$m2->demarrer(98000);
$m2->terminer(98480, "2024-04-03");
echo "Cout mission 2 : " . $m2->calculerCout() . " DH TTC" . PHP_EOL;

echo "Cout total : " . $flotte->coutTotalMissions() . " DH" . PHP_EOL;
print_r($flotte->rapportMissions());






