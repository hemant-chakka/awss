<?php

global $project;
$project = 'mysite';

global $databaseConfig;
$databaseConfig = array(
	"type" => 'MySQLDatabase',
	"server" => 'localhost',
	"username" => 'root',
	"password" => '',
	"database" => 'awss',
	"path" => '',
);

MySQLDatabase::set_connection_charset('utf8');

// Set the current theme. More themes can be downloaded from
// http://www.silverstripe.org/themes/
SSViewer::set_theme('attwiz');


// Set the site locale
i18n::set_locale('en_US');

// Enable nested URLs for this site (e.g. page/sub-page/)
if (class_exists('SiteTree')) SiteTree::enable_nested_urls();

//RecaptchaField::$public_api_key = '6Le2odkSAAAAANk6eZLvQJ1tJT8xYgYlEAx2vgtN';
//RecaptchaField::$private_api_key = '6Le2odkSAAAAAINaKkYcceBw453HCW-W7DGeLdVZ';
//SpamProtectorManager::set_spam_protector('RecaptchaProtector');


// Extend MemberLoginForm
Object::useCustomClass('MemberLoginForm', 'CustomLogin');

//Security::setDefaultAdmin('admin', 'hemant');

Director::addRules(100, array(
	'account-settings/tabs/$Tab' => 'AccountSettings_Controller'
));

// Set the default timezone
date_default_timezone_set('America/New_York');

Object::add_extension('SiteConfig', 'CustomSiteConfig');
