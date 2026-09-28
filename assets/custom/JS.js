function popitup(url,wid) {
    var wid = (wid == null) ? "default" : wid;
    newwindow=window.open(url,wid,'height=500,width=580,resizable=yes,scrollbars=yes,status=no');
    newwindow.moveTo(150,70);
    newwindow.focus();
    return false;
}
function popScreen(url,wid) {
    var wid = (wid == null) ? "default" : wid;
    newwindow=window.open(url,wid,'height='+screen.height+',width='+screen.width+',resizable=yes,scrollbars=yes,status=0,toolbar=yes,menubar=yes');
    newwindow.moveTo(0,0);
    wid.focus();
    return false;
}
function popUpload(url) {
    newwindow=window.open(url,'uploadImg','height=600,width=608,resizable=yes,toolbar=no,menubar=no');
    newwindow.moveTo(350,150)
    if (window.focus) {newwindow.focus()}
    return false;
}
function popSmall(url) {
    newwindow=window.open(url,'winSmall','height=500,width=350,resizable=yes,toolbar=no,menubar=no');
    newwindow.moveTo(380,160)
    if (window.focus) {newwindow.focus()}
    return false;
}
function popBig(url) {
    newwindow=window.open(url,'winBig','height='+screen.availHeight+',width='+screen.availWidth+',resizable=yes,toolbar=no,menubar=no,scrollbars=yes');
    newwindow.moveTo(10,10)
    if (window.focus) {newwindow.focus()}
    return false;
}
function newWin(url) {
    newwindow=window.open(url,'winBig','height=100%,width=100%,resizable=yes,toolbar=no,menubar=no,scrollbars=yes');
    newwindow.moveTo(0,0)
    if (window.focus) {newwindow.focus()}
    return false;
}
function getWidth() {

    setwidth=400;

    x=document.getElementById('image').offsetWidth;
    y=document.getElementById('image').offsetHeight;
    if(x>setwidth) {
        xn=setwidth;
        y=y*xn/x;

        document.getElementById('image').style.width=xn+"px";
        document.getElementById('image').style.height=y+"px";

    }
}

function trim(stringToTrim) {
    return stringToTrim.replace(/^\s+|\s+$/g,"");
}
function ltrim(stringToTrim) {
    return stringToTrim.replace(/^\s+/,"");
}
function rtrim(stringToTrim) {
    return stringToTrim.replace(/\s+$/,"");
}
function removeID(selection){
    thisNode = document.getElementById(selection)
    // thisNode.removeNode(true)
    thisNode.parentNode.removeChild(thisNode)
}
function removeInputID(selection){
    thisNode = document.getElementById('left'+selection)
    thisNode.removeNode(true)
}
function removeSaleID(selection){
    thisNode = document.getElementById('entry'+selection)
    thisNode.removeNode(true)
}
function closeNoConfirm ()
{
    win = top;
    win.opener = top;
    win.close ();
}
function isPopupAllowed()
{
    if(window.open('$appTemplate/Test.html','wmine','width=0,height=0,left=0,top=0,scrollbars=no'))
    {
        return true;
    }
    else
    {
        return false;
    }
}
function bulatkanRatusan(number)
{
    return Math.round(number / 10) * 10;
}



//Disable right mouse click Script
//By Maximus (maximusatnsimail.com) w/ mods by DynamicDrive
//For full source code, visit http://www.dynamicdrive.com

var message="oyyeaaa";

///////////////////////////////////
function clickIE4(){
    if (event.button==2){
        alert(message);
        return false;
    }
}

function clickNS4(e){
    if (document.layers||document.getElementById&&!document.all){
        if (e.which==2||e.which==3){
            alert(message);
            return false;
        }
    }
}

if (document.layers)
{
    document.captureEvents(Event.MOUSEDOWN);
    document.onmousedown=clickNS4;
}
else
if (document.all&&!document.getElementById)
{
    document.onmousedown=clickIE4;
}

// document.oncontextmenu=new Function("return false")

// -->





