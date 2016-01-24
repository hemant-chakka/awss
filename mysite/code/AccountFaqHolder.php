<?php
class AccountFaqHolder extends Page {

 public function getAccountFaqs(){
 	return AccountFaq::get()->sort('SortOrder');
 }

}
class AccountFaqHolder_Controller extends Page_Controller {

	public function init() {
		parent::init();
		Requirements::themedCSS('jquery-ui-1.11.2');
		Requirements::javascript('mysite/js/jquery-1.9.1.js');
		Requirements::javascript('mysite/js/jquery-ui.js');
		Requirements::javascript('mysite/js/faqs.js');
	}
}