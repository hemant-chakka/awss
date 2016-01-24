<?php
class CustomLogin extends MemberLoginForm {

   // this function is overloaded on our sublcass (this) to do something different 
   public function dologin($data) { 
      if($this->performLogin($data)) {
      	if(Permission::check('ADMIN'))
      		$this->logInUserAndRedirect($data);
      	else
	      	$this->controller->redirect('/user-dashboard');
      }else{
      if(array_key_exists('Email', $data)){
				Session::set('SessionForms.MemberLoginForm.Email', $data['Email']);
				Session::set('SessionForms.MemberLoginForm.Remember', isset($data['Remember']));
			}

			if(isset($_REQUEST['BackURL'])) $backURL = $_REQUEST['BackURL']; 
			else $backURL = null; 

			if($backURL) Session::set('BackURL', $backURL);
			
			if($badLoginURL = Session::get("BadLoginURL")) {
				$this->controller->redirect($badLoginURL);
			} else {
				// Show the right tab on failed login
				$loginLink = Director::absoluteURL($this->controller->Link('login'));
				if($backURL) $loginLink .= '?BackURL=' . urlencode($backURL);
				$this->controller->redirect($loginLink . '#' . $this->FormName() .'_tab');
			}
      } 
 
   }
   // overload Actions() function
   public function Actions(){
   		$actions = $this->actions;
   		/*
   		if(Member::currentUser()){
	   		$actions->fieldByName('action_logout')->setAttribute('src', 'themes/attwiz/images/logout.png');
   		}else{ 
   			if($actions->fieldByName('action_dologin'))
	   			$actions->fieldByName('action_dologin')->setAttribute('src', 'themes/attwiz/images/login.png');
   		}*/
   		return $actions;
   }
}    
?>