<!DOCTYPE html>
<html>
<head>
    <title></title>
</head>
<body>
    Dear {{ $full_name }}, <br><br>
    You have received the following information as you have requested your login details through our 'Lost Password' form. <br/>
    Your password has been reset. Please change your password after you login. You can do so by clicking on 'Edit your details' button.<br/><br/>
    Your login details are: <br/>
    Your Username is : {{$username}}<br/>
    Your new Password: {{$password}}<br/>
    To login, please visit http://www.lankapropertyweb.com/myaccount/loginform.php <br/><br/>
    (Please note that the username and password is case sensitive. If there are any upper case/capital letters they will need to be entered in upper case/capital).<br/>
    {{$validated_text}}<br/><br/>.
    Best Regards, <br/>
    LankaPropertyWeb.com Support Team<br/>
</body>
</html>