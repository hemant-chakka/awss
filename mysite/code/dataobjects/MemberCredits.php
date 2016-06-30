<?php 
class MemberCredits extends DataObject {
    private static $db = array(
        "Credits" => 'Int',
    	"ExpireDate" => 'SS_Datetime'
    );
    
    private static $has_one = array(
    		"Member" => "Member",
    		"Product" => "Product",
    		"Subscription" => "Subscription"
    );
    
    private static $summary_fields = array(
    		'Member.Name',
    		'Member.Email',
    		'Product.Name',
    		'Credits',
    		'ExpireDate'
    );
     
    private static $field_labels = array(
    		'Member.Name' => 'Member Name',
    		'Member.Email' => 'Member Email',
    		'Member.Surname' => 'Member Last Name',
    		'Product.Name' => 'Plan',
    		'Credits' => 'Credits',
    		'ExpireDate' => 'Expire Date'
    );
    private static $searchable_fields = array(
    		'Member.FirstName',
    		'Member.Surname',
    		'Member.Email',
    		'Product.Name',
    		'ExpireDate'
    );

}