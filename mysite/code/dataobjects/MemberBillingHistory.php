<?php 

class MemberBillingHistory extends DataObject {
    private static $db = array(
    );
    
    private static $has_one = array(
    		"Member" => "Member",
    		"CreditCard" => "CreditCard",
    		"Product" => "Product",
    		"Subscription" => "Subscription"
    );

}