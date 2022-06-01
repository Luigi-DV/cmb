<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$_POST = sanitize($_POST);
$_GET = sanitize($_GET);
$_REQUEST = sanitize($_REQUEST);
$_SESSION['lastURL'] = "http://$_SERVER[HTTP_HOST]$_SERVER[REQUEST_URI]";

// PARAMÈTRES
$dateDebut = new DateTime();

if (isset($_REQUEST['nb_mois']) && is_numeric($_REQUEST['nb_mois']) && round($_REQUEST['nb_mois']) > 0) {
	$nbMois = $_REQUEST['nb_mois'];
	$_SESSION['nb_mois'] = $_REQUEST['nb_mois'];
} elseif (isset($_SESSION['nb_mois'])) {
	$nbMois = $_SESSION['nb_mois'];
} else {
	$nbMois = 2;
	$_SESSION['nb_mois'] = $nbMois;
}

// French date forcing
// Conversion des dates en mode mobile au format french
if (isset($_REQUEST['date_debut_affiche_projet']) && $_SESSION['isMobileOrTablet']) 
{
	$_REQUEST['date_debut_affiche_projet']=forceUserDateFormat($_REQUEST['date_debut_affiche_projet']);
}
if (isset($_REQUEST['date_debut_affiche_projet'])) {
	$dateDebut = initDateTime($_REQUEST['date_debut_affiche_projet']);
	$_SESSION['date_debut_affiche_projet'] = $_REQUEST['date_debut_affiche_projet'];
} else {
	//$dateDebut->modify('-' . CONFIG_DEFAULT_NB_PAST_DAYS . ' days');
	$_SESSION['date_debut_affiche_projet'] = $dateDebut->format(CONFIG_DATE_LONG);
}
if(!$dateDebut ) {
	echo "Erreur de date";
	exit;
	header('Location: samples.php');
}
if (isset($_REQUEST['statuts']) && is_array($_REQUEST['statuts'])) {
	$listeStatut = $_REQUEST['statuts'];
} elseif (isset($_SESSION['statuts_projet']) && is_array($_SESSION['statuts_projet'])) {
	$listeStatut = $_SESSION['statuts_projet'];
} else {
	$listeStatut = array('Received');
}
$_SESSION['statuts_projet'] = $listeStatut;
setcookie('statuts_projet', json_encode($listeStatut), time()+60*60*24*500, '/');

if (isset($_REQUEST['filtrageProjet'])) {
	$filtrageProjet = $_REQUEST['filtrageProjet'];
} elseif (isset($_SESSION['filtrageProjet'])) {
	$filtrageProjet = $_SESSION['filtrageProjet'];
} else {
	$filtrageProjet = 'tous';
}
$_SESSION['filtrageProjet'] = $filtrageProjet;

if (isset($_REQUEST['order']) && in_array($_REQUEST['order'], array('nom_createur', 'sample_id', 'r_date', 'e_date', 'n_samples'))) {
	$order = $_REQUEST['order'];
} elseif (isset($_SESSION['projet_order'])) {
	$order = $_SESSION['projet_order'];
} else {
	$order = 'sample_id';
}

if (isset($_REQUEST['by'])) {
	$by = $_REQUEST['by'];
} elseif (isset($_SESSION['projet_by'])) {
	$by = $_SESSION['projet_by'];
} else {
	$by = 'ASC';
}

// FIN PARAMÈTRES

$dateFin = clone $dateDebut;
$dateFin->modify('+' . $nbMois . ' months');
$dateFin->modify('-1 days');
$smarty->assign('dateDebut', $dateDebut->format(CONFIG_DATE_LONG));
$smarty->assign('dateFin', $dateFin->format(CONFIG_DATE_LONG));
$smarty->assign('nbMois', $nbMois);
$smarty->assign('listeStatut', $listeStatut);
$smarty->assign('filtrageProjet', $filtrageProjet);
$smarty->assign('order', $order);
$smarty->assign('by', $by);

$samples = new GCollection('Sample');

if(isset($_REQUEST['desactiverfiltreGroupe'])) {
	$filtreGroupeProjet = array();
	$_SESSION['groupe_filtreEquipeProjet'] = $filtreGroupeProjet;
}

if (isset($_REQUEST['filtreGroupeProjet'])) {
	$filtreGroupeProjet = array();
	if(isset($_REQUEST['gp'])) {
		$filtreGroupeProjet = $_REQUEST['gp'];
	}
} elseif (isset($_SESSION['groupe_filtreGroupeProjet'])) {
	$filtreGroupeProjet = $_SESSION['groupe_filtreGroupeProjet'];
} else {
	$filtreGroupeProjet = array();
}

if(isset($_REQUEST['rechercheProjet']) && $_REQUEST['rechercheProjet'] != ''){
	$search = $_REQUEST['rechercheProjet'];
	$search = explode( ' ', $search );

	$isLike = array('0');

	foreach($search as $word){
		$isLike[] = 'planning_sample.sample_id LIKE '.val2sql('%' . $word . '%');
		$isLike[] = 'planning_sample.projet_id LIKE '.val2sql('%' . $word . '%');
		$isLike[] = 'planning_user.nom LIKE '.val2sql('%' . $word . '%');
		$isLike[] = 'planning_projet.nom LIKE '.val2sql('%' . $word . '%');
	}

	$isLike = implode(" OR ", $isLike);
	$sql = "SELECT planning_sample.*, planning_projet.nom AS nom_groupe, planning_user.nom AS nom_createur
			FROM planning_sample
			LEFT JOIN planning_projet ON planning_projet.projet_id = planning_sample.projet_id
			LEFT JOIN planning_user ON planning_user.user_id = planning_sample.user_id
			WHERE (" . $isLike . ") AND planning_sample.statut in ('" . implode("','", $listeStatut) . "')";
	if(count($filtreGroupeProjet) > 0) $sql .= "		AND (planning_sample.projet_id IN ('" . implode("','", $filtreGroupeProjet) . "'))";
	$sql .= "ORDER BY nom_groupe ASC, " . $order . ' ' . $by;

	$smarty->assign('rechercheProjet', $_REQUEST['rechercheProjet']);
}  else {
	// recuperation des projets couvrant la période
	$sql = "SELECT distinct planning_sample.*, planning_projet.nom AS nom_groupe, planning_user.nom AS nom_createur
			FROM planning_sample
			LEFT JOIN planning_projet ON planning_projet.projet_id = planning_sample.projet_id
			LEFT JOIN planning_user ON planning_user.user_id = planning_sample.user_id 
			WHERE planning_sample.statut in ('" . implode("','", $listeStatut) . "')";
	if(count($filtreGroupeProjet) > 0) $sql .= "		AND (planning_sample.projet_id IN ('" . implode("','", $filtreGroupeProjet) . "'))";
	$sql .= "ORDER BY nom_groupe ASC, " . $order . ' ' . $by;
	
	$smarty->assign('rechercheProjet', '');
 }

$samples->db_loadSQL($sql);

$groupeProjets = new GCollection('Projet');
$groupeProjets->db_load(array(), array('nom' => 'ASC'));
$smarty->assign('filtreGroupeProjet', $filtreGroupeProjet);
$smarty->assign('groupeProjets', $groupeProjets->getSmartyData());
$smarty->assign('samples', $samples->getSmartyData());
$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));
$smarty->display('www_samples.tpl');
?>