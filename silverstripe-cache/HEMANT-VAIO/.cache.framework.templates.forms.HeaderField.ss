<?php
$val .= '<h';

$val .= $scope->locally()->XML_val('HeadingLevel', null, true);
$val .= ' ';

$val .= $scope->locally()->XML_val('AttributesHTML', null, true);
$val .= '>';

$val .= $scope->locally()->XML_val('Title', null, true);
$val .= '</h';

$val .= $scope->locally()->XML_val('HeadingLevel', null, true);
$val .= '>';

