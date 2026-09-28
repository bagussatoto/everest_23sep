<?php
/**
 * Created by PhpStorm.
 * User: aziz
 * Date: 8/16/2018
 * Time: 8:51 PM
 */


switch ($mode) {
    case "index":
        // cekHere();
        $p = New Layout("$title", "$subTitle", MODUL_TEMPLATE_PATH . "template/token.html");

        echo "<div class='text-center'>Hai.. <b>".$user_login."</b></div>";
        echo "<div class='text-center'>&nbsp;</div>";
        echo "<div class='text-center'>&nbsp;</div>";
        echo "<div class='text-center'>&nbsp;</div>";

        $test_token = "";
        $test_token .= "<div class=''>";
        $test_token .= "<div class='box box-danger box-solid box-header'>";

        if(!empty($employee)){
            $test_token .= "<select style='margin-bottom: 10px;' data-style='btn-primary btn-block' data-live-search='true' title='login name' data-headers='login_name' data-size='100' data-container='body' type='text' name='login_name' id='login_name' class='login_name selectpicker form-control select2'>";
            foreach($employee as $r => $row){
                $test_token .= "<option value='".$row->nama_login."' class='text-uppercase'>".$row->nama_login."</option>";
            }
            $test_token .= "</select>";
        }

        $test_token .= "<div class='text-center'><input readonly style='letter-spacing: 0.3em' id='token' placeholder='masukan PIN token' class='form-control text-center text-bold'> </div>";



        $test_token .= "";



        $test_token .= "<div style='margin-top: 20px;'>
                            <div id='start_challenge_time' class='text-center text-bold text-red'></div>
                            <div id='start_challenge_user' class='text-center text-bold text-green'></div>
                            <div style='margin-top: 7px;'>
                                <span id='btn_reset_challenge' class='btn btn-warning'>Reset</span>
                                <span id='btn_start_challenge' class='btn btn-success pull-right'>Start Challenge</span>
                                <span id='btn_submit_token' class='btn btn-info pull-right hidden'>Submit TOKEN</span>
                            </div>
                       </div>

        ";

        $test_token .= "</div>";
        $test_token .= "</div>";

        $generator = "";
        $generator .= "<div class=''>";
        $generator .= "<div class='box box-info box-solid box-header'>";

        if(!empty($employee)){
            $generator .= "<select style='margin-bottom: 10px;' data-style='btn-primary btn-block' data-live-search='true' title='login name' data-headers='gen_login_name' data-size='100' data-container='body' type='text' name='gen_login_name' id='gen_login_name' class='gen_login_name selectpicker form-control select2'>";
            foreach($employee as $r => $row){
                $generator .= "<option value='".$row->nama_login."' class='text-uppercase'>".$row->nama_login."</option>";
            }
            $generator .= "</select>";
        }

        $generator .= "<div class='text-center'>
                            <input style='letter-spacing: 0.3em' readonly id='request_token' placeholder='Silahkan Generate TOKEN' class='form-control text-center text-bold'>
                       </div>";
        $generator .= "<div style='margin-top: 40px;' class='box box-warning box-solid box-header'>
        Cara Penggunaan:
        <ol>
            <li>
                ada dua Tab yaitu <b>TOKEN GENERATOR</b> dan <b>TEST TOKEN</b>.<br>
                TOKEN GENERATOR = untuk generate KODE TOKEN,<br>
                TEST TOKEN = untuk melakukan TEST VALID/TIDAKNYA KODE yg dihasilkan TOKEN GENERATOR
            </li>
            <li>
                pilih login nama (harus sama antara TAB generator dan TAB pengguna Token)
            </li>
            <li>
                klik start challernge untuk memulai
            </li>
            <li>
                buka Token Generator (klo bisa buka pada device lain)
            </li>
            <li>
                generate Token pada device/Tab Token Generator untuk mendapatkan PIN/TOKEN
            </li>
        </ol>
        </div>";
        $generator .= "<div style='margin-top: 20px;'>
                            <div style='margin-top: 7px;'>
                                <span id='btn_token_challenge' class='btn btn-success pull-right'>Generate TOKEN</span>
                            </div>
                            <div id='start_token_time' class='text-center text-bold text-red'></div>
                            <div id='start_token_user' class='text-center text-bold text-red'></div>
                       </div>

        ";

        $generator .= "</div>";
        $generator .= "</div>";

        $content = "";
        /*---------------TAB-TAB--------------‎‎*/
        $isi_tab = array();

        $isi_tab["tab_token"] = array(
            "label" => "token generator",
             "active" => true,
            "data" => $generator,
            "css" => "bg-default",
        );

        $isi_tab["tab_challenge"] = array(
            "label" => "TEST TOKEN",
//            "active" => true,
            "data" => $test_token,
            "css" => "bg-default",
            "class" => "bg-aaaaa",
        );

        $content .= $p->layout_tabs($isi_tab);

        $content .= "        <script>

            var arrDataToken = {};
            var arrDataTokenUsed = {};

            function ObjectLength( object ) {
                var length = 0;
                for( var key in object ) {
                    if( object.hasOwnProperty(key) ) {
                        ++length;
                    }
                }
                return length;
            };

            function letterValue(str){
                var anum={
                    a: 1, b: 2, c: 3, d: 4, e: 5, f: 6, g: 7, h: 8, i: 9, j: 10, k: 11,
                    l: 12, m: 13, n: 14,o: 15, p: 16, q: 17, r: 18, s: 19, t: 20,
                    u: 21, v: 22, w: 23, x: 24, y: 25, z: 26
                }
                if(str.length== 1) return anum[str] || ' ';
                return str.split('').map(letterValue);
            }

            var start_time_intv;
            function start_time(dates){
                clearInterval(start_time_intv);
                start_time_intv = setInterval( function(){
                    var dateMulai = dates;
                    var limit = moment().subtract(5, 'minutes').toDate().getTime();
                    $('#start_challenge_time').html( moment(dateMulai-limit).format('mm:ss') )
                    $('#start_challenge_user').html('berlaku untuk login: <b>' + localStorage.start_challenge_user +'<b>')
                    if( (dateMulai-limit)*1 <= 0){
                        reset_challenge('exceeded')
                    }
                }, 1000)
            }

            function reset_challenge(text=''){
                clearInterval(start_time_intv);

                if(text!=''){
                    $('#start_challenge_time').html( 'time exceeded' )
                    $('#start_challenge_user').html( '' )
                }
                else{
                    $('#start_challenge_time').html( '' )
                    $('#start_challenge_user').html( '' )
                }

                arrDataToken = {}
                $('#token').val('').prop('readonly', true)
                console.log(arrDataToken);
                $('#btn_submit_token').addClass('hidden');
                $('#btn_start_challenge').removeClass('hidden');
                localStorage.start_challenge = ''
                localStorage.start_challenge_user = ''
            }

            $('#login_name').on('change', function(){
                reset_challenge();
            })

            $('#btn_start_challenge').on('click', function(){
                var attrUser = $('#login_name').val();
                var newDate = new Date();
                var globalSalt = 1001789;
                var user_id = attrUser!='' ? attrUser : '$user_login';
                var encUserId = letterValue(user_id).join('');
                var times = moment(newDate).format('YYYY-MM-DD HH:mm:00');
                var microtime = {};
                var salt = {};
                var mc = {};
                var final = {};

                for(i=1;i<=99;i++){
                    microtime[i] = moment(times).add(i*789+i, 'seconds').unix();
                    salt[i] = moment.unix(microtime[i]).format(\"mm\");
                    mc[i] = (salt[i]*encUserId)+globalSalt+microtime[i];
                    final[i] = mc[i].toString().slice(-8);
                }

                arrDataToken = final;
                localStorage.start_challenge = newDate;
                localStorage.start_challenge_user = user_id;
                $('#btn_submit_token').removeClass('hidden');
                $(this).addClass('hidden');
                start_time(newDate);
                $('#token').prop('readonly', false).focus();
                console.log(arrDataToken)
            });

            $('#btn_submit_token').on('click', function(){
                var objects = arrDataToken;
                var results = [];
                var toSearch = $('#token').val();
                console.log('ObjectLength(objects): ' + ObjectLength(objects))
                for(var i=1; i<ObjectLength(objects); i++) {
                    if( objects[i].toString() == toSearch.toString() ){
                        results[i] = objects[i].toString();
                    }
                }
                if(results.length>0){
                    swal('TOKEN BENAR', '<b>'+toSearch+'</b> <br>Termasuk salah satu TOKEN yang benar.', 'success');
                }
                else{
                    swal('TOKEN SALAH', '<b>'+toSearch+'</b> tidak ada yg cocok', 'warning');
                }
            });

            $('#btn_reset_challenge').on('click', function(){
                reset_challenge()
            });


            var req_token_time;
            $('#btn_token_challenge').on('click', function(){
                clearInterval(req_token_time);
                var attrUser = $('#gen_login_name').val();
                var newDate = new Date();
                var globalSalt = 1001789;
                var user_id = attrUser!='' ? attrUser : '$user_login';
                var encUserId = letterValue(user_id).join('');
                var times = moment(newDate).format('YYYY-MM-DD HH:mm:00');
                var microtime = {};
                var salt = {};
                var mc = {};
                var final = {};

                for(i=1;i<=99;i++){
                    microtime[i] = moment(times).add(i*789+i, 'seconds').unix();
                    salt[i] = moment.unix(microtime[i]).format(\"mm\");
                    mc[i] = (salt[i]*encUserId)+globalSalt+microtime[i];
                    final[i] = mc[i].toString().slice(-8);
                }

                localStorage.start_token_user = user_id

                $('#request_token').val(final[5]);
                $('#btn_token_challenge').addClass('hidden')
                req_token_time = setInterval( function(){
                    var dateMulai = newDate;
                    var limit = moment().subtract(1, 'minutes').toDate().getTime();
                    $('#start_token_time').html( moment(dateMulai-limit).format('mm:ss') )
                    $('#start_token_user').html('berlaku untuk login: <b>' + localStorage.start_token_user +'<b>')
                    if( (dateMulai-limit)*1 <= 0){
                        $('#start_token_time').html( '' )
                        $('#start_token_user').html('')
                        $('#btn_token_challenge').removeClass('hidden')
                    }
                }, 1000)

            });

        </script>";

        $p->addTags(
            array(
                "content" => $content,
            )
        );
        $p->render();
        break;
}