<?php

class Sample extends GObject
{

	public function __construct()
	{
		$this->table = 'planning_sample';
		
		$this->fields['sample_id'] = new GString('planning_sample', 'sample_id', 20, FALSE, '');
		$this->fields['r_date'] = new GDate('planning_sample', 'r_date', 10, TRUE, NULL);
		$this->fields['projet_id'] = new GString('planning_sample', 'projet_id', 20, TRUE, NULL);
		$this->fields['statut'] = new GString('planning_sample', 'statut', 10, FALSE, 'Received');
		$this->fields['couleur'] = new GString('planning_sample', 'couleur', 6, FALSE, '');
		$this->fields['n_samples'] = new GFloat('planning_sample', 'n_samples', 11, TRUE, NULL);
		$this->fields['e_date'] = new GDate('planning_sample', 'e_date', 10, TRUE, NULL);
		$this->fields['lien'] = new GString('planning_sample', 'lien', 65000, TRUE, NULL);
		$this->fields['user_id'] = new GString('planning_sample', 'user_id', 20, FALSE, '');
		$this->fields['ns'] = new GString('planning_sample', 'ns', 35, TRUE, NULL);
		$this->fields['specif'] = new GString('planning_sample', 'specif', 255, TRUE, NULL);
		$this->primaryKeys = array('sample_id');

		parent::__construct();
	}

	

}

?>