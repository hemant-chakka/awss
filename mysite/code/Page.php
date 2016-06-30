<?php
class Page extends SiteTree {

	private static $db = array(
		'ContentRight' => 'HTMLText',
		'CustomerView' => 'Boolean',
		'TitleBarTitle' => 'Varchar(255)'
	);

	private static $has_one = array(
	);
	
	public function getCMSFields() {
        $fields = parent::getCMSFields();
        $fields->addFieldToTab('Root.SideBarRight', new HTMLEditorField('ContentRight','Content Right'));
        $fields->addFieldToTab('Root.Main', new TextField('TitleBarTitle','Browser Title'),'URLSegment');
        return $fields;
    }
    
	function getSettingsFields() { 
      $fields = parent::getSettingsFields(); 
      $fields->addFieldToTab('Root.Settings', new CheckboxField('CustomerView', 'Show in registered menus?'), 'ShowInSearch'); 
      return $fields; 
    }
    
    // To find if the user is a current subscriber or not
    public function isSubscriber($memberId){
    	$subscription = Subscription::get()->filter(array(
		    'MemberID' => $memberId,
		    'Status' => 1
		))->first();
		if($subscription)
			return true;
		return false;
    }
    // Get Subscription object
    public function getMemberSubscription($memberId){
    	$subscription = Subscription::get()->filter(array(
		    'MemberID' => $memberId,
		    'Status' => 1
		))->first();
		if($subscription)
			return $subscription;
		return false;
    }
    // Get days remaining for the subscription
    public function getDaysRemainingSubscription($memberId){
    	$days = DB::query("select datediff(ExpireDate,now()) from Subscription where MemberID = $memberId and Status = 1")->value();
    	return $days;
    }
    // Get the expiring heatmaps remaining for the subscription
    public function getExpiringHeatmapsRemaining($memberId){
    	$credits = DB::query("select sum(Credits) from MemberCredits where MemberID = $memberId and ProductID IN (1,2,3) and ExpireDate >= now()")->value();
    	if($credits)
    		return $credits;
    	return 0;
    }
    // Get the non-expiring heatmaps remaining
    public function getNonExpiringHeatmapsRemaining($memberId){
    	$credits = DB::query("select sum(Credits) from MemberCredits where MemberID = $memberId and ProductID IN (4,5,6,7,10)")->value();
    	if($credits)
    		return $credits;
    	return 0;
    }
    // get the site copyright date range
    public function getCopyrightYearRange(){
    	//return '2010 -'.date('Y');
    	return date('Y');
    }
}
class Page_Controller extends ContentController {

	/**
	 * An array of actions that can be accessed via a request. Each array element should be an action name, and the
	 * permissions or conditions required to allow the user to access it.
	 *
	 * <code>
	 * array (
	 *     'action', // anyone can access this action
	 *     'action' => true, // same as above
	 *     'action' => 'ADMIN', // you must have ADMIN permissions to access this action
	 *     'action' => '->checkAction' // you can only access this action if $this->checkAction() returns true
	 * );
	 * </code>
	 *
	 * @var array
	 */
	private static $allowed_actions = array ( 
		'logout',
		'login',
		'FormatNumber',
		'updateSuccessfulRecurringPayment',
		'updateFailedRecurringPayment',
		'moveMembersFromJoomlaToSS',
		'moveHeatmapsFromJoomlaToSS',
		'moveCreditCardsFromJoomlaToSS',
		'moveSubscriptionsFromJoomlaToSS',
		'moveOrdersFromJoomlaToSS',
		'moveCreditsFromJoomlaToSS',
		'moveHistoryFromJoomlaToSS',
		'markHeatmapStatusJoomla',
		'updateSubscriptionsRoutine'
	);
	
	private static $url_handlers = array(
        'api/$Action/$ID' => 'updateSuccessfulRecurringPayment',
		'api/$Action/$ID' => 'updateFailedRecurringPayment'
    );

	public function init() {
		parent::init();
		// Note: you should use SS template require tags inside your templates 
		// instead of putting Requirements calls here.  However these are 
		// included so that our older themes still work
		Requirements::themedCSS('reset');
		Requirements::themedCSS('layout'); 
		Requirements::themedCSS('typography'); 
		Requirements::themedCSS('form');
		//Requirements::javascript('themes/attwiz/javascript/jquery.min.js');
		Requirements::javascript(FRAMEWORK_DIR . '/thirdparty/jquery/jquery.min.js');
		Requirements::css('assets/fancybox/jquery.fancybox.css');
		Requirements::javascript('assets/fancybox/jquery.fancybox.js');
		Requirements::javascript('mysite/js/common.js');
		Requirements::javascript(FRAMEWORK_DIR . '/thirdparty/jquery-validate/jquery.validate.min.js');
		Requirements::javascript('mysite/js/login.js');
		Requirements::javascript('mysite/js/change-password.js');
	}
	// Get InfusionSoft API object
	public function getInfusionSoftApi(){
		//include the SDK
		include_once Director::baseFolder().'/mysite/code/isdk/src/isdk.php';
		//build our application object
		$app = new iSDK;
		//connect to the API - change demo to be whatever your connectionName is!
		if($app->cfgCon("connectionName"));
		// Return the application
		return $app;
		
	}
	// Get InfusionSoft Contact ID of the member // deprecated
	public function getISContactID(){
		$member = Member::currentUser();
		return $member->ISContactID;
	}
	// Get InfusionSoft merchant ID
	function login(){
		if(!Member::currentUser())
			$this->redirect("/customer-login/");
	}
	
