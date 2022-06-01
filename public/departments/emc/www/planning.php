<?php
// Include
require('./base.inc');
require(BASE . '/../config.inc');
$smarty = new MySmarty();
require(BASE . '/../includes/header.inc');
require(BASE . '/planning_param.php');

$planning=array();
$planning['lignes']=array();
$planning['colonnes']=array();
$planning['users']=array();
$planning['projets']=array();
$planning['periodes']=array();
$planning['lieux']=array();
$planning['ressources']=array();

//////////////////////////
// RECHERCHE DES TRANCHES HORAIRES POSSIBLES
//////////////////////////
$planning['heures']=array();
$tabTranchesHoraires = explode(',', CONFIG_HOURS_DISPLAYED);
$derniereTranche=end($tabTranchesHoraires)+1;
$i=0;
foreach ($tabTranchesHoraires as $trancheHeureCourante) {
		$i++;
		if ($trancheHeureCourante<$derniereTranche)
		{
			$trancheFin = $trancheHeureCourante + 1;
			if($trancheFin == 24) {
				$trancheFin = 0;
			}
			// Heure pleine
			$heure=sprintf("%'.02d:00", $trancheHeureCourante);
			$planning['heures'][]=$heure;
			if ($base_colonne<>"heures")
			{
				// Demie heure
				if (($trancheHeureCourante+0.5)<$derniereTranche)
				{
					$heure=sprintf("%'.02d:30", $trancheHeureCourante);
					$planning['heures'][]=$heure;		
				}
			}
		}
	}
$maxheures=$i;

//////////////////////////
// RECHERCHE DES USERS
//////////////////////////
$realUsers = new GCollection('User');

$sql = "SELECT pu.*, pug.nom as team_nom
		FROM planning_user pu
		LEFT JOIN planning_user_groupe pug ON pu.user_groupe_id = pug.user_groupe_id";
if($user->checkDroit('tasks_view_specific_users')) {
	$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = pu.user_id AND rou.owner_id = " . val2sql($user->user_id);
}
if(is_array($_SESSION['filtreUser']) && count($_SESSION['filtreUser']) > 0) {
	$sql .= " WHERE pu.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
	$sql .= " AND pu.visible_planning='oui' ";
}else{
	$sql .= " WHERE pu.visible_planning='oui'";
}
if (isset($_SESSION['triPlanningUser']))
{
$sql .= " ORDER BY CAST( user_id AS SIGNED) ASC";
}
$realUsers->db_loadSQL($sql);
$nbRealUsers = $realUsers->getCount();
// FIN RECHERCHE DES USERS

//////////////////////////
// RECHERCHE DES PERIODES
//////////////////////////
// on charge les jours occupés pour toutes les lignes
$periodes = new GCollection('Periode');
$sql = "SELECT planning_periode.*,planning_projet.statut, planning_status.nom as status_nom,  planning_status.barre as statut_barre,planning_status.gras as statut_gras,planning_status.italique as statut_italique,planning_status.souligne as statut_souligne, planning_status.couleur as statut_couleur,planning_status.pourcentage as statut_pourcentage, pu.nom as user_nom, pu.couleur as user_couleur,
		planning_projet.nom as projet_nom, planning_projet.couleur as projet_couleur, pg.nom AS groupe_nom,  pu.*, pug.nom AS team_nom,
		pl.nom as lieu_nom, pr.nom as ressource_nom, planning_projet.charge as charge, planning_projet.createur_id AS projet_createur_id, pm.nom AS projet_manager,
		puc.nom AS nom_createur, pum.nom AS nom_modifier,
		CASE 
		   WHEN planning_periode.duree_details = 'AM' THEN '08:00:00;08:01:00' 
		   WHEN planning_periode.duree_details = 'PM' THEN '14:00:00;14:01:00' 
		   WHEN planning_periode.duree_details = 'duree' THEN NULL    
		   ELSE planning_periode.duree_details 
		END AS tri_heures_taches
		FROM planning_periode
		INNER JOIN planning_projet on planning_projet.projet_id = planning_periode.projet_id
		INNER JOIN planning_status on planning_status.status_id = planning_periode.statut_tache
		INNER JOIN planning_user as pu on planning_periode.user_id = pu.user_id
		LEFT JOIN planning_user as pm on planning_projet.createur_id = pm.user_id
		LEFT JOIN planning_user as puc on planning_periode.createur_id = puc.user_id
		LEFT JOIN planning_user as pum on planning_periode.modifier_id = pum.user_id
		LEFT JOIN planning_user_groupe as pug on pu.user_groupe_id = pug.user_groupe_id
		LEFT JOIN planning_groupe as pg on planning_projet.groupe_id = pg.groupe_id
		LEFT JOIN planning_lieu as pl on planning_periode.lieu_id = pl.lieu_id
		LEFT JOIN planning_ressource as pr on planning_periode.ressource_id = pr.ressource_id";
// Si filtre sur user spécifique
if($user->checkDroit('tasks_view_specific_users')) {
	$sql .= " INNER JOIN planning_right_on_user AS rou ON rou.allowed_id = planning_periode.user_id AND rou.owner_id = " . val2sql($user->user_id);
}
$sql .= "	WHERE planning_periode.projet_id = planning_projet.projet_id and planning_periode.projet_id != 'holiday' and (
			(planning_periode.date_debut <= '" . $dateDebut->format('Y-m-d') . "' AND planning_periode.date_fin >= '" . $dateDebut->format('Y-m-d') . "')
			OR
			(planning_periode.date_debut <= '" . $dateFin->format('Y-m-d') . "' AND planning_periode.date_debut >= '" . $dateDebut->format('Y-m-d') . "')
			)";
