<?php
$val .= '<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui123.css">
<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
';

if ($scope->locally()->hasValue('AccountFaqs', null, true)) { 
$val .= '
	<div id="accordion">
	  ';

$scope->locally()->obj('AccountFaqs', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
		  <h3>';

$val .= $scope->locally()->obj('Question', null, true)->XML_val('RAW', null, true);
$val .= '</h3>
		  <div><p>';

$val .= $scope->locally()->obj('Answer', null, true)->XML_val('RAW', null, true);
$val .= '</p></div>
	  ';


}; $scope->popScope(); 
$val .= '
	</div>
';


}
$val .= '
';

