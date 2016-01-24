<?php
$val .= '<div class="dashboard-chart"
	data-title="';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '" 
	data-xlabel="';

$val .= $scope->locally()->XML_val('XAxisLabel', null, true);
$val .= '" 
	data-ylabel="';

$val .= $scope->locally()->XML_val('YAxisLabel', null, true);
$val .= '"
	data-textinterval="';

$val .= $scope->locally()->XML_val('TextInterval', null, true);
$val .= '"
	data-height="';

$val .= $scope->locally()->XML_val('Height', null, true);
$val .= '"
	data-pointsize="';

$val .= $scope->locally()->XML_val('PointSize', null, true);
$val .= '"
	data-fontsize="';

$val .= $scope->locally()->XML_val('FontSize', null, true);
$val .= '"
	data-textposition="';

$val .= $scope->locally()->XML_val('TextPosition', null, true);
$val .= '"
>

<div id="';

$val .= $scope->locally()->XML_val('ChartID', null, true);
$val .= '" class="dashboard-chart-canvas"></div>

';

$scope->locally()->obj('ChartData', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
<div class="dashboard-chart-data" data-x="';

$val .= $scope->locally()->XML_val('XValue', null, true);
$val .= '" data-y="';

$val .= $scope->locally()->XML_val('YValue', null, true);
$val .= '"></div>
';


}; $scope->popScope(); 
$val .= '


</div>';

