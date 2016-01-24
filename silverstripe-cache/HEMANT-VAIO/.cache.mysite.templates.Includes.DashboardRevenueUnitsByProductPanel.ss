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
        data.addColumn(\'string\', \'Plan\');
        data.addColumn(\'number\', ';

$val .= $scope->locally()->XML_val('SelectCriteria', null, true);
$val .= ');
        data.addRows([
          	';

if ($scope->locally()->hasValue('RevenueUnitsByProduct', null, true)) { 
$val .= '
	       		';

$scope->locally()->obj('RevenueUnitsByProduct', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
        			[\'Trial\', ';

$val .= $scope->locally()->XML_val('Trial', null, true);
$val .= '],
			        [\'Bronze\', ';

$val .= $scope->locally()->XML_val('Bronze', null, true);
$val .= '],
          			[\'Silver\', ';

$val .= $scope->locally()->XML_val('Silver', null, true);
$val .= '],
          			[\'Gold\', ';

$val .= $scope->locally()->XML_val('Gold', null, true);
$val .= '],
          			[\'Prepaid\', ';

$val .= $scope->locally()->XML_val('Prepaid', null, true);
$val .= ']
        		';


}; $scope->popScope(); 
$val .= '
         	';


}
$val .= '
        ]);

        // Set chart options
        var options = {\'title\':\'';

$val .= $scope->locally()->XML_val('SelectCriteria', null, true);
$val .= ' by Product\',
                       \'width\':450,
                       \'height\':220};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById(\'chart_div_';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '\'));
        chart.draw(data, options);
      }
    </script>
<!--Div that will hold the chart-->
<div id="chart_div_';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '"></div>';

