<?php

class Periode extends GObject
{
	protected $repetitionPossible = array('jour','mois','semaine');

	public function __construct()
	{
		$this->table = 'planning_periode';
		
		$this->fields['periode_id'] = new GInteger('planning_periode', 'periode_id', 11, FALSE, 0);
		$this->fields['parent_id'] = new GInteger('planning_periode', 'parent_id', 11, TRUE, NULL);
		$this->fields['projet_id'] = new GString('planning_periode', 'projet_id', 20, FALSE, '');
		$this->fields['user_id'] = new GString('planning_periode', 'user_id', 20, FALSE, '');
		$this->fields['date_debut'] = new GDate('planning_periode', 'date_debut', 10, FALSE, '0000-00-00');
		$this->fields['date_fin'] = new GDate('planning_periode', 'date_fin', 10, TRUE, NULL);
		$this->fields['duree'] = new Gtime('planning_periode', 'duree', 12, TRUE, NULL);
		$this->fields['duree_details'] = new GString('planning_periode', 'duree_details', 20, TRUE, NULL);
		$this->fields['titre'] = new GString('planning_periode', 'titre', 255, TRUE, NULL);
		$this->fields['notes'] = new GString('planning_periode', 'notes', 65000, TRUE, NULL);
		$this->fields['lien'] = new GString('planning_periode', 'lien', 65000, TRUE, NULL);
		$this->fields['statut_tache'] = new GString('planning_periode', 'statut_tache', 10, TRUE, NULL);
		$this->fields['lieu'] = new GString('planning_periode', 'lieu_id', 10, TRUE, NULL);
		$this->fields['ressource'] = new GString('planning_periode', 'ressource_id', 1000, TRUE, NULL);
		$this->fields['livrable'] = new GEnum('planning_periode', 'livrable', array('oui','non'), FALSE, 'non');
		$this->fields['createur_id'] = new GString('planning_periode', 'createur_id', 20, TRUE, NULL);
		$this->fields['date_creation'] = new GDatetime('planning_periode', 'date_creation', 19, TRUE, NULL);
		$this->fields['modifier_id'] = new GString('planning_periode', 'modifier_id', 20, TRUE, NULL);
		$this->fields['date_modif'] = new GDatetime('planning_periode', 'date_modif', 19, TRUE, NULL);
		$this->fields['custom'] = new GString('planning_periode', 'custom', 255, TRUE, NULL);
		$this->fields['price'] = new GFloat('planning_periode', 'price', 20, TRUE, NULL);
		$this->fields['if_res'] = new GInteger('planning_periode', 'if_res', 11, TRUE, NULL);
		$this->fields['if_tec'] = new GInteger('planning_periode', 'if_tec', 11, TRUE, NULL);
		$this->fields['if_date'] = new GInteger('planning_periode', 'if_date', 11, TRUE, NULL);
		$this->fields['sample'] = new GInteger('planning_periode', 'sample', 1, TRUE, NULL);

		$this->primaryKeys = array('periode_id');

		parent::__construct();
	}

	
	public function db_save($updateData = array(), $insertData = array())
	{
		if (!$this->saved) {
			$result = db_query('SELECT MAX(' . $this->primaryKeys[0] . ') AS max FROM ' . $this->table);
			$new = 1;
			while ($var = db_fetch_array($result)) {
				$new = $var['max'] + 1;
			}
			$this->data[$this->primaryKeys[0]] = $new;

			if(isset($_SESSION['user_id'])) {
				$this->createur_id = $_SESSION['user_id'];
				$this->date_creation = date('Y-m-d H:i:s');
			}
		} else {
			if($this->date_creation != date('Y-m-d H:i:s')) {
				$this->modifier_id = $_SESSION['user_id'];
				$this->date_modif = date('Y-m-d H:i:s');
			}
		}
		if($this->lien == CONFIG_DEFAULT_PERIOD_LINK) {
			$this->lien = NULL;
		}
		return parent::db_save($updateData, $insertData);
	}

	public function getSmartyData()
	{
		$data = parent::getSmartyData();
		$data['notes_xajax'] = str_replace(array("\r\n", "\n"), array("¤", "¤"), trim($data['notes']));
		if(strlen($this->duree_details) == 17) {
			$tmp = explode(';', $this->duree_details);
			$data['duree_details_heure_debut'] = $tmp[0];
			$data['duree_details_heure_fin'] = $tmp[1];
		}
		return $data;
	}

