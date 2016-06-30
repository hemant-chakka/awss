<?php
 
class Order extends DataObject {
    private static $db = array(
    	"OrderStatus" => 'Varchar(10)',
    	"Amount" => 'Currency',
    	"JoomlaOrderNumber"=> 'Varchar(32)',
    	//"CreditCardType" => 'Varchar(10)',
    	//"CreditCardNumber" => 'Varchar(50)',
    	"IsTrial" => 'Boolean'
    );
    
    private static $has_one = array(
    	"Member" => "Member",
    	"Product" => "Product",
    	"CreditCard" => "CreditCard"
    );
    
    private static $summary_fields = array(
    		'Member.Name',
    		'Member.Email', 
    		'Product.Name', 
    		'trialStatus'
    );
     
    private static $field_labels = array(
    		'Member.Name' => 'Member Name',
    		'Member.Email' => 'Member Email',
    		'Member.Surname' => 'Member Last Name', 
    		'Product.Name' => 'Plan', 
    		'trialStatus' => 'Status'
    );
    private static $searchable_fields = array(
    		'Member.FirstName',
    		'Member.Surname',
    		'Member.Email',
    		'Product.Name',  
    		'IsTrial'
    );
    
    public function trialStatus(){
    	if($this->IsTrial)
    		return 'Trial';
    	return 'Non-Trial';
    }
}