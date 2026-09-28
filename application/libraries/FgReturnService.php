<?php
defined('BASEPATH') OR exit('No direct script access allowed');

class FgReturnService
{
    private $CI;
    private $cCode = "_TR_9833";
    private $jenisTr = "9833";
    private $modul = "distribusifgproject";
    private $configPath;
    private $gudangProjectID = 9;

    public function __construct($gudangProjectID = null)
    {
        $this->CI =& get_instance();
        $this->configPath = APPPATH . "modules/" . $this->modul . "/config/";
        if ($gudangProjectID !== null) {
            $this->gudangProjectID = $gudangProjectID;
        }
    }

    public function setGudangProjectID($id)
    {
        $this->gudangProjectID = $id;
    }

    public function processReturn($params)
    {
        $produk_id = $params['produk_id'];
        $produk_nama = $params['produk_nama'];
        $no_spk = $params['no_spk'];
        $sub_nomer = $params['sub_nomer'];
        $tasklist_id = $params['tasklist_id'];
        $sub_fase_id = $params['sub_fase_id'];
        $wo_paket_id = $params['wo_paket_id'];
        $wo_paket_nama = $params['wo_paket_nama'];
        $gudang_wo_id = $params['gudang_wo_id'];
        $return_items = $params['return_items'];
        $cabang_id = $params['cabang_id'];
        $cabang_nama = $params['cabang_nama'];
        $bookingNumber = $params['bookingNumber'];
        $task_nama = isset($params['task_nama']) ? $params['task_nama'] : (isset($params['produk_nama']) ? $params['produk_nama'] : "-");
        if (isset($params['gudangProjectID'])) {
            $this->gudangProjectID = $params['gudangProjectID'];
        }

        $this->buildSession($produk_id, $produk_nama, $no_spk, $sub_nomer, $tasklist_id,
            $sub_fase_id, $wo_paket_id, $wo_paket_nama, $gudang_wo_id,
            $return_items, $cabang_id, $cabang_nama, $bookingNumber, $task_nama);

        $holdResult = $this->createHoldEntries($return_items);
        if (is_array($holdResult)) {
            return $holdResult;
        }

        $fillResult = $this->fillValues();
        if (!$fillResult['success']) {
            return $fillResult;
        }

        $result = $this->callSave();
        return $result;
    }