	public function getHeureDebutFin() {
		if(strlen($this->duree_details) == 17) {
			$tab = explode(';', $this->duree_details);
			return array('duree_details_heure_debut' => $tab[0], 'duree_details_heure_fin' => $tab[1]);
		}
		return NULL;
	}

	// repete la planif selon la période demandée : jour, semaine, mois
	// on repete la tache pendant 7 ans
	public function repeter($dateFinRepetition, $dureeDetails) {
		@set_time_limit(180);
		$DAYS_INCLUDED1 = explode(',', CONFIG_DAYS_INCLUDED);
		foreach($DAYS_INCLUDED1 as $a){$DAYS_INCLUDED[$a]=$a;}
		$joursFeries1 = getjoursFeries();
		foreach($joursFeries1 as $b){$joursFeries[$b]=$b;}

		// Creation des objets date (plus facile à manipuler)
		$tmpDate = new DateTime();
		$tmpDate->setDate(substr($this->date_debut,0,4), substr($this->date_debut,5,2), substr($this->date_debut,8,2));
		$dateFin = new DateTime();
		$dateFin->setDate(substr($dateFinRepetition,0,4), substr($dateFinRepetition,5,2), substr($dateFinRepetition,8,2)-1);
		
		// Calcul du nombre de jours
		if($dateFinRepetition > $this->date_debut){
			$nbJours = getNbJours($this->date_debut,$dateFin->format('Y-m-d'));
		}else{
			$nbJours = getNbJours($dateFin->format('Y-m-d'),$this->date_debut );
		}

		// Récupération des donnees
		$data = $this->getData();
		$idPeriode = $data['periode_id'];
		$idUser = $data['user_id'];
		$data['periode_id'] = 0;
		$data['saved'] = 0;
		$periode_tosave=array();
		
		// Si la date de fin est supérieure à la date de début
		if($dateFin > $tmpDate) {
			while ($tmpDate < $dateFin) {

				// Gestion de la répétition
				$tmpDate->modify("+1 day");
				
			
				// Enregistrement temporaire de la période trouvée
				$debut=$tmpDate->format('Y-m-d');
				if (!is_null($this->date_fin)) {
					$fin = calculerDateFin($debut, $nbJours);
				}				
				if ($this->parent_id) {
					$parent = $this->parent_id;
				} else {
					$parent = $this->periode_id;
				}
				$periode_tosave[]=array('debut'=>$debut,'fin'=>$fin,'parent'=>$parent);
				}
		}else{
			while ($dateFin < $tmpDate) {
			
				// Gestion de la répétition
				$tmpDate->modify("-1 day");
				
				// Enregistrement temporaire de la période trouvée
				$debut=$tmpDate->format('Y-m-d');
				if (!is_null($this->date_fin)) {
					$fin = calculerDateFin($debut, $nbJours);
				}				
				if ($this->parent_id) {
					$parent = $this->parent_id;
				} else {
					$parent = $this->periode_id;
				}
				$periode_tosave[]=array('debut'=>$debut,'fin'=>$fin,'parent'=>$parent);				
			}
		}

		// Post-traitement, vérification et enregistrement de toutes les périodes trouvées
		// Vérification de l'utilisation de la ressource
		if (CONFIG_SOPLANNING_OPTION_RESSOURCES == 1 && (!is_null($data['ressource'])))
		{
			$dates_erreurs=array();
			foreach ($periode_tosave as $p)
			{	if(!checkConflitRessource($data['ressource'],$p['debut'],$p['fin'],$dureeDetails,$idUser,$idPeriode))
			{
				$dates_erreurs[]=sqldate2userdate($p['debut']);
			}
			}
			if (!empty($dates_erreurs))
			{
			  $retour="";
			  $sep="";
			  foreach($dates_erreurs as $d)
			  {
			   if (!empty($retour)) $sep=", ";
			   $retour.=$sep.$d;
			  }
			 //return $retour;
			}
		}	
		// Post-traitement, vérification et enregistrement de toutes les périodes trouvées
		// Vérification de l'utilisation du lieu
		if (CONFIG_SOPLANNING_OPTION_LIEUX == 1 && (!is_null($data['lieu'])))
		{
			$dates_erreurs=array();
			foreach ($periode_tosave as $p)
			{	if(!checkConflitLieu($data['lieu'],$p['debut'],$p['fin'],$dureeDetails,$idUser,$idPeriode))
			{
				//$dates_erreurs[]=sqldate2userdate($p['debut']);
			}			
			}
			if (!empty($dates_erreurs))
			{
			  $retour="";
			  $sep="";
			  foreach($dates_erreurs as $d)
			  {
			   if (!empty($retour)) $sep=", ";
			   $retour.=$sep.$d;
			  }
			 //return $retour;
			}
		}	
		// Dédoublonnage de la liste des périodes
		$periode_tosave = array_map("unserialize", array_unique(array_map("serialize", $periode_tosave)));

		// Enregistrement
		foreach ($periode_tosave as $p)
		{
				$newPeriode = new Periode();
				$newPeriode->setData($data);
				$newPeriode->parent_id = $p['parent'];
				$newPeriode->date_debut = $p['debut'];
				$newPeriode->date_fin = $p['fin'];
				if($newPeriode->user_id == 'V89' || $newPeriode->user_id == 'V80' || $newPeriode->user_id == 'V85') {
					$newPeriode->ressource = null;
					$newPeriode->lieu = null;
				}
				
				if (!is_null($newPeriode->ressource)){
					if (!checkConflitRessource($newPeriode->ressource,$newPeriode->date_debut,$newPeriode->date_fin,$newPeriode->duree_details,$newPeriode->user_id,null)) {
						$newPeriode->if_res = 1;
					} else $newPeriode->if_res = 0;
				} else {
					$newPeriode->if_res = 0;
				}
				
				if (!is_null($newPeriode->lieu)){
					if (!checkConflitTechnician($newPeriode->lieu,$newPeriode->date_debut,$newPeriode->date_fin,$newPeriode->duree_details,$newPeriode->user_id,null)) {
						$newPeriode->if_tec = 1;
					} else $newPeriode->if_tec = 0;
				} else {
					$newPeriode->if_tec = 0;
				}
	
				if (checkConflitTaskDate($newPeriode->projet_id, $newPeriode->date_debut, $newPeriode->date_fin, null)) {
					$newPeriode->if_date = 1;
				} else $newPeriode->if_date = 0;
				
				$newPeriode->db_save();
		}
		
		return true;
	}

