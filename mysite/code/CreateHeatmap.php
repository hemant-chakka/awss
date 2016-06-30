<?php
class CreateHeatmap extends Page {
	
	public function canView($member = null) {
		if (Permission::check('ADMIN'))
	    	return true;
      	$member = Member::currentUser();
		if($member->ID && $this->getExpiringHeatmapsRemaining($member->ID))
			return true;
		if($member->ID && $this->getNonExpiringHeatmapsRemaining($member->ID))
			return true;
		return false;
    } 
}
class CreateHeatmap_Controller extends Page_Controller {
	
	private static $allowed_actions = array(
		'CreateHeatmapForm',
		'processCreateHeatmap'
	);

	function init(){
		parent::init();
		Requirements::themedCSS('jquery-filestyle.min');
		Requirements::javascript('mysite/js/jquery-filestyle.min.js');
		Requirements::javascript('mysite/js/create-heatmap.js');
		SSViewer::setOption('rewriteHashlinks', false);
	}
	
	// Create Heatmap Form
	public function CreateHeatmapForm(){
		$includeWatermark = array(
				"1"=>"Yes,Include Watermark",
				"0"=>"No,Remove Watermark"
		);
		$fields = new FieldList(
				$imageField = new FileField('OriginalImage','Upload an Image File'),
				new LiteralField('UploadInfo','Acceptable images are jpg or png, 500-1600 pixels wide by 500-1200 pixels height.<hr>'),
				new OptionsetField('IncludeWatermark','Include Watermark?',$includeWatermark,1)
				
		);
		$imageField->getValidator()->setAllowedExtensions(array('jpg','jpeg','png'));
		$imageField->setAttribute('class', 'jfilestyle');
		$imageField->setAttribute('data-buttonText', "<img src='themes/attwiz/images/button-create-heatmap-browse.jpg'></img>");
		$imageField->setAttribute('data-placeholder', 'No file selected..');
		// Create action
		$actions = new FieldList(
				$submit = new FormAction('processCreateHeatmap','')
		);
		$submit->setAttribute('src', 'themes/attwiz/images/button-create-heatmap-blue-bg.jpg');
		// Create action
		$validator = new RequiredFields('OriginalImage','IncludeWatermark');
		return new Form($this, 'CreateHeatmapForm', $fields, $actions, $validator);
	}
	// Create heatmap action
	public function processCreateHeatmap_old($data,$form){
		//Restrict users create heatmaps who do not have enough credits
		$member = Member::currentUser();
		$memberId = $member->ID;
		if(!$this->getExpiringHeatmapsRemaining($memberId) && !$this->getNonExpiringHeatmapsRemaining($memberId)){
			$form->sessionMessage("You do not have enough heatmap credits to create a heatmap!", 'fail');
			return $this->redirectBack();
		}
		//Restrict images upload whose dimensions are out of required range
		$tmpImageUrl = $data['OriginalImage']['tmp_name'];
		list($imageWidth, $imageHeight) = getimagesize($tmpImageUrl);
		if($imageWidth < 500 || $imageWidth > 1600 || $imageHeight < 500 || $imageHeight > 1200 ){
			$form->sessionMessage("Image should be 500-1600 pixels wide by 500-1200 pixels height!", 'fail');
			return $this->redirectBack();
		}
    	// Create a heatmap folder for the user if it does not exist
    	$heatmapFolder = Folder::find_or_make("/Uploads/heatmaps/$memberId");
    	$heatmapFolderPath = $heatmapFolder->Filename;
    	//chmod($heatmapFolderPath, 0777);
		//Rename the orginal uploaded image
    	$originalImageName = $this->renameUploadedImage($data['OriginalImage']['name']);
    	//Original image path
    	$originalImagePath = Director::baseFolder()."/{$heatmapFolderPath}$originalImageName";
    	//Original image URL
    	$originalImageUrl = Director::absoluteBaseURL()."assets/Uploads/heatmaps/$memberId/$originalImageName";
    	//Move the original image to heatmap folder
    	move_uploaded_file($tmpImageUrl, $originalImagePath);
    	//chmod($originalImagePath, 0777);
    	// Create a file object for the original image
    	$originalImage = new File();
		$originalImage->ClassName = 'Image';
    	$originalImage->Filename = "assets/Uploads/heatmaps/$memberId/$originalImageName";
		$originalImage->Title = pathinfo($originalImageName, PATHINFO_FILENAME);
    	$originalImage->ParentID = $heatmapFolder->ID;
    	$originalImage->OwnerID = $memberId;
    	$originalImage->write();
    	//Create Heatmap
		$viewType = 2;
		$viewDistance = 1;
		$analysisOptions = 511;
		$outputOptions = 21;
		$response = $this->CreateHeatmap('sitetuners', 'tim.ash', $originalImageUrl, $viewType, $viewDistance, $analysisOptions, $outputOptions);	
		if($response['success']){
        	$originalHeatmapUrl = $response['image_url'];
        	$heatmapName = $this->createHeatmapName($originalImageUrl, $memberId);
        	$heatmapUrl = Director::absoluteBaseURL()."assets/Uploads/heatmaps/$memberId/$heatmapName";
        	$heatmapPath = Director::baseFolder()."/{$heatmapFolderPath}$heatmapName";
        	//Save/copy the heatmap
        	$content = file_get_contents($originalHeatmapUrl);
        	file_put_contents($heatmapPath, $content);
        	// Create a file object for the heatmap image
	    	$heatmapImage = new File();
			$heatmapImage->ClassName = 'Image';
	    	$heatmapImage->Filename = "assets/Uploads/heatmaps/$memberId/$heatmapName";
			$heatmapImage->Title = pathinfo($heatmapName, PATHINFO_FILENAME);
	    	$heatmapImage->ParentID = $heatmapFolder->ID;
    		$heatmapImage->OwnerID = $memberId;
    		$heatmapImage->write();
    		//Create the heatmap record
    		$heatmap = new Heatmaps();
    		$heatmap->UploadImageName = $data['OriginalImage']['name'];
    		$heatmap->HeatmapType = 0;
    		$heatmap->MemberID = $memberId;
    		$heatmap->OriginalImageID = $originalImage->ID;
    		$heatmap->HeatmapID = $heatmapImage->ID;
    		if($data['IncludeWatermark'] == 1){
        		$watermarkHeatmapName = $this->createHeatmapName($originalImageUrl, $memberId,true);
        		$watermarkHeatmapUrl = Director::absoluteBaseURL()."assets/Uploads/heatmaps/$memberId/$watermarkHeatmapName";
        		$watermarkHeatmapPath = Director::baseFolder()."/{$heatmapFolderPath}$watermarkHeatmapName";
        		//Save/copy the heatmap
        		$this->createWatermarkHeatmap($heatmapUrl, $watermarkHeatmapPath);
        		// Create a file object for the heatmap image
		    	$watermarkHeatmapImage = new File();
				$watermarkHeatmapImage->ClassName = 'Image';
		    	$watermarkHeatmapImage->Filename = "assets/Uploads/heatmaps/$memberId/$watermarkHeatmapName";
				$watermarkHeatmapImage->Title = pathinfo($watermarkHeatmapName, PATHINFO_FILENAME);
	    		$watermarkHeatmapImage->ParentID = $heatmapFolder->ID;
	    		$watermarkHeatmapImage->OwnerID = $memberId;
    			$watermarkHeatmapImage->write();
    			//Create the heatmap record
    			$heatmap->HeatmapType = 1;
    			$heatmap->WatermarkHeatmapID = $watermarkHeatmapImage->ID;
        	}
			$heatmap->write();
			//Update/Deduct heatmap credits
			$this->updateHeatmapCredits($memberId);
			//Send an email to the user
			$email = new Email();
			$email->setSubject("AttentionWizard heatmap for {$data['OriginalImage']['name']}");
        	$email->setFrom('support@attentionwizard.com');
			$email->setTo($member->Email);
			$email->setTemplate('HeatmapCreatedEmail');
			$email->populateTemplate(array(
			    'fullName' => $member->FirstName.' '.$member->Surname,
			 	'imageName' => $data['OriginalImage']['name'],
				'absoluteLink' => Director::absoluteBaseURL()
			));
			$email->send();
			//Redirect to manage heatmaps page
			$this->setMessage('Success', 'Heatmap is successfully created');
			return $this->redirect('/manage-heatmaps');
        }else{
        	$email = new Email();
        	$email->setSubject('Heatmap Soap Request Not Working! Urgent');
        	$email->setFrom('support@attentionwizard.com');
        	//$email->setTo('rachel@sitetuners.com');
        	$email->setTo('hemant.chakka@gmail.com');
			$email->setTemplate('HeatmapFailedEmail');
			$email->populateTemplate(array(
			    'lastResponse' => $response['soap_response'],
			 	'lastResponseHeaders' => $response['response_headers']
			));
			$email->send();
			//Redirect to manage heatmaps page
			$this->setMessage('Error', 'Heatmap could not be created due to some reason, please try again');
			return $this->redirect('/manage-heatmaps');
        }
	}
	// Create heatmap action ajax
	public function processCreateHeatmap(){
		$data = $this->request->postVars();
		//Restrict users create heatmaps who do not have enough credits
		$member = Member::currentUser();
		$memberId = $member->ID;
		if(!$this->getExpiringHeatmapsRemaining($memberId) && !$this->getNonExpiringHeatmapsRemaining($memberId))
			return "inlineMsg2";
		//Restrict images upload whose dimensions are out of required range
		$tmpImageUrl = $data['OriginalImage']['tmp_name'];
		list($imageWidth, $imageHeight) = getimagesize($tmpImageUrl);
		if($imageWidth < 500 || $imageWidth > 1600 || $imageHeight < 500 || $imageHeight > 1200 )
			return "inlineMsg3";
		// Create a heatmap folder for the user if it does not exist
		$heatmapFolder = Folder::find_or_make("/Uploads/heatmaps/$memberId");
		$heatmapFolderPath = $heatmapFolder->Filename;
		//chmod($heatmapFolderPath, 0777);
		//Rename the orginal uploaded image
		$originalImageName = $this->renameUploadedImage($data['OriginalImage']['name']);
		//Original image path
		$originalImagePath = Director::baseFolder()."/{$heatmapFolderPath}$originalImageName";
		//Original image URL
		$originalImageUrl = Director::absoluteBaseURL()."assets/Uploads/heatmaps/$memberId/$originalImageName";
		//Move the original image to heatmap folder
		move_uploaded_file($tmpImageUrl, $originalImagePath);
		//chmod($originalImagePath, 0777);
		// Create a file object for the original image
		$originalImage = new File();
		$originalImage->ClassName = 'Image';
		$originalImage->Filename = "assets/Uploads/heatmaps/$memberId/$originalImageName";
		$originalImage->Title = pathinfo($originalImageName, PATHINFO_FILENAME);
		$originalImage->ParentID = $heatmapFolder->ID;
		$originalImage->OwnerID = $memberId;
		$originalImage->write();
		//Create Heatmap
		$viewType = 2;
		$viewDistance = 1;
		$analysisOptions = 511;
		$outputOptions = 21;
		$response = $this->CreateHeatmap('sitetuners', 'tim.ash', $originalImageUrl, $viewType, $viewDistance, $analysisOptions, $outputOptions);
		if($response['success']){
			$originalHeatmapUrl = $response['image_url'];
			$heatmapName = $this->createHeatmapName($originalImageUrl, $memberId);
			$heatmapUrl = Director::absoluteBaseURL()."assets/Uploads/heatmaps/$memberId/$heatmapName";
			$heatmapPath = Director::baseFolder()."/{$heatmapFolderPath}$heatmapName";
			//Save/copy the heatmap
			$content = file_get_contents($originalHeatmapUrl);
			file_put_contents($heatmapPath, $content);
			// Create a file object for the heatmap image
			$heatmapImage = new File();
			$heatmapImage->ClassName = 'Image';
			$heatmapImage->Filename = "assets/Uploads/heatmaps/$memberId/$heatmapName";
			$heatmapImage->Title = pathinfo($heatmapName, PATHINFO_FILENAME);
			$heatmapImage->ParentID = $heatmapFolder->ID;
			$heatmapImage->OwnerID = $memberId;
			$heatmapImage->write();
			//Create the heatmap record
			$heatmap = new Heatmaps();
			$heatmap->UploadImageName = $data['OriginalImage']['name'];
			$heatmap->HeatmapType = 0;
			$heatmap->MemberID = $memberId;
			$heatmap->OriginalImageID = $originalImage->ID;
			$heatmap->HeatmapID = $heatmapImage->ID;
			if($data['IncludeWatermark'] == 1){
				$watermarkHeatmapName = $this->createHeatmapName($originalImageUrl, $memberId,true);
				$watermarkHeatmapUrl = Director::absoluteBaseURL()."assets/Uploads/heatmaps/$memberId/$watermarkHeatmapName";
				$watermarkHeatmapPath = Director::baseFolder()."/{$heatmapFolderPath}$watermarkHeatmapName";
				//Save/copy the heatmap
				$this->createWatermarkHeatmap($heatmapUrl, $watermarkHeatmapPath);
				// Create a file object for the heatmap image
				$watermarkHeatmapImage = new File();
				$watermarkHeatmapImage->ClassName = 'Image';
				$watermarkHeatmapImage->Filename = "assets/Uploads/heatmaps/$memberId/$watermarkHeatmapName";
				$watermarkHeatmapImage->Title = pathinfo($watermarkHeatmapName, PATHINFO_FILENAME);
				$watermarkHeatmapImage->ParentID = $heatmapFolder->ID;
				$watermarkHeatmapImage->OwnerID = $memberId;
				$watermarkHeatmapImage->write();
				//Create the heatmap record
				$heatmap->HeatmapType = 1;
				$heatmap->WatermarkHeatmapID = $watermarkHeatmapImage->ID;
			}
			$heatmap->write();
			//Update/Deduct heatmap credits
			$this->updateHeatmapCredits($memberId);
			//Send an email to the user
			$email = new Email();
			$email->setSubject("AttentionWizard heatmap for {$data['OriginalImage']['name']}");
			$email->setFrom('support@attentionwizard.com');
			$email->setTo($member->Email);
			$email->setTemplate('HeatmapCreatedEmail');
			$email->populateTemplate(array(
					'fullName' => $member->FirstName.' '.$member->Surname,
					'imageName' => $data['OriginalImage']['name'],
					'absoluteLink' => Director::absoluteBaseURL()
			));
			$email->send();
			//Redirect to manage heatmaps page
			$this->setMessage('Success', 'Heatmap is created successfully');
			return "url1";
		}else{
			$email = new Email();
			$email->setSubject('Heatmap Soap Request Not Working! Urgent');
			$email->setFrom('support@attentionwizard.com');
			//$email->setTo('rachel@sitetuners.com');
			$email->setTo('hemant.chakka@gmail.com');
			$email->setTemplate('HeatmapFailedEmail');
			$email->populateTemplate(array(
					'lastResponse' => $response['soap_response'],
					'lastResponseHeaders' => $response['response_headers']
			));
			$email->send();
			return "inlineMsg4";
		}
	}
	//Create Heatmap
	function CreateHeatmap($login, $password, $inputImage, $viewType, $viewDistance, $analysisOptions, $outputOptions){
		// define the SOAP client using the url for the service
		//$client = new soapclient('http://service.feng-gui.com/soap/api.asmx?WSDL',
		$client = new soapclient(
			'http://fg11.feng-gui.com/soap/api.asmx?WSDL',
			array(
				'trace' => 1,
				'login' => $login,
				'password' => $password
			)
		);
		$param = array(
			'InputImage' => $inputImage,
			'ViewType' => $viewType,
			'viewDistance' => $viewDistance,
			'analysisOptions' => $analysisOptions,
			'outputOptions' => $outputOptions
		);	
		$response = array();
		ini_set("default_socket_timeout", 240); // PMJ Oct 30 2012 - bump up timeout from 3 minutes to 4 and see what happens
		try {
			$result = $client->ImageAttention($param);
			// passess the results     
			if (is_soap_fault($result)) {
				throw new Exception('SOAP heatmap call unsuccessful.');
			} 
		}
		catch(Exception $e){
			$response = array(
				'success' => false,
				'soap_response' => $client->__getLastResponse(),
				'response_headers' => $client->__getLastResponseHeaders()
			);
			return $response;
		}		
		$response = array(
				'success' => true,
				'image_url' => $result->ImageAttentionResult->OutputImage
			);
		return $response ;
	}
	// Rename the image(that will be made into heatmap)uploaded
	public function renameUploadedImage($beforeImageName){
		$newImageName = preg_replace("/[^\x9\xA\xD\x20-\x7F]/", "", $beforeImageName);
		$newImageName = $this->cleanUrl(filter_var($newImageName, FILTER_SANITIZE_URL));
		$newImageName = date("Y-m-d-His-").$newImageName;
		return $newImageName;
	}
	// Clean url
	public function cleanUrl($text){
		$text=strtolower($text);
		$code_entities_match = array(' ','&quot;','!','@','#','$','%','^','&','*','(',')','+','{','}','|',':','"','<','>','?','[',']','\\',';',"'",',','/','*','+','~','`','=');
		$code_entities_replace = array('-','-','','','','','','','','','','','','','','','','','','','','','','','','');
		$text = str_replace($code_entities_match, $code_entities_replace, $text);
		return $text;
	}
	//Create the heatmap name
	public function createHeatmapName($imageUrl,$memberId,$waterMark=false){
		$fileName = pathinfo($imageUrl, PATHINFO_FILENAME);
		if($waterMark){
			$suffix = date("siH")+mt_rand();
			return "{$fileName}_heatmapwatermark{$suffix}.jpg";
		}else{
			$suffix = date("sHi")+mt_rand()+$memberId;
			return "{$fileName}_heatmap{$suffix}.png";
		}
	}
	//Create heatmap with watermark
	public function createWatermarkHeatmap($heatmapUrl,$watermarkHeatmapUrl){
		$config = SiteConfig::current_site_config();
		$WatermarkFile = Director::absoluteBaseURL().$config->Watermark()->getURL();
		$watermark = imagecreatefrompng($WatermarkFile); 
		imageAlphaBlending($watermark, false);
		imageSaveAlpha($watermark, true);
		$image_string = file_get_contents($heatmapUrl);
		$image = imagecreatefromstring($image_string);	
		$imageWidth=imageSX($image);
		$imageHeight=imageSY($image);
		$watermarkWidth=imageSX($watermark);
		$watermarkHeight=imageSY($watermark);
		$coordinate_X = ( $imageWidth - 5) - ( $watermarkWidth);
		$coordinate_Y = ( $imageHeight - 5) - ( $watermarkHeight);
		imagecopy($image, $watermark, $coordinate_X, $coordinate_Y,0, 0, $watermarkWidth, $watermarkHeight);
		if(!($watermarkHeatmapUrl)) header('Content-Type: image/jpeg');
		imagejpeg ($image, $watermarkHeatmapUrl, 100);
		imagedestroy($image);
		imagedestroy($watermark);
		if(!($watermarkHeatmapUrl)) exit;		
		return true;
	}
	//Update heatmap credits
	public function updateHeatmapCredits($memberId){
		$memberCreditsExp = MemberCredits::get()->filter(array(
		    'ProductID' => array(1,2,3),
			'MemberID' => $memberId,
			'ExpireDate:GreaterThan' => date('Y-m-d H:i:s')
			))->subtract(MemberCredits::get()->filter(array(
			    'ProductID' => array(1,2,3),
				'MemberID' => $memberId,
				'Credits:LessThan' => 1
				)))->sort(array('ExpireDate' =>'ASC'))->First();
		if($memberCreditsExp){
			$memberCreditsExp->Credits = $memberCreditsExp->Credits -1 ;
			$memberCreditsExp->write();
			return true; 
		}
		$memberCreditsNonExp = MemberCredits::get()->filter(array(
		    'ProductID' => array(4,5,6,7),
			'MemberID' => $memberId
			))->subtract(MemberCredits::get()->filter(array(
			    'ProductID' => array(4,5,6,7),
				'MemberID' => $memberId,
				'Credits:LessThan' => 1
				)))->sort(array('Created' =>'ASC'))->First();
   		if($memberCreditsNonExp){
   			$memberCreditsNonExp->Credits = $memberCreditsNonExp->Credits -1 ;
			$memberCreditsNonExp->write();
			return true;
   		} 
		return false;
	}
}