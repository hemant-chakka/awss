<?php
$val .= '<p>SOAP response: ';

$val .= $scope->locally()->XML_val('lastResponse', null, true);
$val .= '</p> 
<p>Response Headers:: ';

$val .= $scope->locally()->XML_val('lastResponseHeaders', null, true);
$val .= '</p>';

