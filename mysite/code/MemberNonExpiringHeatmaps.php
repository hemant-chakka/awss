<?php 

class MemberNonExpiringHeatmaps extends Page 
{

}

class MemberNonExpiringHeatmaps_Controller extends Page_Controller 
{
	//Allow our form as an action
	private static $allowed_actions = array(
		'MemberNonExpiringHeatmapsForm'
	);
	
	function init(){
		parent::init();
		Requirements::javascript('mysite/js/purchase-member-nonexp-heatmaps.js');
	}
	
	//Generate the Member Non-Expiring Heatmaps purchase form
	function MemberNonExpiringHeatmapsForm(){
		// Get current member
		$member = Member::currentUser();
		$isContactID = $member->ISContactID;
		$subscription = $this->getCurrentSubscription($member->ID);
		switch ($subscription->Product()->ID) {
			case 1:
				$productID = 4;
				break;
			case 2:
				$productID = 5;
				break;
			case 3:
				$productID = 6;
				break;
		}
		$price = Product::get()->byID($productID)->Price;
		$shoppingCart = $this->renderWith('PrepaidShoppingCart',array('Price' => $price));
		// Get existing credit card ID
		$creditCard = $this->getCurrentCreditCard($member->ID);
		$fields = new FieldList(
			new HiddenField('FirstName', 'First Name',$member->FirstName),
			new HiddenField('LastName', 'Last Name',$member->Surname),
			new HiddenField('Company', 'Company(optional)',$creditCard->Company),
			new HiddenField('StreetAddress1', 'Street Address1',$creditCard->StreetAddress1),
			new HiddenField('StreetAddress2', 'Street Address2(optional)',$creditCard->StreetAddress2),
			new HiddenField('City', 'City',$creditCard->City),
			new HiddenField('State', 'State/Province',$creditCard->State),
			new HiddenField('PostalCode', 'Zip/Poatal Code',$creditCard->PostalCode),
			new HiddenField('Country','Country',$creditCard->Country),
			new HiddenField('CreditCardType','Credit Card Type',$creditCard->CreditCardType),
			new HiddenField('NameOnCard', 'Name On Card',$creditCard->NameOnCard),
			new HiddenField('CreditCardNumber', 'Credit Card Number',$creditCard->CreditCardNumber),
			new HiddenField('CVVCode', 'Security/CVV Code',$creditCard->CreditCardCVV),
			new HiddenField('ExpirationMonth','Expiration Date',$creditCard->ExpiryMonth),
			new HiddenField('ExpirationYear','',$creditCard->ExpiryYear),
			new LiteralField('ShoppingCart', $shoppingCart),
			new HiddenField('ProductID','',$productID),
			new HiddenField('Price','',$price),
			new HiddenField('Quantity','',1)
		);
	    // Create action
	    $actions = new FieldList(
			$submit = new FormAction('doPurchase','Purchase Heatmaps')
	    );
	    $submit->setAttribute('src', 'themes/attwiz/images/button_purchase.png');
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
							'ExpirationYear'
		);
	 	return new Form($this, 'MemberNonExpiringHeatmapsForm', $fields, $actions, $validator);		
	}
	//Process Member Non-Expiring Heatmaps form
	function doPurchase($data,$form){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		// Get curent date
		$curdate = $app->infuDate(date('j-n-Y'));
		$member = Member::currentUser();
		$isConID = $member->ISContactID;
		$product = Product::get()->byID($data['ProductID']);
		// Get existing credit card ID
		$creditCard = $this->getCurrentCreditCard($member->ID);
		// Get the current InfusionSoft credit card ID
		$ccID = $creditCard->ISCCID;
		// Create an Infusionsoft order
		$config = SiteConfig::current_site_config();
		$invoiceId = $app->blankOrder($isConID,$product->Name, $curdate, 0, 0);
		$orderItem = $app->addOrderItem($invoiceId, intval($this->getNonExpiringIsProductId($data['ProductID'])), 3, floatval($product->Price), intval($data['Quantity']), $product->Name, $product->Name);
		$result = $app->chargeInvoice($invoiceId,$product->Name,$ccID,$config->MerchantAccount,false);
		// Create an order
		$order = new Order();
		$order->OrderStatus = 'P';
		$order->Amount = $product->Price * $data['Quantity'];
		$order->MemberID = $member->ID;
		$order->ProductID = $data['ProductID'];
		$order->CreditCardID = $creditCard->ID;
		$orderID = $order->write();
		if($result['Successful']){
			// Add tag Paid member - prepaid
			$app->grpAssign($isConID, 2290);
			$conDat = array(
					'ContactType' => 'AW Customer'
			);
			$returnFields = array('_AttentionWizard');
			$conDat1 = $app->loadCon($isConID,$returnFields);
			if($conDat1['_AttentionWizard'] != 'Paid and Current' && $conDat1['_AttentionWizard'] != 'Free')
				$conDat['_AttentionWizard'] = 'Prepaid only - no subscription';
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
			$subscription->MemberID = $member->ID;
			$subscription->ProductID = $data['ProductID'];
			$subscription->OrderID = $orderID;
			$subscription->write();
			// Create a MemberCredits record
			$memberCredits = new MemberCredits();
			$memberCredits->Credits = ($product->Credits)*$data['Quantity'];
			$memberCredits->MemberID = $member->ID;
			$memberCredits->ProductID = $product->ID;
			$memberCredits->SubscriptionID = $subscription->ID;
			$memberCredits->write();
			$this->setMessage('Success', 'Purchased non-expiring heatmaps successfully.');
		}else{
			// Add an AW prospect tag
			$app->grpAssign($isConID, $this->getISTagIdByPaymentCode(strtoupper($result['Code'])));
			// Add a note
			$conActionDat = array('ContactId' => $isConID,
				'ActionType'  => 'UPDATE',
				'ActionDescription'  => "Unsuccessful attempt to purchase prepaid plan",
				'CreationDate'  => $curdate,
				'ActionDate'  => $curdate,
				'CompletionDate'  => $curdate,
				'UserID'  => 1
			);
			$conActionID = $app->dsAdd("ContactAction", $conActionDat);
			$this->setMessage('Error', 'Sorry,the payment has failed,please update your credit card.');
			return $this->redirect('/account-settings/#tabs-2');
		}
		return $this->redirect('/account-settings');
	}
}