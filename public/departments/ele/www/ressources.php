<?php

require('./base.inc');
require(BASE . '/../config.inc');

$smarty = new MySmarty();

require BASE . '/../includes/header.inc';

if(!$user->checkDroit('ressources_all')) {
	$_SESSION['erreur'] = 'droitsInsuffisants';
	header('Location: ../index.php');
	exit;
}

if (isset($_GET['order']) && in_array($_GET['order'], array('nom', 'ressource_id', 'nom_groupe'))) {
	$order = $_GET['order'];
} elseif (isset($_SESSION['ressource_order'])) {
	$order = $_SESSION['ressource_order'];
} else {
	$order = 'nom';
}

if (isset($_GET['filtreEquipeGroupe'])) {
	//$filtreEquipe = $_GET['filtreEquipeGroupe'];
} elseif (isset($_SESSION['ressource_filtreEquipeGroupe'])) {
	$filtreEquipe = $_SESSION['ressource_filtreEquipeGroupe'];
} else {
	$filtreEquipe = array();
}

if (isset($_GET['by'])) {
	$by = $_GET['by'];
} elseif (isset($_SESSION['ressource_by'])) {
	$by = $_SESSION['ressource_by'];
} else {
	$by = 'ASC';
}

if(isset($_GET['desactiverfiltreEquipeGroupe'])) {
	$filtreEquipe = array();
	$_SESSION['ressource_filtreEquipeGroupe'] = $filtreEquipe;
}
if (isset($_POST['filtreEquipeGroupe'])) {
	$filtreEquipe = array();
	if(isset($_POST['gu'])) {
		$filtreEquipe = $_POST['gu'];
	}
	if(isset($_POST['gu0'])) {
		$filtreEquipe[] = 'gu0';
	}
} elseif (isset($_SESSION['ressource_filtreEquipeGroupe'])) {
	$filtreEquipe = $_SESSION['ressource_filtreEquipeGroupe'];
} else {
	$filtreEquipe = array();
}

$filtreUser="";
if(isset($_POST['rechercheEqui']))
{
	 $filtreUser=$_POST['rechercheEqui'];
}

$listeRessources = new GCollection('Ressource');
$sql = "SELECT * FROM planning_ressource";
$listeRessources->db_loadSQL($sql);
while ($ressource = $listeRessources->fetch()) {
	if($ressource->date_calibrated < date('Y-m-d') && $ressource->date_calibrated != NULL){
		$ressource->calibrated = 0;
	}
	else{
		if ($ressource->date_calibrated == NULL){
			$ressource->calibrated = 2;
		}
		else{
			$ressource->calibrated = 1;
		}
	}
	$ressource->db_save();
}

$ressources = new GCollection('Ressource');

$sql = 'SELECT distinct pr.nom, pr.ressource_id, prg.nom AS nom_groupe, pr.commentaire, pr.calibrated AS calibrated, pr.date_calibrated as date_calibrated, pr.ressource_groupe_id AS ressource_groupe_id
                    from planning_ressource pr
					LEFT JOIN planning_ressource_groupe prg ON prg.ressource_groupe_id = pr.ressource_groupe_id
					WHERE 1=1';
if(count($filtreEquipe) > 0) {
	$sql .= "		AND (pr.ressource_groupe_id IN ('" . implode("','", $filtreEquipe) . "')";
	if(in_array('gu0', $filtreEquipe)) {
		$sql .= '	OR prg.ressource_groupe_id IS NULL ';
	}
	$sql .= ' )';
}

if($filtreUser<>"")
{	
	$sql .= "		AND ( (pr.nom like '%$filtreUser%') or (pr.ressource_id like '%$filtreUser%') or (pr.commentaire like '%$filtreUser%'))";
}	
$sql .= '			GROUP BY pr.nom, pr.ressource_id, nom_groupe
                    ORDER BY '. $order . ' ' . $by;
$ressources->db_loadSQL($sql);



$smarty->assign('rechercheEqui', $filtreUser);
$smarty->assign('filtreEquipeGroupe', $filtreEquipe);
$smarty->assign('order', $order);
$smarty->assign('by', $by);

$_SESSION['ressource_filtreEquipeGroupe'] = $filtreEquipe;
$_SESSION['ressource_order'] = $order;
$_SESSION['ressource_by'] = $by;

$smarty->assign('ressources', $ressources->getSmartyData(TRUE));

$equipes = new GCollection('Ressource_groupe');
$equipes->db_load(array(), array('nom' => 'ASC'));
$smarty->assign('equipes', $equipes->getSmartyData());

$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));

$smarty->display('www_ressources.tpl');

?>