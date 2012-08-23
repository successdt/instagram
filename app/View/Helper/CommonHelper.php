<?php
class CommonHelper extends HtmlHelper
{
    function general()
    {
        $data = array("header" => "MyBlog", "footer" => "Copyright &copy;Duy Thanh Dao");
        return $data;
    }
    function topmenu()
    {
        $data = array(
            "space" => "<td class='space'></td>",
            "SanPham" => "<td > <div id='Products'>Sản phẩm </div></td>",
            "LienHe" => "<td><a href='index.php?action=contact'> Liên hệ </a></td>",
            "TinTuc" => "<td><a href='index.php?action=news'> Tin tức </a></td>",
            "KhuyenMai" => "<td><a href='index.php?action=promotion'>Khuyến mãi</a></td>",
            "ThuVienAnh" => "<td><a href='index.php?action=gallery'> Thư viện ảnh</a>  </td> ");
        return $data;
    }
    function date_find()
    {
        $date = array(
            '+07:00',
            'GMT',
            'Mon,',
            'Tue,',
            'Wed,',
            'Thu,',
            'Fri,',
            'Sat,',
            'Sun,');
        return $date;
    }

}
?>