// Si filtre sur user
if(is_array($_SESSION['filtreUser']) && count($_SESSION['filtreUser']) > 0) {
	$sql.= " AND planning_periode.user_id IN ('" . implode("','", $_SESSION['filtreUser']) . "')";
}
// Si filtre sur groupe projet
if(count($_SESSION['filtreGroupeProjet']) > 0) {
	$sql.= " AND planning_periode.projet_id IN ('" . implode("','", $_SESSION['filtreGroupeProjet']) . "')";
}
// Si filtre sur groupe lieu
if(count($_SESSION['filtreGroupeLieu']) > 0) {
	$sql.= " AND planning_periode.lieu_id IN ('" . implode("','", $_SESSION['filtreGroupeLieu']) . "')";
}
// Si filtre sur ressource
if(count($_SESSION['filtreGroupeRessource']) > 0) {
	$sql .= "AND ( planning_periode.ressource_id LIKE " . val2sql('%' . $_SESSION['filtreGroupeRessource'] . '%');
    foreach($_SESSION['filtreGroupeRessource'] as $res) {
		$sql .= " OR planning_periode.ressource_id LIKE " . val2sql('%' . $res . '%');
	}
	$sql .= " )";
}
// Si filtre sur statut de tache
if(count($_SESSION['filtreStatutTache']) > 0) {
	$sql.= " AND planning_periode.statut_tache IN ('" . implode("','", $_SESSION['filtreStatutTache']) . "')";
}
// Si filtre sur statut de projet
if(count($_SESSION['filtreStatutProjet']) > 0) {
	$sql.= " AND planning_projet.statut IN ('" . implode("','", $_SESSION['filtreStatutProjet']) . "')";
}
// Si filtre sur ses projets seulement
if($user->checkDroit('tasks_view_own_projects')) {
	$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
}
// Si filtre sur projets de l'équipe
if ($user->checkDroit('tasks_view_team_projects') && !is_null($user->user_groupe_id)) {
	$sql .= " AND planning_periode.projet_id IN ('" . implode("','", $listeProjetsPossibles) . "')";
}
// Si filtre sur ses tâches
if ($user->checkDroit('tasks_view_only_own')) {
	$sql .= " AND planning_periode.lieu_id = " . val2sql($user->user_id);
}
// si filtre sur texte
if($_SESSION['filtreTexte'] != "") {
	$sql.= " AND (convert(planning_periode.notes using utf8) collate utf8_general_ci LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR convert(planning_periode.lien using utf8) collate utf8_general_ci LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') ." OR convert(planning_periode.titre using utf8) collate utf8_general_ci LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR convert(planning_periode.custom using utf8) collate utf8_general_ci LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR planning_periode.projet_id LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " OR convert(planning_periode.user_id using utf8) collate utf8_general_ci LIKE " . val2sql('%' . $_SESSION['filtreTexte'] . '%') . " )";
}

// on trie par la date de début
if ($baseColonne="heures")
{
	$sql .=" ORDER by date_debut,tri_heures_taches";
}else $sql .=" ORDER by date_debut,duree_details asc";
$periodes->db_loadSQL($sql);
$nbLignesTotal = $periodes->getCount();
// FIN RECHERCHE DES PERIODES EN COURS

//////////////////////////
// LIGNES DU PLANNING
//////////////////////////

// Lignes users
if ($base_ligne == 'users')
{
	// liste des users à partir de tous les utilisateurs
	while ($u = $realUsers->fetch())
	{
		$infosUser = $u->getSmartyData();
		if ($user->checkDroit('users_manage_all'))
		{
			$url="xajax_modifRessourceUser('".urlencode($infosUser['user_id'])."')";
		}else $url="";
		$planning['lignes'][$infosUser['user_id']]=array('id'=>$infosUser['user_id'],'nom'=>$infosUser['nom'],'couleur'=>$infosUser['couleur'],'team_nom'=>$infosUser['team_nom'],'url_modif'=>$url);
	}
}

// Lignes projets
if ($base_ligne == 'projets') 
{
	// Si filtre sur groupe projet on supprime les projets non nécessaires
	if(count($_SESSION['filtreGroupeProjet']) > 0) {
		$listeProjets_temp=$projetsFiltre->getSmartyData();
		foreach ($listeProjets_temp as $p)
		{
			if (in_array($p['projet_id'],$_SESSION['filtreGroupeProjet']))
			{
				$listeProjets[]=$p;
			}
		}
	}else 
	{
		$listeProjets=$projetsFiltre->getSmartyData();
	}
	// liste des projets à partir des périodes remontées
	foreach ($listeProjets as $infosJour) {
		if ($user->checkDroit('projects_manage_all'))
		{
			$url="xajax_modifProjet('".urlencode($infosJour['projet_id'])."')";
		}elseif ($user->checkDroit('projects_manage_own') and ($user->user_id==$infosJour['projet_createur_id']))
		{
			$url="xajax_modifProjet('".urlencode($infosJour['projet_id'])."')";
		}else $url="";
		$planning['lignes'][$infosJour['projet_id']]=array('id'=>$infosJour['projet_id'],'nom'=>$infosJour['projet_nom'],'couleur'=>$infosJour['projet_couleur'],'groupe_nom'=>$infosJour['groupe_nom'],'url_modif'=>$url);
	}
	if ($_SESSION['triPlanning']=="nom asc") array_sort_by_column($planning['lignes'],"nom",SORT_ASC);
	if ($_SESSION['triPlanning']=="nom desc") array_sort_by_column($planning['lignes'],"nom",SORT_DESC);	
}

// Ligne lieux
if ($base_ligne == 'lieux') 
{
	// liste des lieux à partir des périodes remontées
	while ($p = $periodes->fetch()) {
		$infosJour = $p->getSmartyData();
		// On force les valeurs nulles
		if (empty($infosJour['lieu_nom'])) $infosJour['lieu_nom']=$smarty->getConfigVars('sans_lieux');
		$planning['lignes'][$infosJour['lieu_id']]=array('id'=>$infosJour['lieu_id'],'nom'=>$infosJour['lieu_nom'],'couleur'=>null,'url_modif'=>"xajax_modifLieu('".urlencode($infosJour['lieu_id'])."')");
	}
	if ($_SESSION['triPlanning']=="nom asc") array_sort_by_column($planning['lignes'],"nom",SORT_ASC);
	if ($_SESSION['triPlanning']=="nom desc") array_sort_by_column($planning['lignes'],"nom",SORT_DESC);
}

// Ligne ressources
if ($base_ligne == 'ressources')
{	
	// liste des ressources à partir des périodes remontées
	while ($p = $periodes->fetch()) {
		$infosJour = $p->getSmartyData();
		// On force les valeurs nulles
		if (empty($infosJour['ressource_nom'])) $infosJour['ressource_nom']=$smarty->getConfigVars('sans_ressources');
		$planning['lignes'][$infosJour['ressource_id']]=array('id'=>$infosJour['ressource_id'],'nom'=>$infosJour['ressource_nom'],'couleur'=>null,'url_modif'=>"xajax_modifRessource('".urlencode($infosJour['ressource_id'])."')");
	}
	if ($_SESSION['triPlanning']=="nom asc") array_sort_by_column($planning['lignes'],"nom",SORT_ASC);
	if ($_SESSION['triPlanning']=="nom desc") array_sort_by_column($planning['lignes'],"nom",SORT_DESC);
}

// Ligne heures
if ($base_ligne == 'heures') 
{
	foreach ($planning['heures'] as $heure)
	{
		$planning['lignes'][$heure]=array('id'=>$heure,'nom'=>$heure,'couleur'=>null,'url_modif'=>null);
	}
}	

//////////////////////////
// CREATION DU TABLEAU PERIODE
//////////////////////////
$totalParJour = array();
$totauxJourUsers = array();

