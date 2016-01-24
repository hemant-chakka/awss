<?php
$val .= '<script type="text/javascript" src="https://www.google.com/jsapi"></script>
<script type="text/javascript">
     // Load the Visualization API and the piechart package.
     google.load(\'visualization\', \'1.0\', {\'packages\':[\'corechart\']});
     // Set a callback to run when the Google Visualization API is loaded.
     google.setOnLoadCallback(drawChart);
     // Callback that creates and populates a data table,
     // instantiates the pie chart, passes in the data and
     // draws it.
     function drawChart() {
       // Create the data table.
       var data = new google.visualization.DataTable();
       data.addColumn(\'string\', \'Month\');
       data.addColumn(\'number\', \'Percentage\');
       data.addRows([
         ';

if ($scope->locally()->hasValue('userTrialData', null, true)) { 
$val .= '
	       	';

$scope->locally()->obj('userTrialData', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
        		';

if ($scope->locally()->hasValue('Last', null, true)) { 
$val .= '
        			[\'';

$val .= $scope->locally()->XML_val('Month', null, true);
$val .= '\', ';

$val .= $scope->locally()->XML_val('Percentage', null, true);
$val .= ']
        		';


}else { 
$val .= '
        			[\'';

$val .= $scope->locally()->XML_val('Month', null, true);
$val .= '\', ';

$val .= $scope->locally()->XML_val('Percentage', null, true);
$val .= '],
        		';


}
$val .= '
        	';


}; $scope->popScope(); 
$val .= '
         ';


}
$val .= '
       ]);
       // Set chart options
       var options = {\'title\':\'Life Time Value\',
                      \'width\':500,
                       \'height\':300};
       // Instantiate and draw our chart, passing in some options.
       var chart = new google.visualization.BarChart(document.getElementById(\'chart_div\'));
       chart.draw(data, options);
      }
</script>
<!--Div that will hold the chart-->
<div id="chart_div"></div>';

