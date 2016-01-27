<script type="text/javascript">
	(function($) { 
	$("document").ready(function(){
		$(function() {
		    $( "#tabs" ).tabs(<% if Tab %>{ active: $Tab }<% end_if %>);
		  });
	});
})(jQuery)
</script>
<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">$Title</td></tr></tbody></table>
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<p></p>
<p></p>

<div id="tabs">
  <ul>
    <li><a href="#tabs-1">Overview</a></li>
    <li><a href="#tabs-2">Credit Card</a></li>
    <li><a href="#tabs-3">Payment History</a></li>
    <li><a href="#tabs-4">Change Subscription</a></li>
    <li><a href="#tabs-5">Non-Expiring Heatmaps</a></li>
  </ul>
  <div id="tabs-1">
    <p>
    <table class="accountsettings" style="text-align: left;" border="0">
<tbody style="text-align: left;">
<tr style="text-align: left;">
<td style="text-align: left;">Login email address:</td>
<td style="text-align: left;">
 <a href="mailto:$CurrentMember.Email">$CurrentMember.Email</a> | <a href="/edit-profile">change email address or password</a></td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Subscription level :</td>
<td style="text-align: left;">
	<% if isSubscriber($CurrentMember.ID) %>
		$getMemberSubscription($CurrentMember.ID).Product.Name
	<% else %>
		You are not currently an AttentionWizard subscriber.
	<% end_if %>&nbsp; 
	<a class="change-sub" href="javascript:">see details </a>| <a class="change-sub" href="javascript:">change subscription</a>
</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Next billing date:</td>
<td style="text-align: left;">
	<% if isSubscriber($CurrentMember.ID) %>
		$getMemberSubscription($CurrentMember.ID).ExpireDate.Format(d M Y)
	<% else %>
		Your subscription is not activated
	<% end_if %>&nbsp;
	<a class="billing-history" href="javascript:">view history</a></td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Heatmaps remaining in billing cycle:</td>
<td style="text-align: left;">$getExpiringHeatmapsRemaining($CurrentMember.ID)</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Non-expiring heatmaps:</td>
<td style="text-align: left;">
<table border="0" align="left">
<tbody>
<tr>
<td>$getNonExpiringHeatmapsRemaining($CurrentMember.ID)</td>
<td>
	<% if isSubscriber($CurrentMember.ID) %>
		<a href="/purchase-member-non-expiring-heatmaps">purchase</a>
	<% else %>
		<a href="/purchase-non-expiring-heatmaps">purchase</a>
	<% end_if %>
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
    <p>$UpdateBillingAddressForm</p>
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
			<% loop BillingHistory %>
			<tr>
				<td align="center"> $Date </td>
				<td align="center"> $Description </td>
				<td align="center"> $CCType </td>
				<td align="center"> $CCNumber </td>
				<td align="center"> $Amount </td>
			</tr>
			<% end_loop %>
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
	<% if isSubscriber($CurrentMember.ID) %>
		$getMemberSubscription($CurrentMember.ID).Product.Name
	<% else %>
		You are not currently an AttentionWizard subscriber.
	<% end_if %>
</span>
</td>
<td align="right"><% if isSubscriber($CurrentMember.ID) %><a href="/account-settings/cancel-subscription/">Cancel subscription</a><% end_if %></td>
</tr>
<tr>
<td colspan="2">You have $getExpiringHeatmapsRemaining($CurrentMember.ID) heatmaps remaining in this billing cycle and $getNonExpiringHeatmapsRemaining($CurrentMember.ID) non-expiring heatmaps available.</td>
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
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Bronze" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">10</td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Silver" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">50</td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Gold" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">200</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left; background: #eef3f7;"><strong>Subscription fee / month</strong></td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Bronze" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">$27 USD</td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Silver" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">$97 USD</td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Gold" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">$197 USD</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left; background: #eef3f7;"><strong>Additional 10-packs of non-expiring heatmaps</strong></td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Bronze" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">
<p>$49 USD</p>
<p> 
<% if getMemberSubscription($CurrentMember.ID).Product.Name == "Bronze" %>
	<a href="/purchase-member-non-expiring-heatmaps">Buy One</a>