// Parcours de l'ensemble des périodes pour en définir les lignes et les cases remplies
while ($p = $periodes->fetch()) {
	$infosJour = $p->getSmartyData();
	$dateDebut_planning = new DateTime();
	$dateDebut_planning->setDate(substr($p->date_debut,0,4), substr($p->date_debut,5,2), substr($p->date_debut,8,2));
	$dateFin_planning = new DateTime();
	$tmpDate = clone $dateDebut_planning;
	if (is_null($p->date_fin)) {
		$dateFin_planning = clone $dateDebut_planning;
	}
	else {
		$dateFin_planning->setDate(substr($p->date_fin,0,4), substr($p->date_fin,5,2), substr($p->date_fin,8,2));
	}
		
	// liste des users du planning
	if (!in_array($infosJour['user_id'],$planning['users']))
	{
		$planning['users'][]=$infosJour['user_id'];
	}
	// liste des projets du planning
	if (!in_array($infosJour['projet_id'],$planning['projets']))
	{
		$planning['projets'][]=$infosJour['projet_id'];
	}
	// liste des lieux du planning
	if (!in_array($infosJour['lieu_id'],$planning['lieux']))
	{
		$planning['lieux'][]=$infosJour['lieu_id'];
	}
	// liste des ressources du planning
	if (!in_array($infosJour['ressource_id'],$planning['ressources']))
	{
		$planning['ressources'][]=$infosJour['ressource_id'];
	}
	// liste des tâches du planning
	if (!in_array($infosJour['periode_id'],$planning['periodes']))
	{
		// Calcul de la durée en heure
		$dureeHeures=0;
		$heureDebut=convertHourToDecimal($planning['heures'][0]);			
		$heureFin=convertHourToDecimal(end($planning['heures']));
		if (empty($infosJour['duree_details'])||($infosJour['duree_details']=="duree"))
		{
			$heureDebutTxt=$planning['heures'][0];			
			$heureFinTxt=end($planning['heures']);			
			$heureDebut=convertHourToDecimal($heureDebutTxt);			
			$heureFin=convertHourToDecimal($heureFinTxt);	
			if (empty($infosJour['duree']))
			{
				$dureeHeures=calcul_duree_heures_non_masquees($heureDebut,$heureFin);
			}else $dureeHeures=convertHourToDecimal($infosJour['duree']);
		}elseif ($infosJour['duree_details']=='AM')
		{
			$dureeAM=convertHourToDecimal(CONFIG_DURATION_AM);
			$heureDebutTxt=$planning['heures'][0];			
			$heureDebut=convertHourToDecimal($planning['heures'][0]);
			$heureFin=$heureDebut + $dureeAM;
			$dureeHeures=calcul_duree_heures_non_masquees($heureDebut,$heureFin);
		}elseif ($infosJour['duree_details']=='PM')
		{
			$dureePM=convertHourToDecimal(CONFIG_DURATION_PM);
			$heureFin=convertHourToDecimal(end($planning['heures']));			
			$heureDebut=$heureFin-$dureePM;
			$heureDebutTxt=$heureDebut;
			$dureeHeures=calcul_duree_heures_non_masquees($heureDebut,$heureFin);
		}else 
		{
			$heureExploded=explode(';',$infosJour['duree_details']);
			$heureDebut=convertHourToDecimal($heureExploded[0]);
			$heureFin=convertHourToDecimal($heureExploded[1]);
			$heureDebutTxt=$heureExploded[0];
			$heureFinTxt=$heureExploded[1];
			$dureeHeures=calcul_duree_heures_non_masquees($heureDebut,$heureFin);
		}
		// Calcule des créneaux masqués
		
		$cellule=array(
			'id'=>$infosJour['periode_id'],
			'date_debut'=>$infosJour['date_debut'],
			'date_fin'=>$infosJour['date_fin'],
			'user_nom'=>$infosJour['user_nom'],
			'team_nom'=>$infosJour['team_nom'],
			'projet_nom'=>$infosJour['projet_nom'],
			'notes'=>$infosJour['notes'],
			'titre'=>$infosJour['titre'],
			'periode_id'=>$infosJour['periode_id'],
			'parent_id'=>$infosJour['parent_id'],
			'projet_id'=>$infosJour['projet_id'],
			'groupe_nom'=>$infosJour['groupe_nom'],
			'charge'=>$infosJour['charge'],
			'user_id'=>$infosJour['user_id'],
			'lieu_id'=>$infosJour['lieu_id'],		
			'ressource_id'=>$infosJour['ressource_id'],			
			'livrable'=>$infosJour['livrable'],
			'statut_nom'=>$infosJour['status_nom'],
			'statut_tache'=>$infosJour['statut_tache'],
			'statut_couleur'=>$infosJour['statut_couleur'],
			'statut_barre'=>$infosJour['statut_barre'],	
			'statut_gras'=>$infosJour['statut_gras'],	
			'statut_italique'=>$infosJour['statut_italique'],	
			'statut_souligne'=>$infosJour['statut_souligne'],	
			'statut_pourcentage'=>$infosJour['statut_pourcentage'],			
			'status'=>$infosJour['status_nom'],
			'livrable'=>$infosJour['livrable'],
			'custom'=>$infosJour['custom'],
			'lieu'=>$infosJour['lieu_id'],
			'ressource'=>$infosJour['ressource_id'],
			'lieu_nom'=>$infosJour['lieu_nom'],
			'ressource_nom'=>$infosJour['ressource_nom'],
			'lien'=>$infosJour['lien'],
			'duree'=>$infosJour['duree'],
			'createur_id'=>$infosJour['createur_id'],
			'nom_modifier'=>$infosJour['nom_modifier'],
			'nom_createur'=>$infosJour['nom_createur'],
			'projet_createur_id'=>$infosJour['projet_createur_id'],
			'date_creation'=>$infosJour['date_creation'],
			'duree_details'=>$infosJour['duree_details'],
			'date_modif'=>$infosJour['date_modif'],
			'couleur'=>$infosJour['projet_couleur'],
			'user_couleur'=>$infosJour['user_couleur'],
			'projet_couleur'=>$infosJour['projet_couleur'],
			'dureeHeures'=>$dureeHeures,
			'price'=>$infosJour['price'],
			'if_res'=>$infosJour['if_res'],
			'if_tec'=>$infosJour['if_tec'],
			'if_date'=>$infosJour['if_date'],
			'test_manager'=>$infosJour['projet_manager'],
			'reserva_HP'=>$infosJour['reserva_HP'],
			'sample'=>$infosJour['sample'],
			'client'=>$infosJour['client']);
		if( $base_ligne=='projets') {
			$cellule['nom_cellule']=xss_protect($infosJour['user_id']);
			$cellule['couleur']=xss_protect($infosJour['user_couleur']);
			$type_cellule=CONFIG_PLANNING_TEXTE_TACHES_PROJET;
		}
		if( $base_ligne=='users') {
			$cellule['nom_cellule']=xss_protect($infosJour['projet_id']);
			$cellule['couleur']=xss_protect($infosJour['projet_couleur']);
			$type_cellule=CONFIG_PLANNING_TEXTE_TACHES_PERSONNE;
		}
		switch($type_cellule)
		{
			case 'code_projet': $cellule['nom_cellule']=xss_protect($infosJour['projet_id']);break;
			case 'code_personne': $cellule['nom_cellule']=xss_protect($infosJour['user_id']);break;
			case 'code_lieu': $cellule['nom_cellule']=xss_protect($infosJour['lieu_id']);break;
			case 'code_ressource': $cellule['nom_cellule']=xss_protect($infosJour['ressource_id']);break;
			case 'nom_projet': $cellule['nom_cellule']=xss_protect($infosJour['projet_nom']);break;
			case 'nom_personne': $cellule['nom_cellule']=xss_protect($infosJour['user_nom']);break;
			case 'nom_lieu': $cellule['nom_cellule']=xss_protect($infosJour['lieu_nom']);break;
			case 'nom_ressource': $cellule['nom_cellule']=xss_protect($infosJour['ressource_nom']);break;
			case 'nom_tache': $cellule['nom_cellule']=xss_protect($infosJour['titre']);break;
			case 'vide': $cellule['nom_cellule']=xss_protect(" ");break;
		}

		if (isset($infosJour['duree_details_heure_debut']))
		{
			$cellule['duree_details_heure_debut']=$infosJour['duree_details_heure_debut'];
		}
		if (isset($infosJour['duree_details_heure_fin']))
		{
			$cellule['duree_details_heure_fin']=$infosJour['duree_details_heure_fin'];
		}
		$planning['periodes'][$infosJour['periode_id']]=$cellule;
	}
	
	// Mode colonne jour
	// traitement de chaque jour (construction du planning en mode jours)
	if ($base_colonne=='jours')
	{
		while ($tmpDate <= $dateFin_planning) {
			$cle=$tmpDate->format('Y-m-d');
			// tâches par user et jour
			if ($base_ligne=='users') 
				$planning['taches'][$infosJour['user_id']][$cle][]=$infosJour;

			// tâches par projet et jour
			if ($base_ligne=='projets')
				$planning['taches'][$infosJour['projet_id']][$cle][]=$infosJour;
		
			// calcul des totaux jours
			if(!isset($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')])) {
				$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = '00:00';
			}
			if($infosJour['date_fin'] != '') {
				$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = ajouterDuree($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_DAY, false));
			} else {
				if ($infosJour['duree_details']=="AM")
				{
					$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = ajouterDuree($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_AM, false));
				}elseif ($infosJour['duree_details']=="PM")
				{
					$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = ajouterDuree($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_PM, false));
				}else
				{
					$totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')] = ajouterDuree($totauxJourUsers[$infosJour['user_id']][$tmpDate->format('Ymd')], usertime2sqltime($infosJour['duree'], false));
				}
			}

			if (!in_array($tmpDate->format('w'), $DAYS_INCLUDED) || array_key_exists($tmpDate->format('Y-m-d'), $joursFeries)) {$weekend=true;}else $weekend=false;
			
			// on additionne le total des jours
			if (CONFIG_PLANNING_HIDE_WEEKEND_TASK == 1 || (CONFIG_PLANNING_HIDE_WEEKEND_TASK == 0 && $weekend==false))
			{
				if(!isset($totalParJour[$tmpDate->format('Ymd')])) {
				$totalParJour[$tmpDate->format('Ymd')] = '00:00';
				}
				if($infosJour['date_fin'] != '') {
					$totalParJour[$tmpDate->format('Ymd')] = ajouterDuree($totalParJour[$tmpDate->format('Ymd')], usertime2sqltime(CONFIG_DURATION_DAY, false));
				} else {
				$totalParJour[$tmpDate->format('Ymd')] = ajouterDuree($totalParJour[$tmpDate->format('Ymd')], usertime2sqltime($infosJour['duree'], false));
				}
			}

		// boucle sur les jours
		$tmpDate->modify('+1 day');
		}
	}
}

