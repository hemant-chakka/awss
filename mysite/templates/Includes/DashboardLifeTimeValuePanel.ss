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
       <% if $SelectCriteria == 'Monthly' %>
       		data.addColumn('string', 'Month');
       <% else %>
       		data.addColumn('string', 'Year');
       <% end_if %>
       data.addColumn('number', 'Percentage');
       data.addRows([
         <% if $SelectCriteria == 'Monthly' %>
       		<% if $userTrialData %>
	       		<% loop $userTrialData %>
        			<% if Last %>
        				['$Month', $Percentage]
	        		<% else %>
    	    			['$Month', $Percentage],
        			<% end_if %>
        		<% end_loop %>
         	<% end_if %>
       	 <% else %>
       		<% if $userTrialData %>
	       		<% loop $userTrialData %>
        			<% if Last %>
        				['$Year', $Percentage]
	        		<% else %>
    	    			['$Year', $Percentage],
        			<% end_if %>
        		<% end_loop %>
         	<% end_if %>
       	 <% end_if %>
       ]);
       // Set chart options
       var options = {'title':'Life Time Value <% if $SelectCriteria == 'Monthly' %>Monthly $MonthText,$Year<% else %>Yearly $Year<% end_if %>',
                      'width':450,
                       'height':220
                       };
       // Instantiate and draw our chart, passing in some options.
       var chart = new google.visualization.BarChart(document.getElementById('chart_div_$ID'));
       chart.draw(data, options);
      }
</script>
<!--Div that will hold the chart-->
<div id="chart_div_$ID"></div>

