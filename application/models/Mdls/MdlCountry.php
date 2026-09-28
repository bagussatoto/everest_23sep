<?php

//--include_once "MdlHistoriData.php";

class MdlCountry extends MdlMother
{
    protected $tableName = "static";
    protected $indexFields = "id";


    protected $listedFieldsForm = array();
    protected $listedFieldsHidden = array();
    protected $search;
    protected $filters = array(
        "jenis='payment'",
        "status='1'",
        "trash='0'",
    );

    protected $validationRules = array(
        "nama" => array("required", "singleOnly"),

    );

    protected $listedFieldsView = array("nama");
    protected $fields = array(
        "id"   => array(
            "label"     => "id",
            "type"      => "int", "length" => "24", "kolom" => "id",
            "inputType" => "hidden",// hidden
            //--"inputName" => "id",
        ),
        "name" => array(
            "label"     => "name",
            "type"      => "int", "length" => "24", "kolom" => "name",
            "inputType" => "text",
        ),


    );
    protected $staticData=array(


                array( "id"=>"AF",
                      "name"=>"Afghanistan",
               ),
                array( "id"=>"AX",
                         "name"=>"Aland Islands",
               ), array( "id"=>"AL",
                         "name"=>"Albania",
               ), array( "id"=>"DZ",
                         "name"=>"Algeria",
               ), array( "id"=>"AS",
                         "name"=>"American Samoa",
               ), array( "id"=>"AD",
                         "name"=>"Andorra",
               ), array( "id"=>"AO",
                         "name"=>"Angola",
               ), array( "id"=>"AI",
                         "name"=>"Anguilla",
               ), array( "id"=>"AQ",
                         "name"=>"Antarctica",
               ), array( "id"=>"AG",
                         "name"=>"Antigua And Barbuda",
               ), array( "id"=>"AR",
                         "name"=>"Argentina",
               ), array( "id"=>"AM",
                         "name"=>"Armenia",
               ), array( "id"=>"AW",
                         "name"=>"Aruba",
               ), array( "id"=>"AU",
                         "name"=>"Australia",
               ), array( "id"=>"AT",
                         "name"=>"Austria",
               ), array( "id"=>"AZ",
                         "name"=>"Azerbaijan",
               ), array( "id"=>"BS",
                         "name"=>"Bahamas",
               ), array( "id"=>"BH",
                         "name"=>"Bahrain",
               ), array( "id"=>"BD",
                         "name"=>"Bangladesh",
               ), array( "id"=>"BB",
                         "name"=>"Barbados",
               ), array( "id"=>"BY",
                         "name"=>"Belarus",
               ), array( "id"=>"BE",
                         "name"=>"Belgium",
               ), array( "id"=>"BZ",
                         "name"=>"Belize",
               ), array( "id"=>"BJ",
                         "name"=>"Benin",
               ), array( "id"=>"BM",
                         "name"=>"Bermuda",
               ), array( "id"=>"BT",
                         "name"=>"Bhutan",
               ), array( "id"=>"BO",
                         "name"=>"Bolivia",
               ), array( "id"=>"BA",
                         "name"=>"Bosnia And Herzegovina",
               ), array( "id"=>"BW",
                         "name"=>"Botswana",
               ), array( "id"=>"BV",
                         "name"=>"Bouvet Island",
               ), array( "id"=>"BR",
                         "name"=>"Brazil",
               ), array( "id"=>"IO",
                         "name"=>"British Indian Ocean Territory",
               ), array( "id"=>"BN",
                         "name"=>"Brunei Darussalam",
               ), array( "id"=>"BG",
                         "name"=>"Bulgaria",
               ), array( "id"=>"BF",
                         "name"=>"Burkina Faso",
               ), array( "id"=>"BI",
                         "name"=>"Burundi",
               ), array( "id"=>"KH",
                         "name"=>"Cambodia",
               ), array( "id"=>"CM",
                         "name"=>"Cameroon",
               ), array( "id"=>"CA",
                         "name"=>"Canada",
               ), array( "id"=>"CV",
                         "name"=>"Cape Verde",
               ), array( "id"=>"KY",
                         "name"=>"Cayman Islands",
               ), array( "id"=>"CF",
                         "name"=>"Central African Republic",
               ), array( "id"=>"TD",
                         "name"=>"Chad",
               ), array( "id"=>"CL",
                         "name"=>"Chile",
               ), array( "id"=>"CN",
                         "name"=>"China",
               ), array( "id"=>"CX",
                         "name"=>"Christmas Island",
               ), array( "id"=>"CC",
                         "name"=>"Cocos (Keeling) Islands",
               ), array( "id"=>"CO",
                         "name"=>"Colombia",
               ), array( "id"=>"KM",
                         "name"=>"Comoros",
               ), array( "id"=>"CG",
                         "name"=>"Congo",
               ), array( "id"=>"CD",
                         "name"=>"Congo, Democratic Republic",
               ), array( "id"=>"CK",
                         "name"=>"Cook Islands",
               ), array( "id"=>"CR",
                         "name"=>"Costa Rica",
               ), array( "id"=>"CI",
                         "name"=>"Cote D-Ivoire",
               ), array( "id"=>"HR",
                         "name"=>"Croatia",
               ), array( "id"=>"CU",
                         "name"=>"Cuba",
               ), array( "id"=>"CY",
                         "name"=>"Cyprus",
               ), array( "id"=>"CZ",
                         "name"=>"Czech Republic",
               ), array( "id"=>"DK",
                         "name"=>"Denmark",
               ), array( "id"=>"DJ",
                         "name"=>"Djibouti",
               ), array( "id"=>"DM",
                         "name"=>"Dominica",
               ), array( "id"=>"DO",
                         "name"=>"Dominican Republic",
               ), array( "id"=>"EC",
                         "name"=>"Ecuador",
               ), array( "id"=>"EG",
                         "name"=>"Egypt",
               ), array( "id"=>"SV",
                         "name"=>"El Salvador",
               ), array( "id"=>"GQ",
                         "name"=>"Equatorial Guinea",
               ), array( "id"=>"ER",
                         "name"=>"Eritrea",
               ), array( "id"=>"EE",
                         "name"=>"Estonia",
               ), array( "id"=>"ET",
                         "name"=>"Ethiopia",
               ), array( "id"=>"FK",
                         "name"=>"Falkland Islands (Malvinas)",
               ), array( "id"=>"FO",
                         "name"=>"Faroe Islands",
               ), array( "id"=>"FJ",
                         "name"=>"Fiji",
               ), array( "id"=>"FI",
                         "name"=>"Finland",
               ), array( "id"=>"FR",
                         "name"=>"France",
               ), array( "id"=>"GF",
                         "name"=>"French Guiana",
               ), array( "id"=>"PF",
                         "name"=>"French Polynesia",
               ), array( "id"=>"TF",
                         "name"=>"French Southern Territories",
               ), array( "id"=>"GA",
                         "name"=>"Gabon",
               ), array( "id"=>"GM",
                         "name"=>"Gambia",
               ), array( "id"=>"GE",
                         "name"=>"Georgia",
               ), array( "id"=>"DE",
                         "name"=>"Germany",
               ), array( "id"=>"GH",
                         "name"=>"Ghana",
               ), array( "id"=>"GI",
                         "name"=>"Gibraltar",
               ), array( "id"=>"GR",
                         "name"=>"Greece",
               ), array( "id"=>"GL",
                         "name"=>"Greenland",
               ), array( "id"=>"GD",
                         "name"=>"Grenada",
               ), array( "id"=>"GP",
                         "name"=>"Guadeloupe",
               ), array( "id"=>"GU",
                         "name"=>"Guam",
               ), array( "id"=>"GT",
                         "name"=>"Guatemala",
               ), array( "id"=>"GG",
                         "name"=>"Guernsey",
               ), array( "id"=>"GN",
                         "name"=>"Guinea",
               ), array( "id"=>"GW",
                         "name"=>"Guinea-Bissau",
               ), array( "id"=>"GY",
                         "name"=>"Guyana",
               ), array( "id"=>"HT",
                         "name"=>"Haiti",
               ), array( "id"=>"HM",
                         "name"=>"Heard Island & Mcdonald Islands",
               ), array( "id"=>"VA",
                         "name"=>"Holy See (Vatican City State)",
               ), array( "id"=>"HN",
                         "name"=>"Honduras",
               ), array( "id"=>"HK",
                         "name"=>"Hong Kong",
               ), array( "id"=>"HU",
                         "name"=>"Hungary",
               ), array( "id"=>"IS",
                         "name"=>"Iceland",
               ), array( "id"=>"IN",
                         "name"=>"India",
               ), array( "id"=>"ID",
                         "name"=>"Indonesia",
               ), array( "id"=>"IR",
                         "name"=>"Iran, Islamic Republic Of",
               ), array( "id"=>"IQ",
                         "name"=>"Iraq",
               ), array( "id"=>"IE",
                         "name"=>"Ireland",
               ), array( "id"=>"IM",
                         "name"=>"Isle Of Man",
               ), array( "id"=>"IL",
                         "name"=>"Israel",
               ), array( "id"=>"IT",
                         "name"=>"Italy",
               ), array( "id"=>"JM",
                         "name"=>"Jamaica",
               ), array( "id"=>"JP",
                         "name"=>"Japan",
               ), array( "id"=>"JE",
                         "name"=>"Jersey",
               ), array( "id"=>"JO",
                         "name"=>"Jordan",
               ), array( "id"=>"KZ",
                         "name"=>"Kazakhstan",
               ), array( "id"=>"KE",
                         "name"=>"Kenya",
               ), array( "id"=>"KI",
                         "name"=>"Kiribati",
               ), array( "id"=>"KR",
                         "name"=>"Korea",
               ), array( "id"=>"KW",
                         "name"=>"Kuwait",
               ), array( "id"=>"KG",
                         "name"=>"Kyrgyzstan",
               ), array( "id"=>"LA",
                         "name"=>"Lao People-s Democratic Republic",
               ), array( "id"=>"LV",
                         "name"=>"Latvia",
               ), array( "id"=>"LB",
                         "name"=>"Lebanon",
               ), array( "id"=>"LS",
                         "name"=>"Lesotho",
               ), array( "id"=>"LR",
                         "name"=>"Liberia",
               ), array( "id"=>"LY",
                         "name"=>"Libyan Arab Jamahiriya",
               ), array( "id"=>"LI",
                         "name"=>"Liechtenstein",
               ), array( "id"=>"LT",
                         "name"=>"Lithuania",
               ), array( "id"=>"LU",
                         "name"=>"Luxembourg",
               ), array( "id"=>"MO",
                         "name"=>"Macao",
               ), array( "id"=>"MK",
                         "name"=>"Macedonia",
               ), array( "id"=>"MG",
                         "name"=>"Madagascar",
               ), array( "id"=>"MW",
                         "name"=>"Malawi",
               ), array( "id"=>"MY",
                         "name"=>"Malaysia",
               ), array( "id"=>"MV",
                         "name"=>"Maldives",
               ), array( "id"=>"ML",
                         "name"=>"Mali",
               ), array( "id"=>"MT",
                         "name"=>"Malta",
               ), array( "id"=>"MH",
                         "name"=>"Marshall Islands",
               ), array( "id"=>"MQ",
                         "name"=>"Martinique",
               ), array( "id"=>"MR",
                         "name"=>"Mauritania",
               ), array( "id"=>"MU",
                         "name"=>"Mauritius",
               ), array( "id"=>"YT",
                         "name"=>"Mayotte",
               ), array( "id"=>"MX",
                         "name"=>"Mexico",
               ), array( "id"=>"FM",
                         "name"=>"Micronesia, Federated States Of",
               ), array( "id"=>"MD",
                         "name"=>"Moldova",
               ), array( "id"=>"MC",
                         "name"=>"Monaco",
               ), array( "id"=>"MN",
                         "name"=>"Mongolia",
               ), array( "id"=>"ME",
                         "name"=>"Montenegro",
               ), array( "id"=>"MS",
                         "name"=>"Montserrat",
               ), array( "id"=>"MA",
                         "name"=>"Morocco",
               ), array( "id"=>"MZ",
                         "name"=>"Mozambique",
               ), array( "id"=>"MM",
                         "name"=>"Myanmar",
               ), array( "id"=>"NA",
                         "name"=>"Namibia",
               ), array( "id"=>"NR",
                         "name"=>"Nauru",
               ), array( "id"=>"NP",
                         "name"=>"Nepal",
               ), array( "id"=>"NL",
                         "name"=>"Netherlands",
               ), array( "id"=>"AN",
                         "name"=>"Netherlands Antilles",
               ), array( "id"=>"NC",
                         "name"=>"New Caledonia",
               ), array( "id"=>"NZ",
                         "name"=>"New Zealand",
               ), array( "id"=>"NI",
                         "name"=>"Nicaragua",
               ), array( "id"=>"NE",
                         "name"=>"Niger",
               ), array( "id"=>"NG",
                         "name"=>"Nigeria",
               ), array( "id"=>"NU",
                         "name"=>"Niue",
               ), array( "id"=>"NF",
                         "name"=>"Norfolk Island",
               ), array( "id"=>"MP",
                         "name"=>"Northern Mariana Islands",
               ), array( "id"=>"NO",
                         "name"=>"Norway",
               ), array( "id"=>"OM",
                         "name"=>"Oman",
               ), array( "id"=>"PK",
                         "name"=>"Pakistan",
               ), array( "id"=>"PW",
                         "name"=>"Palau",
               ), array( "id"=>"PS",
                         "name"=>"Palestinian Territory, Occupied",
               ), array( "id"=>"PA",
                         "name"=>"Panama",
               ), array( "id"=>"PG",
                         "name"=>"Papua New Guinea",
               ), array( "id"=>"PY",
                         "name"=>"Paraguay",
               ), array( "id"=>"PE",
                         "name"=>"Peru",
               ), array( "id"=>"PH",
                         "name"=>"Philippines",
               ), array( "id"=>"PN",
                         "name"=>"Pitcairn",
               ), array( "id"=>"PL",
                         "name"=>"Poland",
               ), array( "id"=>"PT",
                         "name"=>"Portugal",
               ), array( "id"=>"PR",
                         "name"=>"Puerto Rico",
               ), array( "id"=>"QA",
                         "name"=>"Qatar",
               ), array( "id"=>"RE",
                         "name"=>"Reunion",
               ), array( "id"=>"RO",
                         "name"=>"Romania",
               ), array( "id"=>"RU",
                         "name"=>"Russian Federation",
               ), array( "id"=>"RW",
                         "name"=>"Rwanda",
               ), array( "id"=>"BL",
                         "name"=>"Saint Barthelemy",
               ), array( "id"=>"SH",
                         "name"=>"Saint Helena",
               ), array( "id"=>"KN",
                         "name"=>"Saint Kitts And Nevis",
               ), array( "id"=>"LC",
                         "name"=>"Saint Lucia",
               ), array( "id"=>"MF",
                         "name"=>"Saint Martin",
               ), array( "id"=>"PM",
                         "name"=>"Saint Pierre And Miquelon",
               ), array( "id"=>"VC",
                         "name"=>"Saint Vincent And Grenadines",
               ), array( "id"=>"WS",
                         "name"=>"Samoa",
               ), array( "id"=>"SM",
                         "name"=>"San Marino",
               ), array( "id"=>"ST",
                         "name"=>"Sao Tome And Principe",
               ), array( "id"=>"SA",
                         "name"=>"Saudi Arabia",
               ), array( "id"=>"SN",
                         "name"=>"Senegal",
               ), array( "id"=>"RS",
                         "name"=>"Serbia",
               ), array( "id"=>"SC",
                         "name"=>"Seychelles",
               ), array( "id"=>"SL",
                         "name"=>"Sierra Leone",
               ), array( "id"=>"SG",
                         "name"=>"Singapore",
               ), array( "id"=>"SK",
                         "name"=>"Slovakia",
               ), array( "id"=>"SI",
                         "name"=>"Slovenia",
               ), array( "id"=>"SB",
                         "name"=>"Solomon Islands",
               ), array( "id"=>"SO",
                         "name"=>"Somalia",
               ), array( "id"=>"ZA",
                         "name"=>"South Africa",
               ), array( "id"=>"GS",
                         "name"=>"South Georgia And Sandwich Isl.",
               ), array( "id"=>"ES",
                         "name"=>"Spain",
               ), array( "id"=>"LK",
                         "name"=>"Sri Lanka",
               ), array( "id"=>"SD",
                         "name"=>"Sudan",
               ), array( "id"=>"SR",
                         "name"=>"Suriname",
               ), array( "id"=>"SJ",
                         "name"=>"Svalbard And Jan Mayen",
               ), array( "id"=>"SZ",
                         "name"=>"Swaziland",
               ), array( "id"=>"SE",
                         "name"=>"Sweden",
               ), array( "id"=>"CH",
                         "name"=>"Switzerland",
               ), array( "id"=>"SY",
                         "name"=>"Syrian Arab Republic",
               ), array( "id"=>"TW",
                         "name"=>"Taiwan",
               ), array( "id"=>"TJ",
                         "name"=>"Tajikistan",
               ), array( "id"=>"TZ",
                         "name"=>"Tanzania",
               ), array( "id"=>"TH",
                         "name"=>"Thailand",
               ), array( "id"=>"TL",
                         "name"=>"Timor-Leste",
               ), array( "id"=>"TG",
                         "name"=>"Togo",
               ), array( "id"=>"TK",
                         "name"=>"Tokelau",
               ), array( "id"=>"TO",
                         "name"=>"Tonga",
               ), array( "id"=>"TT",
                         "name"=>"Trinidad And Tobago",
               ), array( "id"=>"TN",
                         "name"=>"Tunisia",
               ), array( "id"=>"TR",
                         "name"=>"Turkey",
               ), array( "id"=>"TM",
                         "name"=>"Turkmenistan",
               ), array( "id"=>"TC",
                         "name"=>"Turks And Caicos Islands",
               ), array( "id"=>"TV",
                         "name"=>"Tuvalu",
               ), array( "id"=>"UG",
                         "name"=>"Uganda",
               ), array( "id"=>"UA",
                         "name"=>"Ukraine",
               ), array( "id"=>"AE",
                         "name"=>"United Arab Emirates",
               ), array( "id"=>"GB",
                         "name"=>"United Kingdom",
               ), array( "id"=>"US",
                         "name"=>"United States",
               ), array( "id"=>"UM",
                         "name"=>"United States Outlying Islands",
               ), array( "id"=>"UY",
                         "name"=>"Uruguay",
               ), array( "id"=>"UZ",
                         "name"=>"Uzbekistan",
               ), array( "id"=>"VU",
                         "name"=>"Vanuatu",
               ), array( "id"=>"VE",
                         "name"=>"Venezuela",
               ), array( "id"=>"VN",
                         "name"=>"Viet Nam",
               ), array( "id"=>"VG",
                         "name"=>"Virgin Islands, British",
               ), array( "id"=>"VI",
                         "name"=>"Virgin Islands, U.S.",
               ), array( "id"=>"WF",
                         "name"=>"Wallis And Futuna",
               ), array( "id"=>"EH",
                         "name"=>"Western Sahara",
               ), array( "id"=>"YE",
                         "name"=>"Yemen",
               ), array( "id"=>"ZM",
                         "name"=>"Zambia",
               ), array( "id"=>"ZW",
                         "name"=>"Zimbabwe",
               )
    );




