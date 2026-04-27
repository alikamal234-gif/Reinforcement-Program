<?php

/**
 * EXERCICE 3 - Gestion d'une salle de sport (abonnements et seances)
 *
 * =====================================================================
 * Classe Abonnement :
 *   Proprietes : type ('basic'|'premium'|'vip'), prixMensuel,
 *                maxSeancesParSemaine (null = illimite), accesSauna (bool)
 *
 * Methodes :
 *   getDescription() : string
 */
class Abonnement{
    const TYPE_BASIC = 'basic';  
    const TYPE_PREMIUM = 'premium';  
    const TYPE_Vip = 'vip';  
    public string $type;
    public int $prixMensuel;
    public ?int $maxSeancesParSemaine;
    public bool $accesSauna;

    

    public function __construct($type,$prixMensuel,$maxSeancesParSemaine,$accesSauna){
        if (
            !in_array($type, [
                Abonnement::TYPE_Vip,
                Abonnement::TYPE_BASIC,
                Abonnement::TYPE_PREMIUM
            ])
        ){
            throw new Exception("this type is not defind", 1);
        }else{
            $this->type = $type;
            $this->prixMensuel = $prixMensuel;
            $this->maxSeancesParSemaine = $maxSeancesParSemaine;
            $this->accesSauna = $accesSauna;
        }
            ;
    }

    public function getDescription(): string {
        return "hhhhh";
    }




}
/**
 * =====================================================================
 * Classe Membre :
 *   Proprietes : id, nom, prenom, email, abonnement (Abonnement),
 *                dateDebut, dateFin, seancesCetteSemaine (int), actif
 */

class Membre{
    private int $id ;
    public string $nom;
    public string $prenom;
    public string $email;
    public Abonnement $abonnement;
    public string $dateDebut;
    public string $dateFin;
    public int $seancesCetteSemaine;
    public bool $actif;
    
    public function __construct(
    $nom,$prenom,$email,$abonnement,$dateDebut,$dateFin
){
    $this->nom = $nom;
    $this->prenom = $prenom;
    $this->email = $email;
    $this->abonnement = $abonnement;
    $this->dateDebut = $dateDebut;
    $this->dateFin = $dateFin;

    $this->actif = true;
    $this->seancesCetteSemaine = 0;
}

    public function estAbonnementValide():bool{
        return strtotime($this->dateFin) >= strtotime(date('Y-m-d')) && $this->actif;
    }
    public function peutAccederSauna():bool{
        return $this->abonnement->accesSauna && $this->estAbonnementValide();
    }
    public function enregistrerSeance(){
        if (!$this->estAbonnementValide()) {
        throw new RuntimeException("Abonnement invalide");
    }
    $max = $this->abonnement->maxSeancesParSemaine;
    if ($max !== null && $this->seancesCetteSemaine >= $max) {
        throw new RuntimeException("Quota hebdomadaire atteint");
    }
        $this->abonnement->maxSeancesParSemaine++;
    }
    public function renouveler(int $mois){
        $dateFin = new DateTime($this->dateFin);
        $dateFin->modify("+ $mois months");
        $this->dateFin = (string)$dateFin;

        $total = $mois * $this->abonnement->prixMensuel;

        if($mois <= 3){
            return $total;
        }elseif ($mois >= 4 && $mois <= 11) {
            return $total * 0.9;
        }elseif ($mois == 12){
            return $total * 0.8;
        }
    }

    public function resetSeanceSemaine(){
        $this->seancesCetteSemaine = 0;
    }
}
/*
 * Methodes :
 *
 *   estAbonnementValide() : bool
 *     Retourner true si dateFin >= aujourd'hui ET actif.
 *
 *   peutAccederSauna() : bool
 *     Retourner true si abonnement.accesSauna ET abonnementValide.
 *
 *   enregistrerSeance() : void
 *     Verifier que l'abonnement est valide.
 *     Verifier que le quota hebdomadaire n'est pas atteint
 *     (si maxSeancesParSemaine n'est pas null).
 *     Incrementer seancesCetteSemaine.
 *     Lever RuntimeException si acces refuse.
 *
 *   renouveler(int $mois) : void
 *     Prolonger dateFin de $mois mois.
 *     Calculer et retourner le montant paye :
 *     - 1 a 3 mois : prix normal
 *     - 4 a 11 mois : 10% de remise
 *     - 12 mois : 20% de remise
 *
 *   resetSeancesSemaine() : void
 *     Remettre seancesCetteSemaine a 0 (appele chaque Jour 01).
 *
 * =====================================================================
 * Classe SalleSport :
 *   Proprietes : nom, membres (Membre[]), capaciteMax
 *
 * */
