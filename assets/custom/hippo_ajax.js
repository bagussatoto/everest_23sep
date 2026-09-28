var XMLHttpRequestObject = createXMLHttpRequestObject();

function createXMLHttpRequestObject() {
    var XMLHttpRequestObject = false;

    try {
        XMLHttpRequestObject = new XMLHttpRequest();
    }
    catch (e) {
        var aryXmlHttp = new Array(
            "MSXML2.XMLHTTP",
            "Microsoft.XMLHTTP",
            "MSXML2.XMLHTTP.6.0",
            "MSXML2.XMLHTTP.5.0",
            "MSXML2.XMLHTTP.4.0",
            "MSXML2.XMLHTTP.3.0"
        );
        for (var i = 0; i < aryXmlHttp.length && !XMLHttpRequestObject; i++) {
            try {
                XMLHttpRequestObject = new ActiveXObject(aryXmlHttp[i]);
            }
            catch (e) {
            }
        }
    }

    if (!XMLHttpRequestObject) {
        alert("Error: failed to create the XMLHttpRequest object.");
    }
    else {
        return XMLHttpRequestObject;
    }
}


function getData(dataSource, divID, ifLoading=false) {
//    if (XMLHttpRequestObject) {
//        dataSource += "&parm=" + new Date().getTime();
//
//        XMLHttpRequestObject.open("GET", dataSource);
//        XMLHttpRequestObject.onreadystatechange = function () {
//            try {
//                if (XMLHttpRequestObject.readyState == 4 &&
//                    XMLHttpRequestObject.status == 200) {
//                    var objDiv = document.getElementById(divID);
//                    objDiv.innerHTML = XMLHttpRequestObject.responseText;
//                    // callback({status:'done'});
//                }
//                else {
//                    if (ifLoading) {
//                        var objDiv = document.getElementById(divID);
////            objDiv.innerHTML = "<img src='../../assets/images/loading_32.gif' alt='loading' class='text-center'>";
//                        objDiv.innerHTML = "<div class='text-center' style='border: 0px solid red; position: absolute;margin: 0 auto;width: 100%;'><img src='../../assets/images/loading_16_p.gif' alt='loading'></div>";
////            objDiv.innerHTML = "<a href=# onClick=\"getData(dataSource,divID,ifLoading)\">Loading "+dataSource+"...</a>";
//                    }
//                }
//            }
//            catch (e) {
//                document.write("getData: XMLHttpRequestObject.readyState Error");
//            }
//        }
//        try {
//            XMLHttpRequestObject.send(null);
//        }
//        catch (e) {
//            document.write("getData: XMLHttpRequestObject.onreadystatechange Error");
//        }
//    }
    if (ifLoading) {
        top.open_holdon();
    }
    top.$('#'+divID).load(dataSource, function(){
        top.close_holdon();
    });
}

function postData(dataSource, divID, ifLoading) {
    if (XMLHttpRequestObject) {
        XMLHttpRequestObject.open("POST", dataSource);
        XMLHttpRequestObject.setRequestHeader("Method", "POST " + dataSource + " HTTP/1.1");
        XMLHttpRequestObject.setRequestHeader("Content-Type", "application/x-www-form-urlencoded");

        XMLHttpRequestObject.onreadystatechange = function () {
            try {
                if (XMLHttpRequestObject.readyState == 4 &&
                    XMLHttpRequestObject.status == 200) {
                    var objDiv = document.getElementById(divID);
                    objDiv.innerHTML = XMLHttpRequestObject.responseText;
                }
                else {
                    if (ifLoading) {
                        var objDiv = document.getElementById(divID);
                        objDiv.innerHTML = "<img src='/PUBLIC/CommonImages/hippo_loading.gif'>";
                    }
                }
            }
            catch (e) {
                document.write("postData: XMLHttpRequestObject.readyState Error");
            }
        }

        dataSource += "&parm=" + new Date().getTime();
        try {
            XMLHttpRequestObject.send(dataSource);
        }
        catch (e) {
            document.write("postData: XMLHttpRequestObject.onreadystatechange Error");
        }
    }
}