<?php

class Projet extends GObject
{

	public function __construct()
	{
		$this->table = 'planning_projet';
		
		$this->fields['projet_id'] = new GString('planning_projet', 'projet_id', 20, FALSE, '');
		$this->fields['nom'] = new GString('planning_projet', 'nom', 30, FALSE, '');
		$this->fields['iteration'] = new GString('planning_projet', 'iteration', 255, TRUE, NULL);
		$this->fields['couleur'] = new GString('planning_projet', 'couleur', 6, FALSE, '');
		$this->fields['charge'] = new GString('planning_projet', 'charge', 100, TRUE, NULL);
		$this->fields['livraison'] = new GDate('planning_projet', 'livraison', 10, TRUE, NULL);
		$this->fields['reception'] = new GDate('planning_projet', 'reception', 10, TRUE, NULL);
		$this->fields['lien'] = new GString('planning_projet', 'lien', 65000, TRUE, NULL);
		$this->fields['statut'] = new GString('planning_projet', 'statut', 10, FALSE, NULL);
		$this->fields['statut_bill'] = new GString('planning_projet', 'statut_bill', 10, FALSE, NULL);
		$this->fields['groupe_id'] = new GInteger('planning_projet', 'groupe_id', 11, TRUE, NULL);
		$this->fields['createur_id'] = new GString('planning_projet', 'createur_id', 20, TRUE, NULL);
		$this->fields['pm_id'] = new GString('planning_projet', 'pm_id', 20, TRUE, NULL);
		$this->fields['price'] = new GFloat('planning_projet', 'price', 20, TRUE, NULL);
		$this->fields['ProjetURL'] = new GString('planning_projet', 'ProjetURL', 1000, TRUE, NULL);
		$this->primaryKeys = array('projet_id');

		parent::__construct();
	}

	

}

?>