    private function createHoldEntries($return_items)
    {
        $cCode = $this->cCode;
        $CI = $this->CI;
        $CI->load->model("Mdls/MdlLockerStock");
        $CI->load->helper("he_misc");

        $login = $CI->session->login;
        $cabang_id = $_SESSION[$cCode]['main']['placeID'] ? $_SESSION[$cCode]['main']['placeID'] : $login['cabang_id'];
        $gudang_id = $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangID'] ? $_SESSION[$cCode]['main']['pihakProjekWorkorderSubGudangID'] : ($_SESSION[$cCode]['main']['gudangProjectID'] ? $_SESSION[$cCode]['main']['gudangProjectID'] : $this->gudangProjectID);

        $CI->db->trans_start();

        foreach ($return_items as $item) {
            $produk_id = $item['produk_dasar_id'];
            $qty = $item['jml_return'];
            $nama = $item['nama'];
            $satuan = $item['satuan'] ? $item['satuan'] : "";

            if ($qty <= 0) continue;

            // Deduct from active stock (with FOR UPDATE lock)
            $m = new MdlLockerStock();
            $CI->db->select('*')->from('stock_locker')
                ->where('produk_id', $produk_id)
                ->where('state', 'active')
                ->where('cabang_id', $cabang_id)
                ->where('gudang_id', $gudang_id)
                ->limit(1);
            $query = $CI->db->get_compiled_select();
            $tmpC = $CI->db->query("{$query} FOR UPDATE")->result();

            if (!empty($tmpC)) {
                foreach ($tmpC as $row) {
                    if ($qty > $row->jumlah) {
                        $CI->db->trans_complete();
                        return array("success" => false, "error" => "Stok tidak cukup untuk $nama: tersedia {$row->jumlah}, diminta $qty");
                    }
                    $m->setFilters(array());
                    $m->updateData(
                        array("id" => $row->id),
                        array("jumlah" => $row->jumlah - $qty, "state" => "active")
                    );
                }
            }

            // Upsert hold entry (with FOR UPDATE lock)
            $mh = new MdlLockerStock();
            $CI->db->select('*')->from('stock_locker')
                ->where('jenis', 'produk')
                ->where('produk_id', $produk_id)
                ->where('cabang_id', $cabang_id)
                ->where('gudang_id', $gudang_id)
                ->where('state', 'hold')
                ->where('oleh_id', $login['id'])
                ->where('transaksi_id', '0')
                ->limit(1);
            $queryHold = $CI->db->get_compiled_select();
            $existingHold = $CI->db->query("{$queryHold} FOR UPDATE")->result();

//            return array("success" => false, "error" => "Gagal memproses hold entries", "query" => $CI->db->last_query() );

            if (!empty($existingHold)) {
                $mh->setFilters(array());
                $mh->updateData(
                    array("id" => $existingHold[0]->id),
                    array("jumlah" => $existingHold[0]->jumlah + $qty)
                );
            } else {
                $mh->setFilters(array());
                $mh->addData(array(
                    "jenis" => "produk",
                    "jenis_locker" => "stock",
                    "produk_id" => $produk_id,
                    "nama" => $nama,
                    "satuan" => $satuan,
                    "state" => "hold",
                    "jumlah" => $qty,
                    "oleh_id" => $login['id'],
                    "oleh_nama" => $login['nama'],
                    "cabang_id" => $cabang_id,
                    "gudang_id" => $gudang_id,
                    "transaksi_id" => 0,
                    "nomer" => "0",
                ));
            }
        }

        $CI->db->trans_complete();

        if ($CI->db->trans_status() === false) {
            return array("success" => false, "error" => "Gagal memproses hold entries");
        }
    }

