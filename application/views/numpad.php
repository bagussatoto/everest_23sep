<?php

$title          = isset($title) ? $title : "";
$subTitle       = isset($subTitle) ? $subTitle : "";
$content        = isset($content) ? $content : "";
$script_bottom  = isset($script_bottom) ? $script_bottom : "";

$ids            = isset($id) ? $id : "";
$min            = isset($min) && $min>0 ? $min : 1;
$curValues      = isset($curValue) ? $curValue : $min;
$urls           = isset($url) ? unserialize(base64_decode($url)) : "#";
$nama           = isset($nama) ? $nama : 1;

$p = New Layout("selector numpad", "fill in item qty", "application/template/numpad.html");
$script_bottom = "
<script>

$('.errNotif').on('change', function(){
    setTimeout( function(){
        $('.errNotif').html('');
    }, 3000);
});
$('.done').bind('click', function(){
    var display    = $('.nmpd-display')[0];
    var curValue   = display.value;
    var produk_moq = $('.produk_moq');
    var moqValues  = $(produk_moq).val()!=''&&$(produk_moq).val()>0?$(produk_moq).val():1;
    if(parseFloat(curValue)>=parseFloat(moqValues)){
        top.document.getElementById('result').src = '$urls&jml='+parseFloat(curValue)+'&newQty='+ parseFloat(curValue);
        $('button.close').click();
        $('input#itemKeyword').val('');
        setTimeout( function(){
            $('input#itemKeyword').focus();
        }, 1000);
    }
    else{
        $('.errNotif').html('jumlah belanja dibawah MOQ ('+ moqValues + ')')
                      .css('color', 'red')
                      .css('text-align', 'center')
                      .trigger('change')
    }
});
$('.cancel').bind('click',function(){
    $('button.close').click();
});
$('.del').bind('click',function(){
    var display  = $('.nmpd-display')[0];
    var curValue = display.value;
    var step     = curValue.toString().substring(0,curValue.toString().length-1);
    if(!step){
        display.value = 0;
    }
    else{
        display.value = curValue.toString().substring(0,curValue.toString().length-1);
    }
    $('.nmpd-display').focus();
});

setTimeout( function(){
    $('.nmpd-display').focus();
    $(document).click(function() { $('.nmpd-display').focus() });
}, 1500 );

$('.neg').bind('click', function(){
    var display   = $('.nmpd-display')[0];
    var curValue  = display.value;
    if(parseFloat(curValue)==0){
        display.value = 0;
    }
    else{
        display.value = parseFloat(curValue)-1;
    }
});
$('.sep').bind('click', function(){
    var display   = $('.nmpd-display')[0];
    var curValue  = display.value;
    if(!curValue){
        display.value = 0;
    }
    else{
        display.value = parseFloat(curValue)+1;
    }
});
$('.clear').bind('click', function(){
    var display   = $('.nmpd-display')[0];
    var curValue  = display.value;
    display.value = '';
});

var firstNumb = 0;
$('.numero').bind('click', function(){
    firstNumb++;
    var thisText  = $(this).text();
    var display   = $('.nmpd-display')[0];
    var curValue  = display.value;
    if(curValue=='0' && thisText=='0'){
        display.value = 0
    }
    else{
        if(curValue=='0' || firstNumb==1){
            display.value = thisText
        }
        else{
            display.value = curValue+thisText
        }
    }

});


$('#myModal').on('hidden.bs.modal', function(){
    //console.log(' modal hide ');
});
$('.ui-btn-b').mousedown(function() {
    $(this).css('background-color','#8eefead6');
});
$('.ui-btn-b').mouseup(function() {
    $(this).css('background-color','#333');
});
function handleKeyPress(e) {
    console.log('hello');
    var keycode = (e.which);
    if(keycode==13){ $('a.done')            .click(); }
    if(keycode==43){ $('a.sep')             .click(); }
    if(keycode==45){ $('a.neg')             .click(); }
    if(keycode==48){ $('a.numero.nol')      .click(); }
    if(keycode==49){ $('a.numero.satu')     .click(); }
    if(keycode==50){ $('a.numero.dua')      .click(); }
    if(keycode==51){ $('a.numero.tiga')     .click(); }
    if(keycode==52){ $('a.numero.empat')    .click(); }
    if(keycode==53){ $('a.numero.lima')     .click(); }
    if(keycode==54){ $('a.numero.enam')     .click(); }
    if(keycode==55){ $('a.numero.tujuh')    .click(); }
    if(keycode==56){ $('a.numero.delapan')  .click(); }
    if(keycode==57){ $('a.numero.sembilan') .click(); }
}

