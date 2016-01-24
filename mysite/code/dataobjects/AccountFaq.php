<?php
 
class AccountFaq extends DataObject {
    private static $db = array(
    	"Question" => 'Varchar(255)',
    	"Answer" => 'Text',
    	"SortOrder" => 'Int'
    
    );
    
    private static $summary_fields = array(
		    'Question'
    );
    
    public function getCMSFields() {
    	$fields = parent::getCMSFields();
    	$fields->removeByName('SortOrder');
    	return $fields;
    }
}