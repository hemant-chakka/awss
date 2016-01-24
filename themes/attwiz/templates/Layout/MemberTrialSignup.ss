<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">
	<% if CurrentMember.SignUpTrial %>
		Signup Billing & Subscription Options
	<% else %>
		Trial Signup Billing & Subscription Options
	<% end_if %>
</td></tr></tbody></table>
<% if CurrentMember.SignUpTrial %>
	<p>Once your transaction is complete, you will see $27.00 USD charge on your credit card from AttentionWizard. If you cancel during the next 30 days, you will not be charged again.</p>
<% else %>
	<p>Once your transaction is complete, you will see a 1-cent ($0.01 USD) charge on your credit card from AttentionWizard. If you cancel during the next 30 days, you will not be charged again.</p>
<% end_if %>
<div id="TrialSignupForm">
	<h2>Billing Information</h2>
	<p>Please enter this information exactly as it appears on your credit card.</p>
	$MemberTrialSignupForm
</div>