    private function buildSession($produk_id, $produk_nama, $no_spk, $sub_nomer,
        $tasklist_id, $sub_fase_id, $wo_paket_id, $wo_paket_nama,
        $gudang_wo_id, $return_items, $cabang_id, $cabang_nama, $bookingNumber, $task_nama = "-")
    {
        $login = $this->CI->session->login;
        $dtime = date("Y-m-d H:i:s");
        $fulldate = date("Y-m-d");

        $session = array();
        $session['main'] = array(
            "bookingNumber" => $bookingNumber,
            "olehID" => $login['id'],
            "olehName" => $login['nama'],
            "sellerID" => $login['id'],
            "sellerName" => $login['nama'],
            "placeID" => $cabang_id,
            "placeName" => $cabang_nama,
            "divID" => isset($login['div_id']) ? $login['div_id'] : 0,
            "divName" => isset($login['div_nama']) ? $login['div_nama'] : "default",
            "cabangID" => $cabang_id,
            "cabangName" => $cabang_nama,
            "gudangID" => $gudang_wo_id,
            "gudangName" => "gudang workorder $produk_nama",
            "jenis_usaha" => isset($login['jenis_usaha']) ? $login['jenis_usaha'] : "pkp",
            "tokoID" => 0,
            "tokoNama" => "-",
            "jenisTr" => "9833",
            "jenisTrMaster" => "9833",
            "jenisTrTop" => "9833r",
            "jenisTrName" => "request return distribusi ke dc",
            "stepNumber" => 1,
            "stepCode" => "9833r",
            "dtime" => $dtime,
            "fulldate" => $fulldate,
            "ppnFactor" => 11,
            "ppnFactorDesimal" => 0.11,
            "ppnFactorInclude" => 1.11,
            "pihakID" => -1,
            "pihakName" => "DC/PUSAT",
            "cabang2ID" => -1,
            "cabang2Name" => "DC/PUSAT",
            "place2ID" => -1,
            "place2Name" => "DC/PUSAT",
            "gudang2ID" => $this->gudangProjectID,
            "gudang2Name" => "Gudang Project Pusat",
            "gudangProjectID" => $this->gudangProjectID,
            "gudangProjectName" => "Gudang Project Pusat",
            "gudangProjectNama" => "Gudang Project Pusat",
            "pihakProjekID" => $produk_id,
            "pihakProjekMasterID" => "",
            "pihakProjekName" => $produk_nama,
            "pihakProjekValueSrc" => "",
            "pihakProjekRevertStep" => "",
            "pihakProjekDetailGate" => "items",
            "pihakProjekGudangID" => "-" . $produk_id . "0",
            "pihakProjekGudangName" => "gudang project $produk_nama",
            "pihakProjekGudangNama" => "gudang project $produk_nama",
            "projectCabangID" => $cabang_id,
            "pihakProjekNoSpk" => $no_spk,
            "pihakProjekNoSubSpk" => $sub_nomer,
            "pihakProjekWorkOrderID" => $wo_paket_id,
            "pihakProjekWorkOrderNama" => $wo_paket_nama,
            "pihakProjekWorkorderGudangID" => $gudang_wo_id,
            "pihakProjekWorkorderGudangName" => "gudang workorder $wo_paket_nama",
            "pihakProjekWorkorderGudangNama" => "gudang workorder $wo_paket_nama",
            "pihakProjekWorkOrderSubID" => $sub_fase_id,
            "pihakProjekWorkOrderSubNama" => $wo_paket_nama,
            "pihakProjekWorkorderSubGudangID" => $gudang_wo_id,
            "pihakProjekWorkorderSubGudangName" => "gudang workorder $wo_paket_nama",
            "pihakProjekWorkorderSubGudangNama" => "gudang workorder $wo_paket_nama",
            "description" => "return dari SPK $no_spk",
            "produkProjek" => $produk_id,
            "produkProjek__nama" => $produk_nama,
            "pihakProjekTasklistID" => $tasklist_id,
            "pihakProjekTasklistSPK" => $no_spk,
            "pihakProjekTasklistName" => $task_nama,
            "projectTasklist__id" => $tasklist_id,
            "projectTasklist__no_spk" => $no_spk,
        );

        $items = array();
        if (!empty($return_items)) {
            foreach ($return_items as $item) {
                $dasar_id = isset($item['produk_dasar_id']) ? $item['produk_dasar_id'] : (isset($item['id']) ? $item['id'] : (isset($item['produk_id']) ? $item['produk_id'] : 0));
                if (empty($dasar_id)) {
                    continue;
                }
                $jml_return = isset($item['jml_return']) ? $item['jml_return'] : (isset($item['qty']) ? $item['qty'] : 0);
                $harga = isset($item['harga']) ? $item['harga'] : 0;
                $nama = isset($item['nama']) ? $item['nama'] : (isset($item['produk_nama']) ? $item['produk_nama'] : (isset($item['produk_dasar_nama']) ? $item['produk_dasar_nama'] : ""));
                $satuan = isset($item['satuan']) && strlen($item['satuan']) > 0 ? $item['satuan'] : "n/a";
                $code = isset($item['code']) ? $item['code'] : (isset($item['produk_kode']) ? $item['produk_kode'] : (isset($item['kode']) ? $item['kode'] : ""));

                $items[$dasar_id] = array(
                    "handler" => "distribusifgproject/_processSelectProduct",
                    "id" => $dasar_id,
                    "produk_dasar_id" => $dasar_id,
                    "produk_id" => $dasar_id,
                    "nama" => $nama,
                    "name" => $nama,
                    "produk_nama" => $nama,
                    "produk_dasar_nama" => $nama,
                    "jml" => $jml_return,
                    "qty" => $jml_return,
                    "harga" => $harga,
                    "hpp" => $harga,
                    "nilai_untung" => 0,
                    "nilai_rugi" => 0,
                    "nilai_final_rugilaba" => 0,
                    "subtotal" => 0,
                    "satuan" => $satuan,
                    "produk_kode" => $code,
                    "code" => $code,
                    "label" => "",
                    "no_part" => "",
                    "jenis" => "produk",
                    "stok_awal" => "",
                    "current_stok" => "",
                    "olehID" => $login['id'],
                    "olehName" => $login['nama'],
                    "sellerID" => $login['id'],
                    "sellerName" => $login['nama'],
                    "pihakID" => -1,
                    "pihakName" => "DC/PUSAT",
                    "projectCabangID" => $cabang_id,
                    "placeID" => $cabang_id,
                    "placeName" => $cabang_nama,
                    "cabangID" => $cabang_id,
                    "cabangName" => $cabang_nama,
                    "gudangID" => $gudang_wo_id,
                    "gudangName" => "gudang workorder $produk_nama",
                    "place2ID" => -1,
                    "place2Name" => "DC/PUSAT",
                    "cabang2ID" => -1,
                    "cabang2Name" => "DC/PUSAT",
                    "gudang2ID" => $this->gudangProjectID,
                    "gudang2Name" => "Gudang Project Pusat",
                    "jenisTr" => "9833",
                    "jenisTrMaster" => "9833",
                    "ppnFactor" => 11,
                    "projectID" => $produk_id,
                    "projectName" => $produk_nama,
                    "pihakProjekID" => $produk_id,
                    "pihakProjekMasterID" => "",
                    "pihakProjekName" => $produk_nama,
                    "pihakProjekValueSrc" => "",
                    "pihakProjekRevertStep" => "",
                    "pihakProjekDetailGate" => "items",
                    "pihakProjekGudangID" => "-" . $produk_id . "0",
                    "pihakProjekGudangName" => "gudang project $produk_nama",
                    "pihakProjekGudangNama" => "gudang project $produk_nama",
                    "pihakProjekCustomerID" => 0,
                    "pihakProjekCustomerNama" => "",
                    "pihakProjekWorkOrderID" => $wo_paket_id,
                    "pihakProjekWorkOrderNama" => $wo_paket_nama,
                    "gudangProjectID" => $this->gudangProjectID,
                    "gudangProjectName" => "Gudang Project Pusat",
                    "gudangProjectNama" => "Gudang Project Pusat",
                    "pihakProjekWorkorderGudangID" => $gudang_wo_id,
                    "pihakProjekWorkorderGudangNama" => "gudang workorder $wo_paket_nama",
                    "pihakProjekWorkorderGudangName" => "gudang workorder $wo_paket_nama",
                    "pihakProjekWorkOrderSubID" => $sub_fase_id,
                    "pihakProjekWorkOrderSubNama" => $wo_paket_nama,
                    "pihakProjekWorkorderSubGudangID" => $gudang_wo_id,
                    "pihakProjekWorkorderSubGudangName" => "gudang workorder $wo_paket_nama",
                    "pihakProjekWorkorderSubGudangNama" => "gudang workorder $wo_paket_nama",
                    "jml_dikembalikan" => $jml_return,
                    "qty_dikembalikan" => $jml_return,
                    "jml_dipakai" => -$jml_return,
                    "qty_dipakai" => -$jml_return,
                    "scan_mode" => "simple",
                    "jml_serial" => 0,
                    "max_jml" => $jml_return,
                    "packed_jml" => 0,
                    "sent_jml" => 0,
                    "cancel_jml" => 0,
                    "req_cancel_jml" => 0,
                    "jml_target_scan" => $jml_return,
                );
            }
        }

        // Auto-load all SPK products from sub_tasklist_komposisi if missing
        if (!empty($no_spk)) {
            $this->CI->load->model("Mdls/MdlSubProgresTasklistKomposisi");
            $sptk = new MdlSubProgresTasklistKomposisi();
            $sptk->addFilter("no_spk='" . $this->CI->db->escape_str($no_spk) . "'");
            $sptk->addFilter("progress_id='2'");
            $sptk->addFilter("jenis='produk'");
            $kompRows = $sptk->lookupAll()->result();
            if (!empty($kompRows)) {
                foreach ($kompRows as $rKomp) {
                    $pId = $rKomp->produk_dasar_id;
                    if (!empty($pId) && !isset($items[$pId])) {
                        $pNama = $rKomp->produk_dasar_nama;
                        $pSatuan = strlen($rKomp->satuan) > 0 ? $rKomp->satuan : "n/a";
                        $items[$pId] = array(
                            "handler" => "distribusifgproject/_processSelectProduct",
                            "id" => $pId,
                            "produk_dasar_id" => $pId,
                            "produk_id" => $pId,
                            "nama" => $pNama,
                            "name" => $pNama,
                            "produk_nama" => $pNama,
                            "produk_dasar_nama" => $pNama,
                            "jml" => 0,
                            "qty" => 0,
                            "harga" => $rKomp->harga,
                            "hpp" => $rKomp->harga,
                            "nilai_untung" => 0,
                            "nilai_rugi" => 0,
                            "nilai_final_rugilaba" => 0,
                            "subtotal" => 0,
                            "satuan" => $pSatuan,
                            "produk_kode" => "",
                            "code" => "",
                            "label" => "",
                            "no_part" => "",
                            "jenis" => "produk",
                            "stok_awal" => "",
                            "current_stok" => "",
                            "olehID" => $login['id'],
                            "olehName" => $login['nama'],
                            "sellerID" => $login['id'],
                            "sellerName" => $login['nama'],
                            "pihakID" => -1,
                            "pihakName" => "DC/PUSAT",
                            "projectCabangID" => $cabang_id,
                            "placeID" => $cabang_id,
                            "placeName" => $cabang_nama,
                            "cabangID" => $cabang_id,
                            "cabangName" => $cabang_nama,
                            "gudangID" => $gudang_wo_id,
                            "gudangName" => "gudang workorder $produk_nama",
                            "place2ID" => -1,
                            "place2Name" => "DC/PUSAT",
                            "cabang2ID" => -1,
                            "cabang2Name" => "DC/PUSAT",
                            "gudang2ID" => $this->gudangProjectID,
                            "gudang2Name" => "Gudang Project Pusat",
                            "jenisTr" => "9833",
                            "jenisTrMaster" => "9833",
                            "ppnFactor" => 11,
                            "projectID" => $produk_id,
                            "projectName" => $produk_nama,
                            "pihakProjekID" => $produk_id,
                            "pihakProjekMasterID" => "",
                            "pihakProjekName" => $produk_nama,
                            "pihakProjekValueSrc" => "",
                            "pihakProjekRevertStep" => "",
                            "pihakProjekDetailGate" => "items",
                            "pihakProjekGudangID" => "-" . $produk_id . "0",
                            "pihakProjekGudangName" => "gudang project $produk_nama",
                            "pihakProjekGudangNama" => "gudang project $produk_nama",
                            "pihakProjekCustomerID" => 0,
                            "pihakProjekCustomerNama" => "",
                            "pihakProjekWorkOrderID" => $wo_paket_id,
                            "pihakProjekWorkOrderNama" => $wo_paket_nama,
                            "gudangProjectID" => $this->gudangProjectID,
                            "gudangProjectName" => "Gudang Project Pusat",
                            "gudangProjectNama" => "Gudang Project Pusat",
                            "pihakProjekWorkorderGudangID" => $gudang_wo_id,
                            "pihakProjekWorkorderGudangNama" => "gudang workorder $wo_paket_nama",
                            "pihakProjekWorkorderGudangName" => "gudang workorder $wo_paket_nama",
                            "pihakProjekWorkOrderSubID" => $sub_fase_id,
                            "pihakProjekWorkOrderSubNama" => $wo_paket_nama,
                            "pihakProjekWorkorderSubGudangID" => $gudang_wo_id,
                            "pihakProjekWorkorderSubGudangName" => "gudang workorder $wo_paket_nama",
                            "pihakProjekWorkorderSubGudangNama" => "gudang workorder $wo_paket_nama",
                            "jml_dikembalikan" => 0,
                            "qty_dikembalikan" => 0,
                            "jml_dipakai" => 0,
                            "qty_dipakai" => 0,
                            "scan_mode" => "simple",
                            "jml_serial" => 0,
                            "max_jml" => 0,
                            "packed_jml" => 0,
                            "sent_jml" => 0,
                            "cancel_jml" => 0,
                            "req_cancel_jml" => 0,
                            "jml_target_scan" => 0,
                        );
                    }
                }
            }
        }

        $session['items'] = $items;
        $session['items2'] = array();
        $session['items2_sum'] = array();
        $session['items3'] = array();
        $session['items3_sum'] = array();
        $session['items4_sum'] = array();
        $session['items_child'] = array();
        $session['rsltItems'] = array();
        $session['rsltItems2'] = array();
        $session['extractedItems'] = array();

        $total_harga = 0;
        foreach ($items as $item) {
            $total_harga += $item['jml'] * $item['harga'];
        }
        $session['main']['harga'] = $total_harga;
        $session['main']['grand_total'] = $total_harga;

        $serializedContent = base64_encode(serialize(array("nama" => $produk_nama)));
        $woSerializedContent = base64_encode(serialize(array("nama" => $wo_paket_nama, "employee_nama" => "")));
        
        $session['main_elements'] = array(
            "projectTasklist" => array(
                "elementType" => "dataModel",
                "name" => "projectTasklist",
                "label" => "Tasklist",
                "key" => $tasklist_id,
                "labelSrc" => "nama",
                "labelValue" => $task_nama,
                "mdl_name" => "MdlTasklistProject",
                "contents" => $serializedContent,
                "contents_intext" => $serializedContent,
                "multi" => array(),
            ),
            "produkProjek" => array(
                "elementType" => "dataModel",
                "name" => "produkProjek",
                "label" => "Project",
                "key" => $produk_id,
                "labelSrc" => "nama",
                "labelValue" => $produk_nama,
                "mdl_name" => "MdlProdukProject",
                "contents" => $serializedContent,
                "contents_intext" => $serializedContent,
                "multi" => array(),
            ),
            "workOrderDetails" => array(
                "elementType" => "dataModel",
                "name" => "workOrderDetails",
                "label" => "WORK ORDER",
                "key" => $wo_paket_id,
                "labelSrc" => "nama",
                "labelValue" => $wo_paket_nama,
                "mdl_name" => "MdlProjectWorkOrder",
                "contents" => $woSerializedContent,
                "contents_intext" => $woSerializedContent,
                "multi" => array(),
            ),
            "workOrderSubDetails" => array(
                "elementType" => "dataModel",
                "name" => "workOrderSubDetails",
                "label" => "SUB WORK ORDER",
                "key" => $sub_fase_id,
                "labelSrc" => "nama",
                "labelValue" => $wo_paket_nama,
                "mdl_name" => "MdlProjectWorkOrderSub",
                "contents" => $woSerializedContent,
                "contents_intext" => $woSerializedContent,
                "multi" => array(),
            ),
        );

        $session['tableIn_master'] = array();
        $session['tableIn_detail'] = array();
        $session['tableIn_detail_rsltItems'] = array();
        $session['tableIn_detail_rsltItems2'] = array();
        $session['tableIn_master_values'] = array();
        $session['tableIn_detail_values'] = array();
        $session['tableIn_detail_values_rsltItems'] = array();
        $session['tableIn_detail_values_rsltItems2'] = array();
        $session['main_add_values'] = array();
        $session['main_add_fields'] = array();
        $session['main_inputs'] = array();
        $session['extSteps'] = array();
        $session['paySrcs'] = array();
        $session['lockerPayment'] = array();
        $session['items_komposisi'] = array();
        $session['tableIn_detail2_sum'] = array();
        $session['tableIn_detail_values2_sum'] = array();
        $session['tableIn_sub_detail'] = array();

        $_SESSION[$this->cCode] = $session;
    }

