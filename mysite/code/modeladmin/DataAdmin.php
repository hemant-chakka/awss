<?php
class DataAdmin extends ModelAdmin {
	private static $managed_models = array('Product','Member','Subscription','CreditCard','Heatmaps','Faq','AccountFaq'); // Can manage multiple models
	private static $url_segment = 'manage-data'; // Linked as /admin/products/
	private static $menu_title = 'Manage Data';

	public function getEditForm($id = null, $fields = null) { 
        $form = parent::getEditForm($id, $fields); 
        $listField = $form->Fields()->fieldByName($this->modelClass); 
        if ($gridField = $listField->getConfig()->getComponentByType('GridFieldDetailForm')) 
        	$gridField->setItemRequestClass('AwFieldDetailForm_ItemRequest'); 
		if ($this->modelClass == 'Faq') {
			$gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
			$gridField->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));
		}
		if ($this->modelClass == 'AccountFaq') {
			$gridField = $form->Fields()->fieldByName($this->sanitiseClassName($this->modelClass));
			$gridField->getConfig()->addComponent(new GridFieldSortableRows('SortOrder'));
		}
        return $form; 
    }
    public function getList() {
        $list = parent::getList();
        if($this->modelClass == 'Subscription') {
            $list = $list->exclude('Member.ID', null);
        }
    	if($this->modelClass == 'CreditCard') {
            $list = $list->exclude('Member.ID', null);
        }
	    if($this->modelClass == 'Heatmaps') {
            $list = $list->exclude('Member.ID', null);
        }
        return $list;
    } 
}


class AwFieldDetailForm_ItemRequest extends GridFieldDetailForm_ItemRequest { 
    
    private static $allowed_actions = array ( 
         'edit', 
         'view', 
         'ItemEditForm' 
    ); 
       