//////////////////////////
// CALCUL DU PARALLELISME DES TACHES
//////////////////////////
if (isset($planning['taches_horaires']))
{
	foreach($planning['taches_horaires'] as $creneau)
	{
		foreach ($creneau as $userk=>$tab)
		{
			if (isset($max[$userk]))
			{
				$max[$userk]['largeur']=max($max[$userk]['largeur'],$tab['largeur']);
			}else 
			{
				$max[$userk]['largeur']=$tab['largeur'];
			}
		}
	}
	foreach($planning['taches_horaires_users'] as $u=>$creneaux)
	{
		$max_largeur=0;
		foreach ($creneaux as $c)
		{
			$padding=0;
			foreach ($c as $p)
			{
				// Récupération des infos sur la cellule
				$infos_periode=$planning['periodes'][$p];
				$largeur_cellule=strlen($infos_periode['nom_cellule'])*3+25;
					
				// On selectionne la plus grande largeur réservée
				if (isset($max_p[$p]['largeur2']))
				{
					$max_largeur_cellule=max($largeur_cellule,$max_p[$p]['largeur2']);
				}else $max_largeur_cellule=$largeur_cellule;
					
				if (isset($max_p[$p]['largeur2']))
				{
					$max_p[$p]['largeur2']=$max_largeur_cellule;
				}else $max_p[$p]['largeur2']=$largeur_cellule;
					
				$padding=$padding+$max_p[$p]['largeur2'];
			}
			if (isset($max[$u]['largeur']))
			{
				$max[$u]['largeur']=max($max[$u]['largeur'],$padding);
			}else $max[$u]['largeur']=$padding;
		}
	}
}
			