    private function fillValues()
    {
        $cCode = $this->cCode;

        $configUiFile = $this->configPath . "coTransaksiUi.php";
        $configCoreFile = $this->configPath . "coTransaksiCore.php";
        $configValuesFile = $this->configPath . "coTransaksiValues.php";

        if (!file_exists($configUiFile) || !file_exists($configCoreFile)) {
            return array("success" => false, "error" => "Config files not found");
        }

        $config = array();
        include $configUiFile;
        include $configCoreFile;
        include $configValuesFile;

        $coTransaksiUi = isset($config["coTransaksiUi"]) ? $config["coTransaksiUi"] : array();
        $coTransaksiCore = isset($config["coTransaksiCore"]) ? $config["coTransaksiCore"] : array();
        $coTransaksiValues = isset($config["coTransaksiValues"]) ? $config["coTransaksiValues"] : array();

        $configUiJenis = isset($coTransaksiUi[$this->jenisTr]) ? $coTransaksiUi[$this->jenisTr] : array();
        $configCoreJenis = isset($coTransaksiCore[$this->jenisTr]) ? $coTransaksiCore[$this->jenisTr] : array();
        $configValuesJenis = isset($coTransaksiValues[$this->jenisTr]) ? $coTransaksiValues[$this->jenisTr] : array();

        if (empty($configCoreJenis)) {
            return array("success" => false, "error" => "Config core for 9833 not found");
        }

        $this->CI->load->helper("he_value_builder");

        $ppnFactor = 11;
        fillValues_he_value_builder($this->jenisTr, 1, 1, $configCoreJenis, $configUiJenis, $configValuesJenis, $ppnFactor);

        if (isset($_SESSION[$cCode]['items']) && !empty($_SESSION[$cCode]['items'])) {
            $total_harga = 0;
            foreach ($_SESSION[$cCode]['items'] as $id => $item) {
                $total_harga += $item['jml'] * $item['harga'];
            }
            $_SESSION[$cCode]['main']['harga'] = $total_harga;
            $_SESSION[$cCode]['main']['grand_total'] = $total_harga;
        }

        return array("success" => true);
    }