    protected $listedFields = array(
        "nama"     => "name",
        "due_days" => "due days",
        "status"   => "status",

    );

    public function __construct()
    {

    }

    //region gs

    public function getTableName()
    {
        return $this->tableName;
    }

    public function setTableName($tableName)
    {
        $this->tableName = $tableName;
    }

    public function getIndexFields()
    {
        return $this->indexFields;
    }

    public function setIndexFields($indexFields)
    {
        $this->indexFields = $indexFields;
    }

    public function getListedFieldsForm()
    {
        return $this->listedFieldsForm;
    }

    public function setListedFieldsForm($listedFieldsForm)
    {
        $this->listedFieldsForm = $listedFieldsForm;
    }

    public function getListedFieldsHidden()
    {
        return $this->listedFieldsHidden;
    }

    public function setListedFieldsHidden($listedFieldsHidden)
    {
        $this->listedFieldsHidden = $listedFieldsHidden;
    }

    public function getSearch()
    {
        return $this->search;
    }

    public function setSearch($search)
    {
        $this->search = $search;
    }

    public function getFilters()
    {
        return $this->filters;
    }

    public function setFilters($filters)
    {
        $this->filters = $filters;
    }

    public function getValidationRules()
    {
        return $this->validationRules;
    }

    public function setValidationRules($validationRules)
    {
        $this->validationRules = $validationRules;
    }

