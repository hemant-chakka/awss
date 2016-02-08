<?php
class AccountSettings extends Page {
	
}
class AccountSettings_Controller extends Page_Controller {
	private static $allowed_actions = array(
			'ChangeSubscriptionType',
			'UpdateBillingAddressForm',
			'UpdateRecurringBillingHistory',
			'createSubscription'
	);
	private static $url_handlers = array(
			'ChangeSubscriptionType/$ProductID' => 'ChangeSubscriptionType',
			'createSubscription/$ProductID' => 'createSubscription'
	);
	public function init() {
		parent::init();
		// Note: you should use SS template require tags inside your templates
		// instead of putting Requirements calls here.  However these are
		// included so that our older themes still work
		Requirements::themedCSS('jquery-ui');
		Requirements::javascript('framework/thirdparty/jquery-ui/jquery-ui.js');
		Requirements::javascript('mysite/js/jquery-1.9.1.js');
		Requirements::javascript('mysite/js/jquery-ui.js');
		Requirements::javascript('mysite/js/account-settings.js');
	}
	
	public function isMainBodyFull(){
		return true;
	}
	// Update Billing Address Form
	public function UpdateBillingAddressForm(){
		return new UpdateBillingAddressForm($this, 'UpdateBillingAddressForm');
	}
	
