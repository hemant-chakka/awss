<?php 
class Subscription extends DataObject {
    private static $db = array(
        "SubscriptionID" => 'Int',
    	"StartDate" => 'SS_Datetime',
		"ExpireDate" => 'SS_Datetime',
    	"Status" => 'Boolean',
    	"IsTrial" => 'Boolean',
    	"SubscriptionCount" => 'Int',
    	"ReasonCancelled" => 'Text'
    );
    
    private static $has_one = array(
    		"Member" => "Member",
    		"Product" => "Product",
    		"Order" => "Order"
    );
    
    private static $summary_fields = array(
		    'Member.Name',
    		'Member.Email',
    		'Product.Name',
    		'statusAsText'	  
   );
   
   private static $field_labels = array(
   		'Member.Name' => 'Member Name',
   		'Member.Email' => 'Member Email',
   		'Member.Surname' => 'Member Last Name',
   		'Product.Name' => 'Plan',
   		'statusAsText' => 'Status'
   );
   private static $searchable_fields = array(
   		'Member.FirstName',
   		'Member.Surname',
   		'Member.Email',
   		'Product.Name',
   		'Status'
   );
    
	public function getCMSFields() {
		// callback function        
		$dataSource = function($memberId){  
   		 	$member = Member::get()->byID($memberId);
   		 	// Get the Page controller
			$Page_Ctrl = new Page_Controller;
			$subscription = $Page_Ctrl->getCurrentSubscription($memberId);
			if($subscription){
				switch ($subscription->ProductID) {
					case 1:
						$productArray = array(4);
						break;
					case 2:
						$productArray = array(5);
						break;
					case 3:
						$productArray = array(6);
						break;
				}	
			}else{
				$productArray = array(1,2,3,7);
			}
			return Product::get()->filter(array('ID' => $productArray))->map('ID', 'Name')->toArray();
		};
		// callback function        
		$memberDataSource = function($keyword){  
			return Member::get()->where("FirstName like '%$keyword%' OR Surname like '%$keyword%'")->map('ID', 'Name')->toArray();
		};
		$fields = parent::getCMSFields();
		if($this->ID && $this->Status){
			$products = $fields->dataFieldByName('ProductID');
			$products->setDisabledItems(array(4,5,6,7,10));
			$fields->replaceField('MemberID', new HiddenField('MemberID',$this->MemberID));
        }
		if(!$this->ID){
			$fields->addFieldToTab("Root.Main", new TextField('Keyword','Enter a keyword to search a member'),'MemberID');
			$keyword = $fields->dataFieldByName('Keyword');
			$memberId = DependentDropdownField::create('MemberID','Member', $memberDataSource)->setDepends($keyword)->setEmptyString('(Select a member)');
			$fields->replaceField('MemberID', $memberId);
			$members = $fields->dataFieldByName('MemberID');
			$products = DependentDropdownField::create('ProductID','Product', $dataSource)->setDepends($members)->setEmptyString('(Select a product)');
			$fields->replaceField('ProductID', $products);
			$fields->removeByName('Status');
			$fields->removeByName('SubscriptionID');
        	$fields->removeByName('StartDate');
        	$fields->removeByName('ExpireDate');
        	$fields->removeByName('IsTrial');
        	$fields->removeByName('SubscriptionCount');
        	$fields->removeByName('ReasonCancelled');
        	$fields->removeByName('OrderID');
        	$fields->addFieldToTab("Root.Main", new TextField('Quantity','Quantity(Required if Prepaid)'));
		}
        return $fields;
    }

    public function getCMSActions() { 
         $actions = parent::getCMSActions(); 
         $createSubscription = new FormAction ('createSubscription', 'Create Subscription');
         $createSubscription->addExtraClass('ss-ui-action-constructive');
         $cancelSubscription = new FormAction ('cancelSubscription', 'Cancel Subscription');
         $cancelSubscription->addExtraClass('ss-ui-action-destructive');
         $changeSubscription = new FormAction ('changeSubscription', 'Change Subscription');
         $changeSubscription->addExtraClass('ss-ui-action-constructive');  
		 if($this->ID && $this->Status &&($this->ProductID == 1 || $this->ProductID == 2 || $this->ProductID == 3)){
		 	$actions->push($cancelSubscription);
		 	$actions->push($changeSubscription);
		 }
		 if(!$this->ID)
		 	$actions->push($createSubscription);
         return $actions; 
    }
    
    public function statusAsText(){
    	if($this->ProductID == 4 || $this->ProductID == 5 || $this->ProductID == 6 || $this->ProductID == 7)
    		return 'Prepaid';
    	if($this->Status)
    		return 'Active';
    	return 'Expired';
    }
    
    public function MemberName(){
    	return $this->Member()->getName();
    }
    
	public function getCMSValidator() {
		if($this->ID)
			return new RequiredFields('MemberID', 'ProductID');
		else
			return new RequiredFields('MemberID', 'ProductID');
	}
    
   
    

}