<?php
class UserDashboard extends Page {

}
class UserDashboard_Controller extends Page_Controller {

	public function init() {
		parent::init();
		//Get InfusionSoft Api
		$app = $this->getInfusionSoftApi();
		$member = Member::currentUser();
		if($member && !$member->ISContactID){
			// Get InfusionSoft Contact ID
			$returnFields = array('Id','Leadsource');
			$conInfo = $app->findByEmail($member->Email, $returnFields);
			if(empty($conInfo) || !is_array($conInfo)){
				// If IS contact doesn't exist create one
				$conDat = array(
						'FirstName'  => $member->FirstName,
						'LastName'  => $member->Surname,
						'Email'  => $member->Email
				);
				if(empty($conInfo))
					$conDat['Leadsource'] = 'AttentionWizard';
				$isConID = $app->addCon($conDat);
			}else{
				$isConID = $conInfo[0]['Id'];
			}
			$member->ISContactID = $isConID;
			$member->write();
		}
	}

}