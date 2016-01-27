<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">$Title</td></tr></tbody></table>
<table class="contentpaneopen">
<tbody><tr>
<td valign="top">
<div id="manageHeatmaps">
<div style="text-align: right;"></div>
<table style="width: 100%; height: 100px; text-align: right;" border="0">
<tbody style="text-align: left;">
<tr style="text-align: left;">
<td colspan="2" style="text-align: right;">
<table width="100%" border="0" cellpadding="0" cellspacing="3">
  <tbody><tr>
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
<% if getExpiringHeatmapsRemaining($CurrentMember.ID) || getNonExpiringHeatmapsRemaining($CurrentMember.ID) %>
	&nbsp;
<% else %>
	<tr style="text-align: left;">
<td colspan="2" style="text-align: center;" height="30px"><div style="text-align: left;" id="existing_users_msg">
<h2>Welcome to the new AttentionWizard website.</h2>
<p class="awbutton"><a href="<% if CurrentMember.SignUpTrial %>/member-trial-signup<% else %>/trial-signup<% end_if %>"><img title="Try It Now" alt="Try It Now" src="/themes/attwiz/images/sign-up-one-penny-5.jpg"></a></p>
<p><strong>We have  discontinued our Lite service and its free daily low-resolution  heatmaps. </strong>We are pleased to now offer you three subscription options for creating full-featured AttentionWizard heatmaps (previously called Pro Heatmaps).</p>
<ul>
<li>Get 10 heatmaps for 1 dollar by enrolling  in the one month trial process. <a href="/account-settings/tabs/3">Get started here</a>.</li>
</ul>
<ul>
<li>
	<% if isSubscriber($CurrentMember.ID) %>
		<a href="/purchase-member-non-expiring-heatmaps">Purchase non-expiring heatmaps</a>
	<% else %>
		<a href="/purchase-non-expiring-heatmaps">Purchase non-expiring heatmaps</a>
	<% end_if %>
</li>
</ul>
<p><strong> You will not be able to create any new heatmaps until you complete one of the two steps above</strong></p>
</div></td>
</tr>
<% end_if %>

<tr style="text-align: left;">
<td style="text-align: left;" width="90%"></td>
</tr>
<tr style="text-align: left;">
<td colspan="2" style="text-align: left;"></td>
</tr>
</tbody>
</table>
<br>
<% if recentHeatmap %>
	<% loop recentHeatmap %>
		<table id="download-recent-heatmap">
			<tr><td colspan = 3 class="title">Heatmap Successfully Created</td></tr>
			<tr>
				<td>$OriginalImage.CroppedImage(75,90)</td>
				<td>$Heatmap.CroppedImage(75,90)</td>
				<td class="download-button">
					<% if WatermarkHeatmapID %>
						<a href="{$BaseHref}manage-heatmaps/downloadHeatmap/$WatermarkHeatmapID"><img src="/themes/attwiz/images/download.jpg"></img></a>
					<% else %>
						<a href="{$BaseHref}manage-heatmaps/downloadHeatmap/$HeatmapID"><img src="/themes/attwiz/images/download.jpg"></img></a>
					<% end_if %>
				</td>
			</tr>
		</table>
	<% end_loop %>
<% end_if %>
<h2>Recent Heatmaps:</h2>
</div>
</td>
</tr>
</tbody></table>
<% if $PaginatedHeatmapsList %>
	<span class="article_separator">&nbsp;</span>
	<table id="heatmap-list">
		<% loop $PaginatedHeatmapsList %>
			<tr>
				<td width=70px>$OriginalImage.CroppedImage(60,50)</td>
				<td>
					<ul>
						<li><b>$UploadImageName</b>, Created on $Created.Nice</li>
						<li>
							<% if WatermarkHeatmapID %>
								<a href="{$BaseHref}manage-heatmaps/downloadHeatmap/$WatermarkHeatmapID">download</a>
							<% else %>
								<a href="{$BaseHref}manage-heatmaps/downloadHeatmap/$HeatmapID">download</a>
							<% end_if %>
							 | <a class = "delete-heatmap" href="{$BaseHref}manage-heatmaps/deleteHeatmap/$ID">delete</a>
						</li>
					</ul>
				</td>
			</tr>
    	<% end_loop %>
	</table>
	<% if $PaginatedHeatmapsList.MoreThanOnePage %>
    	<% if $PaginatedHeatmapsList.NotFirstPage %>
        	<a class="prev" href="$PaginatedHeatmapsList.PrevLink">Prev</a>
    	<% end_if %>
	    <% loop $PaginatedHeatmapsList.Pages %>
    	    <% if $CurrentBool %>
        	    $PageNum
	        <% else %>
    	        <% if $Link %>
        	        <a href="$Link">$PageNum</a>
            	<% else %>
                	...
	            <% end_if %>
    	    <% end_if %>
        <% end_loop %>
	    <% if $PaginatedHeatmapsList.NotLastPage %>
    	    <a class="next" href="$PaginatedHeatmapsList.NextLink">Next</a>
	    <% end_if %>
	<% end_if %>
<% else %>
	<p>You have not created any heatmaps yet.</p>
<% end_if %>













