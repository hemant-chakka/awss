<?php 
class CreditCard extends DataObject {
    private static $db = array(
    	"CreditCardType" => "Enum('visa,mc,amex,discover,diners,jcb','visa')",
    	"NameOnCard" => 'Varchar(100)',
        "CreditCardNumber" => 'Varchar(100)',
    	"CreditCardCVV" => 'Int',
    	"ExpiryMonth" => 'Int',
    	"ExpiryYear" => 'Int',
    	"Company" => 'Varchar(100)',
    	"StreetAddress1" => 'Varchar(255)',
    	"StreetAddress2" => 'Varchar(255)',
    	"City" => 'Varchar(100)',
    	"State" => 'Varchar(100)',
    	"PostalCode" => 'Varchar(50)',
    	"Country" => 'Varchar(10)',
    	"Current" => 'Boolean',
    	"UsedForTrial" => 'Boolean',
    	"ISCCID" => 'Int'
    );
    
    private static $has_one = array(
    	"Member" => "Member"
    );
    
    private static $summary_fields = array(
		    'Member.Name',
    		'getFullCardType',
    		'maskCreditCardNumber',
    		'currentAsText'	  
    );
    
    private static $searchable_fields = array(
   		'Member.FirstName',
   		'Member.Surname',
   		'Member.Email',
   		'CreditCardType',
   		'NameOnCard',
    	'CreditCardNumber',
    	'Current'
   );
    
    private static $field_labels = array(
   		'Member.Name' => 'Member Name',
    	'Member.Surname' => 'Member Last Name',
    	'getFullCardType' => 'Card Type',
    	'maskCreditCardNumber' => 'Card Number',
   		'currentAsText' => 'Status'
   );
    // Get the status of the credit card
	public function currentAsText(){
    	if($this->Current)
    		return 'Active';
    	else
    		return 'Inactive';
    }
    //Get full text of credit card type
    public function getFullCardType(){
    	$Page_Cntl = new Page_Controller();
    	return $Page_Cntl->getISCreditCardType($this->CreditCardType);
    }
    
	public function getCMSActions() { 
         $actions = parent::getCMSActions(); 
         $addCreditCard = new FormAction ('addCreditCard', 'Add Credit Card');
         $addCreditCard->addExtraClass('ss-ui-action-constructive');
         $updateCreditCard = new FormAction ('updateCreditCard', 'Update Credit Card');
         $updateCreditCard->addExtraClass('ss-ui-action-constructive');  
		 if($this->ID)
		 	$actions->push($updateCreditCard);
		 if(!$this->ID)
		 	$actions->push($addCreditCard);
         return $actions; 
    }
    
    public function getCMSFields() {
    	$fields = parent::getCMSFields();
	    $monthArray = array();
	    for($i =1;$i <=12; $i++){
	   		$monthArray[$i]=date('F',mktime(0,0,0,$i));
	    }
	    $yearArray = array();
	    $currentYear = date('Y');
		for($i =0;$i <=10; $i++){
    		$yearArray[$currentYear+$i]=$currentYear+$i;
    	}
    	// callback function        
		$memberDataSource = function($keyword){  
			return Member::get()->where("FirstName like '%$keyword%' OR Surname like '%$keyword%'")->map('ID', 'Name')->toArray();
		};
    	if($this->ID){
    		// Format and hide the credit card number
			$creditCardMask = $this->maskCreditCardNumber();
    		$fields->addFieldToTab('Root.Main', new ReadonlyField('CreditCardMask','Credit Card Number',$creditCardMask),'CreditCardNumber');
    		$fields->replaceField('CreditCardNumber', new HiddenField('CreditCardNumber',$this->CreditCardNumber));
    		$fields->replaceField('CreditCardCVV', new PasswordField('CreditCardCVV','Credit Card CVV',$this->CreditCardCVV));
			$fields->replaceField('Country', new CountryDropdownField('Country'));
			$fields->replaceField('MemberID', new HiddenField('MemberID'));
			$fields->replaceField('ISCCID', new HiddenField('ISCCID'));
			$fields->replaceField('ExpiryMonth', new DropdownField('ExpiryMonth','Expiry Month',$monthArray,$this->ExpiryMonth));
	    	$fields->replaceField('ExpiryYear', new DropdownField('ExpiryYear','Expiry Year',$yearArray,$this->ExpiryYear));
	    	if($this->Current)
	    		$fields->replaceField('Current', new HiddenField('Current',$this->Current));
    	}else{
    		$fields->addFieldToTab("Root.Main", new TextField('Keyword','Enter a keyword to search a member'),'MemberID');
			$keyword = $fields->dataFieldByName('Keyword');
    		$memberId = DependentDropdownField::create('MemberID','Member', $memberDataSource)->setDepends($keyword)->setEmptyString('(Select a member)');
			$fields->replaceField('MemberID', $memberId);
    		$fields->removeByName('ISCCID');
    		$fields->replaceField('CreditCardCVV', new PasswordField('CreditCardCVV','Credit Card CVV'));
    		$fields->replaceField('ExpiryMonth', new DropdownField('ExpiryMonth','Expiry Month',$monthArray));
	    	$fields->replaceField('ExpiryYear', new DropdownField('ExpiryYear','Expiry Year',$yearArray));
	    	$fields->replaceField('Country', new CountryDropdownField('Country'));
    	}
    	return $fields;
    }
    
    public function maskCreditCardNumber(){
    	$creditCardLast4 = substr($this->CreditCardNumber, -4);
		return "XXXX-XXXX-XXXX-$creditCardLast4";
    }
    
	public function getCMSValidator() {
		return new RequiredFields(
				'CreditCardType',
				'NameOnCard',
				'CreditCardNumber',
				'CreditCardCVV',
				'ExpiryMonth',
				'ExpiryYear',
				'StreetAddress1',
				'City',
				'State',
				'PostalCode',
				'Country',
				'MemberID'
		);
	}
}