    public function getListedFieldsView()
    {
        return $this->listedFieldsView;
    }

    public function setListedFieldsView($listedFieldsView)
    {
        $this->listedFieldsView = $listedFieldsView;
    }

    public function getFields()
    {
        return $this->fields;
    }

    public function setFields($fields)
    {
        $this->fields = $fields;
    }

    public function getListedFields()
    {
        return $this->listedFields;
    }

    public function setListedFields($listedFields)
    {
        $this->listedFields = $listedFields;
    }
    //endregion


    //@override with static data
    public function lookupAll()
    {
//        arrprint($this->staticData);
        if(isset($this->staticData) && is_array($this->staticData) && sizeof($this->staticData)>0){
//            cekkuning("ada isinya");
            $iCtr=0;
            $sql="";
//			arrprint($this->filters);
            foreach($this->staticData as $iSpec){
                $iCtr++;
                $sql .= 'SELECT ';
                $fCtr=0;
                foreach($this->fields as $fID=>$fSpec){
                    $fCtr++;
                    $sql .= "'".$iSpec[$fID]."' as $fID";
                    if($fCtr<sizeof($this->fields)){
                        $sql.=",";
                    }
                }
                if($iCtr<sizeof($this->staticData)){
                    $sql.=" union ";
                }
            }
//            cekkuning($sql);
            return $this->db->query($sql);
        }else{
//            cekkuning("TIDAK ada isinya");
            return null;
        }

    }

}