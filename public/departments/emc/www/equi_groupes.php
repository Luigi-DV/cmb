<?php

require('./base.inc');
require BASE . '/../config.inc';

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$_POST = sanitize($_POST);
$_GET = sanitize($_GET);

if(!$user->checkDroit('ressources_all')) {
	$_SESSION['erreur'] = 'droitsInsuffisants';
	header('Location: index.php');
	exit;
}

if (isset($_GET['order']) && in_array($_GET['order'], array('nom'))) {
	$order = $_GET['order'];
} elseif (isset($_SESSION['ressource_groupe_order'])) {
	$order = $_SESSION['ressource_groupe_order'];
} else {
	$order = 'nom';
}

if (isset($_GET['by'])) {
	$by = $_GET['by'];
} elseif (isset($_SESSION['ressource_groupe_by'])) {
	$by = $_SESSION['ressource_groupe_by'];
} else {
	$by = 'ASC';
}

$groupes = new GCollection('Ressource_groupe');

$groupes->db_loadSQL("SELECT distinct g.ressource_groupe_id, g.nom, COUNT(r.ressource_id) as 'totalRessources'
						FROM planning_ressource_groupe g LEFT JOIN planning_ressource r ON g.ressource_groupe_id = r.ressource_groupe_id
						GROUP BY g.ressource_groupe_id, g.nom
						ORDER BY ". $order . " " . $by);

$groupes->setPagination(1000);

$smarty->assign('order', $order);
$smarty->assign('by', $by);

$_SESSION['ressource_groupe_order'] = $order;
$_SESSION['ressource_groupe_by'] = $by;

$smarty->assign('groupes', $groupes->getSmartyData(TRUE));

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->display('www_equi_groupes.tpl');
?>