	function logout() { 
	   Security::logout(false); 
	   if(Member::currentUser())
	   		$this->redirect("user-dashboard/");
	   else
		    $this->redirect("home/"); 
	}
	//Format a number to currency
	function FormatNumber(){
		$amount = $_GET['a'];
		return number_format($amount, 2, '.', '');
	}
	// Create InfusionSoft Subscription
	function createISSubscription($contactId,$productId,$price,$ccID,$daysTillCharge){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$returnFields = array('Id');
		$query = array('ProductId' => $productId);
		$cProgram = $app->dsQuery("CProgram",10,0,$query,$returnFields);
		$cProgramId = $cProgram[0]['Id'];
		$subId = $app->addRecurringAdv($contactId,false,$cProgramId,1,floatval($price),true,8,$ccID,0,$daysTillCharge);
		return $subId;
	}
	// Cancel a subscription on InfusionSoft // deprecated
	public function CancelIsSubscription($subscriptionId){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$result = $app->deleteSubscription($subscriptionId);
		return $result;
	}
	// Get Non-Expiring Member Product object
	public function getNonExpiringProduct($memberId){
		$subscription = Subscription::get()->filter(array(
		    'MemberID' => $memberId,
		    'Status' => 1
		))->first();
		if($subscription){
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
			return Product::get()->byID($productID);
		}
		return false;
	}
	// Get Expiring Product ID
	public function getExpiringProductId($productId){
		switch ($productId) {
			case 4:
				$ExpProductID = 1;
				break;
			case 5:
				$ExpProductID = 2;
				break;
			case 6:
				$ExpProductID = 3;
				break;
			return $ExpProductID;
		}
		return false;
	}
	// Get Non-Expiring Member Product object
	public function getNonExpiringIsProductId($productId){
		switch ($productId) {
			case 4:
				$isProductID = 52;
				break;
			case 5:
				$isProductID = 54;
				break;
			case 6:
				$isProductID = 56;
				break;
			case 7:
				$isProductID = 40;
				break;
		}
		return $isProductID;
	}
	// To find if the user is a current subscriber or not
	public function isSubscriber($memberId){
		$subscription = Subscription::get()->filter(array(
		    'MemberID' => $memberId,
		    'Status' => 1
		))->first();
		if($subscription)
			return true;
		return false;
	}
	// To find if the member is currently under trial 
	public function isTrialMember($memberId){
		$subscription = Subscription::get()->filter(array(
		    'MemberID' => $memberId,
		    'Status' => 1,
			'IsTrial' => 1
		))->first();
		if($subscription)
			return true;
		return false;
	}
	// To find if the user is paid member
	public function isPaidMember($memberId){
		$subscription = Subscription::get()->filter(array(
		    'MemberID' => $memberId,
		    'Status' => 1,
			'IsTrial' => 0
		))->first();
		if($subscription)
			return true;
		return false;
	}
	// Get date in InfusionSoft format given time and timezone
	public function getDateInISFormat($time=null,$timezone=null){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$date = new DateTime($time, new DateTimeZone($timezone));
		return $app->infuDate($date->format('j-n-Y'));
	}
	//Find InfusionSoft contact by Email
	public function getISContactByEmail($email){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$returnFields = array('Id','Leadsource');
		$conInfo = $app->findByEmail($email, $returnFields);
		if(empty($conInfo))
			return false;
		return $conInfo[0]['Id'];
	}
	// Find if the credit card is used for trial
	public function isCCUsedForTrial($cardnumber){
		return CreditCard::get()->filter(array('CreditCardNumber' => $cardnumber,'UsedForTrial' => 1))->First();
	}
	// Get InfusionSoft Tag ID by product 
	public function getISTagIdByProduct($productID){
		switch ($productID) {
			case 1:
				$isTagID = 2224;
				break;
			case 2:
				$isTagID = 2286;
				break;
			case 3:
				$isTagID = 2288;
				break;
		}
		return $isTagID;
	}
	// Set a custom error message
	public function setMessage($type, $message){   
        Session::set('Message', array(
            'MessageType' => $type,
            'Message' => $message
        ));
    }
 	// Get a custom error message
    public function getMessage(){
        if($message = Session::get('Message')){
            Session::clear('Message');
            $array = new ArrayData($message);
            return $array->renderWith('Message');
        }
    }
    //Get InfusionSoft prospect tag by payment code	
	public function getISTagIdByPaymentCode($code){
		switch ($code) {
			case 'DECLINED':
				$isTagID = 2694;
				break;
			case 'ERROR':
				$isTagID = 2680;
				break;
			case 'SKIPPED':
				$isTagID = 3019;
				break;
		}
		return $isTagID;
	}
	// Get the InfusionSoft subscription status
	public function getSubscriptionStatus($subscriptionId){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$returnFields = array('Status');
		$query = array('Id' => $subscriptionId);
		$result = $app->dsQuery("RecurringOrderWithContact",10,0,$query,$returnFields);
		$status = $result[0]['Status'];
		return $status;
	}
	// Set the InfusionSoft subscription status
	public function setSubscriptionStatus($subscriptionId,$status){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$data = array('Status'  => $status);
	 	return $app->dsUpdate("RecurringOrder", $subscriptionId, $data);
	 	
	}
	// Get InfusionSoft Credit card type
	public function getISCreditCardType($creditCardType){
		switch ($creditCardType){
			case 'visa':
				$isCCType = 'Visa';
				break;
			case 'mc':
				$isCCType = 'MasterCard';
				break;
			case 'amex':
				$isCCType = 'American Express';
				break;
			case 'discover':
				$isCCType = 'Discover';
				break;
			case 'diners':
				$isCCType = 'Diners Club';
				break;
			case 'jcb':
				$isCCType = 'JCB';
				break;
		}
		return $isCCType;
	}
	//Get current credit card
	public function getCurrentCreditCard($memberId){
		return CreditCard::get()->filter(array(
   			'MemberID' => $memberId, 'Current'=> 1
		))->First();
	}
	//Set current credit card
	public function unsetCurrentCreditCard($memberId){
		$creditCard = CreditCard::get()->filter(array(
   			'MemberID' => $memberId, 'Current'=> 1
		))->First();
		if($creditCard){
			$creditCard->Current = 0;
			$creditCard->write();
			return true;	
		}
		return false;
	}
	//Get current subscription
	public function getCurrentSubscription($memberId){
		return Subscription::get()->filter(array(
	    	'MemberID' => $memberId,
			'Status' => 1
		))->first();
	}
	//Update successful recurring payment
	public function updateSuccessfulRecurringPayment(){
		//Get the user details
		$isContactId = intval($_REQUEST['Id']);
		$email = $_REQUEST['Email'];
		if($email != 'hemant.chakka@yahoo.com' && $email != 'stacey@sitetuners.com')
			return false;
		$productId = intval($this->request->param('ID'));
		$member = Member::get()->filter(array(
	    	'ISContactID' => $isContactId
		))->first();
		//Get the member current subscription
		$subscription = $this->getCurrentSubscription($member->ID);
		//Update the subscription
		$nextBillDate = $this->getSubscriptionNextBillDate($subscription->SubscriptionID);
		$renewalExpireDate= date('Y-m-d H:i:s', strtotime($nextBillDate));
		$renewalStartDate= date('Y-m-d H:i:s', strtotime($renewalExpireDate. "-30 days"));
		$subscription->StartDate = $renewalStartDate;
		$subscription->ExpireDate = $renewalExpireDate;
		$subscription->IsTrial = 0;
		$subscription->SubscriptionCount += 1;
		$subscription->write();
		//Get current credit card
		$creditCard = $this->getCurrentCreditCard($member->ID);
		//Create billing history record
		$billingHistory = new MemberBillingHistory();
		$billingHistory->MemberID = $member->ID;
		$billingHistory->CreditCardID = $creditCard->ID;
		$billingHistory->ProductID = $productId;
		$billingHistory->SubscriptionID = $subscription->ID;
		$billingHistory->write();
		//Update member credits
		$memberCredits = MemberCredits::get()->filter(array(
	    	'MemberID' => $member->ID,
			'SubscriptionID' => $subscription->ID
		))->first();
		$memberCredits->Credits = $subscription->Product()->Credits;
		$memberCredits->ExpireDate = $renewalExpireDate;
		$memberCredits->write();
		//Update Infusionsoft contact
		$isTagId = $this->getISTagIdByProduct($productId);
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$app->grpRemove($isContactId, $isTagId);
		$app->grpAssign($isContactId, $isTagId);
		// Update subscription contacts
		$app->grpRemove($isContactId, 2216);
		$returnFields = array('_AWofmonths');
		$conDat = $app->loadCon($isContactId,$returnFields);
		$conDat = array(
			'_AWofmonths' => $conDat['_AWofmonths']+1,
			'_AttentionWizard' => 'Paid and Current',
			'ContactType' => 'AW Customer',
			'_AWcanceldate' => null
		);
		$app->updateCon($isContactId, $conDat);
		// Remove previous cancel tags
		$app->grpRemove($isContactId, 2226);
		$app->grpRemove($isContactId, 2758);
		$app->grpRemove($isContactId, 2682);
		$app->grpRemove($isContactId, 2680);
		$app->grpRemove($isContactId, 2694);
		$app->grpRemove($isContactId, 3019);
	}
	//Update failed recurring payment
	public function updateFailedRecurringPayment(){
		//Get the user details
		$isContactId = intval($_REQUEST['Id']);
		$emailAddress = $_REQUEST['Email'];
		if($emailAddress != 'hemant.chakka@yahoo.com' && $emailAddress != 'stacey@sitetuners.com')
			return false;
		$productId = intval($this->request->param('ID'));
		$member = Member::get()->filter(array(
	    	'ISContactID' => $isContactId
		))->first();
		//Get the member current subscription
		$subscription = $this->getCurrentSubscription($member->ID);
		//Set the subscription to inactive
		$result = $this->setSubscriptionStatus($subscription->SubscriptionID, 'Inactive');
		if(is_int($result)){
			//Update Infusionsoft contact
			//Get InfusionSoft Api
			$app = $this->getInfusionSoftApi();
			//get the current date
			$curdate = $app->infuDate(date('j-n-Y'));
			// Custom fields populated
			$conDat = array(
					'_AttentionWizard' => 'Cancelled due to card decline',
					'_AWcanceldate' => $curdate
				);
			$app->updateCon($isContactId, $conDat);
			// Remove tag trial member
			$app->grpRemove($isContactId, 2216);
			if($this->isTrialMember($member->ID)){
				// Add Cancel after trial tag
				$app->grpAssign($isContactId, 2226);
				// Add CXL Card declined tag
				$app->grpAssign($isContactId, 2682);
			}else{
				// Add CXL Card declined tag
				$app->grpAssign($isContactId, 2682);
			}
			// Note is added
			if($this->isTrialMember($member->ID)){
				$conActionDat = array('ContactId' => $isContactId,
					'ActionType'  => 'UPDATE',
					'ActionDescription'  => "Cancelled AW Trial",
					'CreationDate'  => $curdate,
					'CreationNotes'     => "Credit card charge failed",
					'ActionDate'  => $curdate,
					'CompletionDate'  => $curdate,
					'UserID'  => 1
				);
			}else{
				$conActionDat = array('ContactId' => $isContactId,
					'ActionType'  => 'UPDATE',
					'ActionDescription'  => "Payment declined by Auth.net - account cancelled",
					'CreationDate'  => $curdate,
					'CreationNotes'     => "Credit card charge failed",
					'ActionDate'  => $curdate,
					'CompletionDate'  => $curdate,
					'UserID'  => 1
				);
			}
			$conActionID = $app->dsAdd("ContactAction", $conActionDat);
			//Set the subscription inactive on site
			$subscription->Status = 0;
			$subscription->write();
			//Send a notification email to Support
			$email = new Email();
			$email->setSubject("AW Notification - auto payment failed");
        	$email->setFrom('support@attentionwizard.com');
			$email->setTo('hemant.chakka@gmail.com');
			$email->setTemplate('PaymentFailedEmail');
			$email->populateTemplate(array(
			    'emailAddress' => $emailAddress,
			 	'subscriptionId' => $subscription->SubscriptionID
			));
			$email->send();
		}
	}
	// Update Subscriptions status routine, runs every day using cron 
	public function updateSubscriptionsRoutine(){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$date = date('Y-m-d',strtotime("-1 days"));
		$subscriptions = Subscription::get()->filter(array(
				'Status' => 1,
				'ExpireDate:LessThan' => $date
		));
		if($subscriptions){
			foreach ($subscriptions as $subscription){
				//Get user details
				$subscriptionId = $subscription->SubscriptionID;
				$isContactId = $subscription->Member()->ISContactID;
				$isProductId = $subscription->Product()->ISProductID;
				$emailAddress = $subscription->Member()->Email;
				$productId = $subscription->ProductID;
				$member = $subscription->Member();
				$subscriptionPayStatus = $this->getSubscriptionPayStatus($subscriptionId, $isContactId, $isProductId);
				if($subscriptionPayStatus){
					//Update the subscription
					$nextBillDate = $this->getSubscriptionNextBillDate($subscription->SubscriptionID);
					$renewalExpireDate= date('Y-m-d H:i:s', strtotime($nextBillDate));
					$renewalStartDate= date('Y-m-d H:i:s', strtotime($renewalExpireDate. "-30 days"));
					$subscription->StartDate = $renewalStartDate;
					$subscription->ExpireDate = $renewalExpireDate;
					$subscription->IsTrial = 0;
					$subscription->SubscriptionCount += 1;
					$subscription->write();
					//Get current credit card
					$creditCard = $this->getCurrentCreditCard($member->ID);
					//Create billing history record
					$billingHistory = new MemberBillingHistory();
					$billingHistory->MemberID = $member->ID;
					$billingHistory->CreditCardID = $creditCard->ID;
					$billingHistory->ProductID = $productId;
					$billingHistory->SubscriptionID = $subscription->ID;
					$billingHistory->write();
					//Update member credits
					$memberCredits = MemberCredits::get()->filter(array(
							'MemberID' => $member->ID,
							'SubscriptionID' => $subscription->ID
					))->first();
					$memberCredits->Credits = $subscription->Product()->Credits;
					$memberCredits->ExpireDate = $renewalExpireDate;
					$memberCredits->write();
					//Update Infusionsoft contact
					$isTagId = $this->getISTagIdByProduct($productId);
					$app->grpRemove($isContactId, $isTagId);
					$app->grpAssign($isContactId, $isTagId);
					// Update subscription contacts
					$app->grpRemove($isContactId, 2216);
					$returnFields = array('_AWofmonths');
					$conDat = $app->loadCon($isContactId,$returnFields);
					$conDat = array(
							'_AWofmonths' => $conDat['_AWofmonths']+1,
							'_AttentionWizard' => 'Paid and Current',
							'ContactType' => 'AW Customer',
							'_AWcanceldate' => null
					);
					$app->updateCon($isContactId, $conDat);
					// Remove previous cancel tags
					$app->grpRemove($isContactId, 2226);
					$app->grpRemove($isContactId, 2758);
					$app->grpRemove($isContactId, 2682);
					$app->grpRemove($isContactId, 2680);
					$app->grpRemove($isContactId, 2694);
					$app->grpRemove($isContactId, 3019);
				}else{
					//Set the subscription to inactive
					$result = $this->setSubscriptionStatus($subscription->SubscriptionID, 'Inactive');
					if(is_int($result)){
						//Update Infusionsoft contact
						//get the current date
						$curdate = $app->infuDate(date('j-n-Y'));
						// Custom fields populated
						$conDat = array(
							'_AttentionWizard' => 'Cancelled due to card decline',
							'_AWcanceldate' => $curdate
						);
						$app->updateCon($isContactId, $conDat);
						// Remove tag trial member
						$app->grpRemove($isContactId, 2216);
						if($this->isTrialMember($member->ID)){
							// Add Cancel after trial tag
							$app->grpAssign($isContactId, 2226);
							// Add CXL Card declined tag
							$app->grpAssign($isContactId, 2682);
						}else{
							// Add CXL Card declined tag
							$app->grpAssign($isContactId, 2682);
						}
						// Note is added
						if($this->isTrialMember($member->ID)){
							$conActionDat = array('ContactId' => $isContactId,
								'ActionType'  => 'UPDATE',
								'ActionDescription'  => "Cancelled AW Trial",
								'CreationDate'  => $curdate,
								'CreationNotes'     => "Credit card charge failed",
								'ActionDate'  => $curdate,
								'CompletionDate'  => $curdate,
								'UserID'  => 1
							);
						}else{
							$conActionDat = array('ContactId' => $isContactId,
								'ActionType'  => 'UPDATE',
								'ActionDescription'  => "Payment declined by Auth.net - account cancelled",
								'CreationDate'  => $curdate,
								'CreationNotes'     => "Credit card charge failed",
								'ActionDate'  => $curdate,
								'CompletionDate'  => $curdate,
								'UserID'  => 1
							);
						}
						$conActionID = $app->dsAdd("ContactAction", $conActionDat);
						//Set the subscription inactive on site
						$subscription->Status = 0;
						$subscription->write();
						//Send a notification email to support
						$email = new Email();
						$email->setSubject("AW Notification - auto payment failed");
						$email->setFrom('support@attentionwizard.com');
						$email->setTo('hemant.chakka@gmail.com');
						$email->setTemplate('PaymentFailedEmail');
						$email->populateTemplate(array(
							'emailAddress' => $emailAddress,
							'subscriptionId' => $subscription->SubscriptionID
						));
						$email->send();
					}
				}
			}
		}
	}
	// Get the current subscription pay status
	function getSubscriptionPayStatus($subscriptionId,$isContactId,$isProductId){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$returnFields = array('LastBillDate');
		$query = array('Id'=>$subscriptionId);
		$result = $app->dsQuery("RecurringOrderWithContact",10,0,$query,$returnFields);
		if($result[0]['LastBillDate']){
			$dateCreated = $result[0]['LastBillDate'];
			$dateCreated = explode('T', $dateCreated);
			$dateCreated = strtotime($dateCreated[0]);
			$dateCreated = date('Y-m-d',$dateCreated);
			$returnFields1 = array('PayStatus');
			$query = array('ContactId' => $isContactId,'DateCreated' => "$dateCreated%",'ProductSold' => "$isProductId");
			$result1 = $app->dsQuery("Invoice",10,0,$query,$returnFields1);
			if(empty($result1) || !$result1[0]['PayStatus'])
				return false;
				if($result1[0]['PayStatus'])
					return true;
		}
		return true;
	}
	// Get the InfusionSoft subscription Next BillDate
	public function getSubscriptionNextBillDate($subscriptionId){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$returnFields = array('NextBillDate');
		$query = array('Id' => $subscriptionId);
		$result = $app->dsQuery("RecurringOrderWithContact",10,0,$query,$returnFields);
		$nextBillDate = $result[0]['NextBillDate'];
		$nextBillDate = explode('T', $nextBillDate);
		return $nextBillDate[0];
	}
	//Get db connection
	public function getDbConnection(){
		return new mysqli('54.197.231.247', 'awlive_dbadm', '5DPDnLqnWY8jpscW', 'attenti17_lives');
	}
	//Move members from Joomla to Silverstripe
	public function moveMembersFromJoomlaToSS(){
		die('test1');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$userGroup = DataObject::get_one('Group', "Code = 'customers'");
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_users u left join jos_aw_iscontacts ic on u.id = ic.member_id");
		$membersCreated = 0;
		while ($obj = $result->fetch_object()) {
			if($this->getSSMember($obj->email))
				continue;
			$member_id = $obj->id;
			$result1 = $mysqli->query("SELECT * FROM jos_osemsc_orders where user_id = $member_id and order_status = 'c' and (ROUND( payment_price, 2 ) = 0.01 or payment_price = 1)");
			if($result1)
				$signUpTrial = 1;
			else 
				$signUpTrial = 0;
			$password = $this->createPassword(8);
			$name = trim(preg_replace('/\s+/', ' ', $obj->name));
			$name = explode(' ', $name);
			$member = new Member();
			$member->Created = $obj->registerDate;
			$member->LastEdited = $obj->lastvisitDate;
			$member->LastVisited = $obj->lastvisitDate;
			if(isset($name[0]))
				$member->FirstName = $name[0];
			if(isset($name[1]))
				$member->Surname = $name[1];
			$member->Email = $obj->email;
			$member->Password = $password;
			$member->ISContactID = $obj->is_contact_id ;
			$member->SignUpTrial = $signUpTrial ;
			$member->write();
			$userGroup->Members()->add($member); 
			//Send an email to the user with new password
			/*
			if($obj->email == 'hemant.chakka@yahoo.com'){
				$email = new Email();
				$email->setSubject("AttentionWizard Update: Your password changed");
    	    	$email->setFrom('support@attentionwizard.com');
				$email->setTo($obj->email);
				$email->setTemplate('NewPasswordEmail');
				$email->populateTemplate(array(
				    'fullName' => $obj->name,
				 	'password' => $password
				));
				$email->send();
			} */
			$membersCreated++;
		}
		$mysqli->close();
		echo "Total Members moved: $membersCreated";
	}
	//check if the user is active and accessed a/c in last 60 days
	public function isActiveUser($memberId,$lastVisitDate){
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_osemsc_member WHERE member_id = $memberId");
		if(!$result->num_rows){
			$mysqli->close();
			return false;
		}
	}
	
