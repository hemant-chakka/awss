<?php 
class Product extends DataObject {
    private static $db = array(
        'Name' => 'Varchar(150)',
    	'IsSubscription' => 'Boolean',
    	'Recurrence' => 'Int',
    	'Price' => 'Currency',
    	'TrialPrice' => 'Currency',
    	'RecurringPrice' => 'Currency',
    	'Credits' => 'Int',
    	'ISProductID' => 'Int',
    	'ISInitialProductID' => 'Int'
    );
    
    private static $summary_fields = array(
		    'Name'
    );
    static $searchable_fields = array(
    		'Name'
    );
    /*
	public function canDelete($member = null) {

		return false;
		
	}
	
	public function canCreate($member = null) {
	
		return false;
	
	}  */
    
    // Create Defaults
    public function requireDefaultRecords() {
    	parent::requireDefaultRecords();
    	if(!DataObject::get_one('Product',"Name = 'Bronze'")){
    		$product = new Product();
    		$product->Name = 'Bronze';
    		$product->IsSubscription = 1;
    		$product->Recurrence = 30;
    		$product->Price = null;
    		$product->TrialPrice = 0.01;
    		$product->RecurringPrice = 27.00;
    		$product->Credits = 10;
    		$product->ISProductID = 42;
    		$product->write();
    	}
    	if(!DataObject::get_one('Product',"Name = 'Silver'")){
    		$product = new Product();
    		$product->Name = 'Silver';
    		$product->IsSubscription = 1;
    		$product->Recurrence = 30;
    		$product->Price = null;
    		$product->TrialPrice = null;
    		$product->RecurringPrice = 97.00;
    		$product->Credits = 50;
    		$product->ISProductID = 44;
    		$product->write();
    	}
    	if(!DataObject::get_one('Product',"Name = 'Gold'")){
    		$product = new Product();
    		$product->Name = 'Gold';
    		$product->IsSubscription = 1;
    		$product->Recurrence = 30;
    		$product->Price = null;
    		$product->TrialPrice = null;
    		$product->RecurringPrice = 197.00;
    		$product->Credits = 200;
    		$product->ISProductID = 48;
    		$product->write();
    	}
    	if(!DataObject::get_one('Product',"Name = 'Non-expiring Heatmaps-Bronze'")){
    		$product = new Product();
    		$product->Name = 'Non-expiring Heatmaps-Bronze';
    		$product->IsSubscription = 0;
    		$product->Recurrence = 0;
    		$product->Price = 49.00;
    		$product->TrialPrice = null;
    		$product->RecurringPrice = null;
    		$product->Credits = 10;
    		$product->ISProductID = 52;
    		$product->write();
    	}
    	if(!DataObject::get_one('Product',"Name = 'Non-expiring Heatmaps-Silver'")){
    		$product = new Product();
    		$product->Name = 'Non-expiring Heatmaps-Silver';
    		$product->IsSubscription = 0;
    		$product->Recurrence = 0;
    		$product->Price = 29.00;
    		$product->TrialPrice = null;
    		$product->RecurringPrice = null;
    		$product->Credits = 10;
    		$product->ISProductID = 54;
    		$product->write();
    	}
    	if(!DataObject::get_one('Product',"Name = 'Non-expiring Heatmaps-Gold'")){
    		$product = new Product();
    		$product->Name = 'Non-expiring Heatmaps-Gold';
    		$product->IsSubscription = 0;
    		$product->Recurrence = 0;
    		$product->Price = 19.00;
    		$product->TrialPrice = null;
    		$product->RecurringPrice = null;
    		$product->Credits = 10;
    		$product->ISProductID = 56;
    		$product->write();
    	}
    	if(!DataObject::get_one('Product',"Name = 'Prepaid, Non-expiring Heatmaps'")){
    		$product = new Product();
    		$product->Name = 'Prepaid, Non-expiring Heatmaps';
    		$product->IsSubscription = 0;
    		$product->Recurrence = 0;
    		$product->Price = 59.00;
    		$product->TrialPrice = null;
    		$product->RecurringPrice = null;
    		$product->Credits = 10;
    		$product->ISProductID = 40;
    		$product->write();
    	}
    	if(!DataObject::get_one('Product',"Name = 'Gift Heatmaps'")){
    		$product = new Product();
    		$product->Name = 'Gift Heatmaps';
    		$product->IsSubscription = 0;
    		$product->Recurrence = 0;
    		$product->Price = 0;
    		$product->TrialPrice = null;
    		$product->RecurringPrice = null;
    		$product->Credits = 10;
    		$product->write();
    	}
    }
}