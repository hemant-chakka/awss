<?php 

class PrepaidSignup extends Page 
{

}

class PrepaidSignup_Controller extends Page_Controller 
{
	//Allow our form as an action
	private static $allowed_actions = array(
		'PrepaidSignupForm',
		'doPrepaidSignup'
	);
	
	public function init() {
		parent::init();
		Requirements::javascript('mysite/js/prepaid-signup.js');
		SSViewer::setOption('rewriteHashlinks', false);
	}
	
	//Generate the Prepaid Signup form
	function PrepaidSignupForm()
	{
		$cardType = array(
	    		"visa"=>"<img src='themes/attwiz/images/visa.png' height=30px></img>",
	    		"mc"=>"<img src='themes/attwiz/images/mastercard.jpeg' height=30px></img>",
	    		"amex"=>"<img src='themes/attwiz/images/ae.jpeg' height=30px></img>",
	    		"discover"=>"<img src='themes/attwiz/images/discover.jpeg' height=30px></img>"
	    );
	    $monthArray = array();
	    for($i =1;$i <=12; $i++){
	    	$monthArray[$i]=date('F',mktime(0,0,0,$i));
	    }
		$yearArray = array();
	    $currentYear = date('Y');
		for($i =0;$i <=10; $i++){
	    	$yearArray[$currentYear+$i]=$currentYear+$i;
	    }
	    $trialExpiryDate = date('F-j-Y',mktime(0,0,0,date('n')+1,date('j'),date('Y')));
		$price = Product::get()->byID(7)->Price;
	    $shoppingCart = $this->renderWith('PrepaidShoppingCart',array('Price' => $price));
	    $whatsThis = '<span id="WhatsThis"><a id="WhatsThisImage" href="themes/attwiz/images/cvv.jpg" title="What\'s this?">What\'s this?</a></span>';
	    $fields = new FieldList(
			new LiteralField('SignupTitle', '<h2>Create Your Account</h2>'),
			new EmailField('Email', 'Email'),
			new ConfirmedPasswordField('Password', 'Password'),
			new LiteralField('BillingInfoTitle', '<h2>Billing Information</h2>'),
			new TextField('FirstName', 'First Name'),
			new TextField('LastName', 'Last Name'),
			new TextField('Company', 'Company(optional)'),
			new TextField('StreetAddress1', 'Street Address1'),
			new TextField('StreetAddress2', 'Street Address2(optional)'),
			new TextField('City', 'City'),
			new TextField('State', 'State/Province'),
			new TextField('PostalCode', 'Zip/Poatal Code'),
	    	//new DropdownField('Country','Country',$countries2,'United States'),
	    	new CountryDropdownField('Country'),
			new OptionsetField('CreditCardType','Credit Card Type',$cardType,'visa'),
			new TextField('NameOnCard', 'Name On Card'),
			new TextField('CreditCardNumber', 'Credit Card Number'),
			new PasswordField('CVVCode', 'Security/CVV Code'),
	    	new LiteralField('WhatIsThis', $whatsThis),
			new DropdownField('ExpirationMonth','Expiration Date',$monthArray),
			new DropdownField('ExpirationYear','',$yearArray),
			new LiteralField('ShoppingCart', $shoppingCart),
	    	new HiddenField('Price','',$price),
			new HiddenField('Quantity','',1)
		);
	 	
	    // Create action
	    $actions = new FieldList(
			$submit = new FormAction('doPrepaidSignup','')
	    );
	    $submit->setAttribute('src', 'themes/attwiz/images/button_purchase.png');
		// Create action
		$validator = new RequiredFields(
							'Email',
							'Password',
							'FirstName',
							'LastName',
							'StreetAddress1',
							'City',
							'State',
							'PoatalCode',
							'Country',
							'CreditCardType',
							'NameOnCard',
							'CreditCardNumber',
							'CVVCode',
							'ExpirationMonth',
							'ExpirationYear',
							'OrderTotal'
					);
		$validator = null;
	 	return new Form($this, 'PrepaidSignupForm', $fields, $actions, $validator);		
	}
	