function klikKanan(rightURL)
{
    var rightclick;
    var e = window.event;
    if (e.which)
    {
        rightclick = (e.which == 3);
    }
    else
    {
        if (e.button)
        {
            rightclick = (e.button == 2);
        }
    }
    if(rightclick)
    {
        //alert('right click');
        displayContextMenu(rightURL);
    }
    else
    {
        //alert('this is not right click');
    }
    //alert('this is klik-kanan function');
}

function copyToClipboard(c)
{
    var copyText = c
    var eLid = $(copyText).attr('id');
//    console.log(eLid);
    var elType = $(copyText).prop("nodeName");
    var CopiedText='';
    switch(elType){
        case "INPUT":
            copyText.select();
            CopiedText = copyText.value;
            navigator.clipboard.writeText( CopiedText );
            swal('text di copy: ' + CopiedText)
            break;
        default:
            var textEl = $(copyText).text();
            var temp = $("<input value='"+textEl+"' id='intemp'>");

            if( $('input#intemp').length > 0){
                $('input#intemp').val(textEl);
                $('input#intemp').select();
                document.execCommand("copy")
                $('input#intemp').remove();
            }
            else{
                top.$(copyText).parent().append(temp);
                top.$('input#intemp').focus();
                top.$('input#intemp').select();
                top.document.execCommand("copy");
                top.$('input#intemp').remove();
            }

            swal('text di copy',textEl,'success');
            swal.enableLoading();
            setTimeout(function(){
                swal.close();
            },1200)

            break;
    }
}

function CopyToClipboard_V2(containerid) {
    if (document.selection) {
        var range = document.body.createTextRange();
        range.moveToElementText(document.getElementById(containerid));
        range.select().createTextRange();
        document.execCommand("copy");
    } else if (window.getSelection) {
        var range = document.createRange();
        range.selectNode(document.getElementById(containerid));
        window.getSelection().addRange(range);
        document.execCommand("copy");
        alert("Text has been copied, now paste in the text-area")
    }
}

function displayContextMenu(url)
{
    var IE = document.all?true:false
    // Temporary variables to hold mouse x-y pos.s
    var tempX = 0
    var tempY = 0
    var e = new Object();

    // Main function to retrieve mouse x-y pos.s
    if (IE) { // grab the x-y pos.s if browser is IE
        /*tempX = event.clientX + document.body.scrollLeft
         tempY = event.clientY + document.body.scrollTop*/
        tempX = event.clientX+document.body.scrollLeft
        tempY = event.clientY+document.body.scrollTop
    } else { // grab the x-y pos.s if browser is NS
        e=window.event;
        tempX = e.pageX
        tempY = e.pageY
    }
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0}
    if (tempY < 0){tempY = 0}
    getData(url,'contextMenu');
    document.getElementById('contextMenuContainer').style.visibility='visible';
    document.getElementById('contextMenuContainer').style.left=(tempX);
    document.getElementById('contextMenuContainer').style.top=(tempY);
}
function displayFlipContextMenu(url)
{
    var IE = document.all?true:false
    // Temporary variables to hold mouse x-y pos.s
    var tempX = 0
    var tempY = 0
    var e = new Object();

    // Main function to retrieve mouse x-y pos.s
    if (IE) { // grab the x-y pos.s if browser is IE
        tempX = event.clientX + document.body.scrollLeft
        tempY = event.clientY + document.body.scrollTop
    } else { // grab the x-y pos.s if browser is NS
        e=window.event;
        tempX = e.pageX
        tempY = e.pageY
    }
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0}
    if (tempY < 0){tempY = 0}
    getData(url,'contextMenu');
    document.getElementById('contextMenuContainer').style.visibility='visible';
    document.getElementById('contextMenuContainer').style.right=(tempX);
    document.getElementById('contextMenuContainer').style.bottom=(tempY);
}
function hideContextMenu()
{
    document.getElementById('contextMenuContainer').style.visibility='hidden';
}

