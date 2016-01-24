<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">$Title</td></tr></tbody></table>
<% if isSubscriber($CurrentMember.ID) %>
	$Content
	<div id="CancelSubscription">
		<p>We're sorry to see you go. Please take a moment to let us know what happened (you may select as many as apply).</p>
		<p>Please select at least one. <span style="color:red;">*</span></p>
		$CancelSubscriptionForm
	</div>
<% else %>
		<p><strong>You are not currently an AttentionWizard subscriber.</strong></p>
<% end_if %>




