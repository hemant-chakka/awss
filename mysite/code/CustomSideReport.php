<?php
// Expired trial member report
class CustomSideReport_ExpiredTrialMembers extends SS_Report {

    // the name of the report
    public function title() {
        return 'AW Reports - Expired Trial Members';
    }

    // what we want the report to return
    public function sourceRecords($params = null) {
		return Subscription::get()->filter(array(
		    'Status' => 0,
			'IsTrial' => 1)
		)->exclude('Member.ID',null);
    }

    // which fields on that object we want to show
    public function columns() {
        $fields = array(
            'Member.Name' => 'Customer Name',
        	'Member.Email' => 'Email',
        	'StartDate' => 'Start Date',
        	'ExpireDate' => 'Cancel Date'
        );

        return $fields;
    }
}
// Active trial member report
class CustomSideReport_ActiveTrialMembers extends SS_Report {

    // the name of the report
    public function title() {
        return 'AW Reports - Active Trial Members';
    }

    // what we want the report to return
    public function sourceRecords($params = null) {
		return Subscription::get()->filter(array(
		    'Status' => 1,
			'IsTrial' => 1)
		)->exclude('Member.ID',null);
    }

    // which fields on that object we want to show
    public function columns() {
        $fields = array(
            'Member.Name' => 'Customer Name',
        	'Member.Email' => 'Email',
        	'StartDate' => 'Start Date',
        	'ExpireDate' => 'End Date'
        );
        return $fields;
    }
}
//Bronze members report
class CustomSideReport_BronzeMembers extends SS_Report {

    // the name of the report
    public function title() {
        return 'AW Reports - Bronze Members';
    }

    // what we want the report to return
    public function sourceRecords($params = null) {
		return Subscription::get()->filter(array(
		    'ProductID' => 1,
			'Status' => 1)
		)->exclude('Member.ID',null);
    }

    // which fields on that object we want to show
    public function columns() {
        $fields = array(
            'Member.Name' => 'Customer Name',
        	'Member.Email' => 'Email',
        	'StartDate' => 'Start Date',
        	'ExpireDate' => 'End Date'
        );
        return $fields;
    }
}

//Silver members report
class CustomSideReport_SilverMembers extends SS_Report {

    // the name of the report
    public function title() {
        return 'AW Reports - Silver Members';
    }

    // what we want the report to return
    public function sourceRecords($params = null) {
		return Subscription::get()->filter(array(
		    'ProductID' => 2,
			'Status' => 1)
		)->exclude('Member.ID',null);
    }

    // which fields on that object we want to show
    public function columns() {
        $fields = array(
            'Member.Name' => 'Customer Name',
        	'Member.Email' => 'Email',
        	'StartDate' => 'Start Date',
        	'ExpireDate' => 'End Date'
        );
        return $fields;
    }
}

//Gold members report
class CustomSideReport_GoldMembers extends SS_Report {

    // the name of the report
    public function title() {
        return 'AW Reports - Gold Members';
    }

    // what we want the report to return
    public function sourceRecords($params = null) {
		return Subscription::get()->filter(array(
		    'ProductID' => 3,
			'Status' => 1)
		)->exclude('Member.ID',null);
    }

    // which fields on that object we want to show
    public function columns() {
        $fields = array(
            'Member.Name' => 'Customer Name',
        	'Member.Email' => 'Email',
        	'StartDate' => 'Start Date',
        	'ExpireDate' => 'End Date'
        );
        return $fields;
    }
}