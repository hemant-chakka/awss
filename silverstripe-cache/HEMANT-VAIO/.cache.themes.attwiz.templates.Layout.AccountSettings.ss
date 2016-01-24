<?php
$val .= '<script type="text/javascript">
	(function($) { 
	$("document").ready(function(){
		$(function() {
		    $( "#tabs" ).tabs(';

if ($scope->locally()->hasValue('Tab', null, true)) { 
$val .= '{ active: ';

$val .= $scope->locally()->XML_val('Tab', null, true);
$val .= ' }';


}
$val .= ');
		  });
	});
})(jQuery)
</script>
<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</td></tr></tbody></table>
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<p></p>
<p></p>

<div id="tabs">
  <ul>
    <li><a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#tabs-1">Overview</a></li>
    <li><a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#tabs-2">Credit Card</a></li>
    <li><a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#tabs-3">Billing History</a></li>
    <li><a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#tabs-4">Change Subscription</a></li>
    <li><a href="' . (Config::inst()->get('SSViewer', 'rewrite_hash_links') ? strip_tags( $_SERVER['REQUEST_URI'] ) : "") . 
				'#tabs-5">Non-Expiring Heatmaps</a></li>
  </ul>
  <div id="tabs-1">
    <p>
    <table class="accountsettings" style="text-align: left;" border="0">
<tbody style="text-align: left;">
<tr style="text-align: left;">
<td style="text-align: left;">Login email address:</td>
<td style="text-align: left;">
 <a href="mailto:';

$val .= $scope->locally()->obj('CurrentMember', null, true)->XML_val('Email', null, true);
$val .= '">';

$val .= $scope->locally()->obj('CurrentMember', null, true)->XML_val('Email', null, true);
$val .= '</a> | <a href="/edit-profile">change email address or password</a></td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Subscription level :</td>
<td style="text-align: left;">
	';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
		';

$val .= $scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true);
$val .= '
	';


}else { 
$val .= '
		You are not currently an AttentionWizard subscriber.
	';


}
$val .= '&nbsp; 
	<a class="change-sub" href="javascript:">see details </a>| <a class="change-sub" href="javascript:">change subscription</a>
</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Next billing date:</td>
<td style="text-align: left;">
	';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
		';

$val .= $scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('ExpireDate', null, true)->XML_val('Format', array('d M Y'), true);
$val .= '
	';


}else { 
$val .= '
		Your subscription is not activated
	';


}
$val .= '&nbsp;
	<a class="billing-history" href="javascript:">view history</a></td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Heatmaps remaining in billing cycle:</td>
<td style="text-align: left;">';

$val .= $scope->locally()->XML_val('getExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true);
$val .= '</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Non-expiring heatmaps:</td>
<td style="text-align: left;">
<table border="0" align="left">
<tbody>
<tr>
<td>';

$val .= $scope->locally()->XML_val('getNonExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true);
$val .= '</td>
<td>
	';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
		<a href="/purchase-member-non-expiring-heatmaps">purchase</a>
	';


}else { 
$val .= '
		<a href="/purchase-non-expiring-heatmaps">purchase</a>
	';


}
$val .= '
</td>
</tr>
</tbody>
</table>
</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;"></td>
<td style="text-align: left;"></td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;"></td>
<td style="text-align: left;"></td>
</tr>
</tbody>
</table>
    
    </p>
  </div>
  <div id="tabs-2">
    <p>';

$val .= $scope->locally()->XML_val('UpdateBillingAddressForm', null, true);
$val .= '</p>
  </div>
  <div id="tabs-3">
    <p>
    	<table align="center" width="90%"><tbody>
			<tr>
				<td class="gery_content" align="center"><b>Date</b></td>
				<td class="gery_content" align="center"><b>Description</b></td>
				<td class="gery_content" align="center"><b>Credit Card Type</b></td>
				<td class="gery_content" align="center"><b>Credit Card</b></td>
				<td class="gery_content" align="center"><b>Amount</b></td>
			</tr>
			<tr><td colspan="5" align="right" width="100%" height="10px"></td></tr>
			';

$scope->locally()->obj('BillingHistory', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= '
			<tr>
				<td align="center"> ';

$val .= $scope->locally()->XML_val('Date', null, true);
$val .= ' </td>
				<td align="center"> ';

$val .= $scope->locally()->XML_val('Description', null, true);
$val .= ' </td>
				<td align="center"> ';

$val .= $scope->locally()->XML_val('CCType', null, true);
$val .= ' </td>
				<td align="center"> ';

$val .= $scope->locally()->XML_val('CCNumber', null, true);
$val .= ' </td>
				<td align="center"> ';

$val .= $scope->locally()->XML_val('Amount', null, true);
$val .= ' </td>
			</tr>
			';


}; $scope->popScope(); 
$val .= '
		</tbody></table>
    </p>
  </div>
  <div id="tabs-4">
    <p>
    <p></p>
<p>&nbsp;</p>
<table style="width: 650px;" border="0">
<tbody>
<tr>
<td align="left">
Your Current Subscription Level: 
<span style="color: #990033;">
	';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
		';

$val .= $scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true);
$val .= '
	';


}else { 
$val .= '
		You are not currently an AttentionWizard subscriber.
	';


}
$val .= '
</span>
</td>
<td align="right">';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '<a href="/account-settings/cancel-subscription/">Cancel subscription</a>';


}
$val .= '</td>
</tr>
<tr>
<td colspan="2">You have ';

