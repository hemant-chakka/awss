<?php 

class MemberBillingHistory extends DataObject {
    private static $db = array(
        //"PayDate" => 'Date',
		//"ProductName" => 'Varchar(20)',
    	//"CreditCardType" => 'Varchar(15)',
    	//"CreditCardNumber" => 'Varchar(50)',
    	//"Amount" => 'Currency'
    );
    
    private static $has_one = array(
    		"Member" => "Member",
    		"CreditCard" => "CreditCard",
    		"Product" => "Product",
    		"Subscription" => "Subscription"
    );

}