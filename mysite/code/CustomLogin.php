<?php
class CustomLogin extends MemberLoginForm {

	public function __construct($controller, $name, $fields = null, $actions = null,
			$checkCurrentUser = true) {
	
				// This is now set on the class directly to make it easier to create subclasses
				// $this->authenticator_class = $authenticatorClassName;
	
				$customCSS = project() . '/css/member_login.css';
				if(Director::fileExists($customCSS)) {
					Requirements::css($customCSS);
				}
	
				if(isset($_REQUEST['BackURL'])) {
					$backURL = $_REQUEST['BackURL'];
				} else {
					$backURL = Session::get('BackURL');
				}
	
				if($checkCurrentUser && Member::currentUser() && Member::logged_in_session_exists()) {
					$fields = new FieldList(
							new HiddenField("AuthenticationMethod", null, $this->authenticator_class, $this)
							);
					$actions = new FieldList(
							new FormAction("logout", _t('Member.BUTTONLOGINOTHER', "Log in as someone else"))
							);
				} else {
					if(!$fields) {
						$label=singleton('Member')->fieldLabel(Member::config()->unique_identifier_field);
						$fields = new FieldList(
								new HiddenField("AuthenticationMethod", null, $this->authenticator_class, $this),
								// Regardless of what the unique identifer field is (usually 'Email'), it will be held in the
								// 'Email' value, below:
								$emailField = new TextField("Email", $label, null, null, $this),
								new PasswordField("Password", _t('Member.PASSWORD', 'Password'))
								);
						if(Security::config()->remember_username) {
							$emailField->setValue(Session::get('SessionForms.MemberLoginForm.Email'));
						} else {
							// Some browsers won't respect this attribute unless it's added to the form
							$this->setAttribute('autocomplete', 'off');
							$emailField->setAttribute('autocomplete', 'off');
						}
						if(Security::config()->autologin_enabled) {
							$fields->push(new CheckboxField(
									"Remember",
									_t('Member.REMEMBERME', "Remember me next time?")
									));
						}
					}
					if(!$actions) {
						$actions = new FieldList(
								new FormAction('dologin', _t('Member.BUTTONLOGIN', "Log in")),
								new LiteralField(
										'forgotPassword',
										'<p id="ForgotPassword"><a href="Security/lostpassword">'
										. _t('Member.BUTTONLOSTPASSWORD', "I've lost my password") . '</a></p>'
										)
								);
					}
				}
	
				if(isset($backURL)) {
					$fields->push(new HiddenField('BackURL', 'BackURL', $backURL));
				}
	
				// Reduce attack surface by enforcing POST requests
				$this->setFormMethod('POST', true);
	
				parent::__construct($controller, $name, $fields, $actions);
	
				$this->setValidator(new RequiredFields());
	
				// Focus on the email input when the page is loaded
				$js = <<<JS
			(function() {
				var el = document.getElementById("MemberLoginForm_LoginForm_Email");
				if(el && el.focus && (typeof jQuery == 'undefined' || jQuery(el).is(':visible'))) el.focus();
			})();
JS;
				Requirements::customScript($js, 'MemberLoginFormFieldFocus');
	}
   
   
   
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