<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">$Title</td></tr></tbody></table>
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<table style="width: 100%;" border="0">
<tbody>
<tr>
<td style="text-align: left;" width="50%"></td>
<td style="text-align: right;">
<table width="100%" border="0" cellpadding="0" cellspacing="3">
  <tbody><tr>
	<!---Second image--->
    <td>
		<% if getExpiringHeatmapsRemaining($CurrentMember.ID) || getNonExpiringHeatmapsRemaining($CurrentMember.ID) %>
			<a href="/create-heatmap/"><img src="/themes/attwiz/images/create_heatmap_button.jpg" alt=""></a>
		<% else %>
			<img src="/themes/attwiz/images/button-create-heatmap-bw.png" alt="">
		<% end_if %>
	</td>
  </tr>
</tbody></table>
</td>
</tr>
</tbody>
</table>
<br>&nbsp;   <br> 
<table style="text-align: left; float: left; width: 90%; height: 100px;" border="0">
<tbody style="text-align: left;">
<tr style="text-align: left;">
<td style="text-align: left;">Subscription Level</td>
<td style="text-align: left;"></td>
<td style="text-align: left;">
	<% if isSubscriber($CurrentMember.ID) %>
		$getMemberSubscription($CurrentMember.ID).Product.Name
	<% else %>
		You are not currently an AttentionWizard subscriber.
	<% end_if %>
</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: right;">
<p style="margin-top: 0pt; margin-bottom: 0pt; margin-left: 0in; text-indent: 0in; text-align: left; direction: ltr; unicode-bidi: embed;">Days Remaining in Subscription period</p>
</td>
<td style="text-align: left;"></td>
<td style="text-align: left;">
	<% if isSubscriber($CurrentMember.ID) %>
		$getDaysRemainingSubscription($CurrentMember.ID) days
	<% else %>
		Your subscription is not activated
	<% end_if %>
</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Heatmaps Remaining in Subscription period<br></td>
<td style="text-align: left;"></td>
<td style="text-align: left;">$getExpiringHeatmapsRemaining($CurrentMember.ID)</td>
</tr>
<tr style="text-align: left;">
<td style="text-align: left;">Non-expiring Heatmaps Remaining<br></td>
<td style="text-align: left;"></td>
<td style="text-align: left;">$getNonExpiringHeatmapsRemaining($CurrentMember.ID)</td>
</tr>
</tbody>
</table>
<table style="width: 100%;" border="0">
<tbody>
<tr>
<td>
<h2>What would you like to do?</h2>
<ul>
<li><a href="/create-heatmap">Select an image</a></li>
<li><a href="/manage-heatmaps">Access Recent Heatmaps</a> </li>
<li><a href="/account-settings/tabs/4">Purchase non-expiring heatmaps</a> </li>
<li><a href="/account-settings/tabs/0">Update account information</a></li>
<li><a href="/account-settings/tabs/3">Change or cancel subscription</a> </li>
</ul>
</td>
</tr>
</tbody>
</table>
</td>
</tr>
</tbody></table>
<span class="article_separator">&nbsp;</span>
					