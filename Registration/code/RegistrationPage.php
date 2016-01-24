<?php 

class RegistrationPage extends Page 
{

}

class RegistrationPage_Controller extends Page_Controller 
{
	//Allow our form as an action
	private static $allowed_actions = array(
		'RegistrationForm',
		'EmailExists',
		'validateSignup'
	);
	
	function init(){
		parent::init();
		Requirements::themedCSS('editor');
		Requirements::javascript('mysite/js/signup-now.js');
		SSViewer::setOption('rewriteHashlinks', false);
	}
	
	//Generate the registration form
	function RegistrationForm()
	{
	    $fields = new FieldList(
			new EmailField('Email', 'Email'),
			new ConfirmedPasswordField('Password', 'Password'),
			new CheckboxField('Terms','I agree to the <a href="/customer-login/terms-of-use/" target="_blank">AttentionWizard Terms of Use</a>',1)
		);
	 	
	    // Create action
	    $actions = new FieldList(
			$submit = new FormAction('doRegister','')
	    );
	    $submit->setAttribute('src', 'themes/attwiz/images/button_continue.gif');
		// Create action
		$validator = new RequiredFields('Email','Password');
		

	 	$form = new Form($this, 'RegistrationForm', $fields, $actions);
	 	
	 	//$form->setTemplate('RegistrationForm');
	 	return $form;
	}
	//Submit the registration form
	function doRegister($data,$form){
		//Check for existing member email address
		if($member = DataObject::get_one("Member", "`Email` = '". Convert::raw2sql($data['Email']) . "'")) 
		{
			//Set error message
			$form->AddErrorMessage('Email', "Sorry, that email address already exists. Please choose another.", 'bad');
			//Set form data from submitted values
			Session::set("FormInfo.Form_RegisterForm.data", $data);		
			//Return back to form
			return $this->redirectBack();			
		}	
		Session::set('RegistrationFormData',$data);
		$this->redirect('/trial-signup');
	}
	//Check if the email exists in user database
	public function EmailExists(){
		if(isset($_GET['email'])){
			$email = $_GET['email'];
			if (filter_var($email, FILTER_VALIDATE_EMAIL) === false) 
				return 2;
			$member = Member::get()->filter(array('Email' => $email));
			if($member->count() > 0)
				return 1;
		}
		return 0;
	}
	//Validate sign-up form
	public function validateSignup(){
		$data = $_POST;
		$messages = array();
		if(!isset($data['Email']))
			$messages[] = "* Email address is required";
		
		if(empty($data['Password']))
			$messages[] = "* Password is required";
		
		if(!isset($data['Terms']))
			$messages[] = "* Please Accept the AttentionWizard Terms of Use";
			
	    $str = '';
	    foreach ($messages as $message){
	    	if($str == '')
	    		$str.= "$message </br>";
	    	else 
	    		$str = "$message </br>";
	    }
		
	    return $str;
	}
}