class SalleSport{
    public string $nom;
    public array $membres = [];
    public int $capaciteMax;

    public function __construct(string $nom,int $capaciteMax){
        $this->nom = $nom;
        $this->capaciteMax = $capaciteMax;
    }
    public function inscrireMembre(Membre $m){
        if(!in_array($m,$this->membres)){
            $this->membres[] = $m;
        }else{
            return throw new Exception('this membre is deja exist', 1);
        }
    }

    public function getMembresActifs():array{
        $results = [];
        foreach($this->membres as $m){
            if($m->estAbonnementValide()){
                $results[] = $m;
            }
        }
        return $results;
    }

    public function getMembresExpirant(int $joursAvant):array{
        $results = [];

        foreach($this->membres as $m){
            $dateFin = new DateTime($m->dateFin);
            $today = new DateTime(date('Y-m-d'));
            if($dateFin - $today <= $joursAvant){
                $results[] = $m;
            }
        }
        return $results;
    }

    public function recetteMensuelle(): float{
        $counMmActive = 0;
        foreach ($this->membres as $m) {
            if($m->actif){
                $counMmActive += $m->abonnement->prixMensuel;
            }
        }
        return $counMmActive;
    }

    public function statistiquesParType() : array{
        $results = [];
        foreach($this->membres as $m){
            if(!$results[$m->abonnement->type]){
                $results[$m->abonnement->type] = 0;
            }
            $results[$m->abonnement->type]++;
        }
        return $results;
    }
}

/*
 * Methodes :
 *
 *   inscrireMembre(Membre $m) : void
 *
 *   getMembresActifs() : array
 *     Membres dont l'abonnement est valide.
 *
 *   getMembresExpirant(int $joursAvant) : array
 *     Membres dont l'abonnement expire dans moins de $joursAvant jours.
 *
 *   recetteMensuelle() : float
 *     Somme des prixMensuel de tous les membres actifs.
 *
 *   statistiquesParType() : array
 *     { 'basic' => N, 'premium' => N, 'vip' => N, 'expire' => N }
 */

// Votre implementation ci-dessous

// Tests
try{
    $basicAbo   = new Abonnement('basic',   199, 3, false);
$premiumAbo = new Abonnement('premium', 349, null, true);
$vipAbo     = new Abonnement('vip',     499, null, true);

$salle = new SalleSport("FitPro Casablanca", 200);

$m1 = new Membre("Alami", "Hassan", "h.alami@email.ma", $premiumAbo, "2024-01-01", "2027-12-31");
$m2 = new Membre("Benali", "Sara",  "s.benali@email.ma", $basicAbo,  "2024-02-01", "2027-03-31");
$m3 = new Membre("Chraibi", "Omar", "o.chraibi@web.ma",  $vipAbo,    "2024-01-15", "2027-07-15");

$salle->inscrireMembre($m1);
$salle->inscrireMembre($m2);
$salle->inscrireMembre($m3);

// Enregistrer seances
$m1->enregistrerSeance();
$m1->enregistrerSeance();

// Test quota basic
try {
    $m2->enregistrerSeance(); // 1
    $m2->enregistrerSeance(); // 2
    $m2->enregistrerSeance(); // 3
    $m2->enregistrerSeance(); // doit echouer
} catch (RuntimeException $e) {
    echo "Limite atteinte : " . $e->getMessage() . PHP_EOL;
}

echo "Recette mensuelle : " . $salle->recetteMensuelle() . " DH" . PHP_EOL;
print_r($salle->statistiquesParType());

$montantRenouvellement = $m1->renouveler(12);
echo "Renouvellement 12 mois : " . $montantRenouvellement . " DH" . PHP_EOL;

}catch(Exception $e){
    echo $e->getMessage();
}