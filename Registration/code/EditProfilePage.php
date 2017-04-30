<?php 

class EditProfilePage extends Page 
{
}

class EditProfilePage_Controller extends Page_Controller 
{
	private static $allowed_actions = array(
		'EditProfileForm'
	);
	
	function init(){
		parent::init();
		Requirements::javascript('mysite/js/edit-profile.js');
	}

	function EditProfileForm()
	{
		//Create our fields
	    $fields = new FieldList(
			new TextField('FirstName', 'First Name'),
	    	new TextField('Surname', 'Last Name'),
			new TextField('Email', 'Email'),
			$pw = new ConfirmedPasswordField('Password', 'New Password')
		);
	    
	    $pw->setCanBeEmpty('true');
	 	
	    // Create action
	    $actions = new FieldList(
			$saveProfileAction = new FormAction('SaveProfile', '')
	    );
		$saveProfileAction->setAttribute('src', 'themes/attwiz/images/button_submit.gif');
		// Create action
		$validator = new RequiredFields(array('FirstName','Surname','Email'));
		$validator = null;
		
	    //Create form
		$Form = new Form($this, 'EditProfileForm', $fields, $actions, $validator);

		//Populate the form with the current members data
		$Member = Member::currentUser();
		$Form->loadDataFrom($Member->data());
		
		//Return the form
		return $Form;
	}
	
	//Save profile
	function SaveProfile($data, $form)
	{
		//Check for a logged in member
		if($CurrentMember = Member::currentUser())
		{
			//Get InfusionSoft Api
			$app = $this->getInfusionSoftApi();
			$returnFields = array('Id');
			$conInfo = $app->findByEmail($data['Email'], $returnFields);
			//Check for another member with the same email address
			if($member = DataObject::get_one("Member", "Email = '". Convert::raw2sql($data['Email']) . "' AND ID != " . $CurrentMember->ID)) 
			{
				$form->addErrorMessage("Email", 'Sorry, that Email address already exists.', "bad");
				Session::set("FormInfo.Form_EditProfileForm.data", $data);
				return $this->redirectBack();
			}elseif($CurrentMember->Email != $data['Email'] && !empty($conInfo)){
				$form->addErrorMessage("Email", 'Sorry, that Email address already exists.', "bad");
				Session::set("FormInfo.Form_EditProfileForm.data", $data);
				return $this->redirectBack();
			}
			//Otherwise check that user IDs match and save
			else
			{
				//Update the InfusionSoft contact details
				$isConID = $CurrentMember->ISContactID;	
				$conDat = array(
					'FirstName'  => $data['FirstName'],
					'LastName'  => $data['Surname'],	
					'Email' => $data['Email']
				);
				$app->updateCon($isConID, $conDat);
				//Update the member on site
				$form->saveInto($CurrentMember);	
				$CurrentMember->write();
				$this->setMessage('Success', 'Your profile has been saved!');
        		return $this->redirectBack();
			}
		}
		//If not logged in then return a permission error
		else
		{
			return Security::PermissionFailure($this->controller, 'You must <a href="register">registered</a> and logged in to edit your profile:');
		}
	}	
	
	//Check for just saved
	function Saved()
	{
		return $this->request->getVar('saved');
	}
	
	//Check for success status
	function Success()
	{
		return $this->request->getVar('success');
	}		
}