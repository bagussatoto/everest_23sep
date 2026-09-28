<?php defined('BASEPATH') OR exit('No direct script access allowed');

/**
 * CodeIgniter Curl Class
 *
 * Work with remote servers via cURL much easier than using the native PHP bindings.
 *
 * @package            CodeIgniter
 * @subpackage         Libraries
 * @category           Libraries
 * @author             Philip Sturgeon
 * @license            http://philsturgeon.co.uk/code/dbad-license
 * @link               http://philsturgeon.co.uk/code/codeigniter-curl
 */
class Pagination
{
    protected $total_pages;

    public function getTotalPages()
    {
        return $this->total_pages;
    }

    public function setTotalPages($total_pages)
    {
        $this->total_pages = $total_pages;
    }

    protected $page;
    protected $limit;

    public function getPage()
    {
        return $this->page;
    }

    public function setPages($page)
    {
        $this->page = $page;
    }

    public function getLimit()
    {
        return $this->limit;
    }

    public function setLimit($limit)
    {
        $this->limit = $limit;
    }

    protected $extra_href;

    public function getExtraHref()
    {
        return $this->extra_href;
    }

    public function setExtraHref($extra_href)
    {
        $this->extra_href = $extra_href;
    }

    protected $web_url_page;

    public function getWebUrlPage()
    {
        return $this->web_url_page;
    }

    public function setWebUrlPage($web_url_page)
    {
        $this->web_url_page = $web_url_page;
    }

    protected $loaderPages;

    public function getLoaderPages()
    {
        return $this->loaderPages;
    }

    public function setLoaderPages($loaderPages)
    {
        $this->loaderPages = $loaderPages;
    }