function showMask(ichar)
{
    document.getElementById('mask').innerHTML=ichar;
    document.getElementById('premask').style.display='block';
    document.getElementById('mask').style.display='block';
}
function hideMask()
{
    document.getElementById('premask').style.display='none';
    document.getElementById('mask').style.display='none';
}
function displaySearch(ichar)
{
    getData('../../General/Home/AutoSearch?key='+ichar,'mainSearch');
    document.getElementById('mainSearchContainer').style.display='block';
}
function hideSearch()
{
    document.getElementById('mainSearchContainer').style.display='none';
}
function displayPopupMenu()
{
    getData('../../Common/Index/MenuSmall?Mode=Popup&Clicked=Yes&parm=9019&Main=1','popupMenu');
    document.getElementById('popupContainer').style.display='block';
}
function hidePopupMenu()
{
    document.getElementById('popupContainer').style.display='none';
}
function togglePopupMenu()
{
    if(document.getElementById('popupContainer').style.display!='block')
    {
        displayPopupMenu();
    }
    else
    {
        hidePopupMenu();
    }
}
function showDialog(url)
{
    showMask(' ');
    getData(url+'&parm=huahsjkah','dialogContent');
    document.getElementById('dialog').style.left=((screen.width  / 2)- (680 / 2));
    document.getElementById('dialog').style.top =((screen.height / 2) / 6);
    document.getElementById('dialog').style.display='block';
}
function showBlankDialog(url)
{
    showMask(' ');
    getData(url+'&parm=huahsjkah','cfmDialogContent');
    document.getElementById('cfmDialog').style.width=500;
    document.getElementById('cfmDialog').style.left=((screen.width  / 2)- (500 / 2));
    document.getElementById('cfmDialog').style.top =((screen.height / 2) / 6);
    document.getElementById('cfmDialog').style.display='block';

}
function postDialog(url)
{
    showMask(' ');
    postData(url+'&parm=huahsjkah','dialogContent');
    document.getElementById('dialog').style.left=((screen.width  / 2)- (680 / 2));
    document.getElementById('dialog').style.top =((screen.height / 2) / 6);
    document.getElementById('dialog').style.display='block';
}
function hideDialog()
{
    hideMask();
    document.getElementById('dialog').style.display='none';
    document.getElementById('cfmDialog').style.display='none';
}

function showStatus(msg)
{
    var IE = document.all?true:false
    // Temporary variables to hold mouse x-y pos.s
    var tempX = 0
    var tempY = 0
    var e = new Object();

    // Main function to retrieve mouse x-y pos.s
    if (IE) { // grab the x-y pos.s if browser is IE
        /*tempX = event.clientX + document.body.scrollLeft
         tempY = event.clientY + document.body.scrollTop*/
        tempX = event.clientX
        tempY = event.clientY
    } else { // grab the x-y pos.s if browser is NS
        e=window.event;
        tempX = e.pageX
        tempY = e.pageY
    }
    // catch possible negative values in NS4
    if (tempX < 0){tempX = 0}
    if (tempY < 0){tempY = 0}

    setTimeout("document.getElementById('statusBar').style.visibility='visible'",300);


    document.getElementById('statusBar').style.left=((screen.width  / 2)- (680 / 2)+document.body.scrollLeft);
    document.getElementById('statusBar').style.top =(document.body.scrollTop+(screen.height/3));
    document.getElementById('statusBar').style.display='block';
    document.getElementById('statusBar').innerHTML=msg;

    /*setTimeout("hideStatus()",1100);*/
}
function hideStatus()
{
    document.getElementById('statusBar').innerHTML='';
    document.getElementById('statusBar').style.display='none';
}

function showTmpDialog(url,detik)
{
    showDialog(url);
    setTimeout("hideDialog()",detik);
}
function showError(msg)
{
    showMask(' ');
    /*document.getElementById('dialogContent').innerHTML=msg;*/
    document.getElementById('dialog').style.left=((screen.width  / 2)- (400 / 2));
    document.getElementById('dialog').style.top =((screen.height / 2) / 4);
    document.getElementById('dialog').style.display='block';
}
function showTmpError(msg,detik)
{
    showError(msg);
    setTimeout("hideDialog()",detik);
}

