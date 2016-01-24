<link rel="stylesheet" href="//code.jquery.com/ui/1.11.2/themes/smoothness/jquery-ui123.css">
<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">$Title</td></tr></tbody></table>
<% if $AccountFaqs %>
	<div id="accordion">
	  <% loop $AccountFaqs %>
		  <h3>$Question.RAW</h3>
		  <div><p>$Answer.RAW</p></div>
	  <% end_loop %>
	</div>
<% end_if %>
