<?php
//category and am were added to db
return [
	'type' => [
		/*'1' => 'Assigned',
		'2' => 'Pending Confirmation',
		'3' => 'Invoiced',
		'4' => 'Member',
		'5' => 'Free - Less than 5 ads',
		'6' => 'Free - Doesn’t want a membership',
		'7' => 'Call back later',
		'8' => 'Cannot contact / No answer',
		'9' => 'Inactive',*/
        '5' => 'Free',
        '4' => 'Member',
        '1' => 'Pending Payment',
        '9' => 'Inactive',
        '2' => 'Expired',
	],
	'category' => [
		'0' => 'TBC',
		'1' => 'Free',
		'2' => 'Trial',
		'3' => 'Single ad',
		'4' => 'Starter',
		'5' => 'Econ',
		'6' => 'Pro',
		'7' => 'Plus',
		'8' => 'Premium',
		'9' => 'Ultimate',
		'10' => 'Add-on',
	],
	'duration' => [
		'1' => '1 month',
		'2' => '2 months',
		'3' => '3 months',
		'4' => '4 months',
		'5' => '5 months',
		'6' => '6 months',
		'7' => '7 months',
		'8' => '8 months',
		'9' => '9 months',
		'10' => '10 months',
		'11' => '11 months',
		'12' => '1 year',
	],
	'payment' => [
	    'Free' => 'Free',
	    'Free Trial' => 'Free Trial',
	    'Monthly' => 'Monthly',
		'Quarterly' => 'Quarterly',
		'Annually' => 'Annually',
		'1' => 'Sales - Apartment',
		'2' => 'Sales - Commercial',
		'3' => 'Sales - Villa',
		'4' => 'Sales - Bungalow',
		'5' => 'Sales - House',
		'6' => 'Sales - Other',
        '7' => 'Rentals - Apartment',
		'8' => 'Rentals - Commercial',
		'9' => 'Rentals - Villa',
		'10' => 'Rentals - Bungalow',
        '11' => 'Rentals - House',
		'12' => 'Rentals - Other',
		'13' => 'Land - Bare',
		'14' => 'Land - Beachfront',
		'15' => 'Land - Cinnamon',
        '16' => 'Land - Coconut',
		'17' => 'Land - Cultivated',
		'18' => 'Land - With House',
		'19' => 'Land - Paddy',
        '20' => 'Land - Quarry',
		'21' => 'Land - Rubber',
		'22' => 'Land - Tea',
	],
	'payment' => [
	    'Free' => 'Free',
	    'Free Trial' => 'Free Trial',
	    'Monthly' => 'Monthly',
		'Quarterly' => 'Quarterly',
		'Annually' => 'Annually',
		'1' => 'Apartment',
		'2' => 'Commercial',
		'3' => 'Villa',
		'4' => 'Bungalow',
		'5' => 'House',
		'6' => 'Other',
		'7' => 'Land - Bare',
		'8' => 'Land - Beachfront',
		'9' => 'Land - Cinnamon',
        '10' => 'Land - Coconut',
		'11' => 'Land - Cultivated',
		'12' => 'Land - With House',
		'13' => 'Land - Paddy',
        '14' => 'Land - Quarry',
		'15' => 'Land - Rubber',
		'16' => 'Land - Tea',
	],
	'am' => [
		'1' => 'Janan',
		'2' => 'Rasheed',
		'3' => 'Irshad',
		'4' => 'Rashmika',
		'5' => 'Gayan R',
		'6' => 'Vidura',
		'7' => 'Thilini',
		'8' => 'Ranula',
	],

    //Membership expired period after payment expire
    'expirePeriod' => 15,

    'user_type' => [
        '1' => 'Property Agent',
        '2' => 'PAA Agent',
        '3' => 'Pvt Seller',
        '4' => 'Ideal Home',
        '5' => 'Developer',
        '6' => 'Internal',
        '7' => 'Other',
        '8' => 'm-PAA Agent',
    ],
];