$val .= $scope->locally()->XML_val('getExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true);
$val .= ' heatmaps remaining in this billing cycle and ';

$val .= $scope->locally()->XML_val('getNonExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true);
$val .= ' non-expiring heatmaps available.</td>
</tr>
</tbody>
</table>
<!-- START: Articles Anywhere -->                 
<table style="width: 650px; height: 359px;" id="subscription" border="0">
<tbody style="text-align: left;">
<tr style="text-align: left;">
<td style="text-align: left;"></td>
<td style="text-align: center; background: #E2EBF2;"><img alt="Bronze" src="/themes/attwiz/images/bronze-subscription-image.gif"></td>
<td style="text-align: center; background: #E2EBF2;"><img alt="Silver" src="/themes/attwiz/images/silver-subscription-image.gif"></td>
<td style="text-align: center; background: #E2EBF2;"><img alt="Gold" src="/themes/attwiz/images/gold-subscription-image.gif"></td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left; background: #f2f7fa;"><strong>Included heatmaps / month</strong></td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Bronze') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">10</td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Silver') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">50</td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Gold') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">200</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left; background: #eef3f7;"><strong>Subscription fee / month</strong></td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Bronze') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">$27 USD</td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Silver') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">$97 USD</td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Gold') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">$197 USD</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left; background: #eef3f7;"><strong>Additional 10-packs of non-expiring heatmaps</strong></td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Bronze') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">
<p>$49 USD</p>
<p> 
';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Bronze') { 
$val .= '
	<a href="/purchase-member-non-expiring-heatmaps">Buy One</a>
';


}
$val .= '

</p>
</td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Silver') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">
<p>$29 USD</p>
<p> 
';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Silver') { 
$val .= '
	<a href="/purchase-member-non-expiring-heatmaps">Buy One</a>
';


}
$val .= '
</p>
</td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Gold') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">
<p>$19 USD</p>
<p>
';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Gold') { 
$val .= '
	<a href="/purchase-member-non-expiring-heatmaps">Buy One</a>
';


}
$val .= ' 
</p>
</td>
</tr>
<tr style="text-align: center;">
<td style="text-align: center;"><br></td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Bronze') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">
<form method="POST" action="">
';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
	';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Bronze') { 
$val .= '
		<div class="currentSubscriptionButtonGray">Your Current Subscription</div>
	';


}else { 
$val .= '
		<a href="/account-settings/ChangeSubscriptionType/1"><span class="changeSubscriptionButtonBlue">Go Bronze</span></a>
	';


}
$val .= '
';


}else { 
$val .= '
	<a href="/account-settings/createSubscription/1"><span class="changeSubscriptionButtonBlue">Buy Now</span></a>
';


}
$val .= '
</form></td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Silver') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">
';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
	';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Silver') { 
$val .= '
		<div class="currentSubscriptionButtonGray">Your Current Subscription</div>
	';


}else { 
$val .= '
		<a href="/account-settings/ChangeSubscriptionType/2"><span class="changeSubscriptionButtonBlue">Go Silver</span></a>
	';


}
$val .= '
';


}else { 
$val .= '
	<a href="/account-settings/createSubscription/2"><span class="changeSubscriptionButtonBlue">Buy Now</span></a>
';


}
$val .= '	
<input type="hidden" name="transfer_msc_id" value="2">
	
	</td>
<td style="text-align: center; ';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Gold') { 
$val .= 'background: #d8a50c;';


}else { 
$val .= 'background: #eef3f7;';


}
$val .= '">
';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
	';

if ($scope->locally()->obj('getMemberSubscription', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->obj('Product', null, true)->XML_val('Name', null, true)=='Gold') { 
$val .= '
		<div class="currentSubscriptionButtonGray">Your Current Subscription</div>
	';


}else { 
$val .= '
		<a href="/account-settings/ChangeSubscriptionType/3"><span class="changeSubscriptionButtonBlue">Go Gold</span></a>
	';


}
$val .= '
';


}else { 
$val .= '
	<a href="/account-settings/createSubscription/3"><span class="changeSubscriptionButtonBlue">Buy Now</span></a>
';


}
$val .= '
</td>
</tr>
</tbody>
</table>
  <!-- END: Articles Anywhere -->
<p></p><div class="clr">&nbsp;</div>
    
    </p>
  </div>
  <div id="tabs-5">
    <p></p>
<p>Non-expiring heatmaps provide you additional access to AttentionWizard heatmaps beyond your monthly subscription allotment.</p>
<p>&nbsp;</p>

<p>You currently have&nbsp; ';

$val .= $scope->locally()->XML_val('getNonExpiringHeatmapsRemaining', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true);
$val .= ' non-expiring heatmaps in your account.</p>
';

if ($scope->locally()->hasValue('isSubscriber', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)) { 
$val .= '
<p class="stButton">Based on your current subscription level, you may purchase 10 non-expiring heatmaps for&nbsp;$&nbsp;';

$val .= $scope->locally()->obj('getNonExpiringProduct', array($scope->locally()->obj('CurrentMember', null, true)->XML_val('ID', null, true)), true)->XML_val('Price', null, true);
$val .= '<a href="/purchase-member-non-expiring-heatmaps"><img src="themes/attwiz/images/button_purchase.png"></a></p>
';


}else { 
$val .= '
<p>You may purchase 10 non-expiring heatmaps for $ 59.00. <a href="/purchase-non-expiring-heatmaps"><img src="themes/attwiz/images/button_purchase.png"></a></p>
';


}
$val .= '

<p><strong></strong></p>
<div class="clr"><strong>&nbsp;</strong></div>
  </div>
</div>

<p></p>
</td>
</tr>
</tbody></table>
';

