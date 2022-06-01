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
$chm02 = array();
$chm05 = array();
$chm1 = array();
$chm1_c = array();
$sem = $dateDebut->format('W');
while($dateFin->format('N')!=7){
	$dateFin->modify('+1 day');
}
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
				if($tmpDate->format('N')==7){
					$lastDay = $tmpDate->format('Y-m-d');
					$weekPeriodes = new GCollection('Periode');
					$sql= "SELECT * FROM `planning_periode` WHERE date_debut>='" . $firstDay . "' AND date_debut<='" . $lastDay . "' AND user_id = '" . $user->user_id . "' AND projet_id != 'holiday' AND projet_id != 'timetable' ORDER BY `date_debut` DESC";
					$weekPeriodes->db_loadSQL($sql);
					$turnos = 0;
					while ($p = $weekPeriodes->fetch()){
						if($p->duree_details == 'AM' OR $p->duree_details == 'PM'){
							$turnos=$turnos+1;
							$totalh += 12;
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
		if($user->user_id == '3' || $user->user_id == '4' || $user->user_id == '5'){ //P3,P4,P5
						$chm02[0]+=$average;
						$chm02[1]+=$totalh;
		}
		if($user->user_id == '6'|| $user->user_id == '7'|| $user->user_id == '9'|| $user->user_id == '10'|| $user->user_id == '11'|| $user->user_id == '12'){ //P6,P7,P9,P10,P11,P12
						$chm05[0]+=$average;
						$chm05[1]+=$totalh;
		}
		if($user->user_id == '16' || $user->user_id == '18' || $user->user_id == '20' || $user->user_id == '21' || $user->user_id == '22'){ //M1;M3;M5;M6;M7
						$chm1[0]+=$average;
						$chm1[1]+=$totalh;
		}
		if($user->user_id == '19'){ //M4
						$chm1_c[0]+=$average;
						$chm1_c[1]+=$totalh;
		}
	}
}
//------------------------------
// ------AGUPACIO DE SALAS------
//------------------------------
$html .='</tr> </table> <br> <br> <table class="ocupationTable-group" id="tableOcupation-group"> <tr> <th class="ocupationTable"> SALAS GROUPS </th>';
$html .='<th class="ocupationTable"> AVERAGE </th>';
$html .='<th class="ocupationTable"> TOTAL h </th>';
// Chamber < 0,2m3
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> Chamber < 0,2m3 </td>';
$html .= '<td class="ocupationTable">'. $chm02[0]/3 .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $chm02[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// Chamber < 0,5m3
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> Chamber < 0,5m3 </td>';
$html .= '<td class="ocupationTable">'. $chm05[0]/6 .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $chm05[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// Chamber <1m3
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> Chamber <1m3 </td>';
$html .= '<td class="ocupationTable">'. $chm1[0]/5 .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $chm1[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// Chamber <1m3 - Condesation Test
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> Chamber <1m3 - Condesation Test </td>';
$html .= '<td class="ocupationTable">'. $chm1_c[0] .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $chm1_c[1].'h' . '</td>'; //total hours
$html .= '</tr>';

$weeks = array_unique($weeks);
$smarty->assign('weeks', $weeks);
$smarty->assign('ocupation', $ocupation);
$smarty->assign('htmlTableau', $html);
$smarty->assign('users', $users->getSmartyData());
$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));
$smarty->display('www_statistics.tpl');