//////////////////////////
// ENTETES DU PLANNING
//////////////////////////
// Colonnes jour
if ($base_colonne=='jours')
{
	$headerMois = '' . CRLF;
	$headerSemaines = '' . CRLF;
	$headerNomJours = '' . CRLF;
	$headerNumeroJours = '' . CRLF;
	$headerNomNumeroJours = '' . CRLF;
	$colspanMois = '0';
	$colspanSemaine = '1';
	$tmpDate = clone $dateDebut;
	$tmpMois = $smarty->getConfigVars('month_' . $tmpDate->format('n')) . ' ' . $tmpDate->format('Y');
	$tmpMoisDateDebut = $tmpDate->format(CONFIG_DATE_FIRST_DAY_MONTH);
	$tmp2Date = clone $tmpDate;
	$tmp2Date->modify('+' . $nbJours . 'days');
	$tmpMoisDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
	while ($tmpDate <= $dateFin) {
		$planning['colonnes'][]=$tmpDate->format('Y-m-d');
		if (in_array($tmpDate->format('w'), $DAYS_INCLUDED) && !array_key_exists($tmpDate->format('Y-m-d'), $joursFeries)) {
			$sClass = 'week';
			$weekend = false;
		} else {
			if (CONFIG_PLANNING_DIFFERENCIE_WEEKEND == 1)
			{
				$sClass = 'weekend';
				$weekend = true;
			}else
			{
				$sClass = 'week';
				$weekend = true;
			}
		}
		if( $tmpDate->format('Y-m-d') == date('Y-m-d')) {
			$sClass .= ' today';
		}
		$tmpJourDateDebut = $tmpDate->format(CONFIG_DATE_LONG);
		$tmp2Date = clone $tmpDate;
		$tmp2Date->modify('+' . $nbJours . 'days');
		$tmpJourDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
		$headerNomJours .= '<th class="planning_head_dayname ' . $sClass . '"><div><a href="process/planning.php?date_debut_affiche='.$tmpJourDateDebut.'&date_fin_affiche='.$tmpJourDateFin.'">' . strtoupper(substr($smarty->getConfigVars('day_' . $tmpDate->format('w')), 0, 1)) . '</a></div></th>' . CRLF;
		$headerNumeroJours .= '<th class="planning_head_day ' . $sClass . '"><a href="process/planning.php?date_debut_affiche='.$tmpJourDateDebut.'&date_fin_affiche='.$tmpJourDateFin.'">' . $tmpDate->format('j') . '</a></th>' . CRLF;
		$headerNomNumeroJours .='<th class="planning_head_day ' . $sClass . '"><div><a href="process/planning.php?date_debut_affiche='.$tmpJourDateDebut.'&date_fin_affiche='.$tmpJourDateFin.'">' . strtoupper(substr($smarty->getConfigVars('day_' . $tmpDate->format('w')), 0, 1)) . '</a><a>-</a><a href="process/planning.php?date_debut_affiche='.$tmpJourDateDebut.'&date_fin_affiche='.$tmpJourDateFin.'">' . $tmpDate->format('j/m/Y') . '</a></div></th>';
		$nomMoisCourant = $smarty->getConfigVars('month_' . $tmpDate->format('n'));
		if ($nomMoisCourant . ' ' . $tmpDate->format('Y') == $tmpMois) {
			$colspanMois++;
		} else {
			$headerMois .= '<th class="planning_head_month" colspan="' . $colspanMois . '"><a href="process/planning.php?date_debut_affiche='.$tmpMoisDateDebut.'&date_fin_affiche='.$tmpMoisDateFin.'">' . $tmpMois . '</a></th>' . CRLF;
			$colspanMois = '1';
			$tmpMois = $nomMoisCourant . ' ' . $tmpDate->format('Y');
			$tmpMoisDateDebut = $tmpDate->format(CONFIG_DATE_FIRST_DAY_MONTH);
			$tmp2Date = clone $tmpDate;
			$tmp2Date->modify('+' . $nbJours . 'days');
			$tmpMoisDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
		}
		// gestion des semaines
		if ($tmpDate->format('w') == 0) {
			// calcul du date de debut et fin de semaine
			$dateTime = strtotime( $tmpDate->format('d-m-Y'));
			$tmpSemaineDateDebut = date(CONFIG_DATE_LONG, strtotime('monday this week', $dateTime));
			$tmp2Date = clone $tmpDate;
			$tmp2Date->modify('+' . $nbJours . 'days');
			$tmpSemaineDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
			$headerSemaines .= '<th class="planning_head_week" colspan="' . $colspanSemaine . '"><a href="process/planning.php?date_debut_affiche='.$tmpSemaineDateDebut.'&date_fin_affiche='.$tmpSemaineDateFin.'">' . $smarty->getConfigVars('planning_semaine') . ' ' . $tmpDate->format('W') . '</a></th>' . CRLF;
			$colspanSemaine = 1;
		} else {
			$colspanSemaine++;
		}
		$tmpDate->modify('+1 day');
	}
	// on cloture le colspan du mois en cours
	$headerMois .= '<th class="planning_head_month" colspan="' . $colspanMois . '"><a href="process/planning.php?date_debut_affiche='.$tmpMoisDateDebut.'&date_fin_affiche='.$tmpMoisDateFin.'">' . $tmpMois . '</a></th>' . CRLF;
	// on cloture le colspan de la semaine en cours
	if($colspanSemaine != 1) {
		// calcul du date de debut et fin de semaine
		$dateTime = strtotime( $tmpDate->format('d-m-Y'));
		$tmpSemaineDateDebut = date(CONFIG_DATE_LONG, strtotime('this week last monday', $dateTime));
		$tmp2Date = clone $tmpDate;
		$tmp2Date->modify('+' . $nbJours . 'days');
		$tmpSemaineDateFin = $tmp2Date->format(CONFIG_DATE_LONG);
		$headerSemaines .= '<th class="planning_head_week" colspan="' . ($colspanSemaine-1) . '"><a href="process/planning.php?date_debut_affiche='.$tmpSemaineDateDebut.'&date_fin_affiche='.$tmpSemaineDateFin.'">' . $smarty->getConfigVars('planning_semaine') .	' ' . $tmpDate->format('W') . '</a></th>' . CRLF;
	}
	if($dimensionCase=='reduit'){
		$html .= '<table class="planningContent" id="tabContenuPlanning">' . CRLF;
		$html .= '<thead><tr>' . CRLF;
		$html .= '<th id="tdUser_0" rowspan="4" class="planning_switch planningFirstRowCol"><div class="text-center"><a id="lienInverse" href="'.$linkswitch.'"><i class="fa fa-exchange fa-3x fa-lg" aria-hidden="true" style="color:white;"></i></a></div></th>' .CRLF;
		$html .= $headerMois . CRLF;
		$html .= '</tr>' . CRLF;
		$html .= '<tr>' . CRLF;
		$html .= $headerSemaines . CRLF;
		$html .= '</tr>' . CRLF;
		$html .= '<tr>' . CRLF;
		$html .= $headerNomJours . CRLF;
		$html .= '</tr>' . CRLF;
		$html .= '<tr>' . CRLF;
		$html .= $headerNumeroJours . CRLF;
		$html .= '</tr></thead><tbody>' . CRLF;
	}
	else{
		$html .= '<table class="planningContent" id="tabContenuPlanning">' . CRLF;
		$html .= '<thead><tr>' . CRLF;
		$html .= '<th id="tdUser_0" rowspan="4" class="planning_switch planningFirstRowCol"><div class="text-center"><a id="lienInverse" href="'.$linkswitch.'"><i class="fa fa-exchange fa-3x fa-lg" aria-hidden="true" style="color:white;"></i></a></div></th>' .CRLF;
		$html .= $headerSemaines . CRLF;
		$html .= '</tr>' . CRLF;
		$html .= $headerNomNumeroJours . CRLF;
		$html .= '</tr></thead><tbody>' . CRLF;
		// FIN ENTETES DU TABLEAU (MOIS, SEMAINE ET JOUR)	
	}	
}

//////////////////////////
// AFFICHAGE DES LIGNES
//////////////////////////
$nbLine = 1;
$groupeCourant = false;
$idGroupeCourant = -1;
$smarty->assign('nbPagesLignes', ceil($nbLignesTotal/$nbLignes));

