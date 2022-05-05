<?php die();

function jQuery(){}

	//check elemet if exist
	if ($("#mydiv").length){  }

	//check all value of obejcet that have a knonw class
	$('.image_category').each(function(){
		$(this).val();
    });

    //select class except some class
    $(".parent").click(function(e) {
        if (!$(e.target).hasClass('child')) {
            alert('Show dialog!');
        }
    });

    //select radio
    var $radios = $('input:radio[name=upgrade_type]');
    if($radios.is(':checked') === false) {
        $radios.filter('[value="'+val+'"]').prop('checked', true);
    }
    document.querySelector('input[name=gender][value=Female]').checked = true;
    $("[name=gender]").val(["Male"]);

    //animation
    $('#australia_div').animate({
        height: 'toggle'
        }, 500, function() {
    });

--------------------------------
function php(){}

    require __DIR__ . '/../../vendor/autoload.php';
    use Google\Spreadsheet\DefaultServiceRequest;

    //error reporting
    error_reporting(E_ALL);
    ini_set("display_errors", 1);


	//ajax file upload
	<input type="file" id="photo" class="hidden">
	$('#cover_upload').on('click', function() {
        $('#photo').click();
    });
    $("#photo").change(function (){
       //var fileName = $(this).val();
        var cover_img = $('#cover_img');
        var file_data = $('#photo').prop('files')[0];   
        var form_data = new FormData();                  
        form_data.append('file', file_data);                           
        $.ajax({
            url: 'cover_img_upload.php', // point to server-side PHP script 
            dataType: 'text',  // what to expect back from the PHP script, if anything
            cache: false,
            contentType: false,
            processData: false,
            data: form_data,                         
            type: 'post',
            success: function(php_script_response){
                if (php_script_response == 1){
                    cover_img.prop('src', '../images/pics/profiles/79284/cover.jpg?' + new Date().getTime());
                    //location.reload();
                }
            }
         });
     });
    data: {
        "_token": "{{ csrf_token() }}",
        "form_data": $('#Form').serialize(),
    }

    $filename = '../images/pics/profiles/' . $_SESSION['UID'];
    if (!file_exists($filename)) {
        mkdir($filename, 0777);
    }
    if ( 0 < $_FILES['file']['error'] ) {
        echo 'Error: ' . $_FILES['file']['error'] . '<br>';
    }
    else {
        move_uploaded_file($_FILES['file']['tmp_name'], $filename . '/' . 'cover.jpg');
        echo 1;
    }

    $date = date('Y-m-d', strtotime(date('Y-m-d') . ' - 7 days'));

    //get month differnce
    $from_date   = new DateTime($exp_date);
    $to_date     = new DateTime();
    $interval    = date_diff($from_date, $to_date);
    $num_months  = $interval->y * 12 + $interval->m + $interval->d/30 + $interval->h / 24;
    $num_months  = (int)$num_months;

    //get country by ip
    if (!empty($_SERVER['HTTP_CLIENT_IP']))
        $ip = $_SERVER['HTTP_CLIENT_IP'];
    elseif (!empty($_SERVER['HTTP_X_FORWARDED_FOR']))
        $ip = $_SERVER['HTTP_X_FORWARDED_FOR'];
    else
        $ip = $_SERVER['REMOTE_ADDR'];
    $url = "http://api.wipmania.com/".$ip;
    $country = file_get_contents($url);

    //allow acces cros origin data
    header("Access-Control-Allow-Origin: *"); 
    header("content-type: application/json");

--------------------------------
function sql(){}
SET GLOBAL sql_mode = '';

--check difference betweent two tables--
SELECT * FROM T1
WHERE ID NOT IN (SELECT ID FROM T2)
UNION
SELECT * FROM T2
WHERE ID NOT IN (SELECT ID FROM T1)


--------------------------------
function laravel(){}
php artisan config:cache
php artisan cache:clear
php artisan view:clear


curl --request GET http://dev.srilankaproperty.lk/su/LPW-Admin/public/cronjob/reminder


--------------------------------
function git(){}
git rm -r --cached logs

show all commit since branch created
git reflog show --no-abbrev <branch name>

dif branch
git diff --name-only START_SHA1 END_SHA1
git log master..<your_branch_name>
git log master...<your_branch_name>


--------------------------------
function gegex(){}
copy all a tags
(?i)<a([^>]+)>(.+?)</a>

copy all href
\s*(?i)href\s*=\s*(\"([^"]*\")|'[^']*'|([^'">\s]+));

copy url
((http(s)?://)?([\w-]+\.)+[\w-]+[.com]+([\w\-\.,@?^=%&amp;:/~\+#]*[\w\-\@?^=%&amp;/~\+#])?)

---------------------------------
function cmd(){}

restart wamp server
NET START/STOP wampapache64
NET START wampmysqld64

php artisan cache:clear
php artisan config:clear
php artisan config:cache

---------------------------------
function linux_cli(){}

zip -r house.lk-2-13.zip . -x 'wp-content/uploads/2014/*' 'wp-content/uploads/2018/*' 'wp-content/uploads/2019/*' 'wp-content/uploads/wpallimport/*'

locate house.lk


---------------------------------
function analytic (){}

onClick="ga('send', 'event', 'Show_tel', 'Click', '<?= $tab.'/'.$adID ?>', { nonInteraction: true });"

onClick="ga('send', 'pageview', {'page': '/mortCal_Project/<?=$feat_dev_name?>'});"

----------------------------------
function git(){}

git reset --hard origin/dev

