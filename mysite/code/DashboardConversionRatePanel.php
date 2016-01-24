<?php

class DashboardConversionRatePanel extends DashboardPanel {

  private static $db = array (
  );


  private static $icon = "dashboard/images/google-analytics.png";
  
  private static $priority = 2;
  
  
  public function getLabel() {
    return _t('Mysite.CONVERSIONRATE','Conversion Rate');
  }


  public function getDescription() {
    return _t('Mysite.CONVERSIONRATEDESCRIPTION','Shows Conversion Rate over time.');
  }

  public function getConfiguration() {
    $fields = parent::getConfiguration();
    return $fields;
  }
  
  public function Chart() {
  	$result = DB::query("SELECT MONTH( Created ) Month , YEAR( Created ) Year , MemberID
  	FROM  `Order`
  	WHERE (Amount = 0.01 OR Amount =1)
  	AND OrderStatus =  'c'
  	ORDER BY Created");
  	if($result->numRecords() == 0)
			return false;
  	$trialMembers = array();
  	while($row = $result->nextRecord()) {
  		if($row['Month'] == 12){
  			$month = 1;
  			$year = $row['Year'] +1;
  		}else{
  			$month = $row['Month']+1;
  			$year = $row['Year'];
  		}
  		if(isset($trialMembers[$year][$month]['Members']))
  			$trialMembers[$year][$month]['Members'].= ','.$row['MemberID'];
  		else
  			$trialMembers[$year][$month]['Members'] = $row['MemberID'];
  		
  		if(isset($trialMembers[$year][$month]['Count']))
  			$trialMembers[$year][$month]['Count']++;
  		else
  			$trialMembers[$year][$month]['Count'] = 1;
  	}
  	$chart = DashboardChart::create("Conversion Rate", "Month,Year", "Percentage conversion");
  	foreach ($trialMembers as $key1 => $value1){
  		foreach ($value1 as $key2 => $value2){
  			$members = $value2['Members'];
  			$result = DB::query("SELECT MIN( Created ) Created, MONTH( Created )
			Month , YEAR( Created ) Year, MemberID
			FROM  `MemberBillingHistory`
			WHERE MemberID IN ($members) AND MONTH( Created ) = $key2 AND YEAR( Created ) = $key1
  			GROUP BY  `MemberID`
		  	ORDER BY Created");
  			$paidMembersCount = $result->numRecords();
  			$percent = ($paidMembersCount/$trialMembers[$key1][$key2]['Count'])*100;
  			$chart->addData(date('M',mktime(0,0,0,$key2,1,$key1)).",$key1", $percent);
  				
  		}
  	
  	}
  	return $chart;
  }
}