<?php


use LambertAnne_France\Afficheurs\AfficheurDeCoursCli;
use LambertAnne_France\Afficheurs\AfficheurDeCoursCliElegant;
use LambertAnne_France\Afficheurs\AfficheurDeCoursHtml;
use LambertAnne_France\CoursLicence2018;
use LambertAnne_France\CoursLicence2019;
use LambertAnne_France\EnsembleDeCours;
use LambertAnne_France\Evaluation;
use LambertAnne_France\Sequence;

spl_autoload_register(function ($class_name) {
    $class_name=substr($class_name, 19);
    include_once './licenceinfo/'.$class_name . '.php';
    
});


CoursLicence2019::setNombreEleves(11);
CoursLicence2018::setNombreEleves(23);

$utc503 = new CoursLicence2019();
$utc503->setIntitule("paradigmes de la programmation");
$utc503->setIdentifiantAcademique("UTC 503");
$utc503->setNote("******");
$sequence1 = new Sequence("Yannick Le Pape", 4, 'Audit des connaissances, rappel et consolidation des principes de la POO, POC en PHP objet, initiation JavaScript objet');
$sequence2 = new Sequence("Cyril Pereira", 2, 'Création d\'un micro-framewor');
$utc503->addSequence($sequence1);
$utc503->addSequence($sequence2);
$evaluation1 = new Evaluation(Evaluation::TYPE_PONCTUELLES, 'TP en tp et à la maison', 6, Evaluation::ECHELLE_ELEVE);
$evaluation2 = new Evaluation(Evaluation::TYPE_PRATIQUE, 'mini projet à réaliser dans un temps limité', 4, Evaluation::ECHELLE_ELEVE);
$utc503->addEvaluation($evaluation1);
$utc503->addEvaluation($evaluation2);

$utc501 = new CoursLicence2018();
$utc501->setIntitule("mathématiques pour l'informatique");
$utc501->setIdentifiantAcademique("UTC 501");
$utc501->setNote("***");
$sequence1 = new Sequence("Imed Nasri", 4, 'Audit des connaissances, rappel et consolidation des principes de la POO, POC en PHP objet, initiation JavaScript objet');
$utc501->addSequence($sequence1);
$evaluation1 = new Evaluation(Evaluation::TYPE_PONCTUELLES, 'TP en tp et à la maison', 6, Evaluation::ECHELLE_ELEVE);
$evaluation2 = new Evaluation(Evaluation::TYPE_PRATIQUE, 'mini projet à réaliser dans un temps limité', 4, Evaluation::ECHELLE_ELEVE);
$utc501->addEvaluation($evaluation1);
$utc501->addEvaluation($evaluation2);

$utc501Clone = $utc501->clonage();

$ensembleDeCours = new EnsembleDeCours();
$ensembleDeCours->addCours($utc503);
$ensembleDeCours->addCours($utc501);
$ensembleDeCours->addCours($utc501Clone);

// comparaison des clones
if($utc501 == $utc501Clone){
    echo '$utc501 == $utc501Clone', PHP_EOL;
}
if($utc501 != $utc501Clone){
    echo '$utc501 != $utc501Clone', PHP_EOL;
}
if($utc501 === $utc501Clone){
    echo '$utc501 === $utc501Clone', PHP_EOL;
}
if($utc501 !== $utc501Clone){
    echo '$utc501 !== $utc501Clone', PHP_EOL;
}

// Si appel par CLI
if (isset($argc)) {

    $optionsDemandees = getopt('', ["elegant", "integral"]);

    if (array_key_exists('elegant', $optionsDemandees)) {
        $afficheurDeCoursCli = new AfficheurDeCoursCliElegant();
    } else {
        $afficheurDeCoursCli = new AfficheurDeCoursCli();
    }

    if (array_key_exists('integral', $optionsDemandees)) {
        $afficheurDeCoursCli->afficheIntegraliteInfos($ensembleDeCours);
    } else {
        $afficheurDeCoursCli->afficheResume($ensembleDeCours);
    }
} else {
    // Autremnet, appel web

    $afficheurDeCoursHtml = new AfficheurDeCoursHtml();

    if (isset($_GET['affichage']) && $_GET['affichage'] === 'integral') {
        echo $afficheurDeCoursHtml->afficheIntegraliteInfos($ensembleDeCours);
    } else {
        echo $afficheurDeCoursHtml->afficheResume($ensembleDeCours);
    }
}

