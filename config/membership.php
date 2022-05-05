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
		'9' => 'Inactive',
        '5' => 'Free',*/
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
            'Pending' => 'Pending',
	    'Free Trial' => 'Free Trial',
	    'Monthly' => 'Monthly',
		'Quarterly' => 'Quarterly',
		'Annually' => 'Annually',
		'1' => 'Apartment',
		'2' => 'Commercial',
		'3' => 'Villa',
		'4' => 'Bungalow',
		'5' => 'House (Western exc.CMB)',
		'24' => 'House (Central exc.Kandy)',
		'27' => 'House (North West province)',
		'25' => 'House (Southeren province)',
		'6' => 'Other',
		'34' => 'Rooms',
		'35' => 'Annexes',
		'13' => 'Land-Land',
		'20' => 'Land-Bare',
		'39' => 'Land-With-House',
		'37' => 'Land-Land (Western province)',
		'40' => 'Land-Land (Central province)',
		'42' => 'Land-Land (North West province)',
		'41' => 'Land-Land (Southeren province)',
		'49' => 'Land-Bare (Western province)',
		'47' => 'Land-Bare (Central province)',
		'45' => 'Land-Bare (North West province)',
		'44' => 'Land-Bare (Southeren province)',
		'50' => 'Ideal Home',
		'53' => 'Land-With-House (Western province)',
		'52' => 'Land-With-House (Central province)',
		'51' => 'Land-With-House (North West province)',
		'55' => 'House (CMB Dist)',
		'56' => 'House (Kandy Dist)',
		'57' => 'Land (CMB Dist)',
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