function onKeyPressBlockNumbers(e)
{
    var key = window.event ? e.keyCode : e.which;
    var keychar = String.fromCharCode(key);
    reg = /\d/;
    return !reg.test(keychar);
}
function stopSubmitOnEnter (e)
{
    var eve = e || window.event;
    var keycode = eve.keyCode || eve.which;

    if (keycode == 13 || keycode == 8) {
        eve.cancelBubble = true;
        eve.returnValue = false;

        if (eve.stopPropagation) {
            eve.stopPropagation();
            eve.preventDefault();
        }
    }
    return false;
}

function detectEnter(e)
{
    var eve = e || window.event;
    var keycode = eve.keyCode || eve.which;

    if (keycode == 13) {
        eve.cancelBubble = true;
        eve.returnValue = false;
        return "yes";
        if (eve.stopPropagation) {
            eve.stopPropagation();
            eve.preventDefault();
        }
    }
    else
    {
        return "no";
    }
}
function detectSpaceBar(e)
{
    var eve = e || window.event;
    var keycode = eve.keyCode || eve.which;

    if (keycode == 32) {
        eve.cancelBubble = true;
        eve.returnValue = false;
        return "yes";
        if (eve.stopPropagation) {
            eve.stopPropagation();
            eve.preventDefault();
        }
    }
    else
    {
        return "no";
    }
}
function detectESC(e)
{
    var eve = e || window.event;
    var keycode = eve.keyCode || eve.which;

    if (keycode == 27) {
        eve.cancelBubble = true;
        eve.returnValue = false;
        return "yes";
        if (eve.stopPropagation) {
            eve.stopPropagation();
            eve.preventDefault();
        }
    }
    else
    {
        return "no";
    }
}

function detectKeyStroke(e)
{
    var eve = e || window.event;
    var keycode = eve.keyCode || eve.which;

    switch(keycode)
    {
        case 13:
            return "enter";
            break;
        case 32:
            return "space";
            break;
        case 27:
            return "ESC";
            break;
        default:
            /*return "undefined";*/
            return keycode;
            break;
    }


}
function getQueryString(ji)
{
    hu = window.location.search.substring(1);
    gy = hu.split("&");
    for (i=0;i<gy.length;i++)
    {
        ft = gy[i].split("=");
        if (ft[0] == ji)
        {
            return ft[1];
        }
    }
}
function getQueryVariable(variable) {
    var query = window.location.search.substring(1);
    var vars = query.split("&");
    for (var i=0;i<vars.length;i++) {
        var pair = vars[i].split("=");
        if (pair[0] == variable) {
            return pair[1];
        }
    }
}
function getAllQuery() {
    var query = window.location.search;
    var vars = query.split("?");
    var jml=vars.length;
    return vars[1];
}
function getURLFileOnly()
{
    var query = window.location.search;
    var vars = query.split("?");
    return vars[0];
}
function readHotKey()
{
    switch(detectKeyStroke())
    {
        case 113:
            location.href="../../Common/Files/index.php";
            return false;
            break;
        case 114:
            location.href="../../Common/Transaksi/index.php";
            return false;
            break;
        case 115:
            location.href="../../Common/Laporan/index.php";
            return false;
            break;
        default:
            break;
    }
}

function getParameterByName(name) {
    name = name.replace(/[\[]/, "\\\[").replace(/[\]]/, "\\\]");
    var regex = new RegExp("[\\?&]" + name + "=([^&#]*)"),
        results = regex.exec(location.search);
    return results == null ? "" : decodeURIComponent(results[1].replace(/\+/g, " "));
}

function getSelfFileName(){
    var baseName = window.location.pathname.substring(window.location.pathname.lastIndexOf("/")+1) || "index.html";
    return baseName;
}

function showStartScreen(url)
{
    showMask("&nbsp;");
    getData('../../Common/Index/StartScreen.php?parm=huahsjkah&url='+url,'cfmDialogContent',1);
    document.getElementById('cfmDialog').style.width=520;
    document.getElementById('cfmDialog').style.left=0;
    document.getElementById('cfmDialog').style.top =0;
    document.getElementById('cfmDialog').style.display='block';

}

