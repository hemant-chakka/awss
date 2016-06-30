<div id="s5_header_wrap" style="width:985px">
	<div id="s5_header_inner" style="width:985px">
		<div id="s5_header_inner2" style="width:985px">
			<a href="$BaseHref"><img width="300" height="56" src="/themes/attwiz/images/AW-Logo-.png"></a>
		</div>
	</div>
</div>
<div id="LoginForm">
	<% if CurrentMember %>
		<p class="LoginFormLabel">Hi, $CurrentMember.Name</p>$LoginForm
	<% else %>
		<p class="LoginFormLabel">Customer Login</p> $LoginForm	
	<% end_if %>
</div>
<% if not $CurrentMember %>
	<div id="site_upgrade_message_wrap">
		<div id="site_upgrade_message_inner">
			<center><h4>Notice to all the existing users!</h4></center>
			Please note that the site has been upgraded and old passwords will not work any more, 
			please reset your passwords by <a href="{$BaseHref}Security/lostpassword">clicking here</a>.
			We regret for the inconvenience caused to you.
		</div>
	</div>
<% end_if %>