	// renvoie vrai si la tache est parente (a des filles), ou est la fille d'une tache
	public function estFilleOuParente() {
		if(!is_null($this->parent_id)) {
			return true;
		}
		$periodes = new GCollection('Periode');
		$periodes->db_load(array('parent_id', '=', $this->periode_id));
		if($periodes->getCount() > 0) {
			return true;
		}
		return false;
	}
	
	// renvoie vrai si la tache est la dernière fille d'une tache
	public function estFilleDerniere() {
		if(!is_null($this->parent_id)) {
			$periodes = new GCollection('Periode');
			$periodes->db_load(array('parent_id', '=', $this->parent_id));
			while($periode = $periodes->fetch()){
				if($periode->date_debut > $this->date_debut) return false;
			}
			return true;
		}
		return false;
	}

	// applique les paramètres au parent et toutes les soeurs
	public function updateOcurrences($nbJoursDecalDest = '') {
		$periodes = new Gcollection('Periode');
		if(is_null($this->parent_id)) {
			// si c'est le parent alors on sélectionne tout les enfants
			$periodes->db_loadSQL('SELECT * FROM planning_periode WHERE parent_id = ' . val2sql($this->periode_id) . ' ORDER BY date_debut ASC' ) ; // modif suppression:  . ' AND date_debut > ' . val2sql($this->date_debut);
		}else{
			// si non, alors c'est déjà une récurrence, on sélectionne le parent et tous les enfants sauf la periode sélectionnée qui a déjà été mise à jour
			$periodes->db_loadSQL('SELECT * FROM planning_periode WHERE (parent_id = ' . val2sql($this->parent_id) . ' AND periode_id <> ' . val2sql($this->periode_id) . ' ) ORDER BY date_debut ASC'); // modif suppression:  AND date_debut > \'' . val2sql($this->date_debut) . '\'
		}
		//si la periode appellante vient d'être supprimée, on verifie qu'il existe plusieurs période récurrentes, si non, on met à jour le parent_id à NULL de la période restante
		if($periodes->getCount() == 1 && !$this->saved ) {
			if($periode = $periodes->fetch()) {
				$periode->parent_id = NULL;
				$periode->db_save();
			}
			return;
		}
		//on gere le cas des deleteAfter et deleteAvant sur periode recurrente restante
		if($periodes->getCount() == 0) {
			$this->parent_id = NULL;
			$this->db_save();
			return;
		}

		$nbJoursDecalNeg = $nbJoursDecalDest*-1; //on inverse le signe si negatif car le calcul de date ne prend que des nombres positifs

		while($periode = $periodes->fetch()) {
		
		if($periode->if_res == 1 || $periode->if_tec == 1){
			checkAllConflits($periode->periode_id);
		}
	
		// modif calcul du nombre de jour de la période pour report sur la nouvelle date
		if (!is_null($periode->date_fin)) {
			if ($nbJoursDecalDest>0){
				$periode->date_debut = calculerDateFin($periode->date_debut, $nbJoursDecalDest);
				$periode->date_fin = calculerDateFin($periode->date_fin, $nbJoursDecalDest);
			} elseif ($nbJoursDecalDest<0) {
				$periode->date_debut = calculerDateDebut($periode->date_debut, $nbJoursDecalNeg);
				$periode->date_fin = calculerDateDebut($periode->date_fin, $nbJoursDecalNeg);
			}
		} else{
			if ($nbJoursDecalDest>0){
				$periode->date_debut = calculerDateFin($periode->date_debut, $nbJoursDecalDest);
			}elseif ($nbJoursDecalDest<0) {
				$periode->date_debut = calculerDateDebut($periode->date_debut, $nbJoursDecalNeg);
			}
			}
			
			if($periode->projet_id == 'timetable' && $periode->ressource_user != ''){
				$periodeTmp = new GCollection('Periode');
				$sql = 'SELECT * FROM planning_periode WHERE user_id = ' . val2sql($periode->ressource_user) . ' AND lieu_id = ' . val2sql($periode->user_id) . ' AND date_debut = ' . val2sql($periode->date_debut) . ' AND projet_id != "timetable" AND projet_id != "holiday" AND duree_details = ' . val2sql($periode->duree_details);
				$periodeTmp->db_loadSQL($sql);
			
				while($p = $periodeTmp->fetch()){
					$p->lieu = null;
					$p->db_save();
				}
			}
			
			$periode->projet_id = $this->projet_id;
			$periode->titre = $this->titre;
			$periode->user_id = $this->user_id;
			$periode->statut_tache = $this->statut_tache;
			$periode->notes = $this->notes;
			$periode->lien = $this->lien;
			$periode->lieu = $this->lieu;
			$periode->ressource = $this->ressource;
			$periode->custom = $this->custom;
			$periode->ressource_user = $this->ressource_user;
			$periode->price = $this->price;
			$periode->sample = $this->sample;
			if($this->projet_id == 'timetable' || $this->projet_id == 'holiday') $periode->duree_details = $this->duree_details;
			
			if (!is_null($periode->ressource)){
				if (!checkConflitRessource($periode->ressource,$periode->date_debut,$periode->date_fin,$periode->duree_details,$periode->user_id,$periode->periode_id, null, $periode->parent_id)) {
					$periode->if_res = 1;
				} else $periode->if_res = 0;
			} else {
				$periode->if_res = 0;
			}
			
			if (!is_null($periode->lieu)){
				if (!checkConflitTechnician($periode->lieu,$periode->date_debut,$periode->date_fin,$periode->duree_details,$periode->user_id,$periode->periode_id, null, $periode->parent_id)) {
					$periode->if_tec = 1;
				} else $periode->if_tec = 0;
			} else {
				$periode->if_tec = 0;
			}
	
			if (checkConflitTaskDate($periode->projet_id, $periode->date_debut, $periode->date_fin, null)) {
				$periode->if_date = 1;
			} else $periode->if_date = 0;
			
			$periode->db_save();
		}
		return;
	}

