<?php

class DashboardLifeTimeValuePanel extends DashboardPanel {

  private static $db = array (
  		'SelectCriteria' => 'Enum(array("Monthly","Yearly"))',
  		'Month' => 'Int',
  		'Year' => 'Int'
  );


  private static $icon = "dashboard/images/google-analytics.png";
  
  private static $priority = 1;
  
  
  public function getLabel() {
    return _t('Mysite.LIFETIMEVALUE','Lifetime Value');
  }


  public function getDescription() {
    return _t('Mysite.LIFETIMEVALUEDESCRIPTION','Shows Lifetime Value.');
  }

  public function getConfiguration() {
    $fields = parent::getConfiguration();
    $fields->push(DropdownField::create('SelectCriteria','Select Criteria',singleton('DashboardLifeTimeValuePanel')->dbObject('SelectCriteria')->enumValues())->setEmptyString('(Select criteria)'));
    $monthArray = array();
    for( $i = 1; $i <= 12; $i++ ) {
    	$month = date( 'F', mktime( 0, 0, 0, $i, 1, 2015 ) );
    	$monthArray[$i] = $month;
    }
    // callback function
    $monthDataSource = function($criteria){
    	if($criteria == 'Monthly')
			return $monthArray;
    };
    $criteria = $fields->dataFieldByName('SelectCriteria');
    //$fields->push(DependentDropdownField::create('Month','Select Month', $monthDataSource)->setDepends($criteria)->setEmptyString('(Select month)'));
    $fields->push(DropdownField::create('Month','Select Month',$monthArray)->setEmptyString('(Select month)'));
    $currentYear = date('Y');
    $yearArray = array();
    for($j=$currentYear-10;$j<=$currentYear;$j++){
    	$yearArray[$j] = $j;
    }
    $fields->push(DropdownField::create('Year','Select Year',$yearArray)->setEmptyString('(Select year)'));
    return $fields;
  }
  
  public function getMonthText(){
  	return date('M',mktime(0,0,0,$this->Month,1,$this->Year));
  }

