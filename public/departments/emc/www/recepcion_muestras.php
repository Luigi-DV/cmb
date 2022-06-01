<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

$ressources = new GCollection('Ressource');
$ressources->db_load(array(), array('nom' => 'ASC'));
$smarty->assign('ressources', $ressources->getSmartyData());

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->display('www_samples.tpl');

?>