	// Get billing history of the current user
	public function BillingHistory(){
		$billingHistory = new ArrayList();
		$orders = Order::get()->filter(array('MemberID' => Member::currentUserID(),'OrderStatus' => 'c'))->sort('Created');
		foreach($orders as $order){
			$productId = $order->ProductID;
			if(($productId == 1 || $productId == 2 || $productId == 3) && $order->IsTrial == 1 ){
				$productDesc = 'First Month Trial';
			}else{
				$product = Product::get()->byID($productId);
				$productDesc = $product->Name;
			}
			$creditCard = $order->CreditCard();
			$ccNumber = 'XXXX-XXXX-XXXX-'.substr($creditCard->CreditCardNumber, -4);
			$orderDetails = array(
					'Date'=>$order->Created,
					'Description'=>$productDesc,
					'CCType'=>strtoupper($creditCard->CreditCardType),
					'CCNumber'=>$ccNumber,
					'Amount'=>$order->Amount
				);
			$billingHistory->push(new ArrayData($orderDetails));
		}
		$memBillHistory = MemberBillingHistory::get()->filter('MemberID',Member::currentUserID())->sort('Created');
		foreach($memBillHistory as $history){
			$creditCard = $history->CreditCard();
			$ccNumber = 'XXXX-XXXX-XXXX-'.substr($creditCard->CreditCardNumber, -4);
			$details = array(
					'Date'=>$history->Created,
					'Description'=> $history->Product()->Name,
					'CCType'=>strtoupper($creditCard->CreditCardType),
					'CCNumber'=>$ccNumber,
					'Amount'=>$history->Product()->RecurringPrice
			);
			$billingHistory->push(new ArrayData($details));
		}
		$sortedBillingHistory = $billingHistory->sort('Date');
		return $sortedBillingHistory;
	}
	// Upgrade/Downgrade the subscription type
	public function ChangeSubscriptionType($request){
		$newProductID = $request->param('ProductID');
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		// Get AttentionWizard member
		$member = Member::currentUser();
		// Get IndusionSoft contact ID
		$isConID = $member->ISContactID;
		//Get current date
		$curdate = $app->infuDate(date('j-n-Y'));
		// Get the current subscription
		$subscription = $this->getCurrentSubscription($member->ID);
		//Get old order
		$oldOrder = $subscription->Order();
		//Get new product
		$product = Product::get()->byID($newProductID);
		$credits = $product->Credits;
		$isProductID = $product->ISProductID;
		// Get the current InfusionSoft credit card ID
		$creditCard = $this->getCurrentCreditCard($member->ID);
		$ccID = $creditCard->ISCCID;
		$subscriptionID = $this->createISSubscription($isConID,$isProductID, $product->RecurringPrice, $ccID, 30);
		if($subscriptionID && is_int($subscriptionID)){
			// Create an order
			$order = new Order();
			$order->OrderStatus = 'P';
			$order->Amount = $product->RecurringPrice;
			$order->MemberID = $member->ID;
			$order->ProductID = $newProductID;
			$order->CreditCardID = $creditCard->ID;
			$orderID = $order->write();
			//Create an infusionsoft order
			$config = SiteConfig::current_site_config(); 
			$invoiceId = $app->blankOrder($isConID,$product->Name, $curdate, 0, 0);
			$orderItem = $app->addOrderItem($invoiceId, $isProductID, 9, floatval($product->RecurringPrice), 1,$product->Name, $product->Name);
			$result = $app->chargeInvoice($invoiceId,$product->Name,$ccID,$config->MerchantAccount,false);
			if($result['Successful']){
				//Set the current subscription to Inactive
				$this->setSubscriptionStatus($subscription->SubscriptionID, 'Inactive');
				//Remove trial tag if exists
				$app->grpRemove($isConID, 2216);
				//get old Tag ID
				if($this->isTrialMember($member->ID))
					$oldISTagID = 2216;
				else
					$oldISTagID = $this->getISTagIdByProduct($oldOrder->ProductID);
				//Remove old tag ID
				$app->grpRemove($isConID, $oldISTagID);
				$newISTagID = $this->getISTagIdByProduct($newProductID);
				//Add new tag ID
				$app->grpAssign($isConID, $newISTagID);
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
				$returnFields = array('_AWofmonths');
				$data = $app->loadCon($isConID,$returnFields);
				if(!isset($data['_AWofmonths']))
					$data['_AWofmonths'] = 0;
				$conDat = array(
					'_AWofmonths' => $data['_AWofmonths']+1,
					'_AttentionWizard' => 'Paid and Current'
				);
				$app->updateCon($isConID, $conDat);
				//Create a new Subscription
				$newSubscription = new Subscription();
				$newSubscription->StartDate = date("Y-m-d H:i:s");
				$expireDate = strtotime("+30 days");
				$newSubscription->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$newSubscription->SubscriptionID = $subscriptionID;
				$newSubscription->Status = 1;
				$newSubscription->IsTrial = 0;
				$newSubscription->SubscriptionCount = 1;
				$newSubscription->MemberID = $member->ID;
				$newSubscription->ProductID = $newProductID;
				$newSubscription->OrderID = $orderID;
				$newSubscription->write();
				// Create a MemberCredits record
				$memberCredits = new MemberCredits();
				$memberCredits->Credits = $credits;
				$memberCredits->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$memberCredits->MemberID = $member->ID;
				$memberCredits->ProductID = $newProductID;
				$memberCredits->SubscriptionID = $newSubscription->ID;
				$memberCredits->write();
				// Update order
				$order->OrderStatus = 'c';
				$order->write();
				//Update old subscription status
				$subscription->Status = 0;
				$subscription->IsTrial = 0;
				$subscription->write();
				$oldProduct = $subscription->Product();
				//Send an email to the user
				$email = new Email();
				$email->setSubject("Your {$oldProduct->Name} Subscription Has Been Cancelled");
        		$email->setFrom('support@attentionwizard.com');
				$email->setTo($member->Email);
				$email->setTemplate('CancelSubscriptionEmail');
				$email->populateTemplate(array(
				    'firstName' => $member->FirstName,
			 		'lastName' => $member->Surname
				));
			$email->send();
			}else{
				//Set the subscription to Inactive 
				$this->setSubscriptionStatus($subscriptionID, 'Inactive');
				$this->setMessage('Error', 'Sorry,the payment has failed due to some reason.please update your credit card');
				return $this->redirect('/account-settings/#tabs-2');
			}
		}else{
			$this->setMessage('Error', 'Sorry,the subscription has failed due to some reason.please try again');
			return $this->redirect('/account-settings/#tabs-4');
		}
		$this->setMessage('Success', 'The Subscription is changed successfully');
		return $this->redirect('/account-settings');
	}
	// Create a new subscription
	function createSubscription($request){
		//Get product id
		$productID = $request->param('ProductID');
		// Get AttentionWizard member
		$member = Member::currentUser();
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		// Get IndusionSoft contact ID
		$isConID = $member->ISContactID;
		$product = Product::get()->byID($productID);
		$credits = $product->Credits;
		// Get existing credit card ID
		$creditCard = $this->getCurrentCreditCard($member->ID);
		$ccID = $creditCard->ISCCID;
		$subscriptionID = $this->createISSubscription($isConID,$product->ISProductID, $product->RecurringPrice, $ccID, 30);
		if($subscriptionID && is_int($subscriptionID)){
			if($productID == 1 && !$this->isCCUsedForTrial($creditCard->CreditCardNumber)){
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
			$order->MemberID = $member->ID;
			$order->ProductID = $productID;
			$order->CreditCardID = $creditCard->ID;
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
				$subscription->ProductID = $productID;
				$subscription->OrderID = $orderID;
				$subscription->write();
				// Create a MemberCredits record
				$memberCredits = new MemberCredits();
				$memberCredits->Credits = $credits;
				$memberCredits->ExpireDate = date("Y-m-d H:i:s",$expireDate);
				$memberCredits->MemberID = $member->ID;
				$memberCredits->ProductID = $productID;
				$memberCredits->SubscriptionID = $subscription->ID;
				$memberCredits->write();
				//Get contact custom fields
				$returnFields = array('_AWofmonths','_AWstartdate');
				$conDat1 = $app->loadCon($isConID,$returnFields);
				if($productID == 1 && !$this->isCCUsedForTrial($creditCard->CreditCardNumber)){
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
					if(!isset($conDat1['_AWstartdate']))
						$conDat['_AWstartdate'] = $curdate;
					$app->updateCon($isConID, $conDat);
					// Mark credit card as TrialCreditCard
					$creditCard->UsedForTrial = 1;
					$creditCard->write();
				}else{
					if(!isset($conDat1['_AWofmonths']))
						$conDat1['_AWofmonths'] = 0;
					// Add the InfusionSoft tag
					$isTagId = $this->getISTagIdByProduct($productID);
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
					if(!isset($conDat1['_AWstartdate']))
						$conDat['_AWstartdate'] = $curdate;
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
				if($productID == 1 && !$this->isCCUsedForTrial($creditCard->CreditCardNumber)){
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
				$this->setMessage('Error', 'Sorry,the payment has failed due to some reason.please update your credit card');
				return $this->redirect('/account-settings/#tabs-2');
			}
		}else{
			// Add an AW prospect tag
			$app->grpAssign($isConID, 3097);
			//Update InfusionSoft contact
			if($productID == 1 && !$this->isCCUsedForTrial($creditCard->CreditCardNumber)){
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
			$this->setMessage('Error', 'Sorry,the subscription has failed due to some reason.please try again');
			return $this->redirect('/account-settings/#tabs-4');
		}
		$this->setMessage('Success', 'The Subscription is created successfully');
		return $this->redirect('/account-settings');
	}
	// Get the tab number
	public function Tab(){
		$this->Title = "Account Settings";
		return $this->request->param('Tab');
	}
	
}

class UpdateBillingAddressForm extends Form {
    public function __construct($controller, $name) {
    	$member = Member::currentUser();
		$isConID = $member->ISContactID;
		//Get the member current credit card 
		$creditCard = Controller::curr()->getCurrentCreditCard($member->ID);
		//Get InfusionSoft Api
		//$app = Controller::curr()->getInfusionSoftApi();
		//$returnFields = array('FirstName','LastName','Company','StreetAddress1','StreetAddress2','City','State','PostalCode','Country');
		//$conDat = $app->loadCon($isConID, $returnFields);
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
	    if($creditCard)
	    	$yearArray[$creditCard->ExpiryYear] = $creditCard->ExpiryYear;
		// Format and hide the credit card number
		$creditCardLast4 = '';
	    if($creditCard)
	    	$creditCardLast4 = substr($creditCard->CreditCardNumber, -4);
		$creditCardMask = "XXXX-XXXX-XXXX-$creditCardLast4";	
		$fields = new FieldList(
				new OptionsetField('CreditCardType','Credit Card Type',$cardType,($creditCard)? $creditCard->CreditCardType:''),
				new TextField('NameOnCard', 'Name On Card',($creditCard)? $creditCard->NameOnCard:''),
				new TextField('CreditCardNumber', 'Credit Card Number',$creditCardMask),
				new HiddenField('CreditCardNumberCopy',null,$creditCardMask),
				new HiddenField('CreditCardNumberCur',null,($creditCard)? $creditCard->CreditCardNumber:''),
				new PasswordField('CreditCardCVV', 'Security/CVV Code',($creditCard)? $creditCard->CreditCardCVV:''),
				new DropdownField('ExpiryMonth','Expiry Date',$monthArray,($creditCard)? $creditCard->ExpiryMonth:null),
				new DropdownField('ExpiryYear','',$yearArray,($creditCard)? $creditCard->ExpiryYear:null),
				new TextField('FirstName', 'First Name',$member->FirstName),
				new TextField('LastName', 'Last Name',$member->Surname),
				new TextField('Company', 'Company(optional)',($creditCard)? $creditCard->Company:''),
				new TextField('StreetAddress1', 'Street Address1',($creditCard)? $creditCard->StreetAddress1:''),
				new TextField('StreetAddress2', 'Street Address2(optional)',($creditCard)? $creditCard->StreetAddress2:''),
				new TextField('City', 'City',($creditCard)? $creditCard->City:''),
				new TextField('State', 'State',($creditCard)? $creditCard->State:''),
				new TextField('PostalCode', 'Zip/Postal Code',($creditCard)? $creditCard->PostalCode:''),
				new CountryDropdownField('Country',null,null,($creditCard)? $creditCard->Country:null)
		);
		// Create action
		$actions = new FieldList(
				$submit = new FormAction('UpdateBillingAddress','')
		);
		$submit->setAttribute('src', 'themes/attwiz/images/button_update_billing.png');
		// Create action
		$validator = new RequiredFields('CreditCardType','NameOnCard','CreditCardNumber','CVVCode','ExpirationMonth','ExpirationYear','FirstName','LastName','StreetAddress1','City','State','PostalCode','Country');
        parent::__construct($controller, $name, $fields, $actions,$validator);
    }
	// Update Billing Address Form Action
	public function UpdateBillingAddress($data,$form){
		$member = Member::currentUser();
		$isConID = $member->ISContactID;
		//Get InfusionSoft Api
		$creditCardType = Controller::curr()->getISCreditCardType($data['CreditCardType']);
		$app = Controller::curr()->getInfusionSoftApi();
		if(!isset($data['CreditCardNumber']) || (($data['CreditCardNumber'] != $data['CreditCardNumberCopy']) && !is_numeric($data['CreditCardNumber']))){
			Controller::curr()->setMessage('Error', 'The credit card is invalid!');
			return Controller::curr()->redirect(Controller::curr()->Link('#tabs-2'));
		}
		if($data['CreditCardNumber'] == $data['CreditCardNumberCopy'])
			$cardNumber = $data['CreditCardNumberCur'];
		else
			$cardNumber = $data['CreditCardNumber'];
		// Validate credit card
		$card = array(
			'CardType' => $creditCardType,
   	   		'ContactId' => $isConID,
	       	'CardNumber' => $cardNumber,
	   		'ExpirationMonth' => sprintf("%02s", $data['ExpiryMonth']),
		    'ExpirationYear' => $data['ExpiryYear'],
    		'CVV2' => $data['CreditCardCVV']
		);
		$result = $app->validateCard($card);
		if($result['Valid'] != 'true'){
			Controller::curr()->setMessage('Error', 'The credit card is invalid!');
			return Controller::curr()->redirect(Controller::curr()->Link('#tabs-2'));
		}
		// Get country text from code
		$country = Geoip::countryCode2name($data['Country']);
		if($data['CreditCardNumber'] == $data['CreditCardNumberCopy']){
			//Get the credit card
			$creditCard = $creditCard = CreditCard::get()->filter(array(
   				'CreditCardNumber' => $data['CreditCardNumberCur']
			))->First();
			//Update the credit card on InfusionSoft
			$ccData = array(
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
				'CVV2'  => $data['CreditCardCVV'],
				'ExpirationMonth'  => sprintf("%02s", $data['ExpiryMonth']),
				'ExpirationYear'  => $data['ExpiryYear']
			);
			$app->dsUpdate("CreditCard", $creditCard->ISCCID, $ccData);
			//Update the credit card on site
			$creditCard->CreditCardType = $data['CreditCardType'];
			$creditCard->NameOnCard = $data['NameOnCard'];
			$creditCard->CreditCardCVV = $data['CreditCardCVV'];
			$creditCard->ExpiryMonth = $data['ExpiryMonth'];
			$creditCard->ExpiryYear = $data['ExpiryYear'];
			$creditCard->Company = $data['Company'];
			$creditCard->StreetAddress1 = $data['StreetAddress1'];
			$creditCard->StreetAddress2 = $data['StreetAddress2'];
			$creditCard->City = $data['City'];
			$creditCard->State = $data['State'];
			$creditCard->PostalCode = $data['PostalCode'];
			$creditCard->Country = $data['Country'];
			$creditCard->Current = 1;
			$creditCard->write();
		}else{
			//Find if the credit card exist
			$creditCard = CreditCard::get()->filter(array(
   				'CreditCardNumber' => $data['CreditCardNumber']
			))->First();
			if($creditCard){
				//Get current credit card, un-mark it as current
				Controller::curr()->unsetCurrentCreditCard($member->ID);
				//Update the credit card on InfusionSoft
				$ccData = array(
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
					'CVV2'  => $data['CreditCardCVV'],
					'ExpirationMonth'  => sprintf("%02s", $data['ExpiryMonth']),
					'ExpirationYear'  => $data['ExpiryYear']
				);
				$app->dsUpdate("CreditCard", $creditCard->ISCCID, $ccData);
				//Update the credit card on site
				$creditCard->CreditCardType = $data['CreditCardType'];
				$creditCard->NameOnCard = $data['NameOnCard'];
				$creditCard->CreditCardCVV = $data['CreditCardCVV'];
				$creditCard->ExpiryMonth = $data['ExpiryMonth'];
				$creditCard->ExpiryYear = $data['ExpiryYear'];
				$creditCard->Company = $data['Company'];
				$creditCard->StreetAddress1 = $data['StreetAddress1'];
				$creditCard->StreetAddress2 = $data['StreetAddress2'];
				$creditCard->City = $data['City'];
				$creditCard->State = $data['State'];
				$creditCard->PostalCode = $data['PostalCode'];
				$creditCard->Country = $data['Country'];
				$creditCard->Current = 1;
				$creditCard->write();
			}else{
				// Add the credit card on InfusionSoft
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
					'CVV2'  => $data['CreditCardCVV'],
					'ExpirationMonth'  => sprintf("%02s", $data['ExpiryMonth']),
					'ExpirationYear'  => $data['ExpiryYear']
				);
				$ccID = $app->dsAdd("CreditCard", $ccData);
				//Get current credit card un-mark it as current
				Controller::curr()->unsetCurrentCreditCard($member->ID);
				// Store Credit card on site and mark it as current
				$newCreditCard = new CreditCard();
				$newCreditCard->CreditCardType = $data['CreditCardType'];
				$newCreditCard->CreditCardNumber = $data['CreditCardNumber'];
				$newCreditCard->NameOnCard = $data['NameOnCard'];
				$newCreditCard->CreditCardCVV = $data['CreditCardCVV'];
				$newCreditCard->ExpiryMonth = $data['ExpiryMonth'];
				$newCreditCard->ExpiryYear = $data['ExpiryYear'];
				$newCreditCard->Company = $data['Company'];
				$newCreditCard->StreetAddress1 = $data['StreetAddress1'];
				$newCreditCard->StreetAddress2 = $data['StreetAddress2'];
				$newCreditCard->City = $data['City'];
				$newCreditCard->State = $data['State'];
				$newCreditCard->PostalCode = $data['PostalCode'];
				$newCreditCard->Country = $data['Country'];
				$newCreditCard->Current = 1;
				$newCreditCard->ISCCID = $ccID;
				$newCreditCard->MemberID = $member->ID;
				$newCreditCard->write();
			}
		}
		//Update Member
		$member->FirstName = $data['FirstName'];
		$member->Surname = $data['LastName'];
		$member->write();
		// Update billing address on InfusionSoft
		$conDat = array(
			'FirstName' => $data['FirstName'],
			'LastName' => $data['LastName'],
			'Company' => $data['Company'],
			'StreetAddress1' => $data['StreetAddress1'],
			'StreetAddress2' => $data['StreetAddress2'],
			'City' => $data['City'],
			'State' => $data['State'],
			'PostalCode' => $data['PostalCode'],
			'Country' => $country
		);
		$conID = $app->updateCon($isConID, $conDat);
		Controller::curr()->setMessage('Success', 'Credit Card and Billing Address successfully updated.');	
		return Controller::curr()->redirect(Controller::curr()->Link('#tabs-2'));
	}
	
    public function forTemplate() {
        return $this->renderWith(array($this->class, 'Form'),array('unsetFormMessage'=>$this->unsetFormMessage()));
    }
    // Unset form message
	public function unsetFormMessage(){
		unset($_SESSION['FormInfo']['UpdateBillingAddressForm_UpdateBillingAddressForm']['formError']);
		return ''; 
	}
}