<% end_if %>

</p>
</td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Silver" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">
<p>$29 USD</p>
<p> 
<% if getMemberSubscription($CurrentMember.ID).Product.Name == "Silver" %>
	<a href="/purchase-member-non-expiring-heatmaps">Buy One</a>
<% end_if %>
</p>
</td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Gold" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">
<p>$19 USD</p>
<p>
<% if getMemberSubscription($CurrentMember.ID).Product.Name == "Gold" %>
	<a href="/purchase-member-non-expiring-heatmaps">Buy One</a>
<% end_if %> 
</p>
</td>
</tr>
<tr style="text-align: center;">
<td style="text-align: center;"><br></td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Bronze" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">
<form method="POST" action="">
<% if isSubscriber($CurrentMember.ID) %>
	<% if getMemberSubscription($CurrentMember.ID).Product.Name == "Bronze" %>
		<div class="currentSubscriptionButtonGray">Your Current Subscription</div>
	<% else %>
		<a href="/account-settings/ChangeSubscriptionType/1"><span class="changeSubscriptionButtonBlue">Go Bronze</span></a>
	<% end_if %>
<% else %>
	<a href="/account-settings/createSubscription/1"><span class="changeSubscriptionButtonBlue">Buy Now</span></a>
<% end_if %>
</form></td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Silver" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">
<% if isSubscriber($CurrentMember.ID) %>
	<% if getMemberSubscription($CurrentMember.ID).Product.Name == "Silver" %>
		<div class="currentSubscriptionButtonGray">Your Current Subscription</div>
	<% else %>
		<a href="/account-settings/ChangeSubscriptionType/2"><span class="changeSubscriptionButtonBlue">Go Silver</span></a>
	<% end_if %>
<% else %>
	<a href="/account-settings/createSubscription/2"><span class="changeSubscriptionButtonBlue">Buy Now</span></a>
<% end_if %>	
<input type="hidden" name="transfer_msc_id" value="2">
	
	</td>
<td style="text-align: center; <% if getMemberSubscription($CurrentMember.ID).Product.Name == "Gold" %>background: #d8a50c;<% else %>background: #eef3f7;<% end_if %>">
<% if isSubscriber($CurrentMember.ID) %>
	<% if getMemberSubscription($CurrentMember.ID).Product.Name == "Gold" %>
		<div class="currentSubscriptionButtonGray">Your Current Subscription</div>
	<% else %>
		<a href="/account-settings/ChangeSubscriptionType/3"><span class="changeSubscriptionButtonBlue">Go Gold</span></a>
	<% end_if %>
<% else %>
	<a href="/account-settings/createSubscription/3"><span class="changeSubscriptionButtonBlue">Buy Now</span></a>
<% end_if %>
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

<p>You currently have&nbsp; $getNonExpiringHeatmapsRemaining($CurrentMember.ID) non-expiring heatmaps in your account.</p>
<% if isSubscriber($CurrentMember.ID) %>
<p class="stButton">Based on your current subscription level, you may purchase 10 non-expiring heatmaps for&nbsp;$&nbsp;{$getNonExpiringProduct($CurrentMember.ID).Price}<a style="float:right;" href="/purchase-member-non-expiring-heatmaps"><img src="themes/attwiz/images/button_purchase.png"></a></p>
<% else %>
<p>You may purchase 10 non-expiring heatmaps for $ 59.00. <a style="float:right;" href="/purchase-non-expiring-heatmaps"><img src="themes/attwiz/images/button_purchase.png"></a></p>
<% end_if %>

<p><strong></strong></p>
<div class="clr"><strong>&nbsp;</strong></div>
  </div>
</div>

<p></p>
</td>
</tr>
</tbody></table>
