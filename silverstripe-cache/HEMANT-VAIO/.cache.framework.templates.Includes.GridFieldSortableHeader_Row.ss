<?php
$val .= '<tr class="sortable-header">
	';

$scope->locally()->obj('Fields', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
		<th class="main col-';

$val .= $scope->locally()->XML_val('getName', null, true);
$val .= '">';

$val .= $scope->locally()->XML_val('Field', null, true);
$val .= '</th>
	';


}; $scope->popScope(); 
$val .= '
</tr>
';

