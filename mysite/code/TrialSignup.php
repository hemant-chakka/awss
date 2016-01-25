<?php 

class TrialSignup extends Page 
{

}

class TrialSignup_Controller extends Page_Controller 
{
	//Allow our form as an action
	private static $allowed_actions = array(
		'TrialSignupForm',
		'doSignup'
	);
	
	function init(){
		parent::init();
		Requirements::javascript('mysite/js/trial-signup.js');
		SSViewer::setOption('rewriteHashlinks', false);
	}
	
	//Generate the Trial Signup form
	function TrialSignupForm(){
	    $cardType = array(
	    	"visa"=>"<img src='themes/attwiz/images/visa.png' height=30px></img>",
	    	"mc"=>"<img src='themes/attwiz/images/mastercard.jpeg' height=30px></img>",
	    	"ae"=>"<img src='themes/attwiz/images/ae.jpeg' height=30px></img>",
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
	    $subscriptionInfo = "<div id='SubscriptionInfo'><h2>Post-Trial Subscription Selection</h2>
	    <p>Your 10 heatmaps will expire on $trialExpiryDate, at which time your account 
	    will be replenished with a new allocation of heatmap credits according to the 
	    subscription level you choose below. You may cancel your subscription any time 
	    before your trial period ends and your  credit card will only be charged 1 dollar.</p></div>";
	    $subscriptionType = array(
	    		"1"=>"Bronze - (10 heatmaps for $27.00 / month)",
	    		"2"=>"Silver - (50 heatmaps for $97.00 / month)",
	    		"3"=>"Gold - (200 heatmaps for $197.00 / month)"
	    );
	    /*
	    $countries1 = array('Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'The Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo, Republic of the', 'Congo, Democratic Republic of the', 'Costa Rica', 'Cote d\'Ivoire', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Timor-Leste', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, North', 'Korea, South', 'Kosovo', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia, Federated States of', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar (Burma)', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City (Holy See)', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe');
		$countries2 = array();
		foreach ($countries1 as $country){
			$countries2[$country] = $country;
		} */
		$fields = new FieldList(
			new TextField('FirstName', 'First Name'),
			new TextField('LastName', 'Last Name'),
			new TextField('Company', 'Company(optional)'),
			new TextField('StreetAddress1', 'Street Address1'),
			new TextField('StreetAddress2', 'Street Address2(optional)'),
			new TextField('City', 'City'),
			new TextField('State', 'State/Province'),
			new TextField('PostalCode', 'Zip/Poatal Code'),
			//new DropdownField('Country','Country',$countries2),
			new CountryDropdownField('Country'),
			new OptionsetField('CreditCardType','Credit Card Type',$cardType,'visa'),
			new TextField('NameOnCard', 'Name On Card'),
			new NumericField('CreditCardNumber', 'Credit Card Number'),
			new PasswordField('CVVCode', 'Security/CVV Code'),
			new DropdownField('ExpirationMonth','Expiration Date',$monthArray),
			new DropdownField('ExpirationYear','',$yearArray),
			new LiteralField('SubscriptionInfo', $subscriptionInfo),
			new OptionsetField('SubscriptionType','',$subscriptionType,'1'),
			new CheckboxField('Agreement',' I understand that this is a recurring subscription and I will be billed monthly unless I cancel.')
		);
	 	
	    // Create action
	    $actions = new FieldList(
			$submit = new FormAction('doSignup','Start Trial')
	    );
	    $submit->setAttribute('src', 'themes/attwiz/images/button_startmytrialnow.gif');
		// Create action
		$validator = new RequiredFields(
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
							'SubscriptionInfo',
							'SubscriptionType',
							'Agreement'
		);
	 	$form = new Form($this, 'TrialSignupForm', $fields, $actions, $validator);
	 	$data = Session::get("FormInfo.Form_TrialSignupForm.data"); 
		if(is_array($data)) 
		   $form->loadDataFrom($data); 
		return $form;		
	}
	//Process Trial Signup form
	function doSignup_old($data,$form){
		if($this->isCCUsedForTrial("{$data['CreditCardNumber']}") && ($data['SubscriptionType'] == 1)){
			$this->setMessage('Error', "We're Sorry!This credit card has previously been used for the one cent trial. Only one trial may be purchased per credit card.");
			//$form->sessionMessage("We're Sorry!This credit card has previously been used for the one cent trial. Only one trial may be purchased per credit card.", 'bad');
            Session::set('FormInfo.' . $form->FormName() . '.data', $data);
			return $this->redirectBack();
		}
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		//Get current date
		$curdate = $app->infuDate(date('j-n-Y'));
		//Get the registration form from session
		$regFormData = Session::get('RegistrationFormData');
		// Get country text from code
		$country = Geoip::countryCode2name($data['Country']);
		// Get InfusionSoft Contact ID
		$returnFields = array('Id','Leadsource');
		$conInfo = $app->findByEmail($regFormData['Email'], $returnFields);
		if(empty($conInfo)){
			// If IS contact doesn't exist create one
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
					'Email'  => $regFormData['Email']
			);
			if(empty($conInfo))
				$conDat['Leadsource'] = 'AttentionWizard';
			$isConID = $app->addCon($conDat);
		}else{
			$isConID = $conInfo[0]['Id'];	
		}
		// Create AttentionWizard member
		$member = new Member();
		$member->FirstName = $data['FirstName'];
		$member->Surname = $data['LastName'];
		$member->Email = $regFormData['Email'];
		$member->Password = $regFormData['Password']['_Password'];
		$member->ISContactID = $isConID;
		$memberID = $member->write();
		//Find or create the 'user' group and add the member to the group
		if(!$userGroup = DataObject::get_one('Group', "Code = 'customers'")){
			$userGroup = new Group();
			$userGroup->Code = "customers";
			$userGroup->Title = "Customers";
			$userGroup->Write();
			$userGroup->Members()->add($member);
		}else{
			$userGroup->Members()->add($member);
		}
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
		//Get product details
		$product = Product::get()->byID($data['SubscriptionType']);
		$credits = $product->Credits;
		if($data['SubscriptionType'] == 1){
			$orderAmount = $product->TrialPrice;
			$productName = "30 days 1-cent Trial";
			$isProductID = 38;
		}else{
			$productName = $product->Name;
			$orderAmount = $product->RecurringPrice;
			$isProductID = $product->ISProductID;
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
		// Create an order
		$order = new Order();
		$order->OrderStatus = 'P';
		$order->Amount = $orderAmount;
		$order->MemberID = $memberID;
		$order->ProductID = $data['SubscriptionType'];
		$order->CreditCardID = $creditCardID;
		$orderID = $order->write();
		//Create the Infusionsoft subscription
		$subscriptionID = $this->createISSubscription($isConID, $product->ISProductID, $product->RecurringPrice, $ccID, 30);
		if($subscriptionID && is_int($subscriptionID)){
			//Create an infusionsoft order
			$config = SiteConfig::current_site_config(); 
			$invoiceId = $app->blankOrder($isConID,$productName, $curdate, 0, 0);
			$orderItem = $app->addOrderItem($invoiceId, $isProductID, 9, floatval($orderAmount), 1, $productName, $productName);
			$result = $app->chargeInvoice($invoiceId,$productName,$ccID,$config->MerchantAccount,false);
			if($result['Successful']){
				// Create a Subscription record
				$subscription = new Subscription();
				$subscription->StartDate = date("Y-m-d H:i:s");
				$expireDate = strtotime("+30 days");
				$subscription->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$subscription->MemberID = $memberID;
				$subscription->ProductID = $data['SubscriptionType'];
				$subscription->OrderID = $orderID;
				$subscription->Status = 1;
				$subscription->SubscriptionID = $subscriptionID;
				$subscription->write();
				// Create a MemberCredits record
				$memberCredits = new MemberCredits();
				$memberCredits->Credits = $credits;
				$memberCredits->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$memberCredits->MemberID = $memberID;
				$memberCredits->ProductID = $data['SubscriptionType'];
				$memberCredits->SubscriptionID = $subscription->ID;
				$memberCredits->write();
				// Update order
				$order->OrderStatus = 'c';
				$order->write();
				// If product selected is bronze do a trial signup
				if($data['SubscriptionType'] == 1){
					//Add the InfusionSoft tag
					$app->grpAssign($isConID, 2216);
					//Update the InfusionSoft contact details
					$conDat = array(
						'ContactType' => 'AW Customer',
						'_AWstartdate' => $curdate,	
						'_AttentionWizard' => 'Free'
					);
					$app->updateCon($isConID, $conDat);
					// Update Subscription
					$subscription->IsTrial = 1;
					$subscription->SubscriptionCount = 0;
					$subscription->write();
					// Update Member
					$member->SignUpTrial = 1;
					$member->write();
					// Update Order
					$order->IsTrial = 1;
					$order->write();
					// Update credit card
					$creditCard->UsedForTrial = 1;
					$creditCard->write();
				}else{
					// Update Subscription
					$subscription->SubscriptionCount = 1;
					$subscription->write();
					// Add the InfusionSoft tag
					$isTagId = $this->getISTagIdByProduct($data['SubscriptionType']);
					$app->grpAssign($isConID, $isTagId);
					//Update the InfusionSoft contact details
					$returnFields = array('_AWofmonths');
					$conDat1 = $app->loadCon($isConID,$returnFields);
					$conDat = array(
						'_AWofmonths' => $conDat1['_AWofmonths']+1,
						'ContactType' => 'AW Customer',	
						'_AttentionWizard' => 'Paid and Current',
						'_AWstartdate' => $curdate
					);
					$app->updateCon($isConID, $conDat);
					//Add a note
					$conActionDat = array('ContactId' => $isConID,
						'ActionType'  => 'UPDATE',
						'ActionDescription'  => "Payment made for AW service",
						'CreationDate'  => $curdate,
						'ActionDate'  => $curdate,
						'CompletionDate'  => $curdate,
						'CreationNotes'     => "{$product->Name} Subscription",
						'UserID'  => 1
					);
					$app->dsAdd("ContactAction", $conActionDat);
				}
				$member->logIn();
				$this->setMessage('Success', 'The Subscription is created successfully');
				return $this->redirect('/account-settings');
			}else{
				//Set the subscription to Inactive 
				$this->setSubscriptionStatus($subscriptionID, 'Inactive');
				//Update InfusionSoft contact
				if($data['SubscriptionType'] == 1){
					$aw = 'Unsuccessful trial sign-up';
				}else{
					$aw = 'Unsuccessful paid sign-up';
				}
				$conDat = array(
					'ContactType' => 'AW Prospect',	
					'_AttentionWizard' => $aw
				);
				$app->updateCon($isConID, $conDat);
				// Add an AW prospect tag
				$app->grpAssign($isConID, $this->getISTagIdByPaymentCode(strtoupper($result['Code'])));
				// Add a note
				$conActionDat = array('ContactId' => $isConID,
					'ActionType'  => 'UPDATE',
					'ActionDescription'  => "Unsuccessful attempt to sign-up for AW",
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
		}else{
			$member->logIn();
			// Add an AW prospect tag
			$app->grpAssign($isConID, 3097);
			//Update InfusionSoft contact
			if($data['SubscriptionType'] == 1){
				$aw = 'Unsuccessful trial sign-up';
			}else{
				$aw = 'Unsuccessful paid sign-up';
			}
			$conDat = array(
				'ContactType' => 'AW Prospect',	
				'_AttentionWizard' => $aw
			);
			$app->updateCon($isConID, $conDat);
			// Add a note
			$conActionDat = array('ContactId' => $isConID,
				'ActionType'  => 'UPDATE',
				'ActionDescription'  => "Unsuccessful attempt to sign-up for AW",
				'CreationDate'  => $curdate,
				'ActionDate'  => $curdate,
				'CompletionDate'  => $curdate,
				'UserID'  => 1
			);
			$conActionID = $app->dsAdd("ContactAction", $conActionDat);
			$this->setMessage('Error', 'Sorry,the subscription has failed due to some reason.please try again');
			return $this->redirect('/account-settings/#tabs-4');
		}
	}
	
	//Process Trial Signup form using ajax request
	function doSignup(){
		$data = $_POST;
		if($this->isCCUsedForTrial("{$data['CreditCardNumber']}") && ($data['SubscriptionType'] == 1))
			return "inlineMsg1";
		$currentYear = date('Y');
		$currentMonth = date('n');
		//Stop sign-up when the credit card is expired
		if($data['ExpirationYear'] < $currentYear){
			return "inlineMsg4";
		}
		if ($data['ExpirationYear'] == $currentYear){
			if($data['ExpirationMonth'] <= $currentMonth)
				return "inlineMsg4";
		}
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		//Get current date
		$curdate = $app->infuDate(date('j-n-Y'));
		//Get the registration form from session
		$regFormData = Session::get('RegistrationFormData');
		// Get country text from code
		$country = Geoip::countryCode2name($data['Country']);
		// Get InfusionSoft Contact ID
		$returnFields = array('Id','Leadsource');
		$conInfo = $app->findByEmail($regFormData['Email'], $returnFields);
		if(empty($conInfo)){
			// If IS contact doesn't exist create one
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
					'Email'  => $regFormData['Email']
			);
			if(empty($conInfo))
				$conDat['Leadsource'] = 'AttentionWizard';
			$isConID = $app->addCon($conDat);
		}else{
			$isConID = $conInfo[0]['Id'];
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
				return "inlineMsg3";
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
		$member->Email = $regFormData['Email'];
		$member->Password = $regFormData['Password']['_Password'];
		$member->ISContactID = $isConID;
		$memberID = $member->write();
		//Find or create the 'user' group and add the member to the group
		if(!$userGroup = DataObject::get_one('Group', "Code = 'customers'")){
			$userGroup = new Group();
			$userGroup->Code = "customers";
			$userGroup->Title = "Customers";
			$userGroup->Write();
			$userGroup->Members()->add($member);
		}else{
			$userGroup->Members()->add($member);
		}
		//Get product details
		$product = Product::get()->byID($data['SubscriptionType']);
		$credits = $product->Credits;
		if($data['SubscriptionType'] == 1){
			$orderAmount = $product->TrialPrice;
			$productName = "30 days 1-cent Trial";
			$isProductID = 38;
		}else{
			$productName = $product->Name;
			$orderAmount = $product->RecurringPrice;
			$isProductID = $product->ISProductID;
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
		// Create an order
		$order = new Order();
		$order->OrderStatus = 'P';
		$order->Amount = $orderAmount;
		$order->MemberID = $memberID;
		$order->ProductID = $data['SubscriptionType'];
		$order->CreditCardID = $creditCardID;
		$orderID = $order->write();
		//Create the Infusionsoft subscription
		$subscriptionID = $this->createISSubscription($isConID, $product->ISProductID, $product->RecurringPrice, $ccID, 30);
		if($subscriptionID && is_int($subscriptionID)){
			//Create an infusionsoft order
			$config = SiteConfig::current_site_config();
			$invoiceId = $app->blankOrder($isConID,$productName, $curdate, 0, 0);
			$orderItem = $app->addOrderItem($invoiceId, $isProductID, 9, floatval($orderAmount), 1, $productName, $productName);
			$result = $app->chargeInvoice($invoiceId,$productName,$ccID,$config->MerchantAccount,false);
			if($result['Successful']){
				// Create a Subscription record
				$subscription = new Subscription();
				$subscription->StartDate = date("Y-m-d H:i:s");
				$expireDate = strtotime("+30 days");
				$subscription->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$subscription->MemberID = $memberID;
				$subscription->ProductID = $data['SubscriptionType'];
				$subscription->OrderID = $orderID;
				$subscription->Status = 1;
				$subscription->SubscriptionID = $subscriptionID;
				$subscription->write();
				// Create a MemberCredits record
				$memberCredits = new MemberCredits();
				$memberCredits->Credits = $credits;
				$memberCredits->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$memberCredits->MemberID = $memberID;
				$memberCredits->ProductID = $data['SubscriptionType'];
				$memberCredits->SubscriptionID = $subscription->ID;
				$memberCredits->write();
				// Update order
				$order->OrderStatus = 'c';
				$order->write();
				// If product selected is bronze do a trial signup
				if($data['SubscriptionType'] == 1){
					//Add the InfusionSoft tag
					$app->grpAssign($isConID, 2216);
					//Update the InfusionSoft contact details
					$conDat = array(
							'ContactType' => 'AW Customer',
							'_AWstartdate' => $curdate,
							'_AttentionWizard' => 'Free'
					);
					$app->updateCon($isConID, $conDat);
					// Update Subscription
					$subscription->IsTrial = 1;
					$subscription->SubscriptionCount = 0;
					$subscription->write();
					// Update Member
					$member->SignUpTrial = 1;
					$member->write();
					// Update Order
					$order->IsTrial = 1;
					$order->write();
					// Update credit card
					$creditCard->UsedForTrial = 1;
					$creditCard->write();
				}else{
					// Update Subscription
					$subscription->SubscriptionCount = 1;
					$subscription->write();
					// Add the InfusionSoft tag
					$isTagId = $this->getISTagIdByProduct($data['SubscriptionType']);
					$app->grpAssign($isConID, $isTagId);
					//Update the InfusionSoft contact details
					$returnFields = array('_AWofmonths');
					$conDat1 = $app->loadCon($isConID,$returnFields);
					$conDat = array(
							'_AWofmonths' => $conDat1['_AWofmonths']+1,
							'ContactType' => 'AW Customer',
							'_AttentionWizard' => 'Paid and Current',
							'_AWstartdate' => $curdate
					);
					$app->updateCon($isConID, $conDat);
					//Add a note
					$conActionDat = array('ContactId' => $isConID,
							'ActionType'  => 'UPDATE',
							'ActionDescription'  => "Payment made for AW service",
							'CreationDate'  => $curdate,
							'ActionDate'  => $curdate,
							'CompletionDate'  => $curdate,
							'CreationNotes'     => "{$product->Name} Subscription",
							'UserID'  => 1
					);
					$app->dsAdd("ContactAction", $conActionDat);
				}
				$member->logIn();
				$this->setMessage('Success', 'The Subscription is created successfully');
				return 'url1';
			}else{
				//Set the subscription to Inactive
				$this->setSubscriptionStatus($subscriptionID, 'Inactive');
				//Update InfusionSoft contact
				if($data['SubscriptionType'] == 1){
					$aw = 'Unsuccessful trial sign-up';
				}else{
					$aw = 'Unsuccessful paid sign-up';
				}
				$conDat = array(
						'ContactType' => 'AW Prospect',
						'_AttentionWizard' => $aw
				);
				
				$app->updateCon($isConID, $conDat);
				// Add an AW prospect tag
				//$app->grpAssign($isConID, $this->getISTagIdByPaymentCode(strtoupper($result['Code'])));
				$app->grpAssign($isConID, $this->getISTagIdByPaymentCode('DECLINED'));
				// Add a note
				$conActionDat = array('ContactId' => $isConID,
						'ActionType'  => 'UPDATE',
						'ActionDescription'  => "Unsuccessful attempt to sign-up for AW",
						'CreationDate'  => $curdate,
						'ActionDate'  => $curdate,
						'CompletionDate'  => $curdate,
						'UserID'  => 1
				);
				$conActionID = $app->dsAdd("ContactAction", $conActionDat);
				$member->logIn();
				$this->setMessage('Error', 'Sorry,the payment has failed due to some reason.please update your credit card');
				return "url2";
			}
		}else{
			$member->logIn();
			// Add an AW prospect tag
			$app->grpAssign($isConID, 3097);
			//Update InfusionSoft contact
			if($data['SubscriptionType'] == 1){
				$aw = 'Unsuccessful trial sign-up';
			}else{
				$aw = 'Unsuccessful paid sign-up';
			}
			$conDat = array(
					'ContactType' => 'AW Prospect',
					'_AttentionWizard' => $aw
			);
			$app->updateCon($isConID, $conDat);
			// Add a note
			$conActionDat = array('ContactId' => $isConID,
					'ActionType'  => 'UPDATE',
					'ActionDescription'  => "Unsuccessful attempt to sign-up for AW",
					'CreationDate'  => $curdate,
					'ActionDate'  => $curdate,
					'CompletionDate'  => $curdate,
					'UserID'  => 1
			);
			$conActionID = $app->dsAdd("ContactAction", $conActionDat);
			$this->setMessage('Error', 'Sorry,the subscription has failed due to some reason.please try again');
			return "url3";
		}
	}
}