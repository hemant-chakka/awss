<?php 

class ContactUs extends Page 
{

}

class ContactUs_Controller extends Page_Controller 
{
	//Allow our form as an action
	private static $allowed_actions = array(
		'ContactUsForm'
	);
	
	function init(){
		parent::init();
		Requirements::javascript('mysite/js/contact-us.js');
	}
	
	//Generate the contact us form
	function ContactUsForm()
	{
		$name = null;
		$email = null;
		$member = Member::currentUser();
		if($member){
			$name = $member->Name;
			$email = $member->Email;
		}
		$fields = new FieldList(
			new TextField('Name', 'Name<span>(*)</span>',$name),
	    	new TextField('Email', 'Email<span>(*)</span>',$email),
			new TextField('Phone', 'Phone'),
			new TextField('Topic', 'Subject<span>(*)</span>'),
			$message = new TextareaField('Message', 'Message<span>(*)</span>'),
			new LiteralField('MessageLimit', '<span style="font-size:9px;margin-left:143px;position:relative;top:-15px;">Enter not more than 500 characters.</span>'),
			new RecaptchaField('MyCaptcha')
		);
		$message->setAttribute('maxlength','500');
	    // Create action
	    $actions = new FieldList(
			$submit = new FormAction('doContact','')
	    );
	    $submit->setAttribute('src', 'themes/attwiz/images/button_send.gif');
		// Create action
		$validator = new RequiredFields('Name','Email','Topic','Message');
		$validator = null;

	 	return new Form($this, 'ContactUsForm', $fields, $actions, $validator);		
	}
	
	//Submit the contact us form
	function doContact($data,$form){
		//Send an email to the support
		$email = new Email();
		$email->setSubject("Contact Us form submitted");
        $email->setFrom($data['Email']);
		$email->setTo('support@attentionwizard.com');
		//$email->setTo('hemant.chakka@yahoo.com');
		$email->setTemplate('ContactUsEmail');
		$email->populateTemplate(array(
		    'Name' => $data['Name'],
		 	'Email' => $data['Email'],
			'Phone' => $data['Phone'],
			'Topic' => $data['Topic'],
			'Message' => $data['Message']
		));
		$email->send();
		$form->sessionMessage('Your email has been sent. Thank you for your message. Someone will respond back to you within 24-48 hours.', 'success');
        return $this->redirectBack();
	}
	// Show the message to the user after the contact us form is submitted
	function contactUsShowMessage(){
		$data = array(
				'Title' => 'Contact us form submitted successfully',
				'Content' => 'Thank you for contacting us!, we will get back to you soon.',
		);
		return $this->customise($data)->renderWith(array('Page'));
	}
}