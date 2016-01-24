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
	}
	
	//Generate the contact us form
	function ContactUsForm()
	{
		$fields = new FieldList(
			new TextField('Name', '<span>*</span> Name'),
	    	new EmailField('Email', '<span>*</span> Email'),
			new TextField('Phone', 'Phone'),
			new TextField('Topic', '<span>*</span> Subject'),
			new TextareaField('Message', '<span>*</span> Message'),
			new RecaptchaField('MyCaptcha')
		);
	 	
	    // Create action
	    $actions = new FieldList(
			$submit = new FormAction('doContact','')
	    );
	    $submit->setAttribute('src', 'themes/attwiz/images/button_send.gif');
		// Create action
		$validator = new RequiredFields('Name','Email','Topic','Message');
		

	 	return new Form($this, 'ContactUsForm', $fields, $actions, $validator);		
	}
	
	//Submit the contact us form
	function doContact($data,$form){
		//Send an email to the support
		$email = new Email();
		$email->setSubject("Contact Us form submitted");
        $email->setFrom($data['Email']);
		//$email->setTo('support@attentionwizard.com');
		$email->setTo('hemant.chakka@yahoo.com');
		$email->setTemplate('ContactUsEmail');
		$email->populateTemplate(array(
		    'Name' => $data['Name'],
		 	'Email' => $data['Email'],
			'Phone' => $data['Phone'],
			'Topic' => $data['Topic'],
			'Message' => $data['Message']
		));
		$email->send();
		$form->sessionMessage('Thank you for contacting us, we will get back to you soon.', 'success');
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