<form $FormAttributes>
    <% if $Message %>
        <div id="{$FormName}_error" class="message $MessageType">$Message.RAW</div>
    <% else %>
        <p id="{$FormName}_error" class="message $MessageType" style="display: none"></p>
    <% end_if %>
    $unsetFormMessage
    <fieldset>
        <h5>Credit Card Details</h5>
        <table id="CreditCardDetails">
        	<tr>
        		<td colspan=2>
        			<div id="CreditCardType" class="field optionset">
            			<label class="left" for="{$FormName}_CreditCardType">Credit Card Type</label>
				        $Fields.dataFieldByName(CreditCardType)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(CreditCardType).MessageType">
                			$Fields.dataFieldByName(CreditCardType).Message
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="NameOnCard" class="field text">
            			<label class="left" for="{$FormName}_NameOnCard">Name On Card</label>
				        $Fields.dataFieldByName(NameOnCard)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(NameOnCard).MessageType">
                			$Fields.dataFieldByName(NameOnCard).Message
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="CreditCardNumber" class="field text">
            			<label class="left" for="{$FormName}_CreditCardNumber">Credit Card Number</label>
				        $Fields.dataFieldByName(CreditCardNumber)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(CreditCardNumber).MessageType">
                			$Fields.dataFieldByName(CreditCardNumber).Message
            			</span>
            			$Fields.dataFieldByName(CreditCardNumberCopy)
            			$Fields.dataFieldByName(CreditCardNumberCur)
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="CreditCardCVV" class="field text">
            			<label class="left" for="{$FormName}_CreditCardCVV">Security/CVV Code</label>
				        $Fields.dataFieldByName(CreditCardCVV)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(CreditCardCVV).MessageType">
                			$Fields.dataFieldByName(CreditCardCVV).Message
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="ExpiryMonth" class="field dropdown">
            			<label class="left" for="{$FormName}_ExpiryMonth">Expiry Date</label>
				        $Fields.dataFieldByName(ExpiryMonth)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(ExpiryMonth).MessageType">
                			$Fields.dataFieldByName(ExpiryMonth).Message
            			</span>
       		 		</div>
       		 		<div id="ExpiryYear" class="field dropdown nolabel">
				        $Fields.dataFieldByName(ExpiryYear)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(ExpiryYear).MessageType">
                			$Fields.dataFieldByName(ExpiryYear).Message
            			</span>
       		 		</div>
        		</td>
        	</tr>
        </table>
        <h5>Billing Address</h5>
        <table id="BillingAddress">
        	<tr>
        		<td>
        			<div id="FirstName" class="field text">
            			<label class="left" for="{$FormName}_FirstName">First Name</label>
				        $Fields.dataFieldByName(FirstName)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(FirstName).MessageType">
                			$Fields.dataFieldByName(FirstName).Message
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="LastName" class="field text">
            			<label class="left" for="{$FormName}_LastName">Last Name</label>
				        $Fields.dataFieldByName(LastName)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(LastName).MessageType">
                			$Fields.dataFieldByName(LastName).Message
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="Company" class="field text">
            			<label class="left" for="{$FormName}_Company">Company(optional)</label>
				        $Fields.dataFieldByName(Company)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(Company).MessageType">
                			$Fields.dataFieldByName(Company).Message
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="StreetAddress1" class="field text">
            			<label class="left" for="{$FormName}_StreetAddress1">Street Address1</label>
				        $Fields.dataFieldByName(StreetAddress1)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(StreetAddress1).MessageType">
                			$Fields.dataFieldByName(StreetAddress1).Message
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="StreetAddress2" class="field text">
            			<label class="left" for="{$FormName}_StreetAddress2">Street Address2(optional)</label>
				        $Fields.dataFieldByName(StreetAddress2)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(StreetAddress2).MessageType">
                			$Fields.dataFieldByName(StreetAddress2).Message
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="City" class="field text">
            			<label class="left" for="{$FormName}_City">City</label>
				        <div>$Fields.dataFieldByName(City)</div>
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(City).MessageType">
                			$Fields.dataFieldByName(City).Message
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="State" class="field text">
            			<label class="left" for="{$FormName}_State">State/Province</label>
				        $Fields.dataFieldByName(State)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(State).MessageType">
                			$Fields.dataFieldByName(State).Message
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="PostalCode" class="field text">
            			<label class="left" for="{$FormName}_PostalCode">Zip/Postal Code</label>
				        $Fields.dataFieldByName(PostalCode)
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(PostalCode).MessageType">
                			$Fields.dataFieldByName(PostalCode).Message
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="Country" class="field dropdown">
            			<label class="left" for="{$FormName}_Country">Country</label>
				        <div>$Fields.dataFieldByName(Country)</div>
            			<span id="{$FormName}_error" class="message $Fields.dataFieldByName(Country).MessageType">
                			$Fields.dataFieldByName(Country).Message
            			</span>
       		 		</div>
        		</td>
        		<td>&nbsp;</td>
        	</tr>
        </table>
        $Fields.dataFieldByName(SecurityID)
    </fieldset>
    <% if $Actions %>
    <div class="Actions">
        <% loop $Actions %>$Field<% end_loop %>
    </div>
    <% end_if %>
</form>