//$('.nmpd-display').keypress(handleKeyPress);
//$('.nmpd-display').focus();
$('.nmpd-display').keypress(function(e) {
    console.log( e );
    var keycode = (e.which);
    if(keycode==13){ $('a.done')            .click(); }
    if(keycode==43){ $('a.sep')             .click(); }
    if(keycode==45){ $('a.neg')             .click(); }
    if(keycode==48){ $('a.numero.nol')      .click(); }
    if(keycode==49){ $('a.numero.satu')     .click(); }
    if(keycode==50){ $('a.numero.dua')      .click(); }
    if(keycode==51){ $('a.numero.tiga')     .click(); }
    if(keycode==52){ $('a.numero.empat')    .click(); }
    if(keycode==53){ $('a.numero.lima')     .click(); }
    if(keycode==54){ $('a.numero.enam')     .click(); }
    if(keycode==55){ $('a.numero.tujuh')    .click(); }
    if(keycode==56){ $('a.numero.delapan')  .click(); }
    if(keycode==57){ $('a.numero.sembilan') .click(); }
});

$('.nmpd-display').on('keydown', function(e){
    if(e.key==0){ $('a.numero.nol')     .css('background-color','#8eefead6'); }
    if(e.key==1){ $('a.numero.satu')    .css('background-color','#8eefead6'); }
    if(e.key==2){ $('a.numero.dua')     .css('background-color','#8eefead6'); }
    if(e.key==3){ $('a.numero.tiga')    .css('background-color','#8eefead6'); }
    if(e.key==4){ $('a.numero.empat')   .css('background-color','#8eefead6'); }
    if(e.key==5){ $('a.numero.lima')    .css('background-color','#8eefead6'); }
    if(e.key==6){ $('a.numero.enam')    .css('background-color','#8eefead6'); }
    if(e.key==7){ $('a.numero.tujuh')   .css('background-color','#8eefead6'); }
    if(e.key==8){ $('a.numero.delapan') .css('background-color','#8eefead6'); }
    if(e.key==9){ $('a.numero.sembilan').css('background-color','#8eefead6'); }
    if(e.key==9){ $('a.numero.sembilan').css('background-color','#8eefead6'); }
    if(e.key=='Enter'){ $('a.done').css('background-color','#8eefead6'); }
    return false;
});

setInterval( function(){
    var hasFocus = $('.nmpd-display').is(':focus');
    if(!hasFocus){
        $('.nmpd-display').focus();
    }
}, 1000);

$('.nmpd-display').on('keyup', function(e){
    var hasFocus = $('.nmpd-display').is(':focus');
    console.log('click ==>>> ' + e.key);
    console.log('Focus ' + hasFocus);
    if(e.key==0){ $('a.numero.nol')         .css('background-color','#333').click(); console.log('nol       clicked'); }
    if(e.key==1){ $('a.numero.satu')        .css('background-color','#333').click(); console.log('satu      clicked'); }
    if(e.key==2){ $('a.numero.dua')         .css('background-color','#333').click(); console.log('dua       clicked'); }
    if(e.key==3){ $('a.numero.tiga')        .css('background-color','#333').click(); console.log('tiga      clicked'); }
    if(e.key==4){ $('a.numero.empat')       .css('background-color','#333').click(); console.log('empat     clicked'); }
    if(e.key==5){ $('a.numero.lima')        .css('background-color','#333').click(); console.log('lima      clicked'); }
    if(e.key==6){ $('a.numero.enam')        .css('background-color','#333').click(); console.log('enam      clicked'); }
    if(e.key==7){ $('a.numero.tujuh')       .css('background-color','#333').click(); console.log('tujuh     clicked'); }
    if(e.key==8){ $('a.numero.delapan')     .css('background-color','#333').click(); console.log('delapan   clicked'); }
    if(e.key==9){ $('a.numero.sembilan')    .css('background-color','#333').click(); console.log('sembilan  clicked'); }
    if(e.key=='Enter'){
        $('a.done').css('background-color','#f6f6f6').click(); console.log('done click');
    }
    if(e.key=='Backspace'){ $('a.del').css('background-color','#f6f6f6').click(); console.log('delete click'); }
    if(e.key=='-'){ $('a.neg').css('background-color','#f6f6f6').click(); console.log('minus click'); }
    if(e.key=='+'){ $('a.sep').css('background-color','#f6f6f6').click(); console.log('plus click'); }
    if(e.key=='Escape'){ return true; }
    if(e.key=='Delete'){ return true; }
    return false;
});
</script>";

$p->addTags(array(
    "content"       => "$content",
    "id"            => "$ids",
    "produk_nama"   => "$nama",
    "curValue"      => "$curValues",
    "lang_min"      => "Minimal Pembelian",
    "min"           => "$min",
    "valueMOQ"      => "$min",
    "lang_delete"   => "hapus",
    "lang_clear"    => "clear",
    "lang_cancel"   => "batal",
    "lang_enter"    => "enter",
    "script_bottom" => "$script_bottom"
));


$p->render();