	//check if ss member object
	public function getSSMember($email){
		return Member::get()->filter(array('Email' => $email))->first();
	}
	//Get the credit cards array
	public function getCreditCardsList(){
		$creditCardList = array();
		$creditCards = CreditCard::get();
		foreach ($creditCards as $creditCard){
			$creditCardList[$creditCard->CreditCardNumber] = $creditCard->ID;
		}
		return $creditCardList;
	}
	//Get the orders array
	public function getOrdersList(){
		$orderList = array();
		$orders = Order::get();
		foreach ($orders as $order){
			$orderList[$order->JoomlaOrderNumber] = $order->ID;
		}
		return $orderList;
	}
	//Mark heatmap status on Joomla
	public function markHeatmapStatusJoomla(){
		//die('test2');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_users u inner join jos_heatmaps h on u.id = h.userid where status_new = 0");
		$totalHeatmaps = 0;
		while ($obj = $result->fetch_object()) {
			//Get heatmap url
	    	$heatmapImageUrl = $obj->heatmap_image_url;
	    	$heatmapImageUrl = str_replace('/web/attentionwizard/', '', $heatmapImageUrl);
	    	$heatmapImageUrl = str_replace('/EBS/sites/wiz.dynacast.net/htdoc/', '', $heatmapImageUrl);
	    	$heatmapImageUrl = "https://www.attentionwizard.com/$heatmapImageUrl";
	    	if(@file_get_contents($heatmapImageUrl)){
	    		$result2 = $mysqli->query("UPDATE jos_heatmaps set status_new = 1 where heatmapid = {$obj->heatmapid}");	
	    	}else{
	    		$result2 = $mysqli->query("UPDATE jos_heatmaps set status_new = 2 where heatmapid = {$obj->heatmapid}");
	    	}
	    	$totalHeatmaps++;
		}
		$mysqli->close();
		echo "Total Heatmaps marked: $totalHeatmaps";
	}
	//Move heatmaps from Joomla to Silverstripe
	public function moveHeatmapsFromJoomlaToSS(){
		die('test2');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$mysqli = $this->getDbConnection();
		$result2 = $mysqli->query("SELECT * FROM jos_users u inner join jos_heatmaps h on u.id = h.userid where h.status_new = 1");
		$totalHeatmaps = 0;
		while ($obj2 = $result2->fetch_object()) {
			//if($obj2->id != 14835 && $obj2->id != 16667 )
				//continue;
			if($this->getSSHeatmapFile(basename($obj2->heatmap_image_url)))
				continue;
			//Get member id
	    	$ssMember = $this->getSSMember($obj2->email);
	    	$memberId = $ssMember->ID;
			$heatmaps = new Heatmaps();
			$heatmaps->Created = $obj2->heatmap_created;
			$heatmaps->LastEdited = $obj2->heatmap_created;
			if($obj2->heatmap_type == 2)
				$heatmaps->HeatmapType = 1;
			$heatmaps->UploadImageName = basename($obj2->original_image);
			$heatmaps->Deleted = $obj2->hstatus;
			$heatmaps->MemberID = $memberId;
			// Create a heatmap folder for the user if it does not exist
	    	$heatmapFolder = Folder::find_or_make("/Uploads/heatmaps/$memberId");
	    	$heatmapFolderPath = $heatmapFolder->Filename;
			if($originalImageUrl = $obj2->original_image_url){
				$originalImageName = basename($originalImageUrl);
		    	//Original image destination URL
    			$originalImagePath = Director::baseFolder()."/{$heatmapFolderPath}$originalImageName";
    			//Save/copy the original image
				if($content = @file_get_contents($originalImageUrl)) {
    	    		file_put_contents($originalImagePath, $content);
					// Create a file object for the original image
		    		$originalImage = new File();
					$originalImage->ClassName = 'Image';
	    			$originalImage->Filename = "assets/Uploads/heatmaps/$memberId/$originalImageName";
					$originalImage->Title = pathinfo($originalImageName, PATHINFO_FILENAME);
	    			$originalImage->ParentID = $heatmapFolder->ID;
    				$originalImage->OwnerID = $memberId;
	    			$originalImage->write();
		    		$heatmaps->OriginalImageID = $originalImage->ID;
				}
			}
   	    	//Get heatmap url
	    	if($heatmapImageUrl = $obj2->heatmap_image_url){
	    		$heatmapImageUrl = str_replace('/web/attentionwizard/', '', $heatmapImageUrl);
		    	$heatmapImageUrl = str_replace('/EBS/sites/wiz.dynacast.net/htdoc/', '', $heatmapImageUrl);
		    	$heatmapImageUrl = "https://www.attentionwizard.com/$heatmapImageUrl";
    			if($content = @file_get_contents($heatmapImageUrl)){
		    		//Save/copy the heatmap
    				$heatmapName = basename($heatmapImageUrl);
		    		$heatmapPath = Director::baseFolder()."/{$heatmapFolderPath}$heatmapName";
    				file_put_contents($heatmapPath, $content);
	    	   		// Create a file object for the heatmap image
		    		$heatmapImage = new File();
					$heatmapImage->ClassName = 'Image';
	    			$heatmapImage->Filename = "assets/Uploads/heatmaps/$memberId/$heatmapName";
					$heatmapImage->Title = pathinfo($heatmapName, PATHINFO_FILENAME);
			    	$heatmapImage->ParentID = $heatmapFolder->ID;
   					$heatmapImage->OwnerID = $memberId;
   					$heatmapImage->write();
		   			$heatmaps->HeatmapID = $heatmapImage->ID;	
    			}
	    	}
	    	//Get wm heatmap url
		    if($wmHeatmapImageUrl = $obj2->watermark_image_url){
			    $wmHeatmapImageUrl = str_replace('/web/attentionwizard/', '', $wmHeatmapImageUrl);
		    	$wmHeatmapImageUrl = str_replace('/EBS/sites/wiz.dynacast.net/htdoc/', '', $wmHeatmapImageUrl);
	    		$wmHeatmapImageUrl = "https://www.attentionwizard.com/$wmHeatmapImageUrl";
    			if($content = @file_get_contents($wmHeatmapImageUrl)){
	    			//Save/copy the heatmap
	    			$wmHeatmapName = basename($wmHeatmapImageUrl);
		    		$wmHeatmapPath = Director::baseFolder()."/{$heatmapFolderPath}$wmHeatmapName";
   	    			file_put_contents($wmHeatmapPath, $content);
       				// Create a file object for the heatmap image
    				$watermarkHeatmapImage = new File();
					$watermarkHeatmapImage->ClassName = 'Image';
			    	$watermarkHeatmapImage->Filename = "assets/Uploads/heatmaps/$memberId/$wmHeatmapName";
					$watermarkHeatmapImage->Title = pathinfo($wmHeatmapName, PATHINFO_FILENAME);
    				$watermarkHeatmapImage->ParentID = $heatmapFolder->ID;
	    			$watermarkHeatmapImage->OwnerID = $memberId;
	   				$watermarkHeatmapImage->write();
   					$heatmaps->WatermarkHeatmapID = $watermarkHeatmapImage->ID;	
	    		}
		    }
    		$heatmaps->write();
    		$totalHeatmaps++;
		}
		$mysqli->close();
		echo "Total Heatmaps moved: $totalHeatmaps";
	}
	//get ss heatmap file object
	public function getSSHeatmapFile($heatmapName){
		return File::get()->filter(array('Name' => $heatmapName))->first();
	}
	//Move credit cards from Joomla to Silverstripe
	public function moveCreditCardsFromJoomlaToSS(){
		die('test2');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$countries = Geoip::getCountryDropDown();
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT *, u.id uid FROM jos_users u
		INNER JOIN jos_aw_creditcard c ON u.id = c.creditcard_userid
		LEFT JOIN jos_aw_iscontacts ic ON c.creditcard_userid = ic.member_id
		LEFT JOIN jos_osemsc_billinginfo bi ON c.creditcard_userid = bi.user_id");
		$totalRec = 0;
		while ($obj = $result->fetch_object()) {
			$ssMember = $this->getSSMember($obj->email);
			if(!$ssMember)
				continue;
			$creditCard = new CreditCard();
			$creditCard->Created = $obj->creditcard_date;
			$creditCard->LastEdited = $obj->creditcard_date;
			$creditCard->CreditCardType = strtolower($obj->creditcard_type);
			$creditCard->NameOnCard = $obj->creditcard_name;
			$creditCard->CreditCardNumber = $obj->creditcard_number;
			$creditCard->CreditCardCVV = $obj->creditcard_cvv;
			$creditCard->ExpiryMonth = $obj->creditcard_month;
			$creditCard->ExpiryYear = $obj->creditcard_year;
			$creditCard->Company = $obj->company;
			$creditCard->StreetAddress1 = $obj->street1;
			$creditCard->StreetAddress2 = $obj->street2;
			$creditCard->City = $obj->city;
			$creditCard->State = $obj->state_id;
			$creditCard->PostalCode = $obj->postcode;
			$countryCode = array_search($obj->country_id, $countries);
			$creditCard->Country = $countryCode;
			if($this->joomlaCurrentCreditCard($obj->creditcard_number, $obj->uid))
				$creditCard->Current = 1;
			if($this->joomlaTrialCreditCard($obj->creditcard_number))
				$creditCard->UsedForTrial = 1;
			if($obj->is_contact_id && $ccID = $this->joomlaGetISCreditCardId($obj->creditcard_number, $obj->is_contact_id))	
				$creditCard->ISCCID = $ccID;
			$creditCard->MemberID = $ssMember->ID;
			$creditCard->write();
			$totalRec++;
		}
		$mysqli->close();
		echo "Total Cards moved: $totalRec";
	}
	//Find if the credit card is current
	public function joomlaCurrentCreditCard($creditCard,$memberId){
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_aw_creditcard WHERE creditcard_userid = $memberId ORDER BY creditcard_date DESC LIMIT 1");
		if(!$result->num_rows){
			$mysqli->close();
			return false;
		}
		$obj = $result->fetch_object();
		if($obj->creditcard_number == $creditCard){
			$mysqli->close();
			return true;
		}
		$mysqli->close();
		return false;
	}
	//Find if the credit card is used for trial
	public function joomlaTrialCreditCard($creditCard){
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_aw_cc WHERE cc_number = $creditCard and (cc_amount = 0.01 OR cc_amount = 1)");
		if($result->num_rows)
			return true;
		return false;
	}
	//Get the infusionsoft credit card id
	public function joomlaGetISCreditCardId($creditCard,$isContactId){
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$ccID = $app->locateCard($isContactId,substr($creditCard,-4,4));
		if($ccID)
			return $ccID;
		return false;
	}
	//Move orders from Joomla to Silverstripe
	public function moveOrdersFromJoomlaToSS(){
		die('test3');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_users u
		INNER JOIN jos_osemsc_orders o ON u.id = o.user_id
		LEFT JOIN jos_aw_cc cc ON o.order_number = cc.cc_order_number");
		$count = 0;
		while ($obj = $result->fetch_object()) {
			$ssMember = $this->getSSMember($obj->email);
			if(!$ssMember)
				continue;
			$order = new Order();
			$order->Created = $obj->date;
			$order->LastEdited = $obj->date;
			$order->OrderStatus = $obj->order_status;
			$order->Amount = $obj->payment_price;
			$order->JoomlaOrderNumber = $obj->order_number;
			if($obj->payment_price == 0.01 || $obj->payment_price == 1 )
				$order->IsTrial = 1;
			$order->MemberID = $ssMember->ID;
			$order->ProductID = $obj->msc_id;
			$order->ProductID = $obj->msc_id;
			if($obj->cc_number && $ssCreditCard = $this->getSSCreditCard($obj->cc_number, $ssMember->ID)){
				$order->CreditCardID = $ssCreditCard->ID;
			}else{
				if($cardId = $this->getSSCreditCardId2($obj->id, $ssMember->ID, $obj->date))
					$order->CreditCardID = $cardId;
			}
			$order->write();
			$count++;
		}
		$mysqli->close();
		echo "Total Orders moved: $count";
	}
	//Get ss credit card
	public function getSSCreditCard($cardNumber,$memberId){
		return CreditCard::get()->filter(array('CreditCardNumber' => $cardNumber,'MemberID' => $memberId))->first();
	}
	//Move subscriptions from Joomla to Silverstripe
	public function moveSubscriptionsFromJoomlaToSS(){
		die('test2');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$mysqli = $this->getDbConnection();
		$count = 0;
		$result = $mysqli->query("SELECT * FROM jos_users u
		INNER JOIN jos_osemsc_orders o ON u.id = o.user_id where o.order_status = 'c'");
		while ($obj = $result->fetch_object()) {
			$ssMember = $this->getSSMember($obj->email);
			if(!$ssMember)
				continue;
			$subscription = new Subscription();
			$subscription->Created = $obj->date;
			$subscription->LastEdited = $obj->date;
			if(ctype_digit($obj->order_number))
				$subscription->SubscriptionID = intval($obj->order_number);
			$member = $this->getJoomlaMember($obj->user_id, $obj->msc_id, $obj->subscription_status_new);
			if($member){
				$subscription->StartDate = $member->start_date;
				$subscription->ExpireDate = $member->expired_date;
			}
			$subscription->Status = $obj->subscription_status_new;
			if(($obj->payment_price == 0.01 || $obj->payment_price == 1) && !$obj->subscription_count)
				$subscription->IsTrial = 1;
			$subscription->SubscriptionCount = $obj->subscription_count;
			$subscription->MemberID = $ssMember->ID;
			$subscription->ProductID = $obj->msc_id;
			if($ssOrder = $this->getSSOrder($obj->order_number))
				$subscription->OrderID = $ssOrder->ID;
			if($obj->subscription_status_new == 0 && ($obj->msc_id == 1 || $obj->msc_id == 2 || $obj->msc_id == 3) && $member){
				if($reasons = $this->getReasonCancelled($obj->user_id, $member->expired_date))
					$subscription->ReasonCancelled = $reasons;	
			}
			$subscription->write();
			$count++;
		}
		$result = $mysqli->query("SELECT * FROM jos_users u
		INNER JOIN jos_osemsc_member_credits mc ON u.id = mc.member_id
		WHERE mc.msc_id =10");
		while ($obj = $result->fetch_object()) {
			$ssMember = $this->getSSMember($obj->email);
			if(!$ssMember)
				continue;
			$subscription = new Subscription();
			$subscription->MemberID = $ssMember->ID;
			$subscription->ProductID = $obj->msc_id;
			$subscription->write();
			$count++;
		}
		$mysqli->close();
		echo "Total Subscriptions moved: $count";
	}
	//Get ss order
	public function getSSOrder($orderNumber){
		return Order::get()->filter(array('JoomlaOrderNumber' => $orderNumber))->first();
	}
	
	public function getJoomlaMember($memberId,$mscId,$statusNew){
		$mysqli = $this->getDbConnection();
		if($statusNew == 1 || $mscId == 4 || $mscId == 5 || $mscId == 6 || $mscId == 7 || $mscId == 10){
			$result = $mysqli->query("SELECT * FROM jos_osemsc_member WHERE member_id = $memberId AND msc_id = $mscId ORDER BY id DESC LIMIT 1");
		}else{
			$result = $mysqli->query("SELECT * FROM jos_osemsc_member_exp WHERE member_id = $memberId AND msc_id = $mscId ORDER BY id DESC LIMIT 1");
		}
		if($result->num_rows){
			$member = $result->fetch_object();
			$mysqli->close();
			return $member;
		}
		$mysqli->close();
		return false;
	}
	public function getReasonCancelled($memberId,$cancelDate){
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_jforms_c43b1 WHERE uid = $memberId AND h089e = '$cancelDate'");
		if($result->num_rows){
			$reasons = $result->fetch_object()->hdff0;
			$mysqli->close();
			return $reasons;
		}
		$mysqli->close();
		return false;
	}
	//Get corresponding joomla order 
	public function getJoomlaOrder($memberId,$msc_id,$status){
		$mysqli = $this->getDbConnection();
		if($status == 1)
			$result = $mysqli->query("SELECT * FROM jos_osemsc_orders WHERE user_id = $memberId AND msc_id = $msc_id ORDER BY date DESC LIMIT 1");
		else
			$result = $mysqli->query("SELECT * FROM jos_osemsc_orders WHERE user_id = $memberId AND msc_id = $msc_id AND subscription_status_new = 0 ORDER BY date DESC LIMIT 1");
		if($result->num_rows){
			$order = $result->fetch_object();
			$mysqli->close();
			return $order;
		}
		$mysqli->close();
		return false;
	}
	//Move credits from Joomla to Silverstripe
	public function moveCreditsFromJoomlaToSS(){
		die('test3');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_users u
		INNER JOIN jos_osemsc_member_credits mc ON u.id = mc.member_id
		WHERE mc.credits !=0");
		$count = 0;
		while ($obj = $result->fetch_object()) {
			$ssMember = $this->getSSMember($obj->email);
			if(!$ssMember)
				continue;
			$subscription = $this->getSSSubscription($ssMember->ID, $obj->msc_id);
			$memberCredits = new MemberCredits();
			$memberCredits->Credits = $obj->credits;
			$memberCredits->MemberID = $ssMember->ID;
			$memberCredits->ProductID = $obj->msc_id;
			if($subscription){
				$memberCredits->Created = $subscription->Created;
				$memberCredits->LastEdited = $subscription->LastEdited;
				$memberCredits->ExpireDate = $subscription->ExpireDate;
				$memberCredits->SubscriptionID = $subscription->ID;
			}
			$memberCredits->write(); 
			$count++;
		}
		$mysqli->close();
		echo "Total Credits moved: $count";
	}
	//Move payment history from Joomla to Silverstripe
	public function moveHistoryFromJoomlaToSS(){
		die('test3');
		error_reporting(E_ALL);
		ini_set('display_errors', 1);
		ini_set('max_execution_time', 0);
		ini_set('memory_limit', '-1');
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_users u
		INNER JOIN jos_osemsc_mem_history mh ON u.id = mh.member_id");
		$count = 0;
		while ($obj = $result->fetch_object()) {
			$ssMember = $this->getSSMember($obj->email);
			if(!$ssMember)
				continue;
			if($obj->date == '0000-00-00 00:00:00')
				$transDate = $obj->expired_date;
			else 
				$transDate = $obj->date;
			$billingHistory = new MemberBillingHistory();
			$billingHistory->Created = $transDate;
			$billingHistory->LastEdited = $transDate;
			$billingHistory->MemberID = $ssMember->ID;
			$creditCardId = $this->getSSCreditCardId($obj->member_id,$ssMember->ID,$transDate);
			if($creditCardId)
				$billingHistory->CreditCardID = $creditCardId;
			$subscriptionId = $this->getSSSubscriptionId($ssMember->ID, $obj->msc_id, $transDate);
			if($subscriptionId)
				$billingHistory->SubscriptionID = $subscriptionId;
			$billingHistory->ProductID = $obj->msc_id;
			$billingHistory->write();
			$count++;
		}
		$mysqli->close();
		echo "Total history moved: $count";
	}
	//Get SS credit card id
	public function getSSCreditCardId($memberId,$ssMemberId,$date){
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_aw_creditcard WHERE creditcard_userid = $memberId ORDER BY creditcard_date");
		$cardNumber = '';
		if($result->num_rows){
			while ($obj = $result->fetch_object()) {
				if((strtotime($date)+200) > strtotime($obj->creditcard_date))
					$cardNumber = $obj->creditcard_number;
			}
			if($cardNumber){
				$creditCard = CreditCard::get()->filter(array(
					'CreditCardNumber' => $cardNumber,
					'MemberID' => $ssMemberId
				))->first();
				if($creditCard){
					$mysqli->close();
					return $creditCard->ID;
				}
			}
		}
		$mysqli->close();
		return false;
	}
	//Get SS credit card id version-2
	public function getSSCreditCardId2($memberId,$ssMemberId,$date){
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_aw_creditcard WHERE creditcard_userid = $memberId AND DATE(creditcard_date) = '$date'");
		if($result->num_rows){
			$obj = $result->fetch_object();
			$cardNumber = $obj->creditcard_number;
			$mysqli->close();
			if($cardNumber){
				$creditCard = CreditCard::get()->filter(array(
					'CreditCardNumber' => $cardNumber,
					'MemberID' => $ssMemberId
				))->first();
				if($creditCard)
					return $creditCard->ID;
			}
		}
		$mysqli->close();
		return false;
	}
	//Get SS subscription id
	public function getSSSubscriptionId($memberId,$productId,$date){
		$subscriptions = Subscription::get()->filter(array('MemberID' => $memberId,'ProductID' => $productId))->sort('Created');
		$subscriptionId = 0;
		if($subscriptions){
			foreach ($subscriptions as $subscription){
				if((strtotime($date)+200)>strtotime($subscription->Created))
					$subscriptionId = $subscription->ID;
			}
			return $subscriptionId;
		}
		return false;
	}
	//Get get joomla order from history
	public function getJoomlaOrderByHistory($memberId,$msc_id,$transDate){
		$mysqli = $this->getDbConnection();
		$result = $mysqli->query("SELECT * FROM jos_osemsc_orders WHERE user_id = $memberId AND msc_id = $msc_id ORDER BY date");
		$orderNumber = '';
		while ($obj = $result->fetch_object()) {
			if(strtotime($transDate) > strtotime($obj->date))
				$orderNumber = $obj->order_number;
		}
		if($orderNumber)
			return $orderNumber;
		return false;
	}
	//Get subscription 
	public function getSSSubscription($memberId,$productId){
		$subscription = Subscription::get()->filter(array(
			'MemberID' => $memberId,
			'ProductID' => $productId,
			'Status' => 1
		))->first();
		if($subscription)
			return $subscription;
		$subscription = Subscription::get()->filter(array(
			'MemberID' => $memberId,
			'ProductID' => $productId
		))->sort('Created', 'DESC')->first();
		return $subscription;
	}
	//Create random password
	public function createPassword( $length = 8 ) {
    	$chars = "abcdefghijklmnopqrstuvwxyzABCDEFGHIJKLMNOPQRSTUVWXYZ0123456789!@#$%^&*()_-=+;:,.?";
	    $password = substr( str_shuffle( $chars ), 0, $length );
    	return $password;
	}
	// Get total number of users
	public function getTotalUsersCount(){
		return number_format(Member::get()->count());
	}
	
}

class CustomLoginForm extends LoginForm{
	
	public function __construct($controller, $name) {
        
		$fields = new FieldList(
            parent::getField('Email'),
            parent::getField('Password')
        );
		//$email = $fields->dataFieldByName('Email');
        //$actions = new FieldList(FormAction::create("login")->setTitle("Log in"));
		$actions = new FieldList(FormAction::create("login"));
        parent::__construct($controller, $name, $fields, $actions);
    }
     
    public function login(array $data, Form $form) {
    	Authenticator::authenticate($data,$this);
        // Do something with $data
        //LoginForm::
    }
     
    public function forTemplate() {
        return $this->renderWith(array($this->class, 'Form'));
    }
}