foreach ($planning['lignes'] as $ligne)
{

	// every xx lines, repeat days/month/etc rows
	if(CONFIG_PLANNING_REPEAT_HEADER > 0) {
		if (($nbLine % CONFIG_PLANNING_REPEAT_HEADER) == 0) {
			$html .= '<tr>' . CRLF;
			$html .= '<th>&nbsp;</th>' . CRLF;
			$html .= $headerMois . CRLF;
			$html .= '</tr>' . CRLF;
			$html .= '<tr>' . CRLF;
			$html .= '<th>&nbsp;</th>' . CRLF;
			$html .= $headerSemaines . CRLF;
			$html .= '</tr>' . CRLF;
			$html .= '<tr>' . CRLF;
			$html .= '<th>&nbsp;</th>' . CRLF;
			$html .= $headerNomJours . CRLF;
			$html .= '</tr>' . CRLF;
			$html .= '<tr>' . CRLF;
			$html .= '<th>&nbsp;</th>' . CRLF;
			$html .= $headerNumeroJours . CRLF;
			$html .= '</tr>' . CRLF;
		}
	}
	$nbLine++;
	
	// gestion de l'affichage des groupes (de user ou projet) dans le planning
	if(strpos($_SESSION['triPlanning'], 'groupe_nom') !== FALSE || strpos($_SESSION['triPlanning'], 'team_nom') !== FALSE) {
		if($base_ligne=="projets") 
		{
			if($ligne['groupe_nom'] !== $groupeCourant) 
			{
				$html .= '<tr>' . CRLF;
				$html .= '<td class="planning_team_div" id="tdUser_' . $idGroupeCourant . '">&nbsp;' . ($ligne['groupe_nom'] != '' ? xss_protect($ligne['groupe_nom']) : $smarty->getConfigVars('planning_pasDeGroupe')) . '&nbsp;' . CRLF;
				$html .= '</td>' . CRLF;

				foreach ($planning['colonnes'] as $cle_colonne) 
				{
					$html .= '<td class="planning_team_div"></td>' . CRLF;
				}

				$html .= '</tr>' . CRLF;
				$idGroupeCourant--;
			}
			$groupeCourant = $ligne['groupe_nom'];
		} elseif($base_ligne=="users") {
			if($ligne['team_nom'] !== $groupeCourant) {
				$html .= '<tr>' . CRLF;
				$html .= '<td class="planning_team_div" id="tdUser_' . $idGroupeCourant . '">&nbsp;' . ($ligne['team_nom'] != '' ? xss_protect($ligne['team_nom']) : $smarty->getConfigVars('planning_pasDeTeam')) . '&nbsp;' . CRLF;
				$html .= '</td>' . CRLF;

				foreach ($planning['colonnes'] as $cle_colonne) 
				{
					$html .= '<td class="planning_team_div"></td>' . CRLF;
				}

				$html .= '</tr>' . CRLF;
				$idGroupeCourant--;
			}
			$groupeCourant = $ligne['team_nom'];
		}
	}
	$ordreJourPrec = array();
	$joursOccupes = array();
	
	// pour chaque période de cette ligne, on rempli le tableau des jours occupés
	$infosJour['nom'] = xss_protect($ligne['nom']);

	// Calcul de l'id de la ligne
	$ligneId=$ligne['id'];
	
	// Calcul des jours occupés
	if (isset($planning['taches'][$ligne['id']]))
	{
		foreach ($planning['taches'][$ligne['id']] as $cle => $tache) 
		{
			foreach ($tache as $t)
			{
				$info_tache=$planning['periodes'][$t];
				$joursOccupes[$cle][]=$t;
			}
		}
	}
	
	// si option de masquer les lignes vides est activée, on masque la ligne si elle est vide
	if($masquerLigneVide == 1 && count($joursOccupes) == 0 && $base_ligne<>"heures") {
		continue;
	}
	$ordreJourCourant = array();
	
	////////////////////////////////////////////////////
	// AFFICHAGE DE LA PREMIERE CASES DE CHAQUE LIGNE
	////////////////////////////////////////////////////
	// on genere la ligne courante
	$html .= '<tr>' . CRLF;
	$html .= '<td id="tdUser_' . ($nbLine-1) . '" ' . ((!is_null($ligne['couleur']) && $ligne['couleur'] != 'FFFFFF') ? ' style="background-color:#'.$ligne['couleur']. ';color:' . buttonFontColor('#' . $ligne['couleur']) . '"' : '') . ' class="planningFirstCol">&nbsp;';
	
	// si le user a le droit, on permet de cliquer pour afficher la fiche de l'item (user ou projet)
	if (!empty($ligne['url_modif']))
	{
		$html .= '<a style="color:' . (!is_null($ligne['couleur']) && $ligne['couleur'] != 'FFFFFF' ? buttonFontColor('#' . $ligne['couleur']) . '' : '#ffffff') . '"';
		$html .= ' href="javascript:'.$ligne['url_modif'].';undefined;">' . $infosJour['nom'] . '</a>';
	}else 
	{
		$html .= '<span style="color:' . (!is_null($ligne['couleur']) && $ligne['couleur'] != 'FFFFFF' ? buttonFontColor('#' . $ligne['couleur']) . '' : '#ffffff') . '"';
		$html .= '>'.$infosJour['nom'].'</span>';
	}
	$html .= '</td>' . CRLF;		
	

	////////////////////////////////////////////////////
	// AFFICHAGE DES CASES DE CHAQUE LIGNE
	////////////////////////////////////////////////////

	// on boucle sur la durée de l'affichage, on parcours tous les jours/semaines/heures
	if ($base_colonne=="jours" || $base_colonne=="users")
	{
		// Dans le cas d'affichage des jours, on boucle sur toutes les dates
		foreach ($planning['colonnes'] as $cle_colonne) 
		{
			// Planning Jour ou User
			if ($base_colonne=="jours"||$base_colonne=="users")
			{
				// Sélection de la clé
				if ($base_colonne=="jours")
				{
					$datePivot = new DateTime($cle_colonne);
					$current_date = $datePivot->format('Y-m-d');
					$current_date2 = $datePivot->format('Ymd');
					$current_week = $datePivot->format('w');
				}
				
				$styleTD = '';
				// Définition du style pour case semaine et WE
				//cocacolita
				if($dimensionCase=='large'){
					
				if ((!in_array($current_week, $DAYS_INCLUDED) || array_key_exists($current_date, $joursFeries)) && $base_colonne<>"users") 
				{
					
					if (array_key_exists($current_date, $joursFeries))
					{
						if (empty($joursFeries[$current_date]['couleur']))
						{
							$classTD = 'weekend';
						}else $styleTD = " style='background-color:#".$joursFeries[$current_date]['couleur']."' ";
						$weekend = true;
					}elseif (CONFIG_PLANNING_DIFFERENCIE_WEEKEND == 1)
					{
						$classTD = 'weekend';
						$weekend = true;
					}else
					{
						$classTD = 'week';
						$weekend = true;
					}
				} else {
					$classTD = 'week';
					$weekend = false;
				}
				
				}
				else{
				   if ((!in_array($current_week, $DAYS_INCLUDED) || array_key_exists($current_date, $joursFeries)) && $base_colonne<>"users") 
				   {
					if (array_key_exists($current_date, $joursFeries))
					{
						if (empty($joursFeries[$current_date]['couleur']))
						{
							$classTD = 'weekend';
						}else $styleTD = " style='background-color:#".$joursFeries[$current_date]['couleur']."' ";
						$weekend = true;
					}elseif (CONFIG_PLANNING_DIFFERENCIE_WEEKEND == 1)
					{
						$classTD = 'weekend';
						$weekend = true;
					}else
					{
						$classTD = 'weekcons';
						$weekend = true;
					}
				} else {
					$classTD = 'weekcons';
					$weekend = false;
				}
				}

				// Si la date est un jour férié
				$ferie = false;
				if (array_key_exists($current_date, $joursFeries)) 
				{
					$ferieObj = new Ferie();
					if($ferieObj->db_load(array('date_ferie', '=', $current_date)) && trim($ferieObj->libelle) != "") 
					{
						if (CONFIG_PLANNING_MASQUER_FERIES == 0)
						{
							$tooltip = '<b>' . $ferieObj->libelle . '</b>';
							$ferie = '<div class=" tooltipster" title="'.$tooltip.'"></div>' . CRLF;
						}
					}
				}
				
				$largeuritems=8;
				// Si la date contient une tâche (jour avec au moins une case remplie)
				if (isset($joursOccupes[$current_date])) 
				{
					// Affichage de la case
					$html .= '<td ' . ' id="td_' . $ligneId . '_' . $current_date2 . '"';
					if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) 
					{
						$droitAjoutPeriode = true;
					}else {
						$droitAjoutPeriode = false;
					}
					$html .= ' '. $styleTD. ' class="' . $classTD . (($current_date == date('Y-m-d')) ? ' today' : '') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">' . CRLF;

					// Si férié, on affiche l'objet férié
					if($ferie !== false) 
					{
						$html .= $ferie;
					}

					$niveauCourant = 0;
					$nbitems=0;
					$html_aux = '';
					$periodes_days = array();
					
					
					// Affichage de toutes les cellules (boucle)
					foreach ($joursOccupes[$current_date] as $j) 
					{
						$b=0;
						if($j['projet_id'] == 'timetable')
						{
							foreach($joursOccupes[$current_date] as $k){
								if($j['periode_id'] != $k['periode_id'] && $j['duree_details'] == $k['duree_details']) {
									$b=1;
									break 1;
								}
							}
						}
						if($b==0) $periodes_days[] = $j;
					}
					
					foreach($periodes_days as $j)
					{
						$jour=$planning['periodes'][$j['periode_id']];
						$nbitems++;
						$ordreJourCourant[] = $jour['periode_id'];
						$niveauCourant++;
						// Génération du tooltip
						$jour['tooltip']=create_tooltip($jour);
						$jour['location']='planning';
						// Génération de la cell projet
						$html_aux.=createCellProject($jour);
					}
					$ordreJourPrec = $ordreJourCourant;
					$ordreJourCourant = array();
					
					//solo tareas de mañanas
					if(strpos($html_aux, '_AM') && !strpos($html_aux, '_PM') && !strpos($html_aux, '_N') && !strpos($html_aux, '_duree"') && !strpos($html_aux, '_duree_cons"')) {
						$html.= $html_aux;
						$html.= '<div class="cellLine"></div>';
						$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_PM" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
						$html.='</div>';
						$html.= '<div class="cellLine"></div>';
						$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_N" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
						$html.='</div>';
					} 
					
					//solo tareas de tardes
					elseif (!strpos($html_aux, '_AM') && strpos($html_aux, '_PM') && !strpos($html_aux, '_N') && !strpos($html_aux, '_duree"') && !strpos($html_aux, '_duree_cons"')) {
						$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_AM" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
						$html.='</div>';
						$html.= '<div class="cellLine"></div>';
						$html.= str_replace("cellProjectPM","cellProjectAM",$html_aux);
						$html.= '<div class="cellLine"></div>';
						$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_N" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';	
						$html.='</div>';
					} 
					
					//solo tareas de noches
					elseif (!strpos($html_aux, '_AM') && !strpos($html_aux, '_PM') && strpos($html_aux, '_N') && !strpos($html_aux, '_duree"') && !strpos($html_aux, '_duree_cons"')) {
						$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_AM" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
						$html.='</div>';
						$html.= '<div class="cellLine"></div>';
						$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_PM" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
						$html.='</div>';
						$html.= '<div class="cellLine"></div>';
						$html.= str_replace("cellProjectN","cellProjectAM",$html_aux);
					} 
					
					//solo tareas de tardes y noches
					elseif (!strpos($html_aux, '_AM') && strpos($html_aux, '_PM') && strpos($html_aux, '_N') && !strpos($html_aux, '_duree"') && !strpos($html_aux, '_duree_cons"')) {
						$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_AM" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
						$html.='</div>';
						$html.= '<div class="cellLine"></div>';
						$html_aux= str_replace("cellProjectPM","cellProjectAM",$html_aux);
						$html.= str_replace("cellProjectN","cellProjectAM",$html_aux);
					} 
					
					//solo tareas de mañanas y tardes
					elseif (strpos($html_aux, '_AM') && strpos($html_aux, '_PM') && !strpos($html_aux, '_N') && !strpos($html_aux, '_duree"') && !strpos($html_aux, '_duree_cons"')) {
						$html.= str_replace("cellProjectPM","cellProjectAM",$html_aux);
						$html.= '<div class="cellLine"></div>';
						$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_N" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
						$html.='</div>';
					} 
					
					//solo tareas de mañanas y noches
					elseif (strpos($html_aux, '_AM') && !strpos($html_aux, '_PM') && strpos($html_aux, '_N') && !strpos($html_aux, '_duree"') && !strpos($html_aux, '_duree_cons"')) {
						$html_aux= str_replace('<div id="td_' . $ligneId . '_' . $current_date2 . '_N','<div id="td_' . $ligneId . '_' . $current_date2 . '_PM" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);"></div><div id="td_' . $ligneId . '_' . $current_date2 . '_N',$html_aux);
						$html.= str_replace("cellProjectN","cellProjectAM",$html_aux);
					} 
					
					//altrament
					else {
						$html_aux= str_replace("cellProjectPM","cellProjectAM",$html_aux);
						$html.= str_replace("cellProjectN","cellProjectAM",$html_aux);
					}

					// Espace vide pour permettre de cliquer en dessous d'une case assignée
					$html.= '<div class="cellLine"></div>';
					$html.= '<div class="cellEmpty"></div>';
					$html .= '</td>' . CRLF;
				} else 
				{
					// Cas d'un jour vide
					$html .= '<td ' . ' id="td_' . $ligneId . '_' . $current_date2 . '"';
					if($user->checkDroit('tasks_modify_all') || $user->checkDroit('tasks_modify_own_project') || $user->checkDroit('tasks_modify_own_task')) 
					{
						$droitAjoutPeriode = true;
					} else 
					{
						$droitAjoutPeriode = false;
					}
					$html .= ' '. $styleTD. ' class="' . $classTD . (($current_date == date('Y-m-d')) ? ' today' : '') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
					if($ferie !== false) 
					{
						$html .= $ferie;
					} else 
					{
						//$html .= '&nbsp;';
						$html .= '';
					}
					$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_' . 'AM" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
					$html.='</div>';
					$html.= '<div class="cellLine"></div>';
					$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_PM" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
					$html.='</div>';
					$html.= '<div class="cellLine"></div>';
					$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . '_N" '. $styleTD. ' class="cellTask' . ($dimensionCase == 'large' ? '' : 'cons') . '" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);">';
					$html.='</div>';
					$html.= '<div class="cellLine"></div>';
					$html.= '<div id="td_' . $ligneId . '_' . $current_date2 . ' '. $styleTD. ' class="cellTask" ondrop="drop(event)" ondragover="allowDrop(event)" ondragleave="leaveDropZone(event);"></div>';
					$html .= '</td>' . CRLF;
				}
			}
		}
	}
	$html .= '</tr>' . CRLF;
}
	////////////////////////////////////////////////////
	// AFFICHAGE DES TOTAUX DE LIGNES
	////////////////////////////////////////////////////
