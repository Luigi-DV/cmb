<?php

class Received extends GObject
{

	public function __construct()
	{
		$this->table = 'planning_received';
		
		$this->fields['projet_id'] = new GString('planning_received', 'projet_id', 20, FALSE, '');
		$this->fields['nom'] = new GString('planning_received', 'nom', 30, FALSE, '');
		$this->fields['customer'] = new GString('planning_received', 'customer', 30, TRUE, NULL);
		$this->fields['price'] = new GFloat('planning_received', 'price', 20, TRUE, NULL);
		$this->fields['OfferURL'] = new GString('planning_received', 'OfferURL', 1000, TRUE, NULL);
		$this->fields['CommandURL'] = new GString('planning_received', 'CommandURL', 1000, TRUE, NULL);
		$this->fields['FolderURL'] = new GString('planning_received', 'FolderURL', 1000, TRUE, NULL);
		$this->primaryKeys = array('projet_id');

		parent::__construct();
	}

	

}

?>