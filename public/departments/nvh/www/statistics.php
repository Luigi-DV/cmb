<?php
// Include
require('./base.inc');
require(BASE . '/../config.inc');
$smarty = new MySmarty();
require(BASE . '/../includes/header.inc');
require(BASE . '/planning_param.php');

// Users
$users = new GCollection('User');
$sql = " SELECT planning_user.* FROM planning_user ORDER BY order_planning ASC ";
$users->db_loadSQL($sql);

// Number of weeks
$weeks = array();
$ocupation = array();
// Salas
$dut50 = array();
$dut50100 = array();
$dut100 = array();
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
							$totalh += 16;
						}
						if($p->duree_details == 'duree'){
							$turnos=$turnos+2;
							$totalh += 32;
						}
					}
					$occ = $turnos/(2*5)*100; //Cas de EMC 2torns*7dias
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
		if($user->user_id == 'V890' || $user->user_id == 'V850' || $user->user_id == 'J240S' || $user->user_id == 'V806Z' || $user->user_id == 'V806LS'){ //v890,v850,vj240s,v806,v806ls
						$dut50[0]+=$average;
						$dut50[1]+=$totalh;
		}
		if($user->user_id == 'V890'|| $user->user_id == 'V850'|| $user->user_id == 'J240S'){ // v890,v850,vj240s
						$dut50100[0]+=$average;
						$dut50100[1]+=$totalh;
		}
		if($user->user_id == 'V890'){ //V890
						$dut100[0]+=$average;
						$dut100[1]+=$totalh;
		}
	}
}
//------------------------------
// ------AGUPACIO DE SALAS------
//------------------------------
$html .='</tr> </table> <br> <br> <table class="ocupationTable-group" id="tableOcupation-group"> <tr> <th class="ocupationTable"> SALAS GROUPS </th>';
$html .='<th class="ocupationTable"> AVERAGE </th>';
$html .='<th class="ocupationTable"> TOTAL h </th>';
// TEST AXIS XYZ (DUT <50 kg) 
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> TEST AXIS XYZ (DUT <50 kg)  </td>';
$html .= '<td class="ocupationTable">'. $dut50[0]/5 .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $dut50[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// TEST AXIS XYZ (50Kg < DUT < 100kg)
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> TEST AXIS XYZ (50Kg < DUT < 100kg) </td>';
$html .= '<td class="ocupationTable">'. $dut50100[0]/3 .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $dut50100[1].'h' . '</td>'; //total hours
$html .= '</tr>';
// TEST AXIS XYZ (DUT > 100Kg) 
$html .= '<tr class="ocupationTable">';
$html .= '<td class="ocupationTable"> TEST AXIS XYZ (DUT > 100Kg)  </td>';
$html .= '<td class="ocupationTable">'. $dut100[0] .'%' . '</td>'; //total occupation
$html .= '<td class="ocupationTable">'. $dut100[1].'h' . '</td>'; //total hours
$html .= '</tr>';
$weeks = array_unique($weeks);
$smarty->assign('weeks', $weeks);
$smarty->assign('ocupation', $ocupation);
$smarty->assign('htmlTableau', $html);
$smarty->assign('users', $users->getSmartyData());
$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));
$smarty->display('www_statistics.tpl');