<div id="mapholder"></div>
<script src="http://maps.google.com/maps/api/js?sensor=false"></script>
<script>
var x=document.getElementById("demo");
function getLocation()
  {
  if (navigator.geolocation)
    {
    navigator.geolocation.getCurrentPosition(showPosition,showError);
    }
  else{x.innerHTML="Geolocation is not supported by this browser.";}
  }

function showPosition(position)
{
    //lat=position.coords.latitude;
    //lon=position.coords.longitude;
    lat=<?php echo $media['photo']['latitude'] ?>;
    lon=<?php echo $media['photo']['longitude'] ?>;
    var map_height=$(document).height()-40;
    var map_width=$(document).width()-200;
    latlon=new google.maps.LatLng(lat, lon)
    mapholder=document.getElementById('mapholder')
    mapholder.style.height=map_height+'px';
    mapholder.style.width=map_width+'px';
    var image = new google.maps.MarkerImage(
        '<?php echo $media['photo']['url_photo'] ?>',
        new google.maps.Size(150,150),
        new google.maps.Point(0,0),
        new google.maps.Point(75,205),
        new google.maps.Size(150,150)
        
    );
    var shadow = new google.maps.MarkerImage(
        '<?php echo $this->webroot?>img/shadow.png',
        new google.maps.Size(190, 220),
        new google.maps.Point(0,0),
        new google.maps.Point(95,225));
    var myOptions={
      center:latlon,zoom:14,
      mapTypeId:google.maps.MapTypeId.ROADMAP,
      mapTypeControl:false,
      navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
    };

    var map=new google.maps.Map(document.getElementById("mapholder"),myOptions);

    var marker=new google.maps.Marker({
        position:latlon,
        map:map,
        shadow:shadow,
        icon:image,
        title:"Click to view"});    
    google.maps.event.addListener(marker, 'click', function() {
        var id='<?php echo $media['photo']['photo_id'] ?>';                
        $('#lightbox ,.preview_wrapper').show('slow');
        $('.preview').load('<?php echo $this->webroot; ?>meshtiles/photo/'+id);
    });
}

function showError(error)
  {
  switch(error.code) 
    {
    case error.PERMISSION_DENIED:
      x.innerHTML="User denied the request for Geolocation."
      break;
    case error.POSITION_UNAVAILABLE:
      x.innerHTML="Location information is unavailable."
      break;
    case error.TIMEOUT:
      x.innerHTML="The request to get user location timed out."
      break;
    case error.UNKNOWN_ERROR:
      x.innerHTML="An unknown error occurred."
      break;
    }
  }
$(document).ready(function (){
    getLocation();
});
</script>
<!-- lightbox -->
<script type="text/javascript">
    $(document).ready(function(){        
       $('#lightbox').click(function(){
            $('#lightbox ,.preview_wrapper').hide('slow');
            $('.preview').html('');
       }) 
    });
</script>