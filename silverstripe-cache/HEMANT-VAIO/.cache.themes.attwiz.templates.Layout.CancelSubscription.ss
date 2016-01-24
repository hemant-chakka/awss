<?php
$val .= '<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
	';

$val .= $scope->locally()->XML_val('Content', null, true);
$val .= '
	<div id="CancelSubscription">
		<p>We\'re sorry to see you go. Please take a moment to let us know what happened (you may select as many as apply).</p>
		<p>Please select at least one. <span style="color:red;">*</span></p>
		';

$val .= $scope->locally()->XML_val('CancelSubscriptionForm', null, true);
$val .= '
	</div>
';


}else { 
$val .= '
		<p><strong>You are not currently an AttentionWizard subscriber.</strong></p>
';


}
$val .= '




';

