<?php
// Include
require('./base.inc');
require(BASE . '/../config.inc');
$smarty = new MySmarty();
require(BASE . '/../includes/header.inc');
require(BASE . '/planning_param.php');

// Users
$users = new GCollection('User');
$sql = " SELECT planning_user.* FROM planning_user ORDER BY visible_planning ";
$users->db_loadSQL($sql);

// Number of weeks
$weeks = array();
$ocupation = array();
// Salas
$sac10m = array();
$sac_auto = array();
$sac_ind = array();
$sr_auto = array();
$sr_ind = array();
$sem = $dateDebut->format('W');
while($dateFin->format('N')!=7){
	$dateFin->modify('+1 day');
}
//---------------------------
//---------OCUPATION---------
//---------------------------
while ($user = $users->fetch()){
	$totalocc = 0;
	$totalh = 0;
	$numofweeks = 0;
	$tmpDate = clone $dateDebut;
	while($tmpDate->format('N')!=1){ //trobar el primer dilluns
		$tmpDate->modify('-1 day');
		$firstDay = $tmpDate->format('Y-m-d');
	}
	$html .= '<tr class="ocupationTable">';
	if($user->visible_planning == 'oui'){
		$html .= '<td class="ocupationTable">'. $user->nom . '</td>';
		while ($tmpDate <= $dateFin) {
			if ($tmpDate->format('W') == $sem){
				if($tmpDate->format('N')==1){
					$firstDay = $tmpDate->format('Y-m-d');
				}
				if($tmpDate->format('N')==7){ //Diumenge
					$lastDay = $tmpDate->format('Y-m-d');
					$weekPeriodes = new GCollection('Periode');
					$sql= "SELECT * FROM `planning_periode` WHERE date_debut>='" . $firstDay . "' AND date_debut<='" . $lastDay . "' AND user_id = '" . $user->user_id . "' AND projet_id != 'holiday' AND projet_id != 'timetable' ORDER BY `date_debut` DESC";
					$weekPeriodes->db_loadSQL($sql);
					$turnos = 0;
					while ($p = $weekPeriodes->fetch()){
						if($p->duree_details == 'AM' OR $p->duree_details == 'PM'){
							$turnos=$turnos+1;
							$totalh += 8;
						}
						if($p->duree_details == 'duree'){
							$turnos=$turnos+2;
							$totalh += 24;
						}
					}
					$occ = $turnos/(2*5)*100; //Cas de EMC 2torns*5dias
					$html .= '<td class="ocupationTable"';
					if($occ>=40 && $occ<60){
						$html .= 'style="background-color:yellow"';
					}
					if($occ>=60){
						$html .= 'style="background-color:#77acf1"';
					}
					$html .= '>'. $occ .'</td>';
					$totalocc += $occ;
				}
			}
			$tmpDate->modify('+1 day');
			if($tmpDate <= $dateFin){
				$sem = $tmpDate->format('W');
				$numofweeks++;
				array_push($weeks, $tmpDate->format('W'));
			}
		}
		array_push($weeks, "AVERAGE");
		array_push($weeks, "TOTAL h");
		$average = floor($totalocc/(floor($numofweeks/7)+1));
		$html .= '<td class="ocupationTable">'. $average .'%' . '</td>'; //total occupation / total weeks
		$html .= '<td class="ocupationTable">'. $totalh.'h' . '</td>'; //total hours
		$html .= '</tr>';
		if($user->user_id == '1'){ //sac0
						$sac10m[0]+=$average;
						$sac10m[1]+=$totalh;
		}
		if($user->user_id == '2'|| $user->user_id == '4'|| $user->user_id == '5'|| $user->user_id == '6'|| $user->user_id == '7'){ //sac1, sac3, sac4, sac5, sac6
						$sac_auto[0]+=$average;
						$sac_auto[1]+=$totalh;
		}
		if($user->user_id == '3'){ //sac2
						$sac_ind[0]+=$average;
						$sac_ind[1]+=$totalh;
		}
		if($user->user_id == '10'|| $user->user_id == '11'){ //SR0, SR1
						$sr_auto[0]+=$average;
						$sr_auto[1]+=$totalh;
		}
		if($user->user_id == '9'){ //SR2
						$sr_ind[0]+=$average;
						$sr_ind[1]+=$totalh;
		}
	}
}
//------------------------------
// ------AGUPACIO DE SALAS------
//------------------------------
$html .='</tr> </table> <br> <br> <table class="ocupationTable-group" id="tableOcupation-group"> <tr> <th class="ocupationTable"> SALAS GROUPS </th>';
$html .='<th class="ocupationTable"> AVERAGE </th>';
$html .='<th class="ocupationTable"> TOTAL h </th>';
// SAC 10 m
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> SAC 10 m </td>';
$html .= '<td class="ocupationTable">'. $sac10m[0] .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $sac10m[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// SAC 1-3 m (Autocomp)
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> SAC 1-3 m (Autocomp) </td>';
$html .= '<td class="ocupationTable">'. $sac_auto[0]/5 .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $sac_auto[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// SAC 1-3 m (Industria)
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> SAC 1-3 m (Industria) </td>';
$html .= '<td class="ocupationTable">'. $sac_ind[0] .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $sac_ind[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// SR (Autocomp)
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> SR (Autocomp) </td>';
$html .= '<td class="ocupationTable">'. $sr_auto[0]/2 .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $sr_auto[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// SR (Industria)
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> SR (Industria) </td>';
$html .= '<td class="ocupationTable">'. $sr_ind[0] .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $sr_ind[1].'h' . '</td>'; //total hours
$html .= '</tr>';

$weeks = array_unique($weeks);
$smarty->assign('weeks', $weeks);
$smarty->assign('ocupation', $ocupation);
$smarty->assign('htmlTableau', $html);
$smarty->assign('users', $users->getSmartyData());
$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));
$smarty->display('www_statistics.tpl');