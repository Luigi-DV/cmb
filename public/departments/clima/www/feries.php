<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

if(!$user->checkDroit('users_manage_all')) {
	$_SESSION['erreur'] = 'droitsInsuffisants';
	header('Location: ../index.php');
	exit;
}

$feries = new GCollection('Ferie');
$sql = 'SELECT * FROM planning_ferie WHERE planning_ferie.intensive IS NULL ORDER BY date_ferie ASC';
$feries->db_loadSQL($sql);
$smarty->assign('feries', $feries->getSmartyData());

$fichiers = glob(BASE . '/../holidays/*.*');
$smarty->assign('fichiers', $fichiers);

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->display('www_feries.tpl');

?>
