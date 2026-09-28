<?php

defined('BASEPATH') OR exit('No direct script access allowed');

require APPPATH . '/libraries/REST_Controller.php';

use Restserver\Libraries\REST_Controller;

class Products extends REST_Controller
{
    private $model;

    function __construct($config = 'rest')
    {

        parent::__construct($config);

//		$this->model = "Mdl".ucfirst($this->uri->segment(4));
        $this->model = "MdlProduk";
        $this->load->database();
        $this->load->model("Mdls/" . $this->model);
//        die("constructor");
    }

    function askTotalNumbers_get()
    {

        $id = $this->get('id');

        $key = $this->uri->segment(4);
        $mdlName = $this->model;
        $o = new $mdlName();

        $result = $o->lookupDataCount($key);
        $this->response($result, 200);
    }

    function askLimited_get()
    {

        $limit_per_page = $this->uri->segment(4);
        $page = $this->uri->segment(5);
        $key = $this->uri->segment(6);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();

//		$tmp = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);
//		die($this->db->last_query());

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function askTotalNumbersInFolder_get()
    {

        $id = $this->get('id');
        $folderID = $this->uri->segment(4);
        $key = $this->uri->segment(5);
        $mdlName = $this->model;
        $o = new $mdlName();

        $o->addFilter("folders='$folderID'");
        $result = $o->lookupDataCount($key);
        $this->response($result, 200);
    }

    function askLimitedInFolder_get()
    {

        $folderID = $this->uri->segment(4);
        $limit_per_page = $this->uri->segment(5);
        $page = $this->uri->segment(6);
        $key = $this->uri->segment(7);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();

        $o->addFilter("folders='$folderID'");

//		$tmp = $o->lookupLimitedData($limit_per_page, $page * $limit_per_page, $key);
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }


    function askAvailNumber_get()
    {

        $productID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $whID = $this->uri->segment(6);

        $mdlName = "MdlLockerStock";
        $this->load->model("Mdls/" . $mdlName);

        $o = new $mdlName();
        $result = $o->cekLoker($branchID, $productID, "active", 0, 0, $whID);

        $this->response($result, 200);
    }

    function whatIsPrice_get()
    {
        $productID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $segment = $this->uri->segment(6);

        $mdlName = "MdlHargaProduk";
        $this->load->model("Mdls/" . $mdlName);

        $o = new $mdlName();
        $o->addFilter("produk_id='$productID'");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("jenis_value='$segment'");
        $tmp = $o->lookupAll()->result();
        if (sizeof($tmp) > 0) {
            $result = $tmp[0]->nilai;
        } else {
            $result = 0;
        }
        $this->response($result, 200);
    }

    function seeItemDetail_get()
    {

        $id = $this->uri->segment(4);
        $mdlName = $this->model;
        $o = new $mdlName();
        $tmp = $o->lookupByID($id)->result();
        $result = array();
//        print_r($tmp);die();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
//                    echo "fName: $fName, kolom: ".$fSpec['kolom']."<br>";
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function seeFolders_get()
    {


        $limit_per_page = $this->uri->segment(4);
        $page = $this->uri->segment(5);
        $key = $this->uri->segment(6);

        $id = $this->get('id');
        $this->load->model("Mdls/MdlFolderProduk");
        $mdlName = "MdlFolderProduk";
        $o = new $mdlName();

        $tmp = $o->lookupAll()->result();

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {

                $result[$row->id] = $row->nama;
            }
        }
        $this->response($result, 200);
    }


    //====with active lockers only

    function askTotalStockNumbers_get()
    {

        $id = $this->get('id');
        $branchID = $this->uri->segment(4);
        $whID = $this->uri->segment(5);

        $key = $this->uri->segment(6);
        $mdlName = $this->model;

        $o = new $mdlName();

        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("gudang_id='$whID'");
        $result = $o->lookupDataCount($key);
//        cekkuning($this->db->last_query());
        $this->response($result, 200);
    }

    function askLimitedStocks_get()
    {


        $branchID = $this->uri->segment(4);
        $whID = $this->uri->segment(5);
        $limit_per_page = $this->uri->segment(6);
        $page = $this->uri->segment(7);
        $key = $this->uri->segment(8);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();


        $className="MdlProduk";
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();
        $arrExtImg = array();

        if (sizeof($dataExtRel) > 0) {
            $this->load->model("Mdls/MdlImages");
            $im = new MdlImages();
            $imgBlob = $im->lookupAll()->result();
            $countData = 0;
            foreach ($imgBlob as $rowImg) {
                $countData++;
                $arrExtImg[$rowImg->parent_id] = $rowImg->files;
                $badgeData[$rowImg->parent_id][] =$countData;
            }
        }

        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

        $this->db->select("*,produk.id as id");
        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("gudang_id='$whID'");
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);
// showLast_query("lime");
// arrPrint($tmp);

        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;


                    if (sizeof($arrExtImg) > 0) {
                        $srcKey = $dataExtRel["srcKey"];
                        $selectID = $row->$srcKey;
                        if (isset($arrExtImg[$selectID])) {
                            $valData = $arrExtImg[$selectID];
                            $img_src = "src='$valData'";
//                            $img_src = "src='data:image/jpeg;base64,$valData'";
                            $badge = sizeof($badgeData[$selectID]) > 1 ? sizeof($badgeData[$selectID]) : "";
                            $notifBadge =$badge > 1 ? "<span class='notify-badge' style=''>$badge</span>" : "";
                        } else {
                            $valData = base_url() . "public/images/img_blank.gif";
                            $img_src = "src='$valData'";
                            $notifBadge = "";
                        }
                        $fieldsImages = "<div class=''>";
                        $fieldsImages .= "<div class='item'>$notifBadge";
                        $fieldsImages .= "<img $img_src class='img-responsive' width='65px'>";
                        $fieldsImages .= "</div>";
                        $fieldsImages .= "</div>";

//                        $tmpData['images'] = $fieldsImages;
                        $tmpData['images'] = $img_src;
                    }

                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function askTotalStockNumbersInFolder_get()
    {

        $id = $this->get('id');
        $folderID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $whID = $this->uri->segment(6);

        $key = $this->uri->segment(7);
        $mdlName = $this->model;

        $o = new $mdlName();

        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $o->addFilter("folders='$folderID'");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("gudang_id='$whID'");
        $result = $o->lookupDataCount($key);
//        cekkuning($this->db->last_query());
        $this->response($result, 200);
    }

    function askLimitedStocksInFolder_get()
    {

        $folderID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $whID = $this->uri->segment(6);
        $limit_per_page = $this->uri->segment(7);
        $page = $this->uri->segment(8);
        $key = $this->uri->segment(9);

        $id = $this->get('id');
        $mdlName = $this->model;
        $o = new $mdlName();


        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

        $this->db->select("*,produk.id as id");
        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $o->addFilter("folders='$folderID'");
        $o->addFilter("cabang_id='$branchID'");
        $o->addFilter("gudang_id='$whID'");
        $tmp = $o->lookupLimitedData($limit_per_page, ($page - 1) * $limit_per_page, $key);


        $className="MdlProduk";
        $dataExtRel = isset($this->config->item('dataExtRelation')[$className]["images"]) ? $this->config->item('dataExtRelation')[$className]["images"] : array();
        $arrExtImg = array();

        if (sizeof($dataExtRel) > 0) {
            $this->load->model("Mdls/MdlImages");
            $im = new MdlImages();
            $imgBlob = $im->lookupAll()->result();
            $countData = 0;
            foreach ($imgBlob as $rowImg) {
                $countData++;
                $arrExtImg[$rowImg->parent_id] = $rowImg->files;
                $badgeData[$rowImg->parent_id][] =$countData;
            }
        }
        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    if (sizeof($arrExtImg) > 0) {
                        $srcKey = $dataExtRel["srcKey"];
                        $selectID = $row->$srcKey;
                        if (isset($arrExtImg[$selectID])) {
                            $valData = $arrExtImg[$selectID];
                            $img_src = "src='$valData'";
//                            $img_src = "src='data:image/jpeg;base64,$valData'";
                            $badge = sizeof($badgeData[$selectID]) > 1 ? sizeof($badgeData[$selectID]) : "";
                            $notifBadge =$badge > 1 ? "<span class='notify-badge' style=''>$badge</span>" : "";
                        } else {
                            $valData = base_url() . "public/images/img_blank.gif";
                            $img_src = "src='$valData'";
                            $notifBadge = "";
                        }
                        $fieldsImages = "<div class=''>";
                        $fieldsImages .= "<div class='item'>$notifBadge";
                        $fieldsImages .= "<img $img_src class='img-responsive' width='65px'>";
                        $fieldsImages .= "</div>";
                        $fieldsImages .= "</div>";

//                        $tmpData['images'] = $fieldsImages;
                        $tmpData['images'] = $img_src;
                    }
                    $tmpData[$fName] = $row->$realFieldName;
                }
                $result[] = $tmpData;
            }
        }
        $this->response($result, 200);
    }

    function askActiveStockAmount_get()
    {

        $prodID = $this->uri->segment(4);
        $branchID = $this->uri->segment(5);
        $whID = $this->uri->segment(6);



        $mdlName2 = "MdlLockerStock";
        $this->load->model("Mdls/$mdlName2");
        $c = new $mdlName2();

//        $this->db->select("*,produk.id as id");
//        $this->db->join($c->getTableName(), $c->getTableName() . ".produk_id = " . $o->getTableName() . ".id and state='active' and jumlah>0 ");
        $c->addFilter("cabang_id='$branchID'");
        $c->addFilter("gudang_id='$whID'");
        $c->addFilter("produk_id='$prodID'");
        $c->addFilter("state='active'");
        $tmp = $c->lookupAll()->result();
//        die($this->db->last_query());

        $result = 0;
        if (sizeof($tmp) > 0) {
            $result=$tmp[0]->jumlah;
        }
        $this->response($result, 200);
    }

    //==see how muach avail active stock in a product

    //react punya
    function loadAllProduk_get()
    {

        // Header untuk mengizinkan akses dari semua origin
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $key = $this->uri->segment(4);
        $val = $this->uri->segment(5);
        $mdlName = $this->model;
        $o = new $mdlName();

        // Ambil parameter dari GET (pakai isset supaya aman di PHP 5.6)
        $limit  = isset($_GET['limit']) ? (int)$_GET['limit'] : 10;
        $offset = isset($_GET['offset']) ? (int)$_GET['offset'] : 0;
        $page   = isset($_GET['page']) ? (int)$_GET['page'] : 1;
        $sortBy = isset($_GET['sortBy']) ? $_GET['sortBy'] : '';
        $merek  = isset($_GET['merek']) ? $_GET['merek'] : '';
        $pk     = isset($_GET['pk']) ? $_GET['pk'] : '';
        $search = isset($_GET['search']) ? $_GET['search'] : '';

        $this->db->from('produk');

        // Jika ada pencarian
        if (!empty($search)) {
            $o->createSmartSearch($search, array('nama', 'deskripsi', 'kategori_nama', 'merek_id'));
        }

        // Jika ada sorting
        if (!empty($sortBy)) {
            $sortField = 'nama'; // Default sorting berdasarkan nama produk
            $sortOrder = 'ASC'; // Default ASC

            if ($sortBy === 'name_desc') {
                $sortField = 'nama';
                $sortOrder = 'DESC';
            }

            $this->db->order_by($sortField, $sortOrder);
        }

        if(!empty($merek)){
            $this->db->where("merek_id", $merek);
        }

        $this->db->where("jenis", "item");
        $this->db->where("status", 1);
        $this->db->where("trash", 0);

        // Pagination (Offset & Limit)
//        $this->db->limit($limit, $offset);

        $query_pk = "";
        if(!empty($pk)){
            $smart_pk = [0.25+$pk, 0.50+$pk, $pk, $pk-0.50, $pk-0.25];
            $this->db->where_in("kapasitas_nama", $smart_pk);
            $query_pk = $this->db->last_query();
        }

        $query = $this->db->get();
        $result = $query->result();
        $query_produk = $this->db->last_query();

        // Hitung total produk (tanpa limit)
        $total_rows = $this->db->count_all('produk');

        // Data gambar produk
        $this->load->model("Mdls/MdlImages");
        $images = $this->MdlImages->callSpecs();
        $imgProduk = array();

        foreach ($images as $image0) {
            foreach ($image0 as $image) {
                $imgProduk[$image->parent_id][] = $image->files;
            }
        }

        $imgProdukDefault = array(img_blank());

        // Data harga produk
        $this->load->model("Mdls/MdlHargaProduk");
        $pp = new MdlHargaProduk();
        $pp->setTokoId(0);
        $pp->setCabangId(CB_ID_PUSAT);
        $harga = $pp->callSpecs();
        $hargaProduk = array();

        foreach ($harga as $produk_id => $itemHarga) {
            foreach ($itemHarga as $item) {
                $hargaProduk[$produk_id][$item->jenis_value] = $item->nilai;
            }
        }

        // Format response
        $data = array();
        foreach ($result as $row) {
            $id = $row->id;
            $tmpData = array();
            foreach ($o->getFields() as $fName => $fSpec) {
                $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                $tmpData[$fName] = $row->$realFieldName;
            }
            $tmpData["images"] = isset($imgProduk[$id]) ? $imgProduk[$id] : $imgProdukDefault;
            $tmpData["harga"] = isset($hargaProduk[$id]) ? $hargaProduk[$id] : 0;
            $data[] = $tmpData;
        }


        //PROTECT HARGA NOL
        $data = array_filter($data, function ($item) {
            return isset($item['harga']['jual']) && (float) $item['harga']['jual'] > 0;
        });

        if ($sortBy === 'price_asc') {
            usort($data, function ($a, $b) {
                $hargaA = isset($a['harga']['jual']) ? (float) $a['harga']['jual'] : 0;
                $hargaB = isset($b['harga']['jual']) ? (float) $b['harga']['jual'] : 0;
                if ($hargaA == 0 && $hargaB > 0) {
                    return 1;
                }
                if ($hargaB == 0 && $hargaA > 0) {
                    return -1;
                }
                return ($hargaA > $hargaB) ? 1 : (($hargaA < $hargaB) ? -1 : 0);
            });
        }

        if ($sortBy === 'price_desc') {
            usort($data, function ($a, $b) {
                $hargaA = isset($a['harga']['jual']) ? (float) $a['harga']['jual'] : 0;
                $hargaB = isset($b['harga']['jual']) ? (float) $b['harga']['jual'] : 0;
                if ($hargaA == 0 && $hargaB > 0) {
                    return 1;
                }
                if ($hargaB == 0 && $hargaA > 0) {
                    return -1;
                }
                return ($hargaA < $hargaB) ? 1 : (($hargaA > $hargaB) ? -1 : 0);
            });
        }

        $data_ori = count($data);

        $offset = ($page - 1) * $limit;
        $data = array_slice($data, $offset, $limit);

        $hasil = array(
            "total" => $data_ori,
            "jml" => $data_ori,
            "data" => array_values($data),
            "last_query" => $query_produk,
            "query_pk" => $query_pk,
            "page" => $page,
            "limit" => $limit,
            "offset" => $offset,
//            "result" => $result,
        );

        $this->response($hasil, 200);
    }
    function seePrices_get()
    {
//        $productID = $this->uri->segment(4);
        $branchID = $this->uri->segment(4);
//        $segment = $this->uri->segment(5);

        $mdlName = "MdlHargaProduk";
        $this->load->model("Mdls/" . $mdlName);

        $o = new $mdlName();
//        $o->addFilter("produk_id='$productID'");
        $o->addFilter("cabang_id='$branchID'");
        $tmp = $o->lookupAll()->result();
        $results = array();
        if (sizeof($tmp) > 0) {

            foreach ($tmp as $row) {
                $results[$row->produk_id][$row->jenis_value] = $row->nilai;
            }
        }
//        cekbiru($this->db->last_query());
        $this->response($results, 200);
    }
    function loadAllProduk2_get()
    {

        // Header untuk mengizinkan akses dari semua origin
        header("Access-Control-Allow-Methods: GET, POST, OPTIONS, PUT, DELETE");
        header("Access-Control-Allow-Headers: Content-Type, Authorization");

        $this->db->select("
            p.id AS produk_id,
            p.kode AS kode,
            p.nama AS produk_nama,
            p.merek_nama AS merek,
            p.kapasitas_nama AS kapasitas,
            p.kategori_nama AS kategori,

            MAX(CASE WHEN pr.jenis_value = 'jual' THEN pr.nilai END) AS harga_jual,
            MAX(CASE WHEN pr.jenis_value = 'jual_online' THEN pr.nilai END) AS harga_jual_online,
            pr.last_update AS harga_terakhir_update,
            img.id AS image_id,
            img.judul AS image_judul,
            img.files AS image_data
        ");
        $this->db->from('produk p');
        $this->db->join('price pr', "p.id = pr.produk_id AND pr.status = 1 AND pr.trash = 0" , 'left');
        $this->db->join('images img', "p.id = img.parent_id AND img.jenis = 'produk' AND img.trash = 0", 'left');
        $this->db->group_by('p.id');

        $query = $this->db->get();

        $hasil = array(
            "data" => $query->result(),
            "query" => $this->db->last_query(),
        );

        $this->response($hasil, 200);
    }
    function seeItemAll_get()
    {

        $key = $this->uri->segment(4);
        $val = $this->uri->segment(5);
        $mdlName = $this->model;
        $o = new $mdlName();

        $total_rows = $this->db->count_all('produk');;

        switch ($key){
            case "id":
                $this->db->where($key,$val);
                break;
            case "limit":
                $this->db->limit($val);
                break;
            case "search":
                // $this->
                $tmpCols = array();

                $listedFieldsSelectItem =$o->getListedFieldsSelectItem();
                if (method_exists($o, "getListedFieldsSelectItem") && count($listedFieldsSelectItem) > 1) {
                    // arrPrint($listedFieldsSelectItem);

                    foreach ($listedFieldsSelectItem as $fName => $fSpec) {
                        $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                        $tmpCols[$fieldName] = $fieldName;
                    }
                }
                else {
                    // arrPrint($o->getFields());
                    foreach ($o->getFields() as $fName => $fSpec) {
                        $fieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                        $tmpCols[$fieldName] = $fieldName;
                    }
                }

                $o->createSmartSearch($val, $tmpCols);
                break;
        }
        $tmp = $o->lookupAll()->result();
        // showLast_query("biru");

        $this->load->model("Mdls/MdlImages");
        $images = $this->MdlImages->callSpecs();
        // showLast_query("merah");
        // arrPrint($images);
        foreach ($images as $image0 ) {
            foreach ($image0 as $image) {

                $parent_id = $image->parent_id;
                $files = $image->files;

                $imgProduk[$parent_id][] = $files;
            }
        }
        $imgProdukDefault[] = img_blank();

        $this->load->model("Mdls/MdlHargaProduk");
        $pp = new MdlHargaProduk();
        $pp->setTokoId(0);
        $pp->setCabangId(CB_ID_PUSAT);
        $harga = $pp->callSpecs();
        // showLast_query("kuning");

        // arrPrint($harga);
        foreach ($harga as $produk_id => $itemHarga) {
            foreach ($itemHarga as $item) {
                $jenis_value = $item->jenis_value;
                $nilai = $item->nilai;

                $hargaProduk[$produk_id][$jenis_value] = $nilai;
            }
        }






        $result = array();
        if (sizeof($tmp) > 0) {
            foreach ($tmp as $row) {
                $id = $row->id;
                $tmpData = array();
                foreach ($o->getFields() as $fName => $fSpec) {
                    $realFieldName = isset($fSpec['kolom']) ? $fSpec['kolom'] : $fName;
                    $tmpData[$fName] = $row->$realFieldName;
                }
                // tambahan data bisa dimasukan disini
                $tmpData["images"] = isset($imgProduk[$id]) ? $imgProduk[$id] : $imgProdukDefault;
                $tmpData["harga"] = isset($hargaProduk[$id]) ? $hargaProduk[$id] : 0;


                $result[] = $tmpData;
            }
        }

        $hasil = array();
        $hasil["jml_total"] = $total_rows;
        $hasil["jml"] = count($result);
        $hasil["data"] = $result;

        // arrPrint($hasil);
        // matiHere(__LINE__);
        $this->response($hasil, 200);
    }

    function seeBrand_get()
    {
        $this->db->distinct();
        $this->db->select('merek_nama, merek_id');
        $this->db->where('merek_nama IS NOT NULL AND merek_nama != ""');
        $this->db->where('merek_id IS NOT NULL AND merek_id != 0');
        $this->db->order_by('merek_nama', 'ASC');
        $query = $this->db->get('produk');

        $result = [];
        foreach ($query->result() as $row) {
            $result[] = [
                "label" => trim($row->merek_nama),
                "value" => $row->merek_id,
            ];
        }

        $this->response($result, 200);
    }
}

?>