    private function callSave()
    {
        $cCode = $this->cCode;

        if (!isset($_SESSION[$cCode]["main"]["bookingNumber"])) {
            unset($_GET['return_json'], $_GET['json']);
            return array("success" => false, "error" => "bookingNumber not set");
        }
        if (!isset($_SESSION[$cCode]['items']) || count($_SESSION[$cCode]['items']) < 1) {
            unset($_GET['return_json'], $_GET['json']);
            return array("success" => false, "error" => "no items to return");
        }

        $ci = $this->CI;
        $bookingNumber = $_SESSION[$cCode]['main']['bookingNumber'];
        $createDir = APPPATH . "modules/" . $this->modul . "/controllers/";

        $modulControllerFile = $createDir . "Modul_Controller.php";
        $createFile = $createDir . "Create.php";

        if (!file_exists($createFile)) {
            return array("success" => false, "error" => "Create.php not found at $createFile");
        }

        $createCode = file_get_contents($createFile);
        $createCode = str_replace('require_once "Modul_Controller.php";', '', $createCode);
        $createCode = str_replace('<?php', '', $createCode);
        $createClassName = 'Create_' . $this->jenisTr;
        $createCode = preg_replace('/\bclass Create\b/', 'class ' . $createClassName, $createCode);

        eval($createCode);
        if (!class_exists($createClassName, false)) {
            return array("success" => false, "error" => "Failed to eval Create.php");
        }

        $reflection = new ReflectionClass($createClassName);
        $create = $reflection->newInstanceWithoutConstructor();

        $login = $ci->session->login;
        $create->jenisTr = $this->jenisTr;
        $create->cCode = $cCode;
        $create->modul = $this->modul;
        $create->session = $ci->session;
        $create->db = $ci->db;
        $create->load = $ci->load;
        $create->uri = $ci->uri;
        $create->config = $ci->config;
        $create->input = $ci->input;
        $create->cabangId = isset($login['cabang_id']) ? $login['cabang_id'] : 0;
        $create->placeId = $create->cabangId;
        $create->dates = date("Y-m-d");
        $create->transaksiMaintenance = false;
        $create->ppnFactor = isset($login['ppnFactor']) ? $login['ppnFactor'] : 11;
        $create->configPath = APPPATH . "modules/" . $this->modul . "/config/";

        $create->load->helper("he_stepping");
        $create->load->helper("he_access_right");
        $create->load->helper("he_session_replacer");
        $create->load->helper('he_angka');

        $config = array(); include $create->configPath . "coTransaksiUi.php";
        $create->configUi = isset($config["coTransaksiUi"]) ? $config["coTransaksiUi"] : array();
        $config = array(); include $create->configPath . "coTransaksiCore.php";
        $create->configCore = isset($config["coTransaksiCore"]) ? $config["coTransaksiCore"] : array();
        $config = array(); include $create->configPath . "coTransaksiLayout.php";
        $create->configLayout = isset($config["coTransaksiLayout"]) ? $config["coTransaksiLayout"] : array();
        $config = array(); include $create->configPath . "coTransaksiValues.php";
        $create->configValues = isset($config["coTransaksiValues"]) ? $config["coTransaksiValues"] : array();

        $create->configUiJenis = isset($create->configUi[$this->jenisTr]) ? $create->configUi[$this->jenisTr] : array();
        $create->configCoreJenis = isset($create->configCore[$this->jenisTr]) ? $create->configCore[$this->jenisTr] : array();
        $create->configLayoutJenis = isset($create->configLayout[$this->jenisTr]) ? $create->configLayout[$this->jenisTr] : array();
        $create->configValuesJenis = isset($create->configValues[$this->jenisTr]) ? $create->configValues[$this->jenisTr] : array();
        $create->jenisTrName = isset($create->configUi[$this->jenisTr]['steps'][1]['label']) ? $create->configUi[$this->jenisTr]['steps'][1]['label'] : "unnamed";
        $create->allSteps = isset($create->configUi[$this->jenisTr]['steps']) ? $create->configUi[$this->jenisTr]['steps'] : array();
        $create->mongoTableList = array(
            "main" => "transaksi",
            "mainValues" => "transaksi_values",
            "detail" => "transaksi_data",
            "detailValues" => "transaksi_data_values",
            "sign" => "transaksi_sign",
            "extras" => "transaksi_extstep",
            "registry" => "transaksi_registry",
        );
        $create->accessList = array();

        $_GET['return_json'] = 1;
        $_GET['json'] = 1;
        if (!defined('SILENT_TRANSAKSIONAL')) {
            define('SILENT_TRANSAKSIONAL', true);
        }

        ob_start();
        try {
            $output = $create->save();
        }
        catch (Exception $e) {
            ob_end_clean();
            unset($_GET['return_json'], $_GET['json']);
            return array("success" => false, "error" => $e->getMessage());
        }
        $bufferedEcho = ob_get_clean();

        if (!empty($bufferedEcho)) {
            $bufferedEcho = function_exists('sanitize_output_script') ? sanitize_output_script($bufferedEcho) : preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $bufferedEcho);
        }
        if (!empty($output)) {
            $output = function_exists('sanitize_output_script') ? sanitize_output_script($output) : preg_replace('/<script\b[^>]*>(.*?)<\/script>/is', '', $output);
        }

