<?php

class DashboardRevenueUnitsByProductPanel extends DashboardPanel {

  private static $db = array (
  		'StartDate' => 'Date',
  		'EndDate' => 'Date',
  		'SelectCriteria' => 'Enum(array("Revenue","Units"))',
  		
  );


  private static $icon = "dashboard/images/google-analytics.png";
  
  private static $priority = 3;
  
  
  public function getLabel() {
    return _t('Mysite.REVENUEUNITSBYPRODUCT','Revenue Units By Product');
  }

  public function getDescription() {
    return _t('Mysite.REVENUEUNITSBYPRODUCTDESCRIPTION','Total Revenue & Units by Product.');
  }

  public function getConfiguration() {
    $fields = parent::getConfiguration();
    $fields->push(DateField::create('StartDate','Start Date')->setConfig('showcalendar', true));
    $fields->push(DateField::create('EndDate','End Date')->setConfig('showcalendar', true));
    $fields->push(DropdownField::create('SelectCriteria','Select Criteria',singleton('DashboardRevenueUnitsByProductPanel')->dbObject('SelectCriteria')->enumValues())->setEmptyString('(Select criteria)'));
    return $fields;
  }

  public function getRevenueUnitsByProduct() {
  	if($this->StartDate == null)
  		return false;
  	$startDate = $this->StartDate;
  	$endDate = $this->EndDate;
  	//Trial revenue and units
  	$result = DB::query("SELECT COUNT( * ) Count, SUM( Amount ) TotalAmount
	FROM  `Order` WHERE OrderStatus =  'c'
	AND ( Amount = 0.01 OR Amount = 1.00)
	AND (Created BETWEEN '$startDate' AND '$endDate')");
  	$trialRevenue = 0;
  	$trialUnits = 0;
  	if($result->numRecords() > 0){
  		$row = $result->nextRecord();
		$trialRevenue = $row['TotalAmount'];
		$trialUnits = $row['Count'];
  	}
    //Bronze revenue and units
  	$result = DB::query("SELECT COUNT( * ) Count, SUM( Amount ) TotalAmount
  	FROM  `Order` WHERE OrderStatus =  'c'
  	AND ProductID = 1 AND Amount != 0.01 AND Amount != 1.00
  	AND (Created BETWEEN '$startDate' AND '$endDate')");
  	$bronzeRevenue = 0;
  	$bronzeUnits = 0;
  	if($result->numRecords() > 0){
  		$row = $result->nextRecord();
  		$bronzeRevenue = $row['TotalAmount'];
  		$bronzeUnits = $row['Count'];
  	}
  	
  	$result = DB::query("SELECT COUNT( * ) Count
	FROM  `MemberBillingHistory` 
	WHERE  `ProductID` =1
  	AND (Created BETWEEN '$startDate' AND '$endDate')");
  	if($result->numRecords() > 0){
  		$row = $result->nextRecord();
  		$bronzeRevenue += ($row['Count']*27);
  		$bronzeUnits += $row['Count'];
  	}
  	//Silver revenue and units
  	$result = DB::query("SELECT COUNT( * ) Count, SUM( Amount ) TotalAmount
  	FROM  `Order` WHERE OrderStatus =  'c'
  	AND ProductID = 2 AND (Created BETWEEN '$startDate' AND '$endDate')");
  	$silverRevenue = 0;
  	$silverUnits = 0;
  	if($result->numRecords() > 0){
  		$row = $result->nextRecord();
  		$silverRevenue = $row['TotalAmount'];
  		$silverUnits = $row['Count'];
  	}
  	$result = DB::query("SELECT COUNT( * ) Count
  			FROM  `MemberBillingHistory`
  			WHERE  `ProductID` =2
  			AND (Created BETWEEN '$startDate' AND '$endDate')");
  	if($result->numRecords() > 0){
  		$row = $result->nextRecord();
  		$silverRevenue += ($row['Count']*97);
  		$silverUnits += $row['Count'];
  	}
  	//Gold revenue and units
  	$result = DB::query("SELECT COUNT( * ) Count, SUM( Amount ) TotalAmount
  			FROM  `Order` WHERE OrderStatus =  'c'
  			AND ProductID = 3 AND (Created BETWEEN '$startDate' AND '$endDate')");
  	$goldRevenue = 0;
  	$goldUnits = 0;
  	if($result->numRecords() > 0){
  		$row = $result->nextRecord();
  		$goldRevenue = $row['TotalAmount'];
  		$goldUnits = $row['Count'];
  	}
  	$result = DB::query("SELECT COUNT( * ) Count
  			FROM  `MemberBillingHistory`
  			WHERE  `ProductID` =3
  			AND (Created BETWEEN '$startDate' AND '$endDate')");
  	if($result->numRecords() > 0){
  		$row = $result->nextRecord();
  		$goldRevenue += ($row['Count']*197);
  		$goldUnits += $row['Count'];
  	}
  	//Prepaid revenue and units
  	$result = DB::query("SELECT SUM( Amount ) TotalAmount, ProductID
	FROM  `Order` 
	WHERE OrderStatus =  'c'
	AND ProductID IN ( 4, 5, 6, 7 )  
	AND (Created BETWEEN '$startDate' AND '$endDate')
	GROUP BY ProductID");
  	$prepaidRevenue = 0;
  	$prepaidUnits = 0;
  	if($result->numRecords() > 0){
  		while($row = $result->nextRecord()) {
			$prepaidRevenue += $row['TotalAmount'];
  			if($row['ProductID'] == 7)
				$prepaidUnits += ($row['TotalAmount']/59);
			if($row['ProductID'] == 6)
				$prepaidUnits += ($row['TotalAmount']/19);
			if($row['ProductID'] == 5)
				$prepaidUnits += ($row['TotalAmount']/29);
			if($row['ProductID'] == 4)
				$prepaidUnits += ($row['TotalAmount']/49);
  		}
  	}
  	if($this->SelectCriteria == 'Revenue'){
  		$revenueByProduct = new ArrayList();
  		$item = new ArrayData(array(
  				'Trial' => $trialRevenue,
  				'Bronze' => $bronzeRevenue,
  				'Silver' => $silverRevenue,
  				'Gold' => $goldRevenue,
  				'Prepaid' => $prepaidRevenue
  		));
  		$revenueByProduct->push($item);
  		return $revenueByProduct;
  	}
  	if($this->SelectCriteria == 'Units'){
  		$revenueByUnits = new ArrayList();
  		$item = new ArrayData(array(
  				'Trial' => $trialUnits,
  				'Bronze' => $bronzeUnits,
  				'Silver' => $silverUnits,
  				'Gold' => $goldUnits,
  				'Prepaid' => $prepaidUnits
  		));
  		$revenueByUnits->push($item);
  		return $revenueByUnits;
  	}
  }
}