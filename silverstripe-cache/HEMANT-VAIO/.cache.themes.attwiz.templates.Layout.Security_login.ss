<?php
$val .= '<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
<div id="LoginFormBody">';

$val .= $scope->locally()->XML_val('Form', null, true);
$val .= '</div>
';

if ($scope->locally()->hasValue('CurrentMember', null, true)) { 

}else { 
$val .= '<p><strong>Don\'t have an account?</strong> <a href="/sign-up-now/">Signup now</a></p>';


}
$val .= '
<p><strong>Need help?</strong> <a href="/contact-us/">Contact customer support</a></p>';

