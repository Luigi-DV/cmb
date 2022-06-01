<?php

class Ressource extends GObject
{

	public function __construct()
	{
		$this->table = 'planning_ressource';
		$this->fields['ressource_id'] = new GString('planning_ressource', 'ressource_id', 10, FALSE, '');
		$this->fields['ressource_groupe_id'] = new GInteger('planning_ressource', 'ressource_groupe_id', 11, TRUE, NULL);
		$this->fields['nom'] = new GString('planning_ressource', 'nom', 50, FALSE, '');
		$this->fields['commentaire'] = new GString('planning_ressource', 'commentaire', 255, TRUE, NULL);
		$this->fields['exclusif'] = new GFloat('planning_ressource', 'exclusif', 4, TRUE, 0);
		$this->fields['date_calibrated'] = new GDate('planning_ressource', 'date_calibrated', 10, TRUE, NULL);
		$this->fields['calibrated'] = new GInteger('planning_ressource', 'calibrated', 1, TRUE, NULL);
		$this->primaryKeys = array('ressource_id');

		parent::__construct();
	}

}

?>