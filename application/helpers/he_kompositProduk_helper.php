<?php
function callKompositProduk(){
    $ci = &get_instance();
    $ci->load->model("Mdls/MdlProdukKomposit");
    $o = new MdlProdukKomposit();

    $temp = $o->lookupAll()->result();
    arrPrint($temp);
    matiHEre();
    return $data;
}

?>