	// supprime la tache, sa mere et ses soeurs
	public function db_deleteAll() {
		if(is_null($this->parent_id)) {
			db_query('DELETE FROM planning_periode where parent_id = ' . val2sql($this->periode_id));
			return $this->db_delete();
		} else {
			db_query('DELETE FROM planning_periode where parent_id = ' . val2sql($this->parent_id));
			db_query('DELETE FROM planning_periode where periode_id = ' . val2sql($this->parent_id));
		}
		return true;
	}

	// modif ajout supprime la tache et ses soeurs apres une date
	public function db_deleteAllApres() {
		if(is_null($this->parent_id)) {
			db_query('DELETE FROM planning_periode where parent_id = ' . val2sql($this->periode_id) . ' AND date_debut > ' . val2sql($this->date_debut)) ;
		} else {
			db_query('DELETE FROM planning_periode where ( periode_id = ' . val2sql($this->parent_id) . ' OR  parent_id = ' . val2sql($this->parent_id) . ') AND date_debut > ' . val2sql($this->date_debut)) ;
		}
		return true;
	}
	// modif ajout supprime la tache et ses soeurs avant une date
	public function db_deleteAllAvant() {
		if(is_null($this->parent_id)) {
			db_query('DELETE FROM planning_periode where parent_id = ' . val2sql($this->periode_id) . ' AND date_debut < ' . val2sql($this->date_debut)) ;
		} else {
			db_query('DELETE FROM planning_periode where ( periode_id = ' . val2sql($this->parent_id) . ' OR  parent_id = ' . val2sql($this->parent_id) . ') AND date_debut < ' . val2sql($this->date_debut)) ;
		}
		return true;
	}

