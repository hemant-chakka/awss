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

}