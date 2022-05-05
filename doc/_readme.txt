================= Database ===================

admin_user
	admin_level
		0 - nothing (only user)
		1 - manager
		2 - am
		3 - account
		4 - supper admin




================= errors =====================
if there any error with redirect after loging, include/filse/checkSession.php






================== online payment =================
email template
includes\files\email-template\payment-confirmation.html

checkout page
myaccount/checkout.php

after payment redirect url
payments\paycorp-payment-confirm.php

msg of after transsction
http://lpw.local/myaccount/payment-confirmation.php

check $payment_for

print custom_data of upgrade_ad
---
echo "<pre>". print_r($custom_data) ."</pre>"; die();

Raw request : {"version":"1.5.6","msgId":"3B9289C8-4CE7-4D92-8755-A93BBFE724A1","operation":"PAYMENT_COMPLETE","requestDate":"2018-03-19 16:05:00","validateOnly":false,"requestData":{"clientId":14000864,"reqid":"d9V18ZH5R4SgqFnUkyPj"}}

Raw response : {"msgId":"3B9289C8-4CE7-4D92-8755-A93BBFE724A1","sessionStatus":null,"error":null,"responseData":{"clientId":14000864,"clientIdHash":"qfim8QhGVe85YKFVlWBWUV0ZBAY","transactionType":"PURCHASE","creditCard":{"type":"VISA","holderName":"f","number":"456445******4564","expiry":"1020"},"transactionAmount":{"totalAmount":0,"paymentAmount":161000,"serviceFeeAmount":0,"currency":"LKR"},"clientRef":"200592","comment":"merchant_additional_data","extraData":{"custom":"payment_for:upgrade_ad;AID:200592;UID:79284;PrID:;type:sales;amount:1,610.00;exp:18-05-2018;del_old:0;upgrade_type:Single Ad;property_type:Commercial;unit_price:700.00;duration:2"},"txnReference":"2018100000493745","responseCode":"00","responseText":"APPROVED (TEST TRANSACTION ONLY)","tokenized":false,"settlementDate":"2018-03-18","authCode":"941877","cvcResponse":"U"}}Array ( [0] => Array ( [custom] => payment_for:upgrade_ad;AID:200592;UID:79284;PrID:;type:sales;amount:1,610.00;exp:18-05-2018;del_old:0;upgrade_type:Single Ad;property_type:Commercial;unit_price:700.00;duration:2 ) ) 
---