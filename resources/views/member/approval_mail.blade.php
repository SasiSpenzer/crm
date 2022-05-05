<!DOCTYPE html>
<html>
    <head>
        <title>Membership changes approval</title>
    </head>
    <body>
        <div>
            <!-- Email body start -->
            Hi {{ $approval_person }},
            <br><br>
            {{$email_body}}
            AM comment : {{$membership_comment}}
            <table rules="all" style="border-color: #060006;" cellpadding="10">
                <tr style='background: #eee;'>
                    <th></th>
                    <th>Old</th>
                    <th>New</th>
                </tr>
            <!--table width="70%" class="table table-striped table-bordered table-hover display nowrap" style="border: 1px !important;"-->
                @foreach($mail_changers As $mail_data)
                    <tr style='background: #eee;'>
                        <td><strong>{{strip_tags($mail_data['type'])}}</strong> </td>
                        <td>{{strip_tags($mail_data['old'])}}</td>
                        <td>{{strip_tags($mail_data['new'])}}</td>
                    </tr>
                @endforeach
            </table>
            <br>
            <a href="{{$mail_url}}">{{$mail_url_name}}</a>
            <br><br>
            Yours sincerely
            <br>
            {{ $brandName }}
            <br>
            {{ $brandTelephone }}
            <br>
            <a href="mailto:{{ $brandEmail }}" target="_top">{{ $brandEmail }}</a>

            <!-- Email body end -->
        </div>
    </body>
</html>
