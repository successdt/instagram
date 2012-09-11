<div class="follow-lazyload">
<script>
    function followload(){
        $('#loading').show();
        $.ajax({
        url: "<?php echo $this->webroot; ?>instagrams/loaduserfollowedby/<?php echo $id ?>",
        success: function(html)
        {
            if(html)
            {
                $('#loading').hide();
                $("#follows").append(html);
                $('.loadmore').show();
            }else
            {
                $('#follows').html('<center>No more posts to show.</center>');
                //alert('Error while loading next page');
            }
        }
        });
    }
    $(document).ready(function(){
        followload();
        $(function(){
          $('#follows').masonry({
                itemSelector : '.mini-block',
                //columnWidth : 225
            });
        }); 
        $('#follow-loadmore').click(function(){
            followload();
        });
    });
</script>
</div>
<div id="follows">

</div>
<div class="loadmore" style="display: none;">
    <button class="white follow" id="follow-loadmore">Load more</button>
</div>
