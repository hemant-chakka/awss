<?php 

class MemberTrialSignup extends Page 
{

}

class MemberTrialSignup_Controller extends Page_Controller 
{
	//Allow our form as an action
	private static $allowed_actions = array(
		'MemberTrialSignupForm'
	);
	
	function init(){
		parent::init();
		if(!Member::currentUser()->SignUpTrial)
			Requirements::javascript('mysite/js/member-trial-signup.js');
	}
	
	//Generate the Member Trial Signup form
	function MemberTrialSignupForm()
	{
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$returnFields = array(
				'FirstName',
				'LastName',
				'Company',
				'StreetAddress1',
				'StreetAddress2',
				'City',
				'State',
				'PostalCode',
				'Country'
		);
		$member = Member::currentUser();
		$isContactID = $member->ISContactID;
		$conDat = $app->loadCon($isContactID , $returnFields);
		$cardType = array(
	    		"visa"=>"<img src='themes/attwiz/images/visa.png' height=20px></img>",
	    		"mc"=>"<img src='themes/attwiz/images/mastercard.jpeg' height=20px></img>",
	    		"ae"=>"<img src='themes/attwiz/images/ae.jpeg' height=20px></img>",
	    		"disover"=>"<img src='themes/attwiz/images/discover.jpeg' height=20px></img>",
	    		"dinners"=>"<img src='themes/attwiz/images/dinnerclub.jpeg' height=20px></img>",
	    		"jcb"=>"<img src='themes/attwiz/images/jcb.jpeg' height=20px></img>"
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
	    $member = Member::currentUser();
	    if($member->SignUpTrial){
	    	$subscriptionInfo = "<div id='SubscriptionInfo'><h2>Subscription Selection</h2>
	    	<p>Your 10 heatmaps will expire on $trialExpiryDate, at which time your account will automatically 
	    	renew  with a new allocation of heatmap credits according to the subscription 
	    	level you choose below.</p></div>";
	    }else{
	    	$subscriptionInfo = "<div id='SubscriptionInfo'><h2>Post-Trial Subscription Selection</h2>
	    	<p>Your 10 heatmaps will expire on $trialExpiryDate, at which time your account
	    	will be replenished with a new allocation of heatmap credits according to the
	    	subscription level you choose below. You may cancel your subscription any time
	    	before your trial period ends and your  credit card will only be charged 1 cent.</p></div>";
	    }
	    
	    $subscriptionType = array(
	    		"1"=>"Bronze - (10 heatmaps for $27.00 / month)",
	    		"2"=>"Silver - (50 heatmaps for $97.00 / month)",
	    		"3"=>"Gold - (200 heatmaps for $197.00 / month)"
	    );
	    $countries1 = array('Afghanistan', 'Albania', 'Algeria', 'Andorra', 'Angola', 'Antigua and Barbuda', 'Argentina', 'Armenia', 'Australia', 'Austria', 'Azerbaijan', 'The Bahamas', 'Bahrain', 'Bangladesh', 'Barbados', 'Belarus', 'Belgium', 'Belize', 'Benin', 'Bhutan', 'Bolivia', 'Bosnia and Herzegovina', 'Botswana', 'Brazil', 'Brunei', 'Bulgaria', 'Burkina Faso', 'Burundi', 'Cambodia', 'Cameroon', 'Canada', 'Cape Verde', 'Central African Republic', 'Chad', 'Chile', 'China', 'Colombia', 'Comoros', 'Congo, Republic of the', 'Congo, Democratic Republic of the', 'Costa Rica', 'Cote d\'Ivoire', 'Croatia', 'Cuba', 'Cyprus', 'Czech Republic', 'Denmark', 'Djibouti', 'Dominica', 'Dominican Republic', 'Timor-Leste', 'Ecuador', 'Egypt', 'El Salvador', 'Equatorial Guinea', 'Eritrea', 'Estonia', 'Ethiopia', 'Fiji', 'Finland', 'France', 'Gabon', 'Gambia', 'Georgia', 'Germany', 'Ghana', 'Greece', 'Grenada', 'Guatemala', 'Guinea', 'Guinea-Bissau', 'Guyana', 'Haiti', 'Honduras', 'Hungary', 'Iceland', 'India', 'Indonesia', 'Iran', 'Iraq', 'Ireland', 'Israel', 'Italy', 'Jamaica', 'Japan', 'Jordan', 'Kazakhstan', 'Kenya', 'Kiribati', 'Korea, North', 'Korea, South', 'Kosovo', 'Kuwait', 'Kyrgyzstan', 'Laos', 'Latvia', 'Lebanon', 'Lesotho', 'Liberia', 'Libya', 'Liechtenstein', 'Lithuania', 'Luxembourg', 'Macedonia', 'Madagascar', 'Malawi', 'Malaysia', 'Maldives', 'Mali', 'Malta', 'Marshall Islands', 'Mauritania', 'Mauritius', 'Mexico', 'Micronesia, Federated States of', 'Moldova', 'Monaco', 'Mongolia', 'Montenegro', 'Morocco', 'Mozambique', 'Myanmar (Burma)', 'Namibia', 'Nauru', 'Nepal', 'Netherlands', 'New Zealand', 'Nicaragua', 'Niger', 'Nigeria', 'Norway', 'Oman', 'Pakistan', 'Palau', 'Panama', 'Papua New Guinea', 'Paraguay', 'Peru', 'Philippines', 'Poland', 'Portugal', 'Qatar', 'Romania', 'Russia', 'Rwanda', 'Saint Kitts and Nevis', 'Saint Lucia', 'Saint Vincent and the Grenadines', 'Samoa', 'San Marino', 'Sao Tome and Principe', 'Saudi Arabia', 'Senegal', 'Serbia', 'Seychelles', 'Sierra Leone', 'Singapore', 'Slovakia', 'Slovenia', 'Solomon Islands', 'Somalia', 'South Africa', 'South Sudan', 'Spain', 'Sri Lanka', 'Sudan', 'Suriname', 'Swaziland', 'Sweden', 'Switzerland', 'Syria', 'Taiwan', 'Tajikistan', 'Tanzania', 'Thailand', 'Togo', 'Tonga', 'Trinidad and Tobago', 'Tunisia', 'Turkey', 'Turkmenistan', 'Tuvalu', 'Uganda', 'Ukraine', 'United Arab Emirates', 'United Kingdom', 'United States', 'Uruguay', 'Uzbekistan', 'Vanuatu', 'Vatican City (Holy See)', 'Venezuela', 'Vietnam', 'Yemen', 'Zambia', 'Zimbabwe');
		$countries2 = array();
		foreach ($countries1 as $country){
			$countries2[$country] = $country;
		}
		if(!isset($conDat['StreetAddress2']))
			$conDat['StreetAddress2'] =  null;
		if(!isset($conDat['Company']))
			$conDat['Company'] =  null;
		$fields = new FieldList(
			new TextField('FirstName', 'First Name',$conDat['FirstName']),
			new TextField('LastName', 'Last Name',$conDat['LastName']),
			new TextField('Company', 'Company(optional)',$conDat['Company']),
			new TextField('StreetAddress1', 'Street Address1',$conDat['StreetAddress1']),
			new TextField('StreetAddress2', 'Street Address2(optional)',$conDat['StreetAddress2']),
			new TextField('City', 'City',$conDat['City']),
			new TextField('State', 'State/Province',$conDat['State']),
			new TextField('PostalCode', 'Zip/Poatal Code',$conDat['PostalCode']),
			//new CountryDropdownField('Country'),
			new DropdownField('Country','Country',$countries2,$conDat['Country']),
			new OptionsetField('CreditCardType','Credit Card Type',$cardType,'visa'),
			new TextField('NameOnCard', 'Name On Card'),
			new TextField('CreditCardNumber', 'Credit Card Number'),
			new TextField('CVVCode', 'Security/CVV Code','',3),
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
	    if($member->SignUpTrial)
	    	$submit->setAttribute('src', 'themes/attwiz/images/button_purchase.png');
	    else
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
	 	return new Form($this, 'MemberTrialSignupForm', $fields, $actions, $validator);		
	}
	//Process Trial Signup form
	function doSignup($data,$form){
		// Get AttentionWizard member
		$member = Member::currentUser();
		if((!$member->SignUpTrial) && ($data['SubscriptionType'] == 1) && $this->isCCUsedForTrial("{$data['CreditCardNumber']}")){
			$form->sessionMessage("We're Sorry!This credit card has previously been used for the one cent trial. Only one trial may be purchased per credit card.", 'bad');
            Session::set('FormInfo.' . $form->FormName() . '.data', $data);
			return $this->redirectBack();
		}
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		// Get IndusionSoft contact ID
		$isConID = $member->ISContactID;
		$product = Product::get()->byID($data['SubscriptionType']);
		$credits = $product->Credits;
		// Locate existing credit card
		$ccID = $app->locateCard($isConID,substr($data['CreditCardNumber'],-4,4));
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
				'BillCountry'  => $data['Country'],
				'CardType'  => $data['CreditCardType'],
				'NameOnCard'  => $data['NameOnCard'],
				'CardNumber'  => $data['CreditCardNumber'],
				'CVV2'  => $data['CVVCode'],
				'ExpirationMonth'  => $data['ExpirationMonth'],
				'ExpirationYear'  => $data['ExpirationYear']
			);
			$ccID = $app->dsAdd("CreditCard", $ccData);
		}
		$subscriptionID = $this->createISSubscription($isConID,$product->ISProductID, $product->RecurringPrice, $ccID, 30);
		if($subscriptionID && is_int($subscriptionID)){
			if($data['SubscriptionType'] == 1 && !$member->SignUpTrial){
				$orderAmount = $product->TrialPrice;
				$productName = "30 days 1-dollar Trial";
				$isProductID = 38;
				$trial = 1;
				$subscriptionCount = 0;
			}else{
				$productName = $product->Name;
				$orderAmount = $product->RecurringPrice;
				$isProductID = $product->ISProductID;
				$trial = 0;
				$subscriptionCount = 1;
			}
			// Create an order
			$order = new Order();
			$order->OrderStatus = 'P';
			$order->Amount = $orderAmount;
			$order->PaymentMethod = 'cc';
			$order->MemberID = $member->ID;
			$order->ProductID = $data['SubscriptionType'];
			$orderID = $order->write();
			//get the current date
			$curdate = $app->infuDate(date('j-n-Y'));
			//Create an infusionsoft order
			$config = SiteConfig::current_site_config(); 
			$invoiceId = $app->blankOrder($isConID,$productName, $curdate, 0, 0);
			$orderItem = $app->addOrderItem($invoiceId, $isProductID, 9, floatval($orderAmount), 1, $productName, $productName);
			$result = $app->chargeInvoice($invoiceId,$productName,$ccID,$config->MerchantAccount,false);
			if($result['Successful']){
				// Update order
				$order->OrderStatus = 'c';
				$order->CreditCardType = $data['CreditCardType'];
				$order->CreditCardNumber = $data['CreditCardNumber'];
				$order->IsTrial = $trial;
				$order->write();
				// Create a Subscription record
				$subscription = new Subscription();
				$subscription->SubscriptionID = $subscriptionID;
				$subscription->StartDate = date("Y-m-d H:i:s");
				$expireDate = strtotime("+30 days");
				$subscription->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$subscription->Status = 1;
				$subscription->IsTrial = $trial;
				$subscription->SubscriptionCount = $subscriptionCount;
				$subscription->MemberID = $member->ID;
				$subscription->ProductID = $data['SubscriptionType'];
				$subscription->OrderID = $orderID;
				$subscription->write();
				// Create a MemberCredits record
				$memberCredits = new MemberCredits();
				$memberCredits->Credits = $credits;
				$memberCredits->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$memberCredits->MemberID = $member->ID;
				$memberCredits->ProductID = $data['SubscriptionType'];
				$memberCredits->write();
				//Get contact custom fields
				$returnFields = array('_AWofmonths','_AWstartdate');
				$conDat1 = $app->loadCon($isConID,$returnFields);
				if($data['SubscriptionType'] == 1 && !$member->SignUpTrial){
					// Update Member
					$member->SignUpTrial = 1;
					$member->write();
					//Add the Trial tag
					$app->grpAssign($isConID, 2216);
					//Update the contact
					$conDat = array(
							'_AttentionWizard' => 'Free',
							'ContactType' => 'AW Customer',
							'_AWcanceldate' => null
					);
					if(!$conDat1['_AWstartdate']){
						$conDat['_AWstartdate'] = $curdate;
					}
					$app->updateCon($isConID, $conDat);
					// Mark credit card as TrialCreditCard
					$trialCC = new TrialCreditCard();
					$trialCC->CreditCardNumber = $data['CreditCardNumber'];
					$trialCC->MemberID = $member->ID;
					$trialCC->write();
				}else{
					// Add the InfusionSoft tag
					$isTagId = $this->getISTagIdByProduct($data['SubscriptionType']);
					$app->grpRemove($isConID, $isTagId);
					$app->grpAssign($isConID, $isTagId);
					//Remove trial tag if exists
					$app->grpRemove($isConID, 2216);
					//Update the InfusionSoft contact details
					$conDat = array(
						'_AWofmonths' => $conDat1['_AWofmonths']+1,
						'ContactType' => 'AW Customer',	
						'_AttentionWizard' => 'Paid and Current',
						'_AWcanceldate' => null
					);
					if(!$conDat1['_AWstartdate']){
						$conDat['_AWstartdate'] = $curdate;
					}
					$app->updateCon($isConID, $conDat);
					// Note is added
					$conActionDat = array('ContactId' => $isConID,
						'ActionType'  => 'UPDATE',
						'ActionDescription'  => "Renewed AW subscription",
						'CreationDate'  => $curdate,
						'ActionDate'  => $curdate,
						'CompletionDate'  => $curdate,
						'UserID'  => 1
					);
					$conActionID = $app->dsAdd("ContactAction", $conActionDat);
					//Delete all the previous pending orders
					//DB::query("DELETE from `Order` where MemberID = $member->ID AND OrderStatus = 'P'");
				}
				// Remove previous cancel tags
				$app->grpRemove($isConID, 2226);
				$app->grpRemove($isConID, 2758);
				$app->grpRemove($isConID, 2682);
				$app->grpRemove($isConID, 2680);
				$app->grpRemove($isConID, 2694);
				$app->grpRemove($isConID, 3019);
				$app->grpRemove($isConID, 3097);
			}else{
				//Set the subscription to Inactive 
				$this->setSubscriptionStatus($subscriptionID, 'Inactive');
				if($data['SubscriptionType'] == 1 && !$member->SignUpTrial){
					$aw = 'Unsuccessful trial sign-up';
				}else{
					$aw = 'Unsuccessful paid sign-up';
				}
				$conDat = array(
					'_AttentionWizard' => $aw
				);
				$app->updateCon($isConID, $conDat);
				// Add an AW prospect tag
				$app->grpAssign($isConID, $this->getISTagIdByPaymentCode($result['Code']));
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
				$this->setMessage('Error', 'Sorry,the payment has failed due to some reason.');
				$this->redirect('/user-dashboard');
				return false;
			}
		}else{
			// Add an AW prospect tag
			$app->grpAssign($isConID, 3097);
			//Update InfusionSoft contact
			if($data['SubscriptionType'] == 1 && !$member->SignUpTrial){
				$aw = 'Unsuccessful trial sign-up';
			}else{
				$aw = 'Unsuccessful paid sign-up';
			}
			$conDat = array(
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
			$this->setMessage('Error', 'Sorry,the subscription has failed due to some reason.');
			$this->redirect('/user-dashboard');
			return false;
		}
		$this->redirect('/user-dashboard');
	}
	// Show the message when the payment is failed
	function paymentFailed(){
		$data = array(
			'Title' => 'Payment Failed',
			'Content' => 'Sorry the payment has failed due to some reason.',
		);
		return $this->customise($data)->renderWith(array('Page'));
	}
	// Show the message when the subscription is failed
	function subscriptionFailed(){
		$data = array(
				'Title' => 'Subscription Failed',
				'Content' => 'Sorry, the subscription has failed due to some reason.',
		);
		return $this->customise($data)->renderWith(array('Page'));
	}
}