	//Process Prepaid Signup form
	function doPrepaidSignup_old($data,$form){
		//Check for existing member email address
		if($member = DataObject::get_one("Member", "`Email` = '". Convert::raw2sql($data['Email']) . "'")){
			//Set error message
			$form->AddErrorMessage('Email', "Sorry, that email address already exists. Please choose another.", 'bad');
			//Set form data from submitted values
			Session::set("FormInfo.Form_PrepaidSignupForm.data", $data);		
			//Return back to form
			return $this->redirectBack();			
		}	
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		// Get country text from code
		$country = Geoip::countryCode2name($data['Country']);
		// Create IndusionSoft contact
		$returnFields = array('Id');
		$conInfo = $app->findByEmail($data['Email'], $returnFields);
		if(count($conInfo)){
			$isConID = $conInfo[0]['Id'];
		}else{
			$conDat = array(
					'FirstName'  => $data['FirstName'],
					'LastName'  => $data['LastName'],
					'Company'  => $data['Company'],
					'StreetAddress1'  => $data['StreetAddress1'],
					'StreetAddress2'  => $data['StreetAddress2'],
					'City'  => $data['City'],
					'State'  => $data['State'],
					'PostalCode'  => $data['PostalCode'],
					'Country'  => $country,
					'Email'  => $data['Email']
			);
			$isConID = $app->addCon($conDat);
		}
		// Create AttentionWizard member
		$member = new Member();
		$member->FirstName = $data['FirstName'];
		$member->Surname = $data['LastName'];
		$member->Email = $data['Email'];
		$member->Password = $data['Password']['_Password'];
		$member->ISContactID = $isConID;
		$memberID = $member->write();
		//Find or create the 'user' group
		if(!$userGroup = DataObject::get_one('Group', "Code = 'customers'")){
			$userGroup = new Group();
			$userGroup->Code = "customers";
			$userGroup->Title = "Customers";
			$userGroup->Write();
		}
		//Add member to user group
		$userGroup->Members()->add($member);
		//Get the current date
		$curdate = $app->infuDate(date('j-n-Y'));
		$product = Product::get()->byID(7);
		// Locate existing credit card
		$ccID = $app->locateCard($isConID,substr($data['CreditCardNumber'],-4,4));
		$creditCardType = $this->getISCreditCardType($data['CreditCardType']);
		if(!$ccID){
			$ccData = array(
				'ContactId' => $isConID,
				'FirstName'  => $data['FirstName'],
				'LastName'  => $data['LastName'],
				'BillAddress1'  => $data['StreetAddress1'],
				'BillAddress2'  => $data['StreetAddress2'],
				'BillCity'  => $data['City'],
				'BillState'  => $data['State'],
				'BillZip'  => $data['PostalCode'],
				'BillCountry'  => $country,
				'CardType'  => $creditCardType,
				'NameOnCard'  => $data['NameOnCard'],
				'CardNumber'  => $data['CreditCardNumber'],
				'CVV2'  => $data['CVVCode'],
				'ExpirationMonth'  => sprintf("%02s", $data['ExpirationMonth']),
				'ExpirationYear'  => $data['ExpirationYear']
			);
			$ccID = $app->dsAdd("CreditCard", $ccData);
		}
		// Store credit card info
		$creditCard = new CreditCard();
		$creditCard->CreditCardType = $data['CreditCardType'];
		$creditCard->CreditCardNumber = $data['CreditCardNumber'];
		$creditCard->NameOnCard = $data['NameOnCard'];
		$creditCard->CreditCardCVV = $data['CVVCode'];
		$creditCard->ExpiryMonth = $data['ExpirationMonth'];
		$creditCard->ExpiryYear = $data['ExpirationYear'];
		$creditCard->Company = $data['Company'];
		$creditCard->StreetAddress1 = $data['StreetAddress1'];
		$creditCard->StreetAddress2 = $data['StreetAddress2'];
		$creditCard->City = $data['City'];
		$creditCard->State = $data['State'];
		$creditCard->PostalCode = $data['PostalCode'];
		$creditCard->Country = $data['Country'];
		$creditCard->Current = 1;
		$creditCard->ISCCID = $ccID;
		$creditCard->MemberID = $memberID;
		$creditCardID = $creditCard->write();
		// Create an Infusionsoft order
		$config = SiteConfig::current_site_config();
		$invoiceId = $app->blankOrder($isConID,$product->Name, $curdate, 0, 0);
		$orderItem = $app->addOrderItem($invoiceId, $this->getNonExpiringIsProductId(7), 3, floatval($data['Price']), intval($data['Quantity']), $product->Name, $product->Name);
		$result = $app->chargeInvoice($invoiceId,$product->Name,$ccID,$config->MerchantAccount,false);
		// Create an order
		$order = new Order();
		$order->OrderStatus = 'P';
		$order->Amount = $data['Price'] * $data['Quantity'];
		$order->MemberID = $memberID;
		$order->ProductID = 7;
		$order->CreditCardID = $creditCardID;
		$orderID = $order->write();
		$returnFields = array('_AttentionWizard','Leadsource');
		$conDat1 = $app->loadCon($isConID,$returnFields);
		if($result['Successful']){
			// Add tag Paid member - prepaid
			$app->grpAssign($isConID, 2290);
			$conDat = array(
					'ContactType' => 'AW Customer'
			);
			if(!isset($conDat1['_AttentionWizard']))
				$conDat['_AttentionWizard'] = 'Prepaid only - no subscription';
			if(isset($conDat1['_AttentionWizard']) && $conDat1['_AttentionWizard'] != 'Paid and Current' && $conDat1['_AttentionWizard'] != 'Free')
				$conDat['_AttentionWizard'] = 'Prepaid only - no subscription';
			if(!isset($conDat1['Leadsource']) || !$conDat1['Leadsource'])
				$conDat['Leadsource'] = 'AttentionWizard';
			$conID = $app->updateCon($isConID, $conDat);
			// Note is added
			$conActionDat = array('ContactId' => $isConID,
				'ActionType'  => 'UPDATE',
				'ActionDescription'  => "Purchased AW Prepaid Credits",
				'CreationDate'  => $curdate,
				'ActionDate'  => $curdate,
				'CompletionDate'  => $curdate,
				'UserID'  => 1
			);
			$conActionID = $app->dsAdd("ContactAction", $conActionDat);
			// Update order
			$order->OrderStatus = 'c';
			$order->write();
			// Create a Subscription record
			$subscription = new Subscription();
			$subscription->StartDate = date("Y-m-d H:i:s");
			$subscription->MemberID = $memberID;
			$subscription->ProductID = 7;
			$subscription->OrderID = $orderID;
			$subscription->write();
			// Create a MemberCredits record
			$memberCredits = new MemberCredits();
			$memberCredits->Credits = ($product->Credits)*$data['Quantity'];
			$memberCredits->MemberID = $memberID;
			$memberCredits->ProductID = 7;
			$memberCredits->SubscriptionID = $subscription->ID;
			$memberCredits->write();
			$member->logIn();
			$this->setMessage('Success', 'Purchased non-expiring heatmaps successfully.');
			return $this->redirect('/account-settings');
		}else{
			//Update Infusionsoft contact
			$conDat = array(
				'_AttentionWizard' => 'Unsuccessful prepaid sign-up',
				'ContactType' => 'AW Prospect'	
			);
			if(!$conDat1['Leadsource'])
				$conDat['Leadsource'] = 'AttentionWizard';
			$app->updateCon($isConID, $conDat);
			// Add an AW prospect tag
			$app->grpAssign($isConID, $this->getISTagIdByPaymentCode(strtoupper($result['Code'])));
			// Add a note
			$conActionDat = array('ContactId' => $isConID,
				'ActionType'  => 'UPDATE',
				'ActionDescription'  => "Unsuccessful attempt to sign-up prepaid plan",
				'CreationDate'  => $curdate,
				'ActionDate'  => $curdate,
				'CompletionDate'  => $curdate,
				'UserID'  => 1
			);
			$conActionID = $app->dsAdd("ContactAction", $conActionDat);
			$member->logIn();
			$this->setMessage('Error', 'Sorry,the payment has failed due to some reason.please update your credit card');
			return $this->redirect('/account-settings/#tabs-2');
		}
	}
	//Process Prepaid ajax Signup form
	function doPrepaidSignup(){
		$data = $_POST;
		//Check for existing member email address
		if($member = DataObject::get_one("Member", "`Email` = '". Convert::raw2sql($data['Email']) . "'"))
			return "inlineMsg1";
		$currentYear = date('Y');
		$currentMonth = date('n');
		//Stop sign-up when the credit card is expired
		if($data['ExpirationYear'] < $currentYear){
			return "inlineMsg6";
		}
		if ($data['ExpirationYear'] == $currentYear){
			if($data['ExpirationMonth'] < $currentMonth)
				return "inlineMsg6";
		}
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		// Get country text from code
		$country = Geoip::countryCode2name($data['Country']);
		// Create IndusionSoft contact
		$returnFields = array('Id');
		$conInfo = $app->findByEmail($data['Email'], $returnFields);
		if(count($conInfo)){
			$isConID = $conInfo[0]['Id'];
		}else{
			$conDat = array(
					'FirstName'  => $data['FirstName'],
					'LastName'  => $data['LastName'],
					'Company'  => $data['Company'],
					'StreetAddress1'  => $data['StreetAddress1'],
					'StreetAddress2'  => $data['StreetAddress2'],
					'City'  => $data['City'],
					'State'  => $data['State'],
					'PostalCode'  => $data['PostalCode'],
					'Country'  => $country,
					'Email'  => $data['Email']
			);
			$isConID = $app->addCon($conDat);
		}
		// Locate existing credit card
		$ccID = $app->locateCard($isConID,substr($data['CreditCardNumber'],-4,4));
		$creditCardType = $this->getISCreditCardType($data['CreditCardType']);
		if(!$ccID){
			//Validate the credit card
			$card = array(
					'CardType' => $creditCardType,
					'ContactId' => $isConID,
					'CardNumber' => $data['CreditCardNumber'],
					'ExpirationMonth' => sprintf("%02s", $data['ExpirationMonth']),
					'ExpirationYear' => $data['ExpirationYear'],
					'CVV2' => $data['CVVCode']
			);
			$result = $app->validateCard($card);
			if($result['Valid'] == 'false')
				return "inlineMsg5";
			$ccData = array(
					'ContactId' => $isConID,
					'FirstName'  => $data['FirstName'],
					'LastName'  => $data['LastName'],
					'BillAddress1'  => $data['StreetAddress1'],
					'BillAddress2'  => $data['StreetAddress2'],
					'BillCity'  => $data['City'],
					'BillState'  => $data['State'],
					'BillZip'  => $data['PostalCode'],
					'BillCountry'  => $country,
					'CardType'  => $creditCardType,
					'NameOnCard'  => $data['NameOnCard'],
					'CardNumber'  => $data['CreditCardNumber'],
					'CVV2'  => $data['CVVCode'],
					'ExpirationMonth'  => sprintf("%02s", $data['ExpirationMonth']),
					'ExpirationYear'  => $data['ExpirationYear']
			);
			$ccID = $app->dsAdd("CreditCard", $ccData);
		}
		// Create AttentionWizard member
		$member = new Member();
		$member->FirstName = $data['FirstName'];
		$member->Surname = $data['LastName'];
		$member->Email = $data['Email'];
		$member->Password = $data['Password']['_Password'];
		$member->ISContactID = $isConID;
		$memberID = $member->write();
		//Find or create the 'user' group
		if(!$userGroup = DataObject::get_one('Group', "Code = 'customers'")){
			$userGroup = new Group();
			$userGroup->Code = "customers";
			$userGroup->Title = "Customers";
			$userGroup->Write();
		}
		//Add member to user group
		$userGroup->Members()->add($member);
		//Get the current date
		$curdate = $app->infuDate(date('j-n-Y'));
		$product = Product::get()->byID(7);
		// Store credit card info
		$creditCard = new CreditCard();
		$creditCard->CreditCardType = $data['CreditCardType'];
		$creditCard->CreditCardNumber = $data['CreditCardNumber'];
		$creditCard->NameOnCard = $data['NameOnCard'];
		$creditCard->CreditCardCVV = $data['CVVCode'];
		$creditCard->ExpiryMonth = $data['ExpirationMonth'];
		$creditCard->ExpiryYear = $data['ExpirationYear'];
		$creditCard->Company = $data['Company'];
		$creditCard->StreetAddress1 = $data['StreetAddress1'];
		$creditCard->StreetAddress2 = $data['StreetAddress2'];
		$creditCard->City = $data['City'];
		$creditCard->State = $data['State'];
		$creditCard->PostalCode = $data['PostalCode'];
		$creditCard->Country = $data['Country'];
		$creditCard->Current = 1;
		$creditCard->ISCCID = $ccID;
		$creditCard->MemberID = $memberID;
		$creditCardID = $creditCard->write();
		// Create an Infusionsoft order
		$config = SiteConfig::current_site_config();
		$invoiceId = $app->blankOrder($isConID,$product->Name, $curdate, 0, 0);
		$orderItem = $app->addOrderItem($invoiceId, $this->getNonExpiringIsProductId(7), 3, floatval($data['Price']), intval($data['Quantity']), $product->Name, $product->Name);
		$result = $app->chargeInvoice($invoiceId,$product->Name,$ccID,$config->MerchantAccount,false);
		// Create an order
		$order = new Order();
		$order->OrderStatus = 'P';
		$order->Amount = $data['Price'] * $data['Quantity'];
		$order->MemberID = $memberID;
		$order->ProductID = 7;
		$order->CreditCardID = $creditCardID;
		$orderID = $order->write();
		$returnFields = array('_AttentionWizard','Leadsource');
		$conDat1 = $app->loadCon($isConID,$returnFields);
		if($result['Successful']){
			// Add tag Paid member - prepaid
			$app->grpAssign($isConID, 2290);
			$conDat = array(
					'ContactType' => 'AW Customer'
			);
			if(!isset($conDat1['_AttentionWizard']))
				$conDat['_AttentionWizard'] = 'Prepaid only - no subscription';
			if(isset($conDat1['_AttentionWizard']) && $conDat1['_AttentionWizard'] != 'Paid and Current' && $conDat1['_AttentionWizard'] != 'Free')
				$conDat['_AttentionWizard'] = 'Prepaid only - no subscription';
			if(!isset($conDat1['Leadsource']) || !$conDat1['Leadsource'])
				$conDat['Leadsource'] = 'AttentionWizard';
			$conID = $app->updateCon($isConID, $conDat);
			// Note is added
			$conActionDat = array('ContactId' => $isConID,
					'ActionType'  => 'UPDATE',
					'ActionDescription'  => "Purchased AW Prepaid Credits",
					'CreationDate'  => $curdate,
					'ActionDate'  => $curdate,
					'CompletionDate'  => $curdate,
					'UserID'  => 1
			);
			$conActionID = $app->dsAdd("ContactAction", $conActionDat);
			// Update order
			$order->OrderStatus = 'c';
			$order->write();
			// Create a Subscription record
			$subscription = new Subscription();
			$subscription->StartDate = date("Y-m-d H:i:s");
			$subscription->MemberID = $memberID;
			$subscription->ProductID = 7;
			$subscription->OrderID = $orderID;
			$subscription->write();
			// Create a MemberCredits record
			$memberCredits = new MemberCredits();
			$memberCredits->Credits = ($product->Credits)*$data['Quantity'];
			$memberCredits->MemberID = $memberID;
			$memberCredits->ProductID = 7;
			$memberCredits->SubscriptionID = $subscription->ID;
			$memberCredits->write();
			$member->logIn();
			$this->setMessage('Success', 'Purchased non-expiring heatmaps successfully.');
			return 'url1';
		}else{
			//Update Infusionsoft contact
			$conDat = array(
					'_AttentionWizard' => 'Unsuccessful prepaid sign-up',
					'ContactType' => 'AW Prospect'
			);
			if(!isset($conDat1['Leadsource']))
				$conDat['Leadsource'] = 'AttentionWizard';
			$app->updateCon($isConID, $conDat);
			// Add an AW prospect tag
			$app->grpAssign($isConID, $this->getISTagIdByPaymentCode(strtoupper($result['Code'])));
			// Add a note
			$conActionDat = array('ContactId' => $isConID,
					'ActionType'  => 'UPDATE',
					'ActionDescription'  => "Unsuccessful attempt to sign-up prepaid plan",
					'CreationDate'  => $curdate,
					'ActionDate'  => $curdate,
					'CompletionDate'  => $curdate,
					'UserID'  => 1
			);
			$conActionID = $app->dsAdd("ContactAction", $conActionDat);
			$member->logIn();
			$this->setMessage('Error', 'Sorry,the payment failed due to some reason.please update your credit card.');
			return 'url2';
		}
	}
}