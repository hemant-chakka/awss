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

}