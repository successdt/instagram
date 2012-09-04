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
            $('.preview').load('<?php echo $this->webroot; ?>instagrams/photo/'+id);                     
       });
       $('#lightbox').click(function(){
            $('#lightbox ,.preview_wrapper').hide('slow');
            $('.preview').html('');
       }) 
    });
</script>
<div class="lazyload">
<!-- lazyload script -->       
    <script type="text/javascript">
    $(window).scroll(function()
    {            
        if($(window).scrollTop() == $(document).height() - $(window).height())
        {
    
            lazyload();
        }
    });
    
    </script>
</div><!-- /lazyload--> 


<script type="text/javascript">
    function lazyload(){
        $('#loading').show();
        $.ajax({
        url: "<?php echo $this->webroot; ?>instagrams/lazyload/<?php echo $tags ?>",
        success: function(html)
        {
            if(html)
            {      
                $("#content").append(html);
                $('#loading').hide();
                prettyTime();
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