    // fungsi pengaturan/option $this->blabla = "value nya blabla"
    function setOption($field, $value)
    {
        $this->$field = $value;
    }
    // fungsi paginasi generate array berupa total jumlah halaman, pagination, data dan posisi start
    // berguna untuk diatur secara fleksible terutama untuk berbasis menggunakan template
    function build()
    {
        // SETUP
        // $tabel = $this->tabel;
        // $where = $this->where;
        $limit = $this->limit;
        // $order = $this->order;

        $loader_id = "";
        if (isset($this->loaderPages)) {

            $loaderPages = isset($this->loaderPages) ? $this->loaderPages : "";
            $loader_id = isset($loaderPages['id']) ? $loaderPages['id'] : "";
            $loader_link = $loaderPages['link'];
            $this->web_url_page = $loader_link;
        }

        $page = $this->page;
        $extra_href = $this->extra_href;

        // SETUP OPTIONAL
        if (!isset($this->web_url_page)) {
            $web_url_page = "?page=";
        }
        else {
            $web_url_page = $this->web_url_page;
        }
        if (!isset($this->adjacents)) {
            $adjacents = "3";
        }
        else {
            $adjacents = $this->adjacents;
        }
        if (!isset($this->txt_prev)) {
            $txt_prev = "&laquo; prev";
        }
        else {
            $txt_prev = $this->txt_prev;
        }
        if (!isset($this->txt_next)) {
            $txt_next = "next &raquo;";
        }
        else {
            $txt_next = $this->txt_next;
        }
        if (!isset($this->txt_titik)) {
            $txt_titik = "...";
        }
        else {
            $txt_titik = $this->txt_titik;
        }
        $total_pages = $this->total_pages;
        // cekHere("$total_pages adjacents: $adjacents | page: $page");

        /* Setup page vars for display. */
        if ($page == 0) $page = 1;                    //if no page var is given, default to 1.
        $prev = $page - 1;                            //previous page is page - 1
        $next = $page + 1;                            //next page is page + 1
        $lastpage = ceil($total_pages / $limit);        //lastpage is = total pages / items per page, rounded up.
        $lpm1 = $lastpage - 1;                        //last page minus 1

        // cekHere("$total_pages / $limit ===== $lastpage");
        /**
            Now we apply our rules and draw the pagination object.
            We're actually saving the code to a variable in case we want to draw it more than once.
        */
        $pagination = "";
        if ($lastpage > 1) {
            // $pagination .= "<div class=\"pagination\">";
            $pagination .= "<ul class='pagination modal-1'>";
            //previous button
            if ($page > 1) {
                $link_prev = $web_url_page . $prev . "" . $extra_href;

                if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_prev', '$loader_id')\">" . $txt_prev . "</a></li>";
                }
                else {
                    $pagination .= "<li><a href=\"" . $link_prev . "\">" . $txt_prev . "</a></li>";
                }
            }
            else {
                $pagination .= "<li><span class=\"disabled-prev\">" . $txt_prev . "</span></li>";
            }
            //pages
            if ($lastpage < 7 + ($adjacents * 2)) {
                //not enough pages to bother breaking it up
                for ($counter = 1; $counter <= $lastpage; $counter++) {
                    if ($counter == $page) {
                        $pagination .= "<li><span class=\"current\">" . $counter . "</span></li>";
                    }
                    else {
                        $link_counter = $web_url_page . $counter . "" . $extra_href;

                        if (isset($this->loaderPages)) {
                        $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_counter', '$loader_id')\">" . $counter . "</a></li>";
                        }
                        else {
                            $pagination .= "<li><a href=\"" . $link_counter . "\">" . $counter . "</a></li>";
                        }

                    }
                }
            }
            elseif ($lastpage > 5 + ($adjacents * 2)) {
                //enough pages to hide some
                if ($page < 1 + ($adjacents * 2)) {
                    //close to beginning; only hide later pages
                    for ($counter = 1; $counter < 4 + ($adjacents * 2); $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<li><span class=\"current\">" . $counter . "</span></li>";
                            // $pagination .= "<li class=\"current\">" . $counter . "///</li>";
                        }
                        else {
                            $link_counter = $web_url_page . $counter . "" . $extra_href;

                            if (isset($this->loaderPages)) {
                            $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_counter', '$loader_id')\">" . $counter . "</a></li>";
                        }
                            else {
                                $pagination .= "<li><a href=\"" . $web_url_page . $counter . "" . $extra_href . "\">$counter</a></li>";
                            }
                        }
                    }
                    $pagination .= "<li><span class='spasi'>$txt_titik</span></li>";
                    $link_lpm1 = $web_url_page . $lpm1 . "" . $extra_href;

                    if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_lpm1', '$loader_id')\">" . $lpm1 . "</a></li>";
                    }
                    else {
                        $pagination .= "<li><a href=\"" . $link_lpm1 . "\">" . $lpm1 . "</a></li>";
                    }
                    // ---------------------------------------------------------------------------------------------------------------------------
                    $link_lastpage = $web_url_page . $lastpage . "" . $extra_href;
                    if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_lastpage', '$loader_id')\">" . $lastpage . "</a></li>";
                }
                    else {
                        $pagination .= "<li><a href=\"" . $link_lastpage . "\">" . $lastpage . "</a></li>";
                    }
                }
                elseif ($lastpage - ($adjacents * 2) > $page && $page > ($adjacents * 2)) {
                    //in middle; hide some front and some back
                    $link_1 = $web_url_page . "1" . $extra_href;
                    if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_1', '$loader_id')\">1</a></li>";
                    }
                    else {
                        $pagination .= "<li><a href=\"" . $web_url_page . "1" . $extra_href . "\">1</a></li>";
                    }
                    // ------------------------------------------------------------------------------------------------------------------
                    $link_2 = $web_url_page . "2" . $extra_href;
                    if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_2', '$loader_id')\">2</a></li>";
                    }
                    else {
                        $pagination .= "<li><a href=\"" . $web_url_page . "2" . $extra_href . "\">2</a></li>";
                    }
                    // -------------------------------------------------------------------------------------------------------------------------
                    $pagination .= "<li><span class='spasi'>$txt_titik</span></li>";
                    for ($counter = $page - $adjacents; $counter <= $page + $adjacents; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<li><span class=\"current\">" . $counter . "</span></li>";
                        }
                        else {
                            $link_counter = $web_url_page . $counter . "" . $extra_href;
                            if (isset($this->loaderPages)) {
                            $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_counter', '$loader_id')\">$counter</a></li>";
                        }
                            else {
                                $pagination .= "<li><a href=\"" . $link_counter . "\">" . $counter . "</a></li>";
                            }
                        }
                    }
                    $pagination .= "<li><span class='spasi'>$txt_titik</span></li>";
                    $link_lpm1 = $web_url_page . $lpm1 . "" . $extra_href;
                    if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_lpm1', '$loader_id')\">" . $lpm1 . "</a></li>";
                    }
                    else {
                        $pagination .= "<li><a href=\"" . $link_lpm1 . "\">" . $lpm1 . "</a></li>";
                    }
                    // ----------------------------------------------------------------------------------------------------------------------------
                    $link_lastpage = $web_url_page . $lastpage . "" . $extra_href;
                    if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_lastpage', '$loader_id')\">" . $lastpage . "</a></li>";
                }
                    else {
                        $pagination .= "<li><a href=\"" . $link_lastpage . "\">" . $lastpage . "</a></li>";
                    }
                }
                else {
                    //close to end; only hide early pages
                    $link_1 = $web_url_page . "1" . $extra_href;
                    if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_1', '$loader_id')\">1</a></li>";
                    }
                    else {
                        $pagination .= "<li><a href=\"" . $link_1 . "\">1</a></li>";
                    }
                    // -----------------------------------------------------------------------------------------------------------------
                    $link_2 = $web_url_page . "2" . $extra_href;
                    if (isset($this->loaderPages)) {
                    $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_2', '$loader_id')\">2</a></li>";
                    }
                    else {
                        $pagination .= "<li><a href=\"" . $link_2 . "\">2</a></li>";
                    }
                    // -------------------------------------------------------------------------------------------------------------------------
                    $pagination .= "<li><span class='spasi'>$txt_titik</span></li>";
                    for ($counter = $lastpage - (2 + ($adjacents * 2)); $counter <= $lastpage; $counter++) {
                        if ($counter == $page) {
                            $pagination .= "<li><span class=\"current\">" . $counter . "</span></li>";
                        }
                        else {
                            $link_counter = $web_url_page . $counter . "" . $extra_href;
                            if (isset($this->loaderPages)) {
                            $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_counter', '$loader_id')\">$counter</a></li>";
                        }
                            else {
                                $pagination .= "<li><a href=\"" . $link_counter . "\">" . $counter . "</a></li>";
                            }
                        }
                    }
                }
            }

            // cekHere("$page < $counter");
            //next button
            if ($page < $counter - 1) {
                $link_txt_next = $web_url_page . $next . "" . $extra_href;
                if (isset($this->loaderPages)) {
                $pagination .= "<li><a href='javascript:void(0)' onclick=\"loadPaging('$link_txt_next', '$loader_id')\">$txt_next</a></li>";
            }
                else {
                    $pagination .= "<li><a href=\"" . $link_txt_next . "\">" . $txt_next . "</a></li>";
                }
            }
            else {
                $pagination .= "<li><span class=\"disabled-next\">" . $txt_next . "</span></li>";

                // $pagination .= "</div>\n"; asli
            }
            $pagination .= "</ul>";
            if (isset($this->loaderPages)) {
            $onclickFungsi = "<script>
                function loadPaging(link,id = '') {
                    open_holdon();
                  if(id == ''){
                    location.href=link;
                  }
                  else {
                      $('#'+id).load(link);
                  }
                }
            </script>";
        }
            else {
                $onclickFungsi = "";
            }
        }
        // hasil dari fungsi build()
        return array("pagination" => $pagination,
                     "total"      => number_format($total_pages),
                     "page"       => $page,
                     "counter"    => isset($counter) ? $counter : null,
                     "lastpage"   => $lastpage,
            // "onclickFungsi"    => $onclickFungsi,
            // "start"      => $start
        );
    }
}