function zxcCkIp(zxckcnu,zxctxt){
    var zxcevt=window.event||arguments.callee.caller.arguments[0];
    var zxcobj=window.event?zxcevt.srcElement:zxcevt.target;
    var zxcpos=zxcobj.value.length;
    if (document.selection){
        zxcobj.focus();
        var zxcsel=document.selection.createRange();
        if(zxcsel.text==''){
            zxcsel.text='µµµ';
            var dummy=zxcobj.createTextRange();
            dummy.findText('µµµ');
            dummy.select();
            zxcpos=zxcobj.value.indexOf('µµµ');
            document.selection.clear();
            zxcobj.focus();
        }
    }
    else if (zxcobj.selectionStart||zxcobj.selectionStart == '0') {//Moz
        zxcpos = zxcobj.selectionStart;
    }
    var zxckc;
    if (zxcevt.which){ zxckc=zxcevt.which; }
    else { zxckc=event.keyCode; }
    if (zxckc==zxckcnu&&!zxcevt.shiftKey){
        setTimeout(function(){ zxcobj.value=zxcobj.value.substring(0,zxcpos)+zxctxt+zxcobj.value.substring(zxcpos+1);},5);
        return false;
    }
    return true;
}

function showModal(url,ntitle){
    /*top.BootstrapDialog.closeAll();*/
    top.BootstrapDialog.show(
        {
            title:ntitle,
            type:BootstrapDialog.TYPE_INFO,
            message: $('<div class=\"text-centers text-bolds\"><img width=\"35%\" src=\"//cdn.mayagrahakencana.com/assets/images/d60eb1v-79212624-e842-4e55-8d58-4ac7514ca8e4.gif\"><br/><h2>MOHON TUNGGU, SYSTEM SEDANG MEMUAT DATA...</h2></div>').load(url),
            size:top.BootstrapDialog.SIZE_WIDE,
            draggable:false,
            closable:true,
        }
    );

}

// function hiliteDiv(divName){
//     if(top.document.getElementById(divName)){
//         d=top.document.getElementById(divName);
//     }else{
//         d=divName;
//     }
//     d.style.background='#ccffcc';
//     setTimeout(function(){ d.style.background='#ffffff';}, 600);
// }

function hiliteDiv(divName){
    if(top.document.getElementById(divName)){
        d=top.document.getElementById(divName);
    }else{
        d=divName;
    }
    d.style.background='#ffee00';
    // setTimeout(function(){ d.style.background='#ffffff';}, 600);
}
function hiliteDivBack(divName){
    if(top.document.getElementById(divName)){
        d=top.document.getElementById(divName);
    }else{
        d=divName;
    }
    d.style.background='#ffee00';
    setTimeout(function(){ d.style.background='#ffffff';}, 600);
}

function disableShopCart()
{

    if (document.getElementById("shopping_cart")) {
        // document.getElementById("shopping_cart").innerHTML=Date();
        document.getElementById("shopping_cart").setAttribute("id", "shopping_cart_old");
    }
    // else {
    //     document.getElementById("div_top2").innerHTML="teste";
    //     document.getElementById("div_top2").setAttribute("id", "div_top1");
    // }
}
function enableShopCart()
{

    if (document.getElementById("shopping_cart_old")) {
        // document.getElementById("shopping_cart_old").innerHTML=Date();
        document.getElementById("shopping_cart_old").setAttribute("id", "shopping_cart");
    }
    // else {
    //     document.getElementById("div_top2").innerHTML="teste";
    //     document.getElementById("div_top2").setAttribute("id", "div_top1");
    // }
}


//$(document).ready( function () {
//    jQuery.each( $('li.treeview'), function(i, view){
//        if( $('.fa-arrow-right',$(view)).length ){
//            if( $(view).hasClass('active') ){
//                console.log('sudah terbuka');
//            }
//            else{
//                $('a.text-white', $(view)).click();
//                console.log('text-muted clicked');
//            }
//        }
//    });
//} );