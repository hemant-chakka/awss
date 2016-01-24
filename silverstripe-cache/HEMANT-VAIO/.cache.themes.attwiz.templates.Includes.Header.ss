<?php
$val .= '<div id="s5_header_wrap" style="width:985px">
	<div id="s5_header_inner" style="width:985px">
		<div id="s5_header_inner2" style="width:985px">
			<a href="';

$val .= $scope->locally()->XML_val('BaseHref', null, true);
$val .= '"><img width="300" height="56" src="/themes/attwiz/images/AW-Logo-.png"></a>
		</div>
	</div>
</div>
<div id="LoginForm">
	';

if ($scope->locally()->hasValue('CurrentMember', null, true)) { 
$val .= '
		<p class="LoginFormLabel">Hi, ';

$val .= $scope->locally()->obj('CurrentMember', null, true)->XML_val('Name', null, true);
$val .= '</p>';

$val .= $scope->locally()->XML_val('LoginForm', null, true);
$val .= '
	';


}else { 
$val .= '
		<p class="LoginFormLabel">Customer Login</p> ';

$val .= $scope->locally()->XML_val('LoginForm', null, true);
$val .= '	
	';


}
$val .= '
</div>';

