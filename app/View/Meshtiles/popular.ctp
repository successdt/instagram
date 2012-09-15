<?php
/**
 * @author duythanh
 * @copyright 2012
 */

/**
 * Nếu không tồn tại SESSION access token thì phải đăng nhập
 *
 */

if (!$session) {
    $instagram = new Instagram($config->cfg);
    $instagram->openAuthorizationUrl();
}
/**
 * Nếu tồn tại rồi thì bắt đầu lấy dữ liệu
 */

?>
<!-- ligthbox script -->
<script type="text/javascript">
    $(document).ready(function(){
       $('.thumbnail').click(function(){        
            var id=$(this).attr('id');                
            $('#lightbox ,.preview_wrapper').show('slow');
            $('.preview').load('<?php echo $this->webroot; ?>meshtiles/photo/'+id);                     
       });
       $('#lightbox').click(function(){
            $('#lightbox ,.preview_wrapper').hide('slow');
            $('.preview').html('');
       }) 
    });
</script>
<!-- lazyload script -->       
    <script type="text/javascript">
    var load;
    load=true;
    $(window).scroll(function()
    {     
        if(($(window).scrollTop()+$(window).height()>($(document).height() -400 ))&&load)
        {
            load=false;
            lazyload(); 
        }
    });
    </script>
</div><!-- /lazyload--> 

<script type="text/javascript">
    function lazyload(){
        $('#loading').show();
        $.ajax({
        url: "<?php echo $this->webroot; ?>meshtiles/loadpopular/<?php echo $tags ?>",
        success: function(html)
        {
            if(html)
            {                   
                $("#content").append(html);
                $('#loading').hide();
                load=true; 
                prettyTime();
                facebook();
                twitter();
            }else
            {
                $('#content').html('<center>No more posts to show.</center>');
                //alert('Error while loading next page');
            }
        }
        });
    }
    $(document).ready(function(){
        lazyload();
    });
</script>