if($afficherLigneTotal == 1) {
	
	// Affichage du libellé
	$html .= '<tr><td id="tdTotal">' . $smarty->getConfigVars('tab_totalJour') . '</td>' .CRLF;
	if ($base_ligne=='heures')
	{
		$html .= '<td id="tdTotal2"></td>' .CRLF;
	}
	
	// on boucle sur la durée de l'affichage
	if ($base_colonne<>"heures")
	{
		foreach ($planning['colonnes'] as $cle_colonne) 
		{
			if ($base_colonne=="jours")
			{
				$datePivot = new DateTime($cle_colonne);
				$current_date = $datePivot->format('Y-m-d');
				$current_date2 = $datePivot->format('Ymd');
				$current_week = $datePivot->format('w');
			}
			if ($base_colonne=="users")
			{
				$datePivot = clone $dateDebut;
				$current_date = $cle_colonne;
				$current_date2 = $cle_colonne;
				$current_week = $cle_colonne;
			}
			if ($base_colonne=="heures")
			{
				$datePivot = new DateTime($cle_colonne);
				$current_date = $datePivot->format('Y-m-d');
				$current_date2 = $datePivot->format('Ymd');
				$current_week = $datePivot->format('w');
			}
		
			// définit le style pour case semaine et WE
			$styleTD='';
			if (!in_array($current_week, $DAYS_INCLUDED) || array_key_exists($current_date, $joursFeries)) {
				if (array_key_exists($current_date, $joursFeries))
				{
					if (empty($joursFeries[$current_date]['couleur']))
					{
						$classTD = 'weekend';
					}else $styleTD = " style='background-color:#".$joursFeries[$current_date]['couleur']."' ";
					$weekend = true;
				}elseif (CONFIG_PLANNING_DIFFERENCIE_WEEKEND == 1)
				{
					$classTD = 'weekend';
					$weekend = true;
				}else
				{
					$classTD = 'week';
					$weekend = true;
				}
			} else {
				$classTD = 'week';
				$weekend = false;
			}

			if( $current_date == date('Y-m-d')) {$classTD .= ' today';}
			if(isset($totalParJour[$current_date2])) 
			{
				$capitalCharge=$nbRealUsers*convertHourToDecimal(CONFIG_DURATION_DAY);
				if($capitalCharge != 0){
					$ratioCharge=round(decimalHours($totalParJour[$current_date2])/$capitalCharge,1);
				}else{
					$ratioCharge=0;
				}
				$ratio=round($ratioCharge*10);
				if ($ratio > 10){
					$ratio=10;
				}
				if($dimensionCase=='large'){
					$symboleH1='h/';
					$symboleH2='h';
				}else{
					$symboleH1='/';
					$symboleH2='';
				}
				if($dimensionCase=='large') 
				{
					if($ratio == 0) {
						$html .= '<td '. $styleTD. ' class="' . $classTD . ' sumCell"><div class="sumLargeCell">' . $totalParJour[$current_date2];
						$html .= '</div><div class="jaugeTD"><div class="jauge0"></div></div></td>' . CRLF;
					} else{
						$html .= '<td '. $styleTD. ' class="' . $classTD . ' sumCell"><div class="sumLargeCell">' . $totalParJour[$current_date2];
						$html .= '</div><div class="jaugeTD"><div class="jauge0">';
						$html .= '<div class="jauge' . $ratio . '">';
						if ($ratio == 10) {
							$html .= '100';
						}
						$html .= '</div></div></div></td>' . CRLF;
					}
				} else
				{
					$html .= '<td '. $styleTD. ' class="' . $classTD . ' sumCell">' . $totalParJour[$current_date2];
					$html .= '</td>' . CRLF;
				}	
			} else 
			{
				$html .= '<td '. $styleTD. ' class="' . $classTD . '"></td>' . CRLF;
			}
		}
	$html .= '</tr>';
	}
}
$html .= '</tbody></table>' . CRLF;