    public function ItemEditForm() { 
       $form = parent::ItemEditForm(); 
       $formActions = $form->Actions(); 
       if ($actions = $this->record->getCMSActions()) 
           foreach ($actions as $action) 
              $formActions->push($action); 
       return $form; 
    }
    //Create a subscription  
    public function createSubscription($data, $form) { 
    	//Get member id
    	$memberId = $data['MemberID'];
    	//Get product id
    	$productId = $data['ProductID'];
    	// Get the Page controller
		$Page_Ctrl = new Page_Controller;
		//Get InfusionSoft Api
		$app = $Page_Ctrl->getInfusionSoftApi();
		// Get curent date
		$curdate = $app->infuDate(date('j-n-Y'));
		$member = Member::get()->byID($memberId);
		$isConID = $member->ISContactID;
		$product = Product::get()->byID($productId);
		// Get existing credit card ID
		$creditCard = $Page_Ctrl->getCurrentCreditCard($memberId);
		// Get the current InfusionSoft credit card ID
		$ccID = $creditCard->ISCCID;
		//Get siteconfig object
		$config = SiteConfig::current_site_config();
		//Process non-expiring heatmaps purchase		
    	if($productId == 4 || $productId == 5 || $productId == 6 || $productId == 7){
    		if(!$data['Quantity']){
    			$form->addErrorMessage('Quantity',"Quantity is required for non-expiring products.", 'bad');
				return $this->edit(Controller::curr()->getRequest());
    		}
			// Create an Infusionsoft order
			$invoiceId = $app->blankOrder($isConID,$product->Name, $curdate, 0, 0);
			$orderItem = $app->addOrderItem($invoiceId, intval($Page_Ctrl->getNonExpiringIsProductId($data['ProductID'])), 3, floatval($product->Price), intval($data['Quantity']), $product->Name, $product->Name);
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
				$form->sessionMessage("Purchased non-expiring heatmaps successfully.", 'good');
				return Controller::curr()->redirect("admin/manage-data/Subscription/EditForm/field/Subscription/item/{$subscription->ID}/edit");
			}else{
				// Add an AW prospect tag
				$app->grpAssign($isConID, $Page_Ctrl->getISTagIdByPaymentCode(strtoupper($result['Code'])));
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
				$form->sessionMessage("Sorry,the payment has failed,please update the user credit card.", 'bad');
				return $this->edit(Controller::curr()->getRequest());
			}
		}
		//Process subscriptions
		if($productId == 1 || $productId == 2 || $productId == 3){
			$credits = $product->Credits;
			$subscriptionID = $Page_Ctrl->createISSubscription($isConID,$product->ISProductID, $product->RecurringPrice, $ccID, 30);
			if($subscriptionID && is_int($subscriptionID)){
				if($productId == 1 && !$Page_Ctrl->isCCUsedForTrial($creditCard->CreditCardNumber)){
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
				$order->ProductID = $productId;
				$order->CreditCardID = $creditCard->ID;
				$orderID = $order->write();
				//Create an infusionsoft order
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
					$subscription->ProductID = $productId;
					$subscription->OrderID = $orderID;
					$subscription->write();
					// Create a MemberCredits record
					$memberCredits = new MemberCredits();
					$memberCredits->Credits = $credits;
					$memberCredits->Expire = 1;
					$memberCredits->ExpireDate = date("Y-m-d H:i:s",$expireDate);
					$memberCredits->MemberID = $member->ID;
					$memberCredits->ProductID = $productId;
					$memberCredits->SubscriptionID = $subscription->ID;
					$memberCredits->write();
					//Get contact custom fields
					$returnFields = array('_AWofmonths','_AWstartdate');
					$conDat1 = $app->loadCon($isConID,$returnFields);
					if($productId == 1 && !$Page_Ctrl->isCCUsedForTrial($creditCard->CreditCardNumber)){
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
						$isTagId = $Page_Ctrl->getISTagIdByProduct($productId);
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
					}
					// Remove previous cancel tags
					$app->grpRemove($isConID, 2226);
					$app->grpRemove($isConID, 2758);
					$app->grpRemove($isConID, 2682);
					$app->grpRemove($isConID, 2680);
					$app->grpRemove($isConID, 2694);
					$app->grpRemove($isConID, 3019);
					$app->grpRemove($isConID, 3097);
					$form->sessionMessage("The Subscription is created successfully.", 'good');
					return Controller::curr()->redirect("admin/manage-data/Subscription/EditForm/field/Subscription/item/{$subscription->ID}/edit");
				}else{
					//Set the subscription to Inactive 
					$Page_Ctrl->setSubscriptionStatus($subscriptionID, 'Inactive');
					if($productId == 1 && !$Page_Ctrl->isCCUsedForTrial($creditCard->CreditCardNumber)){
						$aw = 'Unsuccessful trial sign-up';
					}else{
						$aw = 'Unsuccessful paid sign-up';
					}
					$conDat = array(
						'_AttentionWizard' => $aw
					);
					$app->updateCon($isConID, $conDat);
					// Add an AW prospect tag
					$app->grpAssign($isConID, $Page_Ctrl->getISTagIdByPaymentCode($result['Code']));
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
					$form->sessionMessage("Sorry,the payment has failed due to some reason.please update the user credit card.", 'bad');
					return $this->edit(Controller::curr()->getRequest());
				}
			}else{
				// Add an AW prospect tag
				$app->grpAssign($isConID, 3097);
				//Update InfusionSoft contact
				if($productId == 1 && !$Page_Ctrl->isCCUsedForTrial($creditCard->CreditCardNumber)){
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
				$form->sessionMessage("Sorry,the subscription has failed due to some reason.please try again.", 'bad');
				return $this->edit(Controller::curr()->getRequest());
			}
		}
    }
	//Admin cancel subscription
	public function cancelSubscription($data, $form) { 
		//Get the record ID
		$id = Controller::curr()->request->param('ID');
		// Get the reasons for cancel
		$reasons = $data['ReasonCancelled'];
		// Get the subscription
		$subscription = Subscription::get()->byID($id);
		// Get the member
		$member = $subscription->Member();
		// Get the member order
		$order = $subscription->Order();
		// Get the account settings controller
		$As_Ctrl = new AccountSettings_Controller;
		//Get InfusionSoft Api
		$app = $As_Ctrl->getInfusionSoftApi();
		//Set the current subscription to Inactive 
		$result = $As_Ctrl->setSubscriptionStatus($subscription->SubscriptionID, 'Inactive');
		if(is_int($result)){
			$isConId = $member->ISContactID;
			//Remove IS trial tag
			$app->grpRemove($isConId, 2216);
			$date = $app->infuDate(date('j-n-Y'));
			if($As_Ctrl->isTrialMember($member->ID)){
				// Add tag - Cancelled after trial
				$app->grpAssign($isConId, 2226);
				//Add a note
				$conActionDat = array('ContactId' => $isConId,
						'ActionType'  => 'UPDATE',
						'ActionDescription'  => "Cancelled AW Trial",
						'CreationDate'  => $date,
						'ActionDate'  => $date,
						'CompletionDate'  => $date,
						'CreationNotes'     => $reasons,
						'UserID'  => 1
				);
				$conActionID = $app->dsAdd("ContactAction", $conActionDat);
				//Update IS Contact
				$conDat = array(
						'_AttentionWizard' => 'Cancelled after trial',
						'_AWcanceldate' => $date
				);
				$conID = $app->updateCon($isConId, $conDat);
			}else{
				// Add tag - Cancelled paid subscription
				$app->grpAssign($isConId, 2758);
				// Add a note
				$conActionDat = array('ContactId' => $isConId,
					'ActionType'  => 'UPDATE',
					'ActionDescription'  => "Cancelled AW Subscription",
					'CreationDate'  => $date,
					'ActionDate'  => $date,
					'CompletionDate'  => $date,
					'CreationNotes'     => $reasons,
					'UserID'  => 1
				);
				$conActionID = $app->dsAdd("ContactAction", $conActionDat);
				// Update IS Contact
				$conDat = array(
					'_AttentionWizard' => 'Cancelled paid subscription',
					'_AWcanceldate' => $date
				);
				$conID = $app->updateCon($isConId, $conDat);
			}
			// Set the subscription status to inactive
			$subscription->Status = 0;
			$subscription->ReasonCancelled = $reasons;
			$subscription->write();
			$form->sessionMessage("Subscription is cancelled successfully.", 'good');
			$randomNumber = rand();
			return Controller::curr()->redirect("admin/manage-data/Subscription/EditForm/field/Subscription/item/{$subscription->ID}/edit?rand=$randomNumber");
		}else{
			$form->sessionMessage("Subscription could not be cancelled,please try again later.", 'bad');
			return $this->edit(Controller::curr()->getRequest());
		}
	}
	// Admin change subscription
	public function changeSubscription($data, $form) {
		//Get the record ID
		$id = Controller::curr()->request->param('ID');
		// Get the subscription
		$subscription = Subscription::get()->byID($id);		
		//Get the new product ID
		$newProductID = $data['ProductID'];
		if($subscription->ProductID == $newProductID){
			$form->sessionMessage("Please select a new subscription first.", 'bad');
			return $this->edit(Controller::curr()->getRequest());
		}
		// Get the Page controller
		$Pg_Ctrl = new Page_Controller();
		// Get InfusionSoft Api
		$app = $Pg_Ctrl->getInfusionSoftApi();
		// Get AttentionWizard member
		$member = Member::get()->byID($data['MemberID']);
		// Get IndusionSoft contact ID
		$isConID = $member->ISContactID;
		//Get current date
		$curdate = $app->infuDate(date('j-n-Y'));
		//Get old order
		$oldOrder = $subscription->Order();
		//Get new product
		$product = Product::get()->byID($newProductID);
		$credits = $product->Credits;
		$isProductID = $product->ISProductID;
		// Get the current InfusionSoft credit card ID
		$creditCard = $Pg_Ctrl->getCurrentCreditCard($member->ID);
		if(!$creditCard){
			$form->sessionMessage("The user does not have a Credit Card on account, please add a credit card.", 'bad');
			return $this->edit(Controller::curr()->getRequest());
		}
		$ccID = $creditCard->ISCCID;
		$subscriptionID = $Pg_Ctrl->createISSubscription($isConID,$isProductID, $product->RecurringPrice, $ccID, 30);
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
				$Pg_Ctrl->setSubscriptionStatus($subscription->SubscriptionID, 'Inactive');
				//Remove trial tag if exists
				$app->grpRemove($isConID, 2216);
				//get old Tag ID
				if($Pg_Ctrl->isTrialMember($member->ID))
					$oldISTagID = 2216;
				else
					$oldISTagID = $Pg_Ctrl->getISTagIdByProduct($oldOrder->ProductID);
				//Remove old tag ID
				$app->grpRemove($isConID, $oldISTagID);
				$newISTagID = $Pg_Ctrl->getISTagIdByProduct($newProductID);
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
				$conData = $app->loadCon($isConID,$returnFields);
				$conDat = array(
					'_AWofmonths' => (isset($conData['_AWofmonths'])?$conData['_AWofmonths']:0)+1,
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
				$memberCredits->Expire = 1;
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
			}else{
				//Set the subscription to Inactive 
				$Pg_Ctrl->setSubscriptionStatus($subscriptionID, 'Inactive');
				$form->sessionMessage("Sorry,the payment has failed due to some reason.please update your credit card", 'bad');
				return $this->edit(Controller::curr()->getRequest());
			}
		}else{
			$form->sessionMessage("Sorry,the subscription has failed due to some reason.please try again", 'bad');
			return $this->edit(Controller::curr()->getRequest());
		}
		$form->sessionMessage("The Subscription is changed successfully.", 'good');
		return Controller::curr()->redirect("admin/manage-data/Subscription/EditForm/field/Subscription/item/{$newSubscription->ID}/edit");
	}
	//Admin add credit card / billing info
	public function addCreditCard($data, $form){
		//Check if the credit card exists
		$creditCard = CreditCard::get()->filter(array(
   			'CreditCardNumber' => $data['CreditCardNumber']
		))->First();
		if($creditCard){
			$form->sessionMessage("The credit card exists already!", 'bad');
			return $this->edit(Controller::curr()->getRequest());
		}
		//Get the member
		$member = Member::get()->byID($data['MemberID']);
		$isConID = $member->ISContactID;
		// Get the Page controller
		$Pg_Ctrl = new Page_Controller();
		//Get InfusionSoft Api
		$creditCardType = $Pg_Ctrl->getISCreditCardType($data['CreditCardType']);
		$app = $Pg_Ctrl->getInfusionSoftApi();
		// Validate credit card
		$card = array(
			'CardType' => $creditCardType,
   	   		'ContactId' => $isConID,
	       	'CardNumber' => $data['CreditCardNumber'],
	   		'ExpirationMonth' => sprintf("%02s", $data['ExpiryMonth']),
		    'ExpirationYear' => $data['ExpiryYear'],
    		'CVV2' => $data['CreditCardCVV']
		);
		$result = $app->validateCard($card);
		if($result['Valid'] != 'true'){
			$form->sessionMessage("The credit card details are invalid!", 'bad');
			return $this->edit(Controller::curr()->getRequest());
		}
		//Get country text
		$country = Geoip::countryCode2name($data['Country']);
		// Locate existing credit card
		$ccID = $app->locateCard($isConID,substr($data['CreditCardNumber'],-4,4));
		if(!$ccID){
			//Add credit card on InfusionSoft
			$ccData = array(
				'ContactId' => $isConID,
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
		}else{
			//Update credit card on InfusionSoft
			$ccData = array(
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
			$app->dsUpdate("CreditCard", $ccID, $ccData);			
		}
		if(isset($data['Current'])){
			// Update billing address on InfusionSoft
			$conDat = array(
				'Company' => $data['Company'],
				'StreetAddress1' => $data['StreetAddress1'],
				'StreetAddress2' => $data['StreetAddress2'],
				'City' => $data['City'],
				'State' => $data['State'],
				'PostalCode' => $data['PostalCode'],
				'Country' => $country
			);
			$Pg_Ctrl->unsetCurrentCreditCard($member->ID);
		}
		$conID = $app->updateCon($isConID, $conDat);
		//Add the credit card on site
		$creditCard = new CreditCard();
		$creditCard->CreditCardType = $data['CreditCardType'];
		$creditCard->CreditCardNumber = $data['CreditCardNumber'];
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
		$creditCard->ISCCID = $ccID;
		$creditCard->MemberID = $member->ID;
		if(isset($data['Current']))
			$creditCard->Current = 1;
		if(isset($data['UsedForTrial']))
			$creditCard->UsedForTrial = 1;
		$creditCard->write();
		$form->sessionMessage("Credit Card and Billing Address successfully added.", 'good');
		return Controller::curr()->redirect("admin/manage-data/CreditCard/EditForm/field/CreditCard/item/{$creditCard->ID}/edit");
	}
	//Admin update credit card / billing info
	public function updateCreditCard($data, $form){
		$member = Member::get()->byID($data['MemberID']);
		$isConID = $member->ISContactID;
		// Get the Page controller
		$Pg_Ctrl = new Page_Controller();
		//Get InfusionSoft Api
		$creditCardType = $Pg_Ctrl->getISCreditCardType($data['CreditCardType']);
		$app = $Pg_Ctrl->getInfusionSoftApi();
		// Validate credit card
		$card = array(
			'CardType' => $creditCardType,
   	   		'ContactId' => $isConID,
	       	'CardNumber' => $data['CreditCardNumber'],
	   		'ExpirationMonth' => sprintf("%02s", $data['ExpiryMonth']),
		    'ExpirationYear' => $data['ExpiryYear'],
    		'CVV2' => $data['CreditCardCVV']
		);
		$result = $app->validateCard($card);
		if($result['Valid'] != 'true'){
			$form->sessionMessage("The credit card details are invalid!", 'bad');
			return $this->edit(Controller::curr()->getRequest());
		}
		//Update the credit card on InfusionSoft
		$country = Geoip::countryCode2name($data['Country']);
		$ccData = array(
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
		$app->dsUpdate("CreditCard", $data['ISCCID'], $ccData);
		if(isset($data['Current'])){
			// Update billing address on InfusionSoft
			$conDat = array(
				'Company' => $data['Company'],
				'StreetAddress1' => $data['StreetAddress1'],
				'StreetAddress2' => $data['StreetAddress2'],
				'City' => $data['City'],
				'State' => $data['State'],
				'PostalCode' => $data['PostalCode'],
				'Country' => $country
			);
			$conID = $app->updateCon($isConID, $conDat);
		}
		//Get the credit card
		$creditCard = CreditCard::get()->filter(array(
   			'CreditCardNumber' => $data['CreditCardNumber']
		))->First();
		//Unset the current card
		if(isset($data['Current']) && !$creditCard->Current)
			$Pg_Ctrl->unsetCurrentCreditCard($member->ID);
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
		if(isset($data['Current']) && !$creditCard->Current)
			$creditCard->Current = 1;
		if(isset($data['UsedForTrial']))
			$creditCard->UsedForTrial = 1;
		$creditCard->write();
		$form->sessionMessage("Credit Card and Billing Address successfully updated.", 'good');
		$randomNumber = rand();
		return $this->getController()->redirect("admin/manage-data/CreditCard/EditForm/field/CreditCard/item/{$creditCard->ID}/edit?rand={$randomNumber}");
	}
}