        if (empty($output) && !empty($bufferedEcho)) {
            $output = $bufferedEcho;
        }

        if (!empty($output)) {
            $firstBrace = strpos($output, '{');
            $lastBrace = strrpos($output, '}');
            if ($firstBrace !== false && $lastBrace !== false && $lastBrace >= $firstBrace) {
                $output = substr($output, $firstBrace, ($lastBrace - $firstBrace + 1));
            }
        }

        unset($_GET['return_json'], $_GET['json']);

        $result = json_decode($output,true);

        if (!$result || !isset($result['success'])) {
            if (isset($result['transaksi_id']) && $result['transaksi_id'] > 0) {
                return array(
                    "success" => true,
                    "transaksi_id" => (int)$_SESSION[$cCode]['main']['transaksi_id'],
                    "nomer" => isset($_SESSION[$cCode]['main']['nomer']) ? $_SESSION[$cCode]['main']['nomer'] : "",
                );
            }
            return array("success" => false, "error" => "LINE: ".__LINE__." | save output not valid JSON: " . substr($output, 0, 500));
        }

        if ($result['success']) {
            return array(
                "success" => true,
                "transaksi_id" => isset($result['transaksi_id']) ? (int)$result['transaksi_id'] : 0,
                "nomer" => isset($result['nomer']) ? $result['nomer'] : "",
            );
        }

        return array("success" => false, "error" => isset($result['error']) ? $result['error'] : "unknown error");
    }
}
