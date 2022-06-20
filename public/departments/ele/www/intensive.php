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
$feries->db_load(array('intensive', '=', '1'), array('date_ferie' => 'ASC'));
$smarty->assign('feries', $feries->getSmartyData());

$fichiers = glob(BASE . '/../holidays/*.*');
$smarty->assign('fichiers', $fichiers);

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->display('www_intensive.tpl');

?>
