<?php
if ($scope->locally()->hasValue('Message', null, true)) { 
$val .= '
    <p class=\'CustomMessage\' id=\'';

$val .= $scope->locally()->XML_val('MessageType', null, true);
$val .= 'Message\'>
        ';

$val .= $scope->locally()->XML_val('Message', null, true);
$val .= '
    </p>
';


}
