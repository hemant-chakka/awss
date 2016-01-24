<?php
$val .= '<form ';

$val .= $scope->locally()->XML_val('FormAttributes', null, true);
$val .= '>
    ';

if ($scope->locally()->hasValue('Message', null, true)) { 
$val .= '
        <div id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->XML_val('MessageType', null, true);
$val .= '">';

$val .= $scope->locally()->obj('Message', null, true)->XML_val('RAW', null, true);
$val .= '</div>
    ';


}else { 
$val .= '
        <p id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->XML_val('MessageType', null, true);
$val .= '" style="display: none"></p>
    ';


}
$val .= '
    ';

$val .= $scope->locally()->XML_val('unsetFormMessage', null, true);
$val .= '
    <fieldset>
        <h5>Credit Card Details</h5>
        <table id="CreditCardDetails">
        	<tr>
        		<td colspan=2>
        			<div id="CreditCardType" class="field optionset">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_CreditCardType">Credit Card Type</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('CreditCardType'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('CreditCardType'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('CreditCardType'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="NameOnCard" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_NameOnCard">Name On Card</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('NameOnCard'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('NameOnCard'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('NameOnCard'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="CreditCardNumber" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_CreditCardNumber">Credit Card Number</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('CreditCardNumber'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('CreditCardNumber'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('CreditCardNumber'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
            			';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('CreditCardNumberCopy'), true);
$val .= '
            			';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('CreditCardNumberCur'), true);
$val .= '
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="CreditCardCVV" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_CreditCardCVV">Security/CVV Code</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('CreditCardCVV'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('CreditCardCVV'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('CreditCardCVV'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="ExpiryMonth" class="field dropdown">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_ExpiryMonth">Expiry Date</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('ExpiryMonth'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('ExpiryMonth'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('ExpiryMonth'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
       		 		<div id="ExpiryYear" class="field dropdown nolabel">
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('ExpiryYear'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('ExpiryYear'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('ExpiryYear'), true)->XML_val('Message', null, true);
$val .= '
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
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_FirstName">First Name</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('FirstName'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('FirstName'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('FirstName'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="LastName" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_LastName">Last Name</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('LastName'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('LastName'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('LastName'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="Company" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_Company">Company(optional)</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('Company'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('Company'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('Company'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="StreetAddress1" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_StreetAddress1">Street Address1</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('StreetAddress1'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('StreetAddress1'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('StreetAddress1'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="StreetAddress2" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_StreetAddress2">Street Address2(optional)</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('StreetAddress2'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('StreetAddress2'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('StreetAddress2'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="City" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_City">City</label>
				        <div>';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('City'), true);
$val .= '</div>
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('City'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('City'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="State" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_State">State/Province</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('State'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('State'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('State'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        		<td>
        			<div id="PostalCode" class="field text">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_PostalCode">Zip/Postal Code</label>
				        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('PostalCode'), true);
$val .= '
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('PostalCode'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('PostalCode'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        	</tr>
        	<tr>
        		<td>
        			<div id="Country" class="field dropdown">
            			<label class="left" for="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_Country">Country</label>
				        <div>';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('Country'), true);
$val .= '</div>
            			<span id="';

$val .= $scope->locally()->XML_val('FormName', null, true);
$val .= '_error" class="message ';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('Country'), true)->XML_val('MessageType', null, true);
$val .= '">
                			';

$val .= $scope->locally()->obj('Fields', null, true)->obj('dataFieldByName', array('Country'), true)->XML_val('Message', null, true);
$val .= '
            			</span>
       		 		</div>
        		</td>
        		<td>&nbsp;</td>
        	</tr>
        </table>
        ';

$val .= $scope->locally()->obj('Fields', null, true)->XML_val('dataFieldByName', array('SecurityID'), true);
$val .= '
    </fieldset>
    ';

if ($scope->locally()->hasValue('Actions', null, true)) { 
$val .= '
    <div class="Actions">
        ';

$scope->locally()->obj('Actions', null, true); $scope->pushScope(); while (($key = $scope->next()) !== false) {
$val .= $scope->locally()->XML_val('Field', null, true);

}; $scope->popScope(); 
$val .= '
    </div>
    ';


}
$val .= '
</form>';

