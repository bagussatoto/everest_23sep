<?php

if (isset($items)) {

    echo "<script>
if (typeof window.sendCartResultRequest !== 'function') {
    window.__cartResultRequestTimer = null;
    window.__cartResultRequestLastUrl = '';
    window.__cartResultRequestLastTime = 0;
    window.__cartInputState = window.__cartInputState || {
        id: '',
        name: '',
        value: '',
        selectionStart: null,
        selectionEnd: null,
        focusId: '',
        focusName: '',
        focusType: '',
        focusValue: '',
        focusIndex: -1,
        focusTouchedAt: 0,
        touchedAt: 0,
        restoreNeeded: false,
        requestAt: 0
    };

    window.isCartEditableInput = function(el) {
        if (!el || (!el.id && !el.name)) {
            return false;
        }

        var tag = (el.tagName || '').toUpperCase();
        if (tag !== 'INPUT' && tag !== 'TEXTAREA' && tag !== 'SELECT') {
            return false;
        }

        var type = (el.type || '').toLowerCase();
        if (type === 'radio' || type === 'checkbox' || type === 'button' || type === 'submit' || type === 'hidden') {
            return false;
        }

        var cartNode = document.getElementById('shopping_cart');
        if (cartNode && cartNode !== el && !cartNode.contains(el)) {
            return false;
        }

        return true;
    };

    window.isCartFocusableInput = function(el) {
        if (!el || (!el.id && !el.name)) {
            return false;
        }

        var tag = (el.tagName || '').toUpperCase();
        if (tag !== 'INPUT' && tag !== 'TEXTAREA' && tag !== 'SELECT' && tag !== 'BUTTON') {
            return false;
        }

        var type = (el.type || '').toLowerCase();
        if (type === 'hidden') {
            return false;
        }

        if (el.disabled === true) {
            return false;
        }

        var cartNode = document.getElementById('shopping_cart');
        if (cartNode && cartNode !== el && !cartNode.contains(el)) {
            return false;
        }

        return true;
    };

    window.resolveCartFocusTarget = function(el) {
        if (!el) {
            return null;
        }

        var cursor = el;
        while (cursor && cursor !== document) {
            if (window.isCartFocusableInput(cursor)) {
                return cursor;
            }
            cursor = cursor.parentNode;
        }

        var tag = (el.tagName || '').toUpperCase();
        if (tag === 'LABEL') {
            var forId = el.getAttribute('for');
            if (forId) {
                var linked = document.getElementById(forId);
                if (window.isCartFocusableInput(linked)) {
                    return linked;
                }
            }
            if (typeof el.querySelector === 'function') {
                var nested = el.querySelector('input,textarea,select,button');
                if (window.isCartFocusableInput(nested)) {
                    return nested;
                }
            }
        }

        return null;
    };

    window.captureCartFocusTarget = function(el) {
        var target = window.resolveCartFocusTarget(el);
        if (!target) {
            return;
        }

        var state = window.__cartInputState;
        var now = new Date().getTime();
        state.focusId = target.id ? String(target.id) : '';
        state.focusName = target.name ? String(target.name) : '';
        state.focusType = (target.type || '').toLowerCase();
        state.focusValue = (typeof target.value !== 'undefined' && target.value !== null) ? String(target.value) : '';
        state.focusIndex = -1;
        state.focusTouchedAt = now;

        if (state.focusName.length > 0) {
            var namedNodes = document.getElementsByName(state.focusName);
            if (namedNodes && namedNodes.length > 0) {
                for (var i = 0; i < namedNodes.length; i++) {
                    if (namedNodes[i] === target) {
                        state.focusIndex = i;
                        break;
                    }
                }
            }
        }

        if (window.isCartEditableInput(target)) {
            window.captureCartInputState(target);
        }
    };

    window.captureCartInputState = function(el) {
        if (!window.isCartEditableInput(el)) {
            return;
        }

        var state = window.__cartInputState;
        state.id = el.id ? String(el.id) : '';
        state.name = el.name ? String(el.name) : '';
        state.value = (typeof el.value !== 'undefined' && el.value !== null) ? String(el.value) : '';
        state.touchedAt = new Date().getTime();

        try {
            state.selectionStart = (typeof el.selectionStart === 'number') ? el.selectionStart : null;
            state.selectionEnd = (typeof el.selectionEnd === 'number') ? el.selectionEnd : null;
        } catch (err) {
            state.selectionStart = null;
            state.selectionEnd = null;
        }
    };

    window.markCartFocusRestoreNeeded = function() {
        var state = window.__cartInputState;
        var now = new Date().getTime();
        var pointerLockActive = (now - state.focusTouchedAt) < 900;

        // If user just clicked another field, keep that target as restore anchor.
        // Without this guard, activeElement from previous field can overwrite target.
        if (!pointerLockActive && window.isCartFocusableInput(document.activeElement)) {
            window.captureCartFocusTarget(document.activeElement);
        } else if ((now - state.focusTouchedAt) > 1800 && (now - state.touchedAt) > 1800) {
            state.id = '';
            state.name = '';
            state.value = '';
            state.selectionStart = null;
            state.selectionEnd = null;
            state.focusId = '';
            state.focusName = '';
            state.focusType = '';
            state.focusValue = '';
            state.focusIndex = -1;
        }
        state.restoreNeeded = true;
        state.requestAt = now;
    };

    window.findCartFocusableByName = function(name, value, indexHint, typeHint) {
        var cleanName = (name || '');
        if (cleanName.length < 1) {
            return null;
        }

        var named = document.getElementsByName(cleanName);
        if (!named || named.length < 1) {
            return null;
        }

        var pool = [];
        for (var i = 0; i < named.length; i++) {
            if (!window.isCartFocusableInput(named[i])) {
                continue;
            }
            if (typeHint && typeHint.length > 0) {
                var nodeType = (named[i].type || '').toLowerCase();
                if (nodeType !== typeHint) {
                    continue;
                }
            }
            pool.push(named[i]);
        }

        if (pool.length < 1) {
            return null;
        }

        if (indexHint >= 0 && indexHint < pool.length) {
            return pool[indexHint];
        }

        if (value !== null && typeof value !== 'undefined' && String(value).length > 0) {
            for (var x = 0; x < pool.length; x++) {
                var nodeValue = (typeof pool[x].value !== 'undefined' && pool[x].value !== null) ? String(pool[x].value) : '';
                if (nodeValue === String(value)) {
                    return pool[x];
                }
            }
        }

        return pool[0];
    };

    window.restoreCartInputState = function() {
        var state = window.__cartInputState;
        if (!state || state.restoreNeeded !== true) {
            return;
        }

        if ((new Date().getTime() - state.requestAt) > 5000) {
            state.restoreNeeded = false;
            return;
        }

        var target = null;

        if (state.focusId) {
            target = document.getElementById(state.focusId);
        }

        if (!window.isCartFocusableInput(target)) {
            target = window.findCartFocusableByName(state.focusName, state.focusValue, state.focusIndex, state.focusType);
        }

        if (!window.isCartFocusableInput(target) && state.id) {
            target = document.getElementById(state.id);
        }

        if (!window.isCartFocusableInput(target)) {
            target = window.findCartFocusableByName(state.name, null, -1, '');
        }

        if (!window.isCartFocusableInput(target)) {
            state.restoreNeeded = false;
            return;
        }

        // var tag = (target.tagName || '').toUpperCase();
        // if ((tag === 'INPUT' || tag === 'TEXTAREA') && typeof target.value !== 'undefined') {
        //     target.value = state.value;
        // }

        try {
            target.focus();
        } catch (err) {}

        try {
            if (window.isCartEditableInput(target) && typeof target.setSelectionRange === 'function' && state.selectionStart !== null && state.selectionEnd !== null) {
                target.setSelectionRange(state.selectionStart, state.selectionEnd);
            }
        } catch (err) {}

        state.restoreNeeded = false;
    };

    window.bindCartInputStateListeners = function() {
        if (window.__cartInputStateBound) {
            return;
        }

        var handler = function(evt) {
            if (!evt || !evt.target) {
                return;
            }
            window.captureCartFocusTarget(evt.target);
        };

        document.addEventListener('mousedown', handler, true);
        document.addEventListener('touchstart', handler, true);
        document.addEventListener('focusin', handler, true);
        document.addEventListener('input', handler, true);
        document.addEventListener('keyup', handler, true);
        window.__cartInputStateBound = true;
    };

    window.resolveCartResultFrame = function() {
        var frame = null;

        try {
            if (window.top && window.top.document && typeof window.top.document.getElementById === 'function') {
                frame = window.top.document.getElementById('result');
            }
        } catch (err) {
            frame = null;
        }

        if (!frame && typeof document.getElementById === 'function') {
            frame = document.getElementById('result');
        }

        return frame;
    };

    window.sendCartResultRequest = function(url, delayMs) {
        if (!url) {
            return;
        }

        var now = new Date().getTime();
        if (window.__cartResultRequestLastUrl === url && (now - window.__cartResultRequestLastTime) < 250) {
            return;
        }

        if (window.__cartResultRequestTimer) {
            clearTimeout(window.__cartResultRequestTimer);
        }

        var wait = parseInt(delayMs, 10);
        if (isNaN(wait) || wait < 0) {
            wait = 120;
        }
        var sender = function() {
            var resultFrame = window.resolveCartResultFrame();
            var mutationHost = window;
            try {
                if (window.top && typeof window.top.markCartMutationStart === 'function') {
                    mutationHost = window.top;
                }
            } catch (err) {
                mutationHost = window;
            }
            if (typeof window.markCartFocusRestoreNeeded === 'function') {
                window.markCartFocusRestoreNeeded();
            }

            window.__cartResultRequestLastUrl = url;
            window.__cartResultRequestLastTime = new Date().getTime();

            if (resultFrame) {
                if (mutationHost && typeof mutationHost.markCartMutationStart === 'function') {
                    mutationHost.markCartMutationStart('sendCartResultRequest');
                }
                resultFrame.src = url;
                return;
            }

            // Fallback only when iframe #result is not available.
            if (window.top && window.top.$) {
                window.top.$('#result').load(url);
                return;
            }
            if (window.$) {
                window.$('#result').load(url);
            }
        };

        if (wait === 0) {
            sender();
            return;
        }

        window.__cartResultRequestTimer = setTimeout(sender, wait);
    };
}

if (typeof window.cartSafeValue !== 'function') {
    window.cartSafeValue = function(input) {
        var raw = '';

        if (input === null || typeof input === 'undefined') {
            return '';
        }

        if (typeof input === 'object') {
            if (typeof input.value !== 'undefined' && input.value !== null) {
                raw = input.value;
            } else if (input.target && typeof input.target.value !== 'undefined') {
                raw = input.target.value;
            } else if (window.jQuery && input.jquery && input.length > 0) {
                raw = input.val();
            } else if (typeof input.id !== 'undefined') {
                raw = input.id;
            } else {
                raw = '';
            }
        } else {
            raw = input;
        }

        if (typeof raw === 'object') {
            if (raw === null) {
                raw = '';
            } else if (typeof raw.value !== 'undefined') {
                raw = raw.value;
            } else if (typeof raw.id !== 'undefined') {
                raw = raw.id;
            } else {
                raw = '';
            }
        }

        return encodeURIComponent(String(raw));
    };
}

if (typeof window.bindCartInputStateListeners === 'function') {
    window.bindCartInputStateListeners();
}
if (typeof window.restoreCartInputState === 'function') {
    setTimeout(function() {
        window.restoreCartInputState();
    }, 40);
}
</script>";

    if (isset($fixedNoteTop)) {
        echo "<div class='alert alert-danger' style='margin-top: 0px;font-size: 15px;'>";
        echo "<span>$fixedNoteTop</span>";
        echo "</div>";
    }

    $showItems = isset($showItems) && strlen($showItems) > 0 && $showItems == "false" ? false : "true";

    if (sizeof($items) > 0) {


        //-------------------------------------------------------------------------------
        if (sizeof($shopingCartPaymentItemsColor) > 0) {
            $legend = "";
            foreach ($shopingCartPaymentItemsColor['colorCode'] as $ix => $ixSpec) {
                $bgcolor = $ixSpec["color"];
                $legend .= "<span class='btn btn-sm' style='background-color:$bgcolor;'> </span> " . $ixSpec['label'] . "&nbsp;&nbsp;&nbsp;&nbsp;";
            }
            echo $legend;
        }
        //-------------------------------------------------------------------------------


        /*===bagian logic tambahan taxes untuk payment src*/
        if (isset($shopingCartAddTax) && sizeof($shopingCartAddTax) > 0) {
            echo "<div class=''>";
            echo "<div class='text-center text-bold bg-red text-uppercase'> Tipe konsumen </div>";
            foreach ($shopingCartAddTax["fields"] as $sels => $label) {
                $checked = $checkTaxes == $sels ? "checked" : "";
                echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' id='switch_pajak' name='switch_pajak' $checked value='$sels'  onclick=\"sendCartResultRequest('" . $shopingCartAddTaxAction . "/?val='+this.value+'&p=$sels', 0);\">
                              <span>$label</span>
                          </label>";
            }
            echo "</div>";
        }

        /*============end tambahan*/
        $jmlKolomHeader = sizeof($itemLabels) + 2;
        // cekHijau($jmlKolomHeader);
        echo "<div class='table-responsive no-padding no-border'>";
        /*=============== BADGE PPN / NON PPN =================*/
        if (sizeof($arrHeaderElement) > 0) {
            foreach ($arrHeaderElement as $el => $eDetails) {
                $elLabel = $eDetails['label'];
                $elClass = $eDetails['class'];
                echo "<div class='$elClass'>";
                echo "<div class='text-center text-bold bg-yellow'> $elLabel </div>";
                foreach ($eDetails['subElements'] as $sels => $seDetails) {
                    $selsLabel = $seDetails['label'];
                    $selsValue = $seDetails['value'];
                    $selsMainTarget = $seDetails['srcMain'];
                    $selsItemsTarget = $seDetails['srcItem'];
                    $mainOverwrite = $seDetails['overWriteMain'];
                    $currentPPN = isset($main[$selsMainTarget]) ? $main[$selsMainTarget] : 0;
                    $ppnPersenItems = isset($items[0]['ppnVendor']) ? $items[0]['ppnVendor'] : 0;
                    $autoTerapkan = ($ppnPersenItems != $currentPPN) && ($selsValue == $currentPPN) ? true : false;
                    $checked = $selsValue == $currentPPN ? "checked" : "";

                    $jenisTr = isset($arrHeaderElementJenis) ? $arrHeaderElementJenis : "";
                    // cekhitam($checked."$currentPPN");
                    echo "<label class='badge text-uppercase' style='padding:4px 6px 4px 6px;color:#454545;background:#e0e0e0;'>
                              <input type='radio' name='switch_ppn' value='$selsValue' $checked 
                              onclick=\"sendCartResultRequest('" . MODUL_PATH . "_processSelectProductPpn/select/$jenisTr?ppn='+this.value+'&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget&overWriteMain=$mainOverwrite', 0);\">
                              <span>$selsLabel</span>
                          </label>";

                    //                     if ($autoTerapkan) {
                    //                         echo "
                    //                         <script>
                    // //                            setTimeout( function(){ $('input[name=switch_ppn]:checked').click() }, 500);
                    //                             $('#result').load('" . base_url() . "Selectors/_processSelectProductPpn/select/466?ppn=$currentPPN&ppnTargetItems=$selsItemsTarget&ppnTargetMain=$selsMainTarget')
                    //                         </script>";
                    //                     }
                }
                echo "</div>";
            }
        }
        /*=============== BADGE PPN / NON PPN =================*/
        echo "<table group-table='items' class='table table-condensed no-padding table-bordered no-margin'>";
        /*===============header shoping cart======================*/
        if (isset($itemLabels)) {
            if (sizeof($itemLabels) && (is_array($itemLabels)) && $showItems) {
                echo "<tr class='bg-grey-2 text-uppercase'>";
                echo "<th style='width:1%;' class='text-muted text-center'>";
                echo "NO";
                echo "</th>";
                foreach ($itemLabels as $key => $label) {
                    echo "<th style='width:1%;white-space: nowrap;' class='text-muted text-center'>";
                    echo $label;
                    echo "</th>";
                }

                //----------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "V";
                    echo "</th>";
                }
                //----------
                if (!$avoidRemove) {
                    echo "<th style='width:1%;' class='text-muted text-center'>";
                    echo "x";
                    echo "</th>";
                }
                echo "</tr>";
            }
        }

        /*===============body shoping cart=======================================*/
        $no = 0;
        foreach ($items as $iSpec) {

            if ($showItems) {

                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }

                //------
                if (isset($iSpec['background_pembayaran'])) {
                    $bgColor = $iSpec['background_pembayaran'];
                }
                //------

                echo "<tr group='items' id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td style='vertical-align:middle; width:1%' class='text-center'>";
                echo $no;
                echo "</td>";
                $colCtr = 0;
                $queryParams = "";
                $colID = array();
                $listMode = array();
                $readOnly = array();
                $qtyParam = "";
                if (isset($itemLabels['jml'])) {
                    $qtyParam = "+removeCommas(document.getElementById('jml_$no').value)";
                }
                foreach ($itemLabels as $key => $label) {
                    $listMode[$key] = "input";
                    $keyupEvent[$key] = "";
                    $keyUpStr[$key] = "";
                    if (array_key_exists($key, $keyUpEvents)) {
                        //                    cekbiru("$key has events");
                        if (sizeof($selectedPrices) > 0) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                //                            $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                        if (isset($keyupAction) && $keyupAction == true) {
                            $keyupEvent[$key] = $keyUpEvents[$key];
                            foreach ($selectedPrices as $k => $v) {
                                //                            $nameLabel = "value_" . $yID . "_" . $xID . "_" . $k . ""; //==untuk nama/ID input
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                            foreach ($itemLabels as $k => $v) {
                                $nameLabel = $k . "_" . $no;
                                $keyupEvent[$key] = str_replace("{" . $k . "}", $nameLabel, $keyupEvent[$key]);
                            }
                        }
                    }
                    else {
                    }
                    if (strlen($keyupEvent[$key]) > 2) {
                        $keyUpStr[$key] = " onkeyup=\"" . $keyupEvent[$key] . "\" ";
                    }
                    if (in_array($key, $editableFields)) {
                        $readOnly[$key] = "";
                        if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
                            $readOnly[$key] = "readonly_xz";
                        }
                        if (isset($paramsForceEditable[$key])) {
                            if ($paramsForceEditable[$key] == true) {

                            }
                            else {
                                $readOnly[$key] = "readonly_xxz";
                                $listMode[$key] = "text";
                            }
                        }
                    }
                    else {
                        $readOnly[$key] = "readonly_xxz";
                        $listMode[$key] = "text";
                    }
                    $colID[$key] = $key . "_" . $no;
                    if ($listMode[$key] == "input") {
                        $queryParams .= "&$key='+removeCommas(document.getElementById('" . $colID[$key] . "').value)+'";
                    }
                }
                foreach ($itemLabels as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    echo "<td align='left'>";
                    $colID = $key . "_" . $no;
                    $keyID = $key;
                    $noID = $no;
                    $tabIndexNum = $colCtr . $no;
                    $fieldVal = "";
                    if (substr($key, 0, 1) == "*") {
                        $key_p = str_replace("*", "", $key);
                        $key_ex = explode("#", $key_p);
                        $pair_name = $key_ex[0];
                        $pair_key = $key_ex[1];
                        $pair_key_val = $iSpec[$pair_key];
                        if (sizeof($key_ex) > 1) {
                            $fieldVal = isset($pairedValue[$pair_name][$pair_key_val]) ? $pairedValue[$pair_name][$pair_key_val] : "0";
                        }
                        else {
                            $fieldVal = isset($pairedValue[$pair_name]) ? $pairedValue[$pair_name] : "0";
                        }
                    }
                    else {
                        if (isset($iSpec[$key])) {
                            if (is_numeric($iSpec[$key])) {
                                $fieldVal = $iSpec[$key] + 0;
                            }
                            else {
                                $fieldVal = $iSpec[$key];
                            }
                        }
                    }
                    if (sizeof($minValues) > 0) {
                        $moq = isset($minValues['moq'][$iID]) ? $minValues['moq'][$iID] : 0;
                        $validateKey_up = true;
                    }
                    else {
                        $moq = 0;
                        $validateKey_up = false;
                    }
                    $keyupData = (($key == "qty" || $key == "jml") && $validateKey_up == true) ? "onkeydown=\"if(parseInt(this.value)<$moq){setTimeout(function(){ this.value='" . $iSpec[$key] . "'}, 1000);} \"" : "";

                    switch ($listMode[$key]) {
                        case "input":
                            echo "<input type='text'  min='$moq' autocomplete='off' " . $readOnly[$key] . " keyid=$keyID noid=$noID id_jml=$iID id=$colID  class='form-control text-right' style='color:$color;' value='" . niceDecimal($fieldVal) . "' onclick='this.select()' " . $keyUpStr[$key] . " ";
                            $baseInputName = isset($unionSelectors['base']) ? "document.getElementById('" . $unionSelectors['base'] . "_" . $no . "')" : "this";
                            $pemicuGerbangAsli = "onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);sendCartResultRequest('" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams', 0);} \" $keyupData";
                            $pemicuGerbangAsli .= "*onmouseoutx=\"if(this.value!=this.defaultValue){hiliteDiv(this);sendCartResultRequest('" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams', 0);}\" ";
                            $pemicuGerbang = "onblur=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);sendCartResultRequest('" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams', 0);}\" $keyupData ";
                            $pemicuGerbang .= "*onmouseoutx=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);sendCartResultRequest('" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams', 0);}\" ";
                            $pemicuGerbangUnion = "onchange=\"if($baseInputName.value!=$baseInputName.defaultValue){hiliteDiv($baseInputName);sendCartResultRequest('" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams', 0);} \" ";

                            if (isset($unionSelectors['base'])) {
                                if ($unionSelectors['base'] == $key) {//==jadi acuan kiriman
                                    echo str_replace("this", $baseInputName, $pemicuGerbang);
                                }
                                else {
                                    if (in_array($key, $unionSelectors['members'])) {//==jadi member union, tidak memicu perubahan gerbang
                                        echo $pemicuGerbangUnion;
                                    }
                                    else {//==biasa aja, memicu perubahan gerbang
                                        echo $pemicuGerbangAsli;
                                    }
                                }
                            }
                            else {
                                echo $pemicuGerbangAsli;
                            }

                            if (isset($keyupAction) && $keyupAction == true) {
                                echo "onkeyup=\"sendCartResultRequest('" . $iSpec['editTarget'] . "'$qtyParam+'$queryParams', 220);if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            else {
                                echo "onkeyup=\"delay( function(){ $('#shopping_cart').trigger('change') }, 400, this );if(parseFloat(removeCommas(this.value))>0){ this.value=addCommas(this.value) }else{ this.value=0 }\"";
                            }
                            echo ">";

                            break;
                        case "text":
                            if (is_numeric($fieldVal)) {
                                echo "<span keyid=$keyID noid=$noID id=$colID class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                            }
                            else {
                                if (strlen($fieldVal) > 10) {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                                else {
                                    echo "<span keyid=$keyID noid=$noID id=$colID class='form-control' style='color:$color;border:0px;'>" . formatField($key, $fieldVal) . "</span>";
                                }
                            }
                            break;
                    }
                    echo "</td>";
                }

                //-----------------
                if (isset($checkOpname) && ($checkOpname == true)) {
                    if (isset($iSpec['ceklist_opname']) && ($iSpec['ceklist_opname'] == 1)) {
                        $ceklist_checked = "checked";
                    }
                    else {
                        $ceklist_checked = "";
                    }
                    echo "<td width='1%'>";
                    echo "<input type='checkbox' $ceklist_checked 
                        onclick=\"document.getElementById('result').src='" . $checkOpnamePaired . "?id=$iID';\">";
                    echo "</td>";
                }
                //-----------------
                //region remover per row
                if (!$avoidRemove) {
                    echo "<td width='1%'>";
                    echo "<a class='text-black btn btn-warning btn-sm' title='remove this item' data-toggle='tooltip' data-placement='left' onclick=\"document.getElementById('result').src='" . $iSpec['removeTarget'] . "';\"><span class='glyphicon glyphicon-remove'></span></a>";
                    echo "</td>";
                }
                //endregion

                echo "</tr>";

                echo "
            <script>
                \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).html(\"<i class='fa fa-check'></i>\");
                \n$('#check_" . trim($iSpec['id']) . "', $('#pilihan_item')).addClass(\"text-green text-bold pull-right\");
            </script>
            ";

                if ($noteEnabled == true) {
                    $colspan2 = $imageEnable == true ? 1 : -1;
                    $colspan = sizeof($itemLabels) - $colspan2;
                    echo "<tr group='noteEnabled'>";
                    echo "<td>&nbsp;</td>";
                    echo "<td colspan='" . $colspan . "'>";
                    $noteVal = isset($iSpec['note']) ? $iSpec['note'] : "";
                    if (isset($noteType)) {
                        switch ($noteType) {
                            case "textarea":
                                echo "<textarea class='form-control' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);sendCartResultRequest('" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID', 0);}\"
                                >$noteVal</textarea>";
                                break;
                            case "text":
                            default:
                                echo "<input type=text class='form-control' value='$noteVal' placeholder='write notes here'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);sendCartResultRequest('" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID', 0);}\"
                                >";
                                break;
                        }
                    }

                    echo "</td>";
                    if ($imageEnable == true) {
                        echo "<td colspan='2'>";
                        $imageVal = isset($iSpec['images']) ? $iSpec['images'] : "";
                        if (isset($imageType)) {
                            switch ($imageType) {
                                case "images":

                                    $file_e = "";
                                    $file = isset($iSpec['images']) ? $iSpec['images'] : "";
                                    $file_e = urlencode($file);
                                    echo "<div class='input-groups'>";
                                    if (strlen($imageVal) > 0) {
                                        $modals = array(
                                            "title" => "Attachment " . $iSpec['nama'],
                                            "body" => array($file),
                                        );
                                        $modal_e = urlencode(blobEncode($modals));
                                        $modal_l = base_url() . "Katalog/modal/$modal_e";

                                        echo "<a href='$modal_l' data-toggle='modal' data-target='#myModal'><img src='$file' class='img-rounder' height='50px' style='float: right;'></a>";
                                        echo "<input type='hidden' name='img_$iID' value='$file'>";
                                    }

                                    echo "<form class='input-group' id='myForm_$iID' method='post' enctype='multipart/form-data' action='$imageRecorder/$iID?valValue=$file_e' target='result'>";

                                    echo "<input type='file' id='file-upload' style='border: none;' name='file' class='file' onchange=\"document.getElementById('myForm_$iID').submit();swal({'text':'uploading image ... ... ',showConfirmButton: false,timer:5000,});\">";

                                    echo "</form>";
                                    echo "</div>";

                                    break;
                                case "text":
                                default:
                                    echo "<input type=text class='form-control' value='$noteVal'
                                onblur=\"if(this.value!=this.defaultValue){hiliteDiv(this);sendCartResultRequest('" . $noteRecorder . "?val='+encodeURIComponent(this.value)+'&iid=$iID', 0);}\"
                                >";
                                    break;
                            }
                        }
                        echo "</td>";
                    }
                    echo "</tr>";
                }
                if ($pairedItemEnabled == true) {
                    if (sizeof($pairedItemField) > 0) {
                        $listModePairedItem = array();
                        $readOnlyPairedItem = array();
                        foreach ($pairedItemField as $key => $label) {
                            $listModePairedItem[$key] = "input";
                            if (in_array($key, $editableFields)) {
                                $readOnlyPairedItem[$key] = "";
                                if (isset($iSpec["jml"]) && $iSpec["jml"] < 1) {
                                    $readOnlyPairedItem[$key] = "readonly_x";
                                }
                            }
                            else {
                                $readOnlyPairedItem[$key] = "readonly_xx";
                                $listModePairedItem[$key] = "text";
                            }
                        }
                    }
                    echo "<tr group='pairedItemEnabled'>";
                    echo "<td>&nbsp;</td>";
                    $c_itemLabels = sizeof($itemLabels);
                    $c_pairedItemField = sizeof($pairedItemField);
                    $c_colspan = ($c_itemLabels - $c_pairedItemField + 1);
                    echo "<td colspan='" . $c_colspan . "'>";
                    //==pairedItems, if any
                    if (isset($selItems) && sizeof($selItems) > 0) {
                        echo "<select
                                title='Choose one of the following...'
                                data-header='Ketik Nama/Kode/Folder/Barcode'
                                data-size='10'
                                data-container='body'
                                class='picker_$iID selectpicker form-control select2 show-tick'
                                data-style='btn-primary'
                                data-live-search='true'
                                classs='form-control'
                                onchange=\"sendCartResultRequest('" . $pairedItemRecorder . "?val='+(this.value)+'&iid=$iID', 0)\"
                                >";

                        asort($selItems);

                        foreach ($selItems as $piID => $piName) {
                            if ($piID != $iSpec['id']) {
                                $selectedState = (isset($pairedItems[$iID]) && ($piID == $pairedItems[$iID]['id'])) ? "selected" : "";
                                $selItemsKodes = isset($selItemsKode[$piID]) ? $selItemsKode[$piID] : "-";
                                $selItemsFolders = isset($selItemsFolder[$piID]) ? $selItemsFolder[$piID] : "-";
                                $selItemsKeterangans = isset($selItemsKeterangan[$piID]) ? $selItemsKeterangan[$piID] : "-";
                                $selItemsBarcodes = isset($selItemsBarcode[$piID]) ? $selItemsBarcode[$piID] : "-";
                                echo "<option data-subtext='$selItemsKodes' data-tokens='$piID $selItemsFolders $selItemsKeterangans $selItemsBarcodes' value='$piID' $selectedState>$piName </option>";
                            }
                        }

                        echo "</select>";

                    }

                    echo "</td>";

//                echo "<script>top.$('.select2').selectpicker();</script>";
//                echo "<script> setTimeout( function(){ top.$('.picker_$iID').selectpicker(); console.log('dari shopingcart picker_$iID') }, 100 ); </script>";

                    echo "<script> $('.picker_$iID').selectpicker(); </script>";

//                echo "<script> setTimeout( function(){ top.$('.select2').selectpicker(); console.log('dari shopingcart') }, 500 ); </script>";

                    if (sizeof($pairedItemField) > 0) {
                        foreach ($pairedItemField as $key => $label) {
                            $pairedItems2ID = isset($pairedItems[$iID]['id']) ? $pairedItems[$iID]['id'] : 0;
                            $pairedItems2Qty = isset($pairedItems[$iID]['jml']) ? $pairedItems[$iID]['jml'] : 0;
                            $fieldVal = isset($pairedItems[$iID][$key]) ? $pairedItems[$iID][$key] : "";
                            echo "<td>";
                            switch ($listMode[$key]) {
                                case "input":
                                    echo "<input type='text' class='form-control text-right' value='" . $pairedItems2Qty . "' min='0' autocomplete='off'
                                    onblur=\"sendCartResultRequest('" . $pairedItemRecorder . "?newQty='+removeCommas(this.value)+'&iid=$iID&val=$pairedItems2ID', 0);\"
                                    >";
                                    break;
                                case "text":
                                    if (is_numeric($fieldVal)) {
                                        echo "<span class='form-control text-right' style='color:$color;background:#f0f0f0;'>" . niceDecimal($fieldVal) . "</span>";
                                    }
                                    else {
                                        echo "<span class='form-control text-left' style='color:$color;border:0px;'>" . str_replace(" ", "&nbsp;", $fieldVal) . "</span>";
                                    }
                                    break;
                            }
                            echo "</td>";
                        }
                    }
                    echo "</tr>";
                }
            }


        }

        //region items2, kalau salah satunya untuk produksi dan konversi
        if (isset($items2) && sizeof($items2) > 0) {
            echo "<tr class='bg-info'>";
            echo "<td colspan='$jmlKolomHeader'>";

            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table table-group='items2' class='table table-condensed table-striped no-padding no-border'>";

            if (sizeof($itemLabels2) && (is_array($itemLabels2)) && $showItems) {
                //region header table anakan
                echo "<tr>";
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";
                foreach ($itemLabels2 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }
                echo "</tr>";
                //endregion
            }

            $no = 0;
            //region body table anakan
            $kurangStoks = array();
            foreach ($items2 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($items2_sum_kurang) && is_array($items2_sum_kurang)) {
                    if (isset($items2_sum_kurang[$iID])) {
                        $bgColor = "yellow";
                    }
                }
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                echo "<tr group='items2' id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='5%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels2 as $key => $label) {
                    //                if(in_array($key,$editableFields)){
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                    //                }
                }

                foreach ($itemLabels2 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    //region membuat array stok yang kurang
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    //endregion
                    echo "<td class='$cAlign'>";
                    $tabIndexNum = $colCtr . $no;

                    if (is_numeric($iSpec[$key])) {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo formatField($key, $iSpec[$key]);
                        // echo $iSpec[$key];
                    }
                    else {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
            //endregion

            echo "</table>";
            echo "</div>"; // anakan table

            // arrPrint($kurangStoks);

            echo "</td>";
            echo "</tr>";
        }
        //endregion

        //region items3
        if (isset($items3) && sizeof($items3) > 0) {
            echo "<tr class='bg-info'>";
            echo "<td colspan='$jmlKolomHeader'>";

            // echo "<div class='table-responsive no-padding no-border border-cek overflow-h'>";
            echo "<div class='panel no-margin'>"; // anakan table
            echo "<table table-group='items3' class='table table-condensed table-striped no-padding no-border'>";

            if (sizeof($itemLabels3) && (is_array($itemLabels3)) && $showItems) {
                //region header table anakan
                echo "<tr>";
                echo "<td class='text-muted bg-grey-1 text-center'>";
                echo "No";
                echo "</td>";
                foreach ($itemLabels3 as $key => $label) {
                    echo "<td class='text-muted bg-grey-1 text-center text-capitalize'>";
                    echo $label;
                    echo "</td>";
                }
                echo "</tr>";
                //endregion
            }

            $no = 0;
            //region body table anakan
            $kurangStoks = array();
            foreach ($items3 as $iSpec) {
                $iID = $iSpec['id'];
                $no++;
                $bgColor = "transparent";
                if (isset($_SESSION['errLines'])) {
                    if (in_array($iSpec['id'], $_SESSION["errLines"])) {
                        $bgColor = "#ffff77";
                    }
                }
                echo "<tr group='items3' id='tr_" . $iSpec['id'] . "' bgcolor=$bgColor>";
                echo "<td width='5%'>";
                echo $no;
                echo ".</td>";
                $colCtr = 0;
                $queryParams = "";
                foreach ($itemLabels3 as $key => $label) {
                    //                if(in_array($key,$editableFields)){
                    $colID = $key . "_" . $no;
                    $queryParams .= "&$key='+removeCommas(document.getElementById('$colID').value)+'";
                    //                }
                }

                foreach ($itemLabels3 as $key => $label) {
                    $colCtr++;
                    $color = "343434";
                    if (isset($_SESSION['errFields'][$iSpec['id']])) {
                        if (in_array($key, $_SESSION['errFields'][$iSpec['id']])) {
                            $color = "#dd3300";
                        }
                    }
                    $cAlign = is_numeric($iSpec[$key]) ? "text-right" : "text-left";
                    //region membuat array stok yang kurang
                    if ($key == "sisa") {
                        if ($iSpec[$key] < 0) {
                            $kurangStoks[$iSpec['nama']] = $iSpec['sisa'];
                            $cAlign .= " text-red text-bold";
                        }
                        else {
                            $cAlign .= "";
                        }
                    }
                    //endregion
                    echo "<td class='$cAlign'>";
                    $tabIndexNum = $colCtr . $no;

                    if (is_numeric($iSpec[$key])) {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control text-right' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    else {
                        // echo "<input type=text autocomplete='off' readOnly id=$colID class='form-control' style='color:$color;' value='" . $iSpec[$key] . "' >";
                        echo $iSpec[$key];
                    }
                    echo "</td>";
                }
                echo "</tr>";
            }
            //endregion


            if (isset($sumRows3) && sizeof($sumRows3) > 0) {
                $nr = 0;
                foreach ($sumRows3 as $key => $label) {
                    $val = 0;
                    $nr++;
                    $bottom_borderless = $nr < sizeof($sumRows3) ? "bottom-borderless" : "";

                    if (isset($main[$key]) && $main[$key] > 0) {
                        $val = $main[$key];
                    }
                    else {
                        if (isset($addValues[$key]) && $addValues[$key] > 0) {
                            $val = $addValues[$key];
                        }
                    }

                    echo "<tr group='sumRows3' class='bg-grey-01 3'>";
                    echo "<td colspan='" . sizeof($itemLabels3) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td class='right-borderlesss'>";
                    echo formatField($key, $val);
                    echo "</td>";
                    echo "</tr>";
                }
            }

            echo "</table>";
            echo "</div>"; // anakan table

            echo "</td>";
            echo "</tr>";
        }
        //endregion
        /*=============================sumrows============================*/
        if (isset($sumRows) && sizeof($sumRows) > 0) {
            $nr = 0;
            foreach ($sumRows as $key => $label) {
                $val = 0;
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows) ? "bottom-borderless" : "";

                if (isset($main[$key]) && $main[$key] > 0) {
                    $val = $main[$key];
                }
                else {
                    if (isset($addValues[$key]) && $addValues[$key] > 0) {
                        $val = $addValues[$key];
                    }
                }

                if ($showItems) {
                    echo "<tr class='bg-grey-01 0'>";
                    echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right $bottom_borderless valign-m text-uppercase'>$label</td>";
                    echo "<td colspan='3' class='right-borderlesss'>";
                    echo "<input type='text' id='$key' class='form-control text-right' readonly value='" . niceDecimal($val) . "' >";
                    echo "</td>";
                    echo "</tr>";
                }

            }
        }
        if (isset($sumRows2) && sizeof($sumRows2) > 0) {

            echo "<!-- ===========sumRows2============= -->";
            echo "<tr bgcolor='#e0e0e0'>";
            echo "<td colspan='" . (sizeof($itemLabels2) + 1) . "' class='text-left text-muted'><span class='fa fa-cog'></span> additional fees</td>";
            echo "</td>";
            echo "</tr>";
            $nr = 0;
            foreach ($sumRows2 as $key => $label) {
                $nr++;
                $bottom_borderless = $nr < sizeof($sumRows2) ? "bottom-borderless" : "";

                echo "<tr bgcolor='#f0f0f5'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' class='text-right bottom-borderless valign-m text-uppercase'>$label</td>";
                echo "<td>";
                echo $sumSpec2[$key];
                echo "</td>";
                echo "</tr>";
            }
        }

        if (sizeof($addRows) > 0) {
//arrPrint($addRowLabels);
            $nr = 0;
            $targetLink = "";
            foreach ($addRowLabels as $k => $label) {
                $tic_biaya = "";
                if (isset($shopingCartTicBox[$k])) {
                    $targetLink = MODUL_PATH . $shopingCartTicBox[$k]["methode"];
                    $target_name = $shopingCartTicBox[$k]["name"];
                    $biaya_checked = isset($main[$target_name]) && $main[$target_name] == "true" ? "checked" : "";
                    $tic_biaya = "<span></span><input name='$target_name' class='validate-biaya' data-id='$target_name' type='checkbox' class='fform-control' $biaya_checked onclick=\"$('#result').load('" . $targetLink . "/?state='+this.checked+'&key=$target_name');\"></span>";
                }
                $nr++;
                $bottom_borderless = $nr < sizeof($addRowLabels) ? "bottom-borderless" : "";
//                arrPrint($addRowHiddens[$k]);
                $rowHide = isset($addRowHiddens[$k]) ? $addRowHiddens[$k] : "tidak_hidden";
                echo "<tr class='$rowHide'>";
                echo "<td colspan='" . sizeof($itemLabels) . "' id='label_$k' class='text-right $bottom_borderless valign-m text-uppercase'> $label $tic_biaya </td>";
                echo "<td colspan='2' class='text-right'>";
                echo $addRows[$k];
                echo "</td>";
                echo "</tr>";
            }
        }

        //region clear shoping cart
        if ((!$avoidRemove) || (!$avoidRemoveAll_items)) {
            $addColspan = (isset($checkOpname) && ($checkOpname == true)) ? 3 : 2;
            echo "<tr class='bg-grey-2'>";
            echo "<td colspan='" . (sizeof($itemLabels) + $addColspan) . "'>";

            echo "<span class='pull-left'>";
            echo "<a class='text-red' href='javascript:void(0)' title='remove ALL ITEMS' data-toggle='tooltip' data-placement='right' onclick=\"confirm_alert_result('Attention !!!','Remove all items on shopping cart?','$resetLink','YES CLEAR');\"><i class='fa fa-trash'> </i> Clear Shoping Cart</a>";
            echo "</span>";

            echo "</td>";
            echo "</tr>";
        }
        //endregion
        echo "</table class='table'>";
        echo "</div class='table-responsive'>";

        echo "<script>                                                          
                        function labelMencolok(key) {
                            var saldotext = $('#saldo_' + key).text();
                            var num_saldotext = Number(saldotext.replace(/\./g, ''));
                            let nilai_round = Number($('#nilai_round').val());
                            let lebih_bayar = Number($('#lebih_bayar').val());
                            let ketikan = $('#'+ key).val();
                            
                            if(num_saldotext > 0){
                                $('#label_' + key).addClass('text-red text-bold');
                                $('#' + key).prop('disabled', false);
                            }
                            else if(num_saldotext == 0){
                                 $('#' + key).prop('disabled', true);
                            }
                            
                            
                            let new_sisa = Number($('#new_sisa').val());
                            if(new_sisa <= 0 && lebih_bayar == 0){
                                  $('#nilai_entry').prop('disabled', true);
                            }
                            else {
                                $('#nilai_entry').prop('disabled', false).css('background-color','yellow');
                            }
                            
                            if(ketikan > nilai_round){
                                    swal({
                                            title: 'peringatan.. !!',
                                            html: 'maximal value yang bisa digunakan ' + addCommas(nilai_round) + ', sekarang ' + addCommas(ketikan)
                                        });
                                    $('#' + key).css('background-color','#fdb5b5');
                                    
                                    return false; 
                                }
                        }
                                                
                        var labelKeis = ['credit_amount', 'point_konsumen_qtt','uang_muka_dipakai','uang_muka_dipakai_ppn'];                        
                        labelKeis.forEach(function(item) {                        
                            labelMencolok(item);   
                        });
                        
                        labelKeis.forEach(function(item) {
                            $('#' + item).on('blur', function() {
                                let ketikan = $('#'+ item).val();
                                let saldotext = $('#saldo_' + item).text();
                                let num_saldotext = Number(saldotext.replace(/\./g, ''));
                                let nilai_round = Number($('#nilai_round').val());
                            });                                            
                        });
                        
            </script>";

        //--------
        $faktur = "";
        if (count($shopingCartFakturItems) > 0) {
            if (isset($showFormulirFaktur) && ($showFormulirFaktur == true)) {
                $faktur .= "<div class='panel panel-default' style=' margin-top: 10px;'>";
                $faktur .= "<table class='table'>";
                $faktur .= "<tr class='bg-primary'>";
                foreach ($shopingCartFakturParam["fields"] as $ff => $ff_abels) {
                    $faktur .= "<th>$ff_abels</th>";
                }
                $faktur .= "</tr>";
//arrPrintCyan($shopingCartFakturParam["editableFields"]);
                $pakai_ini = 0;
                if ($pakai_ini == 1) {
                    //-----------------------------
                    $faktur .= "<tr>";
                    $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
                    foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                        if (isset($shopingCartFakturParam["editableFields"][$fff])) {
                            $inputType = $shopingCartFakturParam["editableFields"][$fff];
                            $defValues = isset($shopingCartFakturItems[$fff]) ? $shopingCartFakturItems[$fff] : "";
                            if ($shopingCartFakturParam["editableFields"][$fff] == "checkbox") {
                                $classinputType = "";
                                $labels = "tic disini jika faktur belum tersedia";
                                $vals = "checked";
                                $checked = isset($shopingCartFakturItems[$fff]) && $shopingCartFakturItems[$fff] == "true" ? $vals : "";
                            }
                            else {
                                $classinputType = "form-control ";
                                $labels = "";
                                $vals = "value";
                                $checked = "";
                            }
                            $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name)\">";
                        }
                        else {
                            $value = formatField($fff, $shopingCartFakturItems[$fff]);
                        }
                        $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'>$labels </span></td>";
                    }
                    $faktur .= "</tr>";
                    //-----------------------------
                }
                else {
                    if (sizeof($formulirFaktur) > 0) {
                        $countItems = sizeof($items);
                        if ($countItems == 1) {
//                            unset($shopingCartFakturParam["editableFields"]["dpp_final"]);
                        }
                        foreach ($formulirFaktur as $ctt => $fSpec) {
                            $faktur .= "<tr>";
                            foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                                $linkFaktur = MODUL_PATH . $shopingCartFakturTarget . "/";
                                $labels = "";
                                $btn_formulir = "";
                                $btn_formulir_delete = "";
//                                cekHere("[$fff]");
                                if (isset($shopingCartFakturParam["editableFields"][$fff])) {
//                                    cekKuning("[$fff]");
                                    $inputType = $shopingCartFakturParam["editableFields"][$fff];
                                    $defValues = isset($fSpec[$fff]) ? $fSpec[$fff] : "";
                                    if ($shopingCartFakturParam["editableFields"][$fff] == "checkbox") {
                                        $classinputType = "";
                                        $labels = ($ctt == 0) ? "tic disini jika faktur belum tersedia" : "";
                                        $vals = "checked";
                                        $checked = isset($fSpec[$fff]) && $fSpec[$fff] == "true" ? $vals : "";
                                        $value = ($ctt == 0) ? "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name,$ctt)\">" : "";
                                        $btn_formulir = (($ctt == 0) && ($countItems > 1)) ? "<button class='btn btn-warning' onclick=\"$('#result').load('$cloneFormulirFaktur');\"><span class='glyphicon glyphicon-plus'></span> Tambah Formulir Faktur</button>" : "";
                                        $btn_formulir_delete = ($ctt > 0) ? "<button class='btn btn-danger' onclick=\"$('#result').load('$cloneFormulirFakturDelete/$ctt');\"><span class='glyphicon glyphicon-trash'></span></button>" : "";
                                    }
                                    else {
                                        $classinputType = "form-control ";
                                        $labels = "";
                                        $vals = "value";
                                        $checked = "";
                                        $value = "<input type='$inputType' id='$fff' class='$classinputType' name='$fff' onclick='this.select()' value='$defValues' $checked onblur=\"eksekutor(this.$vals,this.name,$ctt)\">";
                                    }
                                }
                                else {
//                                    cekHitam("[$fff]");
                                    $value = formatField($fff, $fSpec[$fff]);
                                }

                                $faktur .= "<td id='td_$fff'>$value <span class='text-danger text-bold text-blink'> $labels </span>";
                                $faktur .= $btn_formulir;
                                $faktur .= $btn_formulir_delete;
                                $faktur .= "<script>
                                        function eksekutor(nilai,nama,ctt) {
                                            $('#result').load('$linkFaktur'+ctt+'?nilai='+nilai+'&nama='+nama)
                                        }
                                        </script>";
                                $faktur .= "</td>";

                                if (is_numeric($fSpec[$fff])) {
                                    if (!isset($sub_total_bawah[$fff])) {
                                        $sub_total_bawah[$fff] = 0;
                                    }
                                    $sub_total_bawah[$fff] += $fSpec[$fff];
                                }
                            }
                            $faktur .= "</tr>";
                        }
                        if (sizeof($formulirFaktur) > 1) {
                            $bgcolor = isset($formulirFakturStyle["bgcolor"]) ? $formulirFakturStyle["bgcolor"] : "";
                            $faktur .= "<tr style='background-color:$bgcolor;'>";
                            foreach ($shopingCartFakturParam["fields"] as $fff => $f_labels) {
                                $value = isset($sub_total_bawah[$fff]) ? formatField($fff, $sub_total_bawah[$fff]) : "";
                                $faktur .= "<td id='td_$fff' class='text-bold' style='font-size:15px;'>$value";
                                $faktur .= "</td>";
                            }
                            $faktur .= "</tr>";
                        }
                    }
                }

                $faktur .= "</table>";
                $faktur .= "<div id='wr_skip_efakture'></div>";
                $faktur .= "</div>";
//                $faktur .= "<script>
//                                var skip_faktur = $('#skip_faktur').prop('checked');
//                                var dateFaktur = $('#dateFaktur').val();
//                                var eFaktur = $('#eFaktur').val();
//                                if(skip_faktur == false && dateFaktur == '' && eFaktur == '' && konfirmasi_cek == true){
//                                    $('#td_dateFaktur').append('<r>Isikan tanggal e-faktur</r>');
//                                    $('#td_eFaktur').append('<r>Isikan e-faktur</r>');
//                                    $('#dateFaktur').css('border-color', 'red');
//                                    $('#eFaktur').css('border-color', 'red');
//                                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
//                                    // swal({type: 'warning',title: 'Upss..',html: 'Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia'});
//                                    konfirmasi_cek = false;
//                                    $('#wr_skip_efakture').html('<r>Silahkan isikan e-faktur dan tanggal terbitnya, atau tik kotak bila belum tersedia</r>');
//                                }
//                                if(nilai_entry > 0 && isCa == 0 && konfirmasi_cek == true){
//                                    $('#elTitle_cash_account').parent().append('<r>Pilih salah satu sumber dana</r>').css('border-color', 'red').focus();
//                                    $('#konfirmasi_cek').prop('disabled', true).prop('checked', false);
//                                    konfirmasi_cek = false;
//                                }
//                                else if(nilai_entry == 0 && isCa == 0) {
//                                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
//                                }
//                                $('input[name=\"cash_account\"]').change(function(){
//                                    $('#konfirmasi_cek').prop('disabled', false).prop('checked', false);
//                                });
//                            </script>";

            }

        }
        echo $faktur;


        if (isset($fixedNote)) {
            echo "<div class='alert alert-danger' style='margin-top: 10px;font-size: 15px;'>";
            echo "<span>$fixedNote</span>";
            echo "</div>";
        }

        /*---------------------sum CBM CKD------------------------------------*/
        $volume_gross = "";
        $berat_gross = "";
        if (isset($detilSizeBar)) {
            if (sizeof($detilSizeBar) > 0) {

                $volume_gross = isset($detilSizeBar['volume_gross']) ? $detilSizeBar['volume_gross'] : 0;
                $berat_gross = isset($detilSizeBar['berat_gross']) ? $detilSizeBar['berat_gross'] : 0;

                $volume = isset($detilSizeBar['volume']) ? $detilSizeBar['volume'] : 0;
                $berat = isset($detilSizeBar['berat']) ? $detilSizeBar['berat'] : 0;


                echo "<div class='row bg-danger' style='background: #ffdecf;padding: 7px;'>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CBU CBM</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CBU (KG)</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CKD CBM</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$volume_gross' disabled=''>
                        </div>
                     </div>";
                echo "<div class='col-md-3 col-lg-3'>
                        <div class='input-group'>
                        <span class='input-group-addon' style='color: #000000;'>CKD (KG)</span>
                        <input type='text' class='form-control bg-danger' style='color: #000000;font-weight: bolder;' value='$berat_gross' disabled=''>
                        </div>
                     </div>";
                echo "</div>";
            }
        }

        //--------
        if (isset($checkOpnameEnabled) && ($checkOpnameEnabled == true)) {
            $noteEncode1 = blobEncode($checkOpnameNote1);
            $noteEncode2 = blobEncode($checkOpnameNote2);

            if (isset($checkOpnameCek1) && ($checkOpnameCek1 == 1)) {
                $ceklist_checked_1 = "checked";
            }
            else {
                $ceklist_checked_1 = "";
            }
            if (isset($checkOpnameCek2) && ($checkOpnameCek2 == 1)) {
                $ceklist_checked_2 = "checked";
            }
            else {
                $ceklist_checked_2 = "";
            }

            $strcekNote = "<br><div class='alert alert-danger' style='text-align: left;'>";

            $strcekNote .= "<input type='checkbox' value='' $ceklist_checked_1
                onclick=\"document.getElementById('result').src='" . $checkOpnameNotePaired . "?note1=$noteEncode1';\">";
            $strcekNote .= "<span style='font-size: 20px;'>&nbsp;&nbsp; $checkOpnameNote1</span>";

            $strcekNote .= "<br><input type='checkbox' value='' $ceklist_checked_2
                onclick=\"document.getElementById('result').src='" . $checkOpnameNotePaired . "?note2=$noteEncode2';\">";
            $strcekNote .= "<span style='font-size: 20px;'>&nbsp;&nbsp; $checkOpnameNote2</span>";

            $strcekNote .= "</div>";
            echo $strcekNote;
        }

        if (sizeof($elements) > 0) {
            echo "<div class='panel-body table-responsive ' style='bborder:1px solid red;'>";
            echo "<div class='row'>";
            echo "<div class='col-md-12'>";
            echo "<h4 class='text-blue text-left'>Please fill in details below</h4>";
            echo "</div class='col-md-12'>";
            echo "</div class='row'>";
            echo "<div id='detailsElementsContainer' class='col-lg-12 no-padding text-center ' style='text-align:center;'>";
            $elCtr = 0;
            foreach ($elements as $eName => $pSpec) {

                $hiddenBox = "";
                if (isset($pSpec['hiddenBox']) && $pSpec['hiddenBox'] == "hidden") {
                    $hiddenBox = "hidden";
                }

                $hiddenSelect = "";
                if (isset($pSpec['hiddenSelect']) && $pSpec['hiddenSelect'] == "hidden") {
                    $hiddenSelect = "hidden";
                }
                $elCtr++;
                if (isset($pSpec['type']) && ($pSpec['type'] == "hidden")) {
                    // type hidden tidak perlu tampil di ui //
                }
                else {
                    //region penampil untuk elemen pada shopingcart
                    if ($elCtr % 2 == 0) {
                    }
                    else {
                        echo "<div class='col-lg-12 no-padding'>";
                        echo "<div class='row row-eq-height'>";
                    }
                    echo "<div class='col-md-6 col-lg-6 $hiddenBox' style='border:2px #e1ece6 solid;margin:0px;background:" . $pSpec['bgColor'] . "'>";

                    echo "<div id='elTitle_$eName' class='text-left text-muted text-bold text-capitalize'>";

                    echo $pSpec['label'] . " ";
                    if (isset($elementConfigs[$eName]['autoSelect']) && $elementConfigs[$eName]['autoSelect']) {

                    }
                    else {
                        echo "<a href='javascript:void(0)' onclick=\"hiliteDiv(this);document.getElementById('result').src='" . $elementResetTarget . "$eName';\"><span class='fa fa-eraser'></span></a>";
                    }
                    //----------------------------------------
                    if (isset($elementConfigMutasi[$eName])) {
//                        echo "&nbsp;&nbsp;&nbsp;<a href='" . $elementConfigMutasi[$eName] . "' target='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
                        $modalDialog = modalDialogBtn('&nbsp;', $elementConfigMutasi[$eName], $auto_close = 0, 'saldo');
                        echo "&nbsp;&nbsp;&nbsp;<a href='javascript:void(0);' onclick=\"$modalDialog\" ttarget='_blank' title='klik untuk melihat mutasi'><span class='glyphicon glyphicon-time'></span></a>";
                    }
                    //----------------------------------------
                    echo "<span class='pull-right'><sup>" . $pSpec['editStr'] . "&nbsp;" . $pSpec['addStr'] . "</sup></span>";

                    echo "</div class='box-title'>";

                    if (isset($elementConfigs[$eName]['warningLabel']) && $elementConfigs[$eName]['warningLabel']) {
                        echo "<div class='col-md-12'>" . $elementConfigs[$eName]['warningLabel'] . "</div>";
                    }


                    echo "<div class='line_" . __LINE__ . " $hiddenSelect'>&nbsp;</div>";
                    echo $pSpec['string'];

                    echo "</div>";
                    if ($elCtr % 2 == 0) {
                        echo "</div>";
                        echo "</div>";
                    }
                    //endregion
                }
            }

            echo "</div class='row'>";
            echo "</div class='row'>";
            echo "</div class='row'>";
            echo "<script>
                $('#detailsElementsContainer').on('mousedown touchstart change', 'input,select,textarea,button,a', function(){
                    if (window.__cartResultRequestTimer) {
                        clearTimeout(window.__cartResultRequestTimer);
                        window.__cartResultRequestTimer = null;
                    }
                });
            </script>";

            if (isset($showScheme) && sizeof($showScheme) > 0) {

                echo "<div class='clearfix'><hr></div>";
                echo "<div class='col-md-12 no-padding'>";
                echo "<div class='text-center text-danger text-bold'>-- SKEMA PINJAMAN ANDA --</div>";
                echo "<div class='text-center text-danger text-bold meta'>generator skema hanya berlaku untuk single kreditur</div>";
                echo "<div class='text-center text-danger text-bold'> ========================================== </div>";

                //header skema
                echo "<div class='col-md-12 no-padding'>";

                echo "<span class='col-md-2 text-left text-bold no-padding'>Nama Pemegang Saham </span>
                <span class='text-left col-md-9 no-padding text-capitalize'>: " . $headerScheme['nama'] . "</span>";

//                $headerScheme = array(
//                    "nama" => "$nmPemengangSaham",
//                    "jml_pinjaman" => "$nilai_pinjaman",
//                    "bunga_tahunan" => "$rate_bunga",
//                    "awal_meminjam" => "$awal_pinjaman",
//                    "pelunasan_pinjaman" => "$jatuh_tempo",
//                    "lama_pinjaman" => "$total_hari hari ($total_bulan bln)",
//                );

                echo "<span class='col-md-2 text-left text-bold no-padding'>Jumlah Pinjaman </span>      <span class='text-left col-md-9 no-padding'>: " . number_format($headerScheme['jml_pinjaman']) . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Bunga Tahunan </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['bunga_tahunan'] . "%</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Awal Meminjam </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['awal_meminjam'] . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Pelunasan Pinjaman </span>   <span class='text-left col-md-9 no-padding'>: " . $headerScheme['pelunasan_pinjaman'] . "</span>";
                echo "<span class='col-md-2 text-left text-bold no-padding'>Lama Pinjaman </span>        <span class='text-left col-md-9 no-padding'>: " . $headerScheme['lama_pinjaman'] . "</span>";

                echo "</div>";
                echo "<div class='clearfix'>&nbsp;</div>";
                echo "<div><table id='main_table' class='table datatable table-bordered table-hover table-striped'><thead>";
                echo "<tr>  <th width='1%'>No</th>
                            <th>Periode</th>
                            <th>jml hari / periode</th>
                            <th>Pokok Pinjaman</th>
                            <th>Rate Bunga</th>
                            <th>Nilai Bunga</th>
                            <th>PPh23</th>
                            <th>bunga setelah dipotong PPh</th>
                      </tr>";

                echo "</thead><tbody>";

                $total_bunga = 0;
                $total_pph23 = 0;
                $total_bunga_pph23 = 0;
                $total_hari = 0;
                $no = 1;

                foreach ($showScheme as $thnbln => $pinjaman) {

                    $setBackground = isset($pinjaman['silangan']) ? $pinjaman['silangan'] : "merah";
                    $bgColor = " ";

                    switch ($setBackground) {
                        default:
                        case "merah":
                            $bgColor = "bg-white";
                            break;
                        case "hijau":
                            $bgColor = "bg-success";
                            break;
                        case "berjalan":
                            $bgColor = "bg-warning";
                            break;
                    }

                    echo "  <tr>
                                <td class='$bgColor'>$no</td>
                                <td class='$bgColor'>" . date('F Y', strtotime($pinjaman['thnbln'] . '-01')) . "</td>
                                <td class='$bgColor'>" . $pinjaman['jml_hari_dbln'] . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pinjaman'], 0) . "</td>
                                <td class='$bgColor'>" . $pinjaman['rate_bunga'] . "%</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_bunga'], 0) . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nilai_pph23'], 0) . "</td>
                                <td class='$bgColor'>" . number_format($pinjaman['nett_bunga'], 0) . "</td>
                            </tr>";

                    $no++;

                    $total_bunga += $pinjaman['nilai_bunga'] * 1;
                    $total_pph23 += $pinjaman['nilai_pph23'] * 1;
                    $total_bunga_pph23 += $pinjaman['nett_bunga'] * 1;
                    $total_hari += $pinjaman['jml_hari_dbln'] * 1;
                }

                echo "<tfoot>
                        <tr>
                            <td>-</td>
                            <td>-</td>
                            <td>" . $total_hari . "</td>
                            <td>-</td>
                            <td>-</td>
                            <td>" . number_format($total_bunga, 0) . "</td>
                            <td>" . number_format($total_pph23, 0) . "</td>
                            <td>" . number_format($total_bunga_pph23, 0) . "</td>
                        </tr>
                    </tfoot>";

                echo "</tbody>
                        </table>
                        </div>";
                echo "<div class='clearfix'>&nbsp;</div>";
                echo "<div class='text-left'>Keterangan:</div>";
                echo "<div class='text-left'> - periode dengan background hijau akan otomatis dibuatkan <span class='text-capitalize text-bold'>request loan interest</span> sesaat setelah request pinjaman diapprove </div>";
                echo "</div>";
            }
            echo "</div class='panel-body table-responsive' style='bborder:1px solid red;'>";
        }

        if (sizeof($inputs) > 0) {
            echo "<div class='col-lg-12 no-padding' style='margin-top:5px;'>";
            echo "<div class='alert alert-info-dot'>";
            echo "<h4 class='text-left'>additional values</h4>";
            echo "<table class='table table-condensed'>";
            echo "<tr>";
            foreach ($inputs as $eName => $eStr) {
                echo "<td class='text-muted'>";
                echo $inputLabels[$eName];
                echo "</td>";
            }
            echo "</tr>";
            echo "<tr>";
            foreach ($inputs as $eName => $eStr) {
                echo "<td>";
                echo $eStr;
                echo "</td>";
            }
            echo "</div>";
            echo "</div>";
            echo "</tr>";
            echo "</table class='table table-condensed'>";
            echo "</div class='panel-default'>";
            echo "</div class='panel'>";
        }

        if (isset($previewJurnal) && sizeof($previewJurnal) > 0) {
            $headersJurnal = $previewJurnal['header'];

//            echo "<div class='panel panel-info col-md-12'>";

            foreach ($previewJurnal['jurnal'] as $cabangID => $subItems) {
                if (sizeof($subItems) > 0) {
                    $cabangNama = isset($previewJurnal['cabang'][$cabangID]) ? $previewJurnal['cabang'][$cabangID] : "";


                    echo "<h4 class='text-blue' style='text-align: left;margin-top: 10px;'><span class='fa fa-book'></span> preview journal entries ($cabangNama)</h4>";

                    echo "<div class='tabel table-responsive'>";
                    echo "<table class='table table-condensed'>";

                    echo "<tr bgcolor='#f0f0f0'>";
                    foreach ($headersJurnal as $key => $label) {
                        echo "<td>";
                        echo "$label";
                        echo "</td>";
                    }
                    echo "</tr>";

                    foreach ($subItems as $iSpec) {
                        echo "<tr>";
                        foreach ($headersJurnal as $key => $label) {
                            echo "<td style='text-align: left;'>";
                            echo formatField($key, $iSpec[$key]);
                            echo "</td>";
                            if (is_numeric($iSpec[$key])) {
                                if (!isset($total[$cabangID][$key])) {
                                    $total[$cabangID][$key] = 0;
                                }
                                $total[$cabangID][$key] += $iSpec[$key];
                            }
                        }
                        echo "</tr>";
                    }

                    echo "<tr style='font-size: 15px;font-weight: bold;'>";
                    foreach ($headersJurnal as $key => $label) {
                        echo "<td>";
                        if (isset($total[$cabangID][$key])) {
                            echo formatField($key, $total[$cabangID][$key]);
                        }
                        echo "</td>";
                    }
                    echo "</tr>";

                    echo "</table>";
                    echo "</div>";

                }
                else {
                    echo "<div class='text-center text-warning'>";
                    echo "- no journal affected by this transaction -<br><br>";
                    echo "</div class='text-center text-warning'>";
                }
            }
//            echo "</div>";
        }


        if (isset($viewDescriptionNote) && ($viewDescriptionNote == true)) {
            echo "<span>Catatan:</span>";
            echo "<div class=\"box-footer bg-gray\">";
            echo "<div class=\"row\">";
            echo "<div class=\"col-md-12\">";
            echo "<textarea class=\"form-control\" placeholder=\"description note\"
                  style=\"font-style:italic;font-family:Monaco, Menlo, Consolas, 'Courier New', monospace;\"
                  onblur=\"sendCartResultRequest('$columnRecorderTarget/description?val='+encodeURIComponent(this.value), 0);\"
                >$default_description</textarea>";
            echo "</div class=\"col-md-12\">";
            echo "</div class=\"row\">";
            echo "</div class=\"box-footer bg-gray\">";
        }


        echo "<script>
                if( $('span[keyid=qty_debet]').length > 0 ){
                    top.shoppingCardValidator()
                    //top.console.log('perlu validator shoppingcart');
                }
                else{
                    //top.console.error('tidak perlu validator shoppingcart');
                }
                                
                </script>";

        if ($tipe_penjualan == 1) {// marketplace
            echo "<script>
                $(\"input[name = 'switch_pajak']\").prop('disabled', true);            
                </script>";
        }

        //--------
        if (count($arrItemTidakDibayar) > 0) {
            echo "<script>
                top.document.getElementById('checkbox_payment').disabled=true;
                top.document.getElementById('btnSave').disabled=true;
                console.log('HIHIHIHI');
            </script>";
        }
        else {
            echo "<script>
                top.document.getElementById('checkbox_payment').disabled=false;
                console.log('HOHOHOHOH');
            </script>";
        }
        //--------

//        cekHitam($label_disabled_ceklist);
        if($label_disabled_ceklist != ""){
            echo "<div class='alert alert-danger' style='margin-top:10px;font-size:15px;'>";
            echo "<span style='font-size:20px;font-weight:bold;'>Transaksi TIDAK BISA dilanjutkan: </span><br>";
            echo "<span>$label_disabled_ceklist</span>";
            echo "</div>";
        }

    }
    else {
        echo "<div class='panel-body'>";
        echo "<div class='text-danger'>";
        echo "- <strong>you have not chosen any item yet</strong> -<br>";
        echo "<small>you can do so by selecting items from available selectors</small><br>";
        echo "</div class='text-warning'>";
        echo "</div class='panel-body'>";

        echo "<script>

var paymentSrc=$('.paymentSrc')
                    jQuery.each(paymentSrc,function (a,b) {
    $(b).prop('disabled',false)                    
    
    // console.log($(b).parent().parent())
                    })
close_holdon()
</script>";
        /*
         * ini milik setor PPN Bulanan*/
//        echo "<div class='panel-body'>";
//        echo "<div class='text-danger text-center'>";
//        echo "- <strong>KAMU BELUM MEMILIH PPN KELUARAN/PPN MASUKAN</strong> -<br>";
//        echo "<small>KAMU SETIDAK NYA HARUS MEMILIH SATU (1) PPN KELUARAN dan SATU (1) PPN MASUKAN</small><br>";
//        echo "</div>";
//        echo "</div>";

        echo "<script>
                top.document.getElementById('checkbox_payment').disabled=true;
                console.log('HAHAHAHA');
            </script>";
    }

    $sessionCleares = array("errLines", "errFields", "errMsg");
    foreach ($sessionCleares as $s) {
        if (isset($_SESSION[$s])) {
            unset($_SESSION[$s]);
        }
    }

}