  public function userTrialData() {
  	if($this->SelectCriteria == 'Monthly'){
		$result = DB::query("SELECT MIN( Created ) Created, MemberID
		FROM  `MemberBillingHistory` 
		GROUP BY  `MemberID`
		HAVING MONTH( Created ) =$this->Month
		AND YEAR( Created ) =$this->Year");
		if($result->numRecords() == 0)
			return false;
		$members = array();
		$membersString = '';
		$data = new ArrayList();
		while($row = $result->nextRecord()) {
			$members[] =  $row['MemberID'];
			if($membersString == '')
				$membersString = $row['MemberID'];
			else	
				$membersString .= ','.$row['MemberID'];
		}
		$totalMembers = count($members);
		if(($this->Month+1)> 12){
			$month = $this->Month+1-12;
			$year = $this->Year + 1;
		}else{
			$month = $this->Month+1;
			$year = $this->Year;
		}
		$result = DB::query("SELECT * 
		FROM  `MemberBillingHistory` 
		WHERE MONTH( Created ) = $month
		AND YEAR( Created ) = $year  
		AND MemberID IN ( $membersString ) 
		GROUP BY MemberID");
		$totalMembersMonth1 = 0;
		if($result->numRecords() > 0) {
			while($row = $result->nextRecord()) {
				$totalMembersMonth1++;
			}
		}
		$item = new ArrayData(array('Month' => date( "M,Y", mktime( 0, 0, 0, $month, 1, $year ) ),'Percentage' =>(($totalMembersMonth1*100)/$totalMembers)));
		$data->push($item);
		if(($this->Month+2)> 12){
			$month = $this->Month+2-12;
			$year = $this->Year + 1;
		}else{
			$month = $this->Month+2;
			$year = $this->Year;
		}
		$result = DB::query("SELECT *
				FROM  `MemberBillingHistory`
				WHERE MONTH( Created ) = $month
				AND YEAR( Created ) = $year
				AND MemberID IN ( $membersString )
				GROUP BY MemberID");
		$totalMembersMonth2 = 0;
		if($result->numRecords() > 0) {
			while($row = $result->nextRecord()) {
				$totalMembersMonth2++;
			}
		}
		$item = new ArrayData(array('Month' => date( "M,Y", mktime( 0, 0, 0, $month, 1, $year ) ),'Percentage' =>(($totalMembersMonth2*100)/$totalMembers)));
		$data->push($item);
		if(($this->Month+3)> 12){
			$month = $this->Month+3-12;
			$year = $this->Year + 1;
		}else{
			$month = $this->Month+3;
			$year = $this->Year;
		}
		$result = DB::query("SELECT *
				FROM  `MemberBillingHistory`
				WHERE MONTH( Created ) = $month
				AND YEAR( Created ) = $year
				AND MemberID IN ( $membersString )
				GROUP BY MemberID");
		$totalMembersMonth3 = 0;
		if($result->numRecords() > 0) {
			while($row = $result->nextRecord()) {
				$totalMembersMonth3++;
			}
		}
		$item = new ArrayData(array('Month' => date( "M,Y", mktime( 0, 0, 0, $month, 1, $year ) ),'Percentage' =>(($totalMembersMonth3*100)/$totalMembers)));
		$data->push($item);
		if(($this->Month+4)> 12){
			$month = $this->Month+4-12;
			$year = $this->Year + 1;
		}else{
			$month = $this->Month+4;
			$year = $this->Year;
		}
		$result = DB::query("SELECT *
				FROM  `MemberBillingHistory`
				WHERE MONTH( Created ) = $month
				AND YEAR( Created ) = $year
				AND MemberID IN ( $membersString )
				GROUP BY MemberID");
		$totalMembersMonth4 = 0;
		if($result->numRecords() > 0) {
			while($row = $result->nextRecord()) {
				$totalMembersMonth4++;
			}
		}
		$item = new ArrayData(array('Month' => date( "M,Y", mktime( 0, 0, 0, $month, 1, $year ) ),'Percentage' =>(($totalMembersMonth4*100)/$totalMembers)));
		$data->push($item);
		return $data;		
	}
	if($this->SelectCriteria == 'Yearly'){
		$result = DB::query("SELECT MIN( Created ) Created, MemberID
				FROM  `MemberBillingHistory`
				GROUP BY  `MemberID`
				HAVING YEAR( Created ) =$this->Year");
		if($result->numRecords() == 0)
			return false;
		$members = array();
		$membersString = '';
		$data = new ArrayList();
		while($row = $result->nextRecord()) {
			$members[] =  $row['MemberID'];
			if($membersString == '')
				$membersString = $row['MemberID'];
			else
				$membersString .= ','.$row['MemberID'];
		}
		$totalMembers = count($members);
		$year = $this->Year + 1;
		$result = DB::query("SELECT *
				FROM  `MemberBillingHistory`
				WHERE YEAR( Created ) = $year
				AND MemberID IN ( $membersString )
				GROUP BY MemberID");
		$totalMembersMonth1 = 0;
		if($result->numRecords() > 0) {
			while($row = $result->nextRecord()) {
				$totalMembersMonth1++;
			}
		}
	
		$item = new ArrayData(array('Year' => $year,'Percentage' =>(($totalMembersMonth1*100)/$totalMembers)));
		$data->push($item);
		$year = $this->Year + 2;
		$result = DB::query("SELECT *
				FROM  `MemberBillingHistory`
				WHERE YEAR( Created ) = $year
				AND MemberID IN ( $membersString )
				GROUP BY MemberID");
		$totalMembersMonth2 = 0;
		if($result->numRecords() > 0) {
			while($row = $result->nextRecord()) {
				$totalMembersMonth2++;
			}
		}
		$item = new ArrayData(array('Year' => $year,'Percentage' =>(($totalMembersMonth2*100)/$totalMembers)));
		$data->push($item);
		$year = $this->Year + 3;
		$result = DB::query("SELECT *
				FROM  `MemberBillingHistory`
				WHERE YEAR( Created ) = $year
				AND MemberID IN ( $membersString )
				GROUP BY MemberID");
		$totalMembersMonth3 = 0;
		if($result->numRecords() > 0) {
			while($row = $result->nextRecord()) {
				$totalMembersMonth3++;
			}
		}
		$item = new ArrayData(array('Year' => $year,'Percentage' =>(($totalMembersMonth3*100)/$totalMembers)));
		$data->push($item);
		$year = $this->Year + 4;
		$result = DB::query("SELECT *
				FROM  `MemberBillingHistory`
				WHERE YEAR( Created ) = $year
				AND MemberID IN ( $membersString )
				GROUP BY MemberID");
		$totalMembersMonth4 = 0;
		if($result->numRecords() > 0) {
			while($row = $result->nextRecord()) {
				$totalMembersMonth4++;
			}
		}
		$item = new ArrayData(array('Year' => $year,'Percentage' =>(($totalMembersMonth4*100)/$totalMembers)));
		$data->push($item);
		return $data;
	}
  }
  
}