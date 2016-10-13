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