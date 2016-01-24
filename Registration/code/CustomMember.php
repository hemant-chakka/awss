<?php

class CustomMember extends DataExtension {
	
	private static $db = array(
        "ISContactID" => 'Int',
		"SignUpTrial" => 'Boolean'
    );
    
    public function getCMSFields() {
		$this->extend('updateCMSFields', $fields);
		return $fields;
	}
	
	//Add form fields to CMS
	public function updateCMSFields(FieldList $fields) {
		$fields->renameField('ISCCID', 'InfusionSoft Credit Card ID');
		$fields->renameField('ISContactID', 'InfusionSoft Contact ID'); 		
	}
	
	
	public function getCMSValidator(){
		return new RequiredFields(
			'FirstName',
			'Surname',
			'Email',
			'Password',
			'ISContactID',
			'ISCCID'
		); 
	}
}