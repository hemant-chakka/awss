<table class="contentpaneopen"><tbody><tr><td class="contentheading" width="100%">$Title</td></tr></tbody></table>
<% if CurrentMember %><% else %>$Content<% end_if %>
<div id="LoginFormBody">$LoginForm</div>
<% if CurrentMember %><% else %><p id="ForgotPassword"><strong>Forgot your password?</strong> <a href="Security/lostpassword">Request reminder</a></p><% end_if %>
<% if CurrentMember %><% else %><p><strong>Don't have an account?</strong>   <a href="/sign-up-now/">Signup now</a></p><% end_if %>
<p><strong>Need help?</strong>   <a href="/contact-us/">Contact customer support</a></p>