<?php
$val .= '<ul id="';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '" class="';

$val .= $scope->locally()->XML_val('extraClass', null, true);
$val .= '">
	';

if ($scope->locally()->obj('Options', null, true)->hasValue('Count', null, true)) { 
$val .= '
		';

$scope->locally()->obj('Options', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
			<li class="';

$val .= $scope->locally()->XML_val('Class', null, true);
$val .= '">
				<input id="';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '" class="checkbox" name="';

$val .= $scope->locally()->XML_val('Name', null, true);
$val .= '" type="checkbox" value="';

$val .= $scope->locally()->XML_val('Value', null, true);
$val .= '"';

if ($scope->locally()->hasValue('isChecked', null, true)) { 
$val .= ' checked="checked"';


}
if ($scope->locally()->hasValue('isDisabled', null, true)) { 
$val .= ' disabled="disabled"';


}
$val .= ' />
				<label for="';

$val .= $scope->locally()->XML_val('ID', null, true);
$val .= '">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</label>
			</li> 
		';


}; $scope->popScope(); 
$val .= '
	';


}else { 
$val .= '
		<li>No options available</li>
	';


}
$val .= '
</ul>
';

