<?php

require('./base.inc');
require(BASE . '/../config.inc');
$smarty = new MySmarty();
require BASE . '/../includes/header.inc';

$dateDebut = new DateTime();
$dateFin = new DateTime();
$dateDebut->setDate(substr($_SESSION['date_debut_affiche'],6,4), substr($_SESSION['date_debut_affiche'],3,2), substr($_SESSION['date_debut_affiche'],0,2));
$dateFin->setDate(substr($_SESSION['date_fin_affiche'],6,4), substr($_SESSION['date_fin_affiche'],3,2), substr($_SESSION['date_fin_affiche'],0,2));

header("Content-Type: application/vnd.ms-excel");
header("Content-disposition: attachment; filename=statistics_" . $dateDebut->format('Y-m-d') . "_" . $dateFin->format('Y-m-d') . ".csv");
// Users
$users = new GCollection('User');
$sql = " SELECT planning_user.* FROM planning_user ORDER BY visible_planning ";
$users->db_loadSQL($sql);

// Number of weeks
$weeks = array();
$sem = $dateDebut->format('W');
while($dateFin->format('N')!=7){
	$dateFin->modify('+1 day');
}
while ($user = $users->fetch()){
	$tmpDate = clone $dateDebut;
	while($tmpDate->format('N')!=1){ //trobar el primer dilluns
		$tmpDate->modify('-1 day');
	}
	$html .= '<tr>';
	if($user->visible_planning == 'oui'){
		$html .= '<td>'. $user->nom . '</td>';
		while ($tmpDate <= $dateFin) {
			if ($tmpDate->format('W') == $sem){
				if($tmpDate->format('N')==1){
					$firstDay = $tmpDate->format('Y-m-d');
				}
				if($tmpDate->format('N')==7){
					$lastDay = $tmpDate->format('Y-m-d');
					$weekPeriodes = new GCollection('Periode');
					$sql= "SELECT * FROM `planning_periode` WHERE date_debut>='" . $firstDay . "' AND date_debut<='" . $lastDay . "' AND user_id = '" . $user->user_id . "' AND projet_id != 'holiday' AND projet_id != 'timetable' ORDER BY `date_debut` DESC";
					$weekPeriodes->db_loadSQL($sql);
					$turnos = 0;
					while ($p = $weekPeriodes->fetch()){
						if($p->duree_details == 'AM' OR $p->duree_details == 'PM'){
							$turnos=$turnos+1;
						}
						if($p->duree_details == 'duree'){
							$turnos=$turnos+2;
						}
					}
					$occ = $turnos/(2*5)*100; //Cas de EMC 2torns*7dias
					$html .= '<td>'. $occ . '</td>';
				}
			}
			$tmpDate->modify('+1 day');
			if($tmpDate <= $dateFin){
				$sem = $tmpDate->format('W');
				array_push($weeks, $tmpDate->format('W'));
			}
		}
		$html .= '</tr>';
	}
}
$weeks = array_unique($weeks);
$smarty->assign('weeks', $weeks);
$smarty->assign('htmlTableau', $html);
$smarty->assign('users', $users->getSmartyData());
$smarty->display('www_csv_statistics.tpl');

?>