	public function prochaineOccurence() {
		$sql = 'SELECT * FROM planning_periode WHERE (parent_id = ' . val2sql($this->periode_id) . ' OR (parent_id IS NOT NULL AND parent_id = ' . val2sql($this->parent_id) . ' AND periode_id <> ' . val2sql($this->periode_id) . ')) AND date_debut > ' . val2sql($this->date_debut) . ' ORDER BY date_debut LIMIT 1';
		$periode = new Periode();
		if(!$periode->db_loadSQL($sql)) {
			return false;
		}
		return $periode->date_debut;
	}

	
	// envoi un mail en cas de creation/modification de tache
	public function envoiNotification($action = false, $repetition = false) {
		$userAssigne = new User();
		if(!$userAssigne->db_load(array('user_id', '=', $this->user_id))) {
			return false;
		}
		$projet = new Projet();
		if(!$projet->db_load(array('projet_id', '=', $this->projet_id))) {
			return false;
		}

		if($userAssigne->notifications == 'oui' && !is_null($userAssigne->email)) {
			$smarty = new MySmarty();
			$smarty->assign('periode', $this->getData());
			$smarty->assign('projet', $projet->getData());
			
			if(strlen($this->duree_details) == 17) {
				$tmp = explode(';', $this->duree_details);
				$smarty->assign('heure_debut',$tmp[0]);
				$smarty->assign('heure_fin',$tmp[1]);
			}
			
			if(CONFIG_SOPLANNING_URL != "") {
				$smarty->assign('lienTache', CONFIG_SOPLANNING_URL . '/process/login.php?direct_periode_id=' . $this->periode_id . '&date=' . date('Y-m-d') . '&hash=' . md5($this->periode_id . '!¤' . date('Y-m-d') . '¤' . CONFIG_SECURE_KEY));
			}
			
			$createur = new User();
			$createur->db_load(array('user_id', '=', $this->createur_id));
			$smarty->assign('createur', $createur->getData());
			if($action == 'delete') {
				$sujet = CONFIG_SOPLANNING_TITLE . ' - ' . $smarty->getConfigVars('mail_supprTache_sujet') . ($this->titre != '' ? ' - ' . $this->titre : '');
				$corps = $smarty->getHtml('mail_suppr_tache.tpl');
			} else {
				if($repetition !== FALSE && $repetition !== "" && !in_array($repetition, $this->repetitionPossible)) {
					return false;
				}
				$smarty->assign('repetition', $repetition);

				if($action == 'creation') {
					$sujet = CONFIG_SOPLANNING_TITLE . ' - ' . $smarty->getConfigVars('mail_creationTache_sujet') . ($this->titre != '' ? ' - ' . $this->titre : '');
					$corps = $smarty->getHtml('mail_creation_tache.tpl');
				} else {
					$oldPeriode = new Periode();
					$oldPeriode->db_load(array('periode_id', '=', $this->periode_id));
					if(strlen($oldPeriode->duree_details) == 17) {
						$tmp = explode(';', $oldPeriode->duree_details);
						$smarty->assign('heure_debut_old',$tmp[0]);
						$smarty->assign('heure_fin_old',$tmp[1]);
					}
					$smarty->assign('oldPeriode', $oldPeriode->getData());
					$oldProjet = new Projet();
					$oldProjet->db_load(array('projet_id', '=', $oldPeriode->projet_id));
					$smarty->assign('oldProjet', $oldProjet->getData());
					$sujet = CONFIG_SOPLANNING_TITLE . ' - ' . $smarty->getConfigVars('mail_modifTache_sujet')  . ($this->titre != '' ? ' - ' . $this->titre : '');
					$corps = $smarty->getHtml('mail_modif_tache.tpl');
				}
			}

			$mail = new Mailer($userAssigne->email, $sujet, $corps, true);
			try {
				$result = $mail->send();
			} catch (phpmailerException $e) {
				echo 'error while sending the email :';
				print_r($e);
			}
			return $result;
		}
		return true;
	}


	
	
	
}

?>