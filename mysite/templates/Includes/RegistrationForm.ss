<!--
<form id="Form_RegistrationForm" action="/sign-up-now/RegistrationForm" method="post" enctype="application/x-www-form-urlencoded">

	
	<p id="Form_RegistrationForm_error" class="message " style="display: none"></p>
	
	
	<fieldset>
		 
		
			<div id="Email" class="field email text">
	<label class="left" for="Form_RegistrationForm_Email"><span>*</span> Email</label>
	<div class="middleColumn">
		<input type="email" name="Email" class="email text" id="Form_RegistrationForm_Email" required="required" aria-required="true">
	</div>
	
	
	
</div>

		
			<div id="Password" class="field confirmedpassword nolabel">
	
	<div class="middleColumn">
		<div id="Password[_Password]" class="field text password">
	<label class="left" for="Password-_Password"><span>*</span> Password</label>
	<div class="middleColumn">
		<input type="password" name="Password[_Password]" class="text password" id="Password-_Password" autocomplete="off">
	</div>
	
	
	
</div>
<div id="Password[_ConfirmPassword]" class="field text password">
	<label class="left" for="Password-_ConfirmPassword">Confirm Password</label>
	<div class="middleColumn">
		<input type="password" name="Password[_ConfirmPassword]" class="text password" id="Password-_ConfirmPassword" autocomplete="off">
	</div>
	
	
	
</div>

	</div>
	
	
	
</div>

		
			<div id="Terms" class="field checkbox">
	<input type="checkbox" name="Terms" value="1" class="checkbox" id="Form_RegistrationForm_Terms" required="required" aria-required="true" checked="checked">
	<label class="right" for="Form_RegistrationForm_Terms">I agree to the <a href="/customer-login/terms-of-use/" target="_blank">AttentionWizard Terms of Use</a></label>
	
	
</div>

		
			<input type="hidden" name="SecurityID" value="31f1a5b8eedffccb68e95fff7340a58d191ac891" class="hidden" id="Form_RegistrationForm_SecurityID">

		
		<div class="clear"><!-- --></div>
	</fieldset>

	
	<div class="Actions">
		
			
	<input type="image" name="action_doRegister" class="action nolabel" id="Form_RegistrationForm_action_doRegister" src="themes/attwiz/images/button_continue.gif">


		
	</div>
	

</form>


-->



<form $FormAttributes>
    <table>
    	<tr><td>Email</td><td>$Fields.dataFieldByName(Email)</td></tr>
    	<tr><td>Password</td><td>$Fields.dataFieldByName(Password)</td></tr>
    </table>

    <div class="Actions">
        <% loop $Actions %>$Field<% end_loop %>
    </div>
</form>