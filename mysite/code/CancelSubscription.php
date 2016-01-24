<?php 

class CancelSubscription extends Page 
{

}

class CancelSubscription_Controller extends Page_Controller 
{
	//Allow our form as an action
	private static $allowed_actions = array(
		'CancelSubscriptionForm'
	);
	
	function init(){
		parent::init();
	}
	
	//Generate the Cancel Subscription form
	function CancelSubscriptionForm()
	{
		$reasons = array(
				"I didn't use the product as much as I anticipated" => "I didn't use the product as much as I anticipated", 
				"The cost was too high" => "The cost was too high", 
				"I had technical problems generating heatmaps" => "I had technical problems generating heatmaps", 
				"I have changed jobs/careers" => "I have changed jobs/careers",
				"I had problems with customer service" => "I had problems with customer service",
				"I didn't find the heatmaps helpful" => "I didn't find the heatmaps helpful",
				"Other (you may contact us support@attentionwizard.com with additional feedback)" => "Other (you may contact us support@attentionwizard.com with additional feedback)"
				);
		$info = '<p>Please remember, you can continue to purchase non-expiring heatmaps and access your heatmap inventory. Your account will remain open and available to you even after your subscription is cancelled.</p>
				<p>&nbsp;</p>
<p style="font-style:italic;">Note: accounts with no activity for a 60-day period may be closed, and heatmaps associated with the account purged.If you wish to completely close your account please email <a href="mailto:support@attentionwizard.com">support@attentionwizard.com</a></p>
				';
		$fields = new FieldList(
		  new CheckboxSetField('Reasons','',$reasons),
			new LiteralField('Info', $info)		
		);
	 	
	    // Create action
	    $actions = new FieldList(
			$submit = new FormAction('cancelSubscription','')
	    );
	    $submit->setAttribute('src', 'themes/attwiz/images/button_cancel_sub.png');
		// Create action
		$validator = new RequiredFields('Reasons');
	 	return new Form($this, 'CancelSubscriptionForm', $fields, $actions, $validator);		
	}
	//Process Cancel Subscription form
	function cancelSubscription($data,$form){
		// Get the reasons for cancel
		$reasons = '';
		foreach($data['Reasons'] as $reason){
			if($reasons == '')
				$reasons = $reason;
			else
				$reasons .= ", $reason"; 
		}
		$member = Member::currentUser();
		$subscription = Subscription::get()->filter(array(
		    'MemberID' => $member->ID,
		    'Status' => 1
		))->first();  
		// Get the member order
		$order = $subscription->Order();
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		//Set the current subscription to Inactive 
		$result = $this->setSubscriptionStatus($subscription->SubscriptionID, 'Inactive');
		if(is_int($result)){
			$isConId = $member->ISContactID;
			//Remove IS trial tag
			$app->grpRemove($isConId, 2216);
			$returnFields = array('_AWofmonths');
			$conDat = $app->loadCon($isConId, $returnFields);
			$subCount = $conDat['_AWofmonths'];
			$date = $app->infuDate(date('j-n-Y'));
			if($this->isTrialMember($member->ID)){
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
			$subscription->IsTrial = 0;
			$subscription->ExpireDate = date('Y-m-d H:i:s'); 
			$subscription->write();
			$product = $subscription->Product();
			//Send an email to the user
			$email = new Email();
			$email->setSubject("Your {$product->Name} Subscription Has Been Cancelled");
        	$email->setFrom('support@attentionwizard.com');
			$email->setTo($member->Email);
			$email->setTemplate('CancelSubscriptionEmail');
			$email->populateTemplate(array(
			    'firstName' => $member->FirstName,
			 	'lastName' => $member->Surname
			));
			$email->send();
			// Redirect to user dashboard
			$this->setMessage('Success', 'The subscription is cancelled successfully.');
			$this->redirect('/account-settings');
		}else{
			$this->setMessage('Error', 'Sorry the cancel subscription failed due to some reason,please try again later.');
			$this->redirect('/account-settings/#tabs-4');
			return false;
		}
	}
}