// anchor for show/hide, move the page to be the entire project table
$html .= '<a id="anchorProjectTable"></a>';

////////////////////////////////////////////////////
// AFFICHAGE DU TABLEAU RECAPITULATIF
////////////////////////////////////////////////////
$html_recap="";
if ($_SESSION['afficherTableauRecap']=="1")
{
	include "planning_recap.php";
}

////////////////////////////////////////////////////
// PROJECTES NO PLANIFICATS
////////////////////////////////////////////////////
$consulta= "SELECT *
			FROM planning_received";
$ReceivedProjects = array();
$ReceivedProjects = new GCollection('Received');
$ReceivedProjects->db_loadSQL($consulta);
$smarty->assign('receivedProjects', $ReceivedProjects->getSmartyData());

// Assignation du tableau
$smarty->assign('htmlTableau', $html);
// Assignation du tableau récapitulatif
$smarty->assign('htmlRecap', $html_recap);
$smarty->assign('modeAffichage', $_SESSION['planningView']);
$smarty->assign('dimensionCase', $_SESSION['dimensionCase']);
$smarty->assign('baseligne', $base_ligne);
// pour savoir combien de groupes à afficher dans colonne de gauche
$smarty->assign('nbGroupes', ($idGroupeCourant+1));
$smarty->assign('droitAjoutPeriode',$droitAjoutPeriode);
$smarty->assign('xajax', $xajax->getJavascript("", "assets/js/xajax.js"));
$smarty->display('www_planning.tpl');