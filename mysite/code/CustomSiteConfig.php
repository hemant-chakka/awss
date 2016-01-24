<?php
 
class CustomSiteConfig extends DataExtension {
     
    static $db = array(
        'MerchantAccount' => 'Int'
    );
    
    static $has_one = array(
    		'Watermark' => 'Image'
    );
 
    public function updateCMSFields(FieldList $fields) {
        $fields->addFieldToTab("Root.Main", new TextField("MerchantAccount", "Merchant Account"));
        $fields->addFieldToTab("Root.Main", $waterMark = new UploadField("Watermark", "Watermark Image"));
		//$waterMark->setAllowedFileCategories('image');
    }
}