<script type="text/javascript" src="https://www.google.com/jsapi"></script>
    <script type="text/javascript">

      // Load the Visualization API and the piechart package.
      google.load('visualization', '1.0', {'packages':['corechart']});

      // Set a callback to run when the Google Visualization API is loaded.
      google.setOnLoadCallback(drawChart);

      // Callback that creates and populates a data table,
      // instantiates the pie chart, passes in the data and
      // draws it.
      function drawChart() {

        // Create the data table.
        var data = new google.visualization.DataTable();
        data.addColumn('string', 'Plan');
        data.addColumn('number', '$SelectCriteria');
        data.addRows([
          	<% if $getRevenueUnitsByProduct %>
	       		<% loop $getRevenueUnitsByProduct %>
        			['Trial', $Trial],
			        ['Bronze', $Bronze],
          			['Silver', $Silver],
          			['Gold', $Gold],
          			['Prepaid', $Prepaid]
        		<% end_loop %>
         	<% end_if %>
        ]);

        // Set chart options
        var options = {'title':"$SelectCriteria by Product between $StartDate.Format('jS M Y') & $EndDate.Format('jS M Y')",
                       'width':450,
                       'height':220};

        // Instantiate and draw our chart, passing in some options.
        var chart = new google.visualization.PieChart(document.getElementById('chart_div_$ID'));
        chart.draw(data, options);
      }
    </script>
<!--Div that will hold the chart-->
<div id="chart_div_$ID"></div>