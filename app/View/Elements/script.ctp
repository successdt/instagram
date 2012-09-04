<script>
$(function(){
      $('#content').masonry({
        // options
            itemSelector : '.display-block',
            columnWidth : 252
      });
    });                
</script>
<script>
    $(document).ready(function(){
        $('#show-hide-sidebar').toggle(function(){
            $('#sidebar').hide();
            $('#top-nav').css('margin-left','0px');
            $('#right-nav').css('right','30px');
            $('#content').masonry('reload');
        },function(){
            $('#sidebar').show();
            $('#top-nav').css('margin-left','200px');
            $('#right-nav').css('right','230px');
            $('#content').masonry('reload');
        });        
    });
</script>
<!-- timeago -->
<script>
    function prettyTime(){
        jQuery(document).ready(function() {
          jQuery("abbr.timeago").timeago();
        });    
    }
    prettyTime();
    setInterval(function(){prettyTime()},10000);
</script>

<!-- location -->
<script language="JavaScript" src="http://www.geoplugin.net/javascript.gp" type="text/javascript"></script>
<script type="text/javascript">
    $(document).ready(function(){    
        var latitude=geoplugin_latitude();
        var longitude=geoplugin_longitude();
        $('#nearby').click(function(){
           window.location.href="<?php echo $this->webroot?>instagrams/nearby/"+latitude+'/'+longitude; 
        });        
    });
</script>
<!-- facebook -->
<script>
function fb_like() {
    url=location.href;
    title=document.title;
    window.open('http://www.facebook.com/sharer.php?u='+encodeURIComponent(url)+'&t='+encodeURIComponent(title),'sharer','toolbar=0,status=0,width=626,height=436');
    return false;
}
</script>
