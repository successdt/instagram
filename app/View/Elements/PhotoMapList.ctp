
    <script src="https://maps.googleapis.com/maps/api/js?sensor=false"></script>
    <script>
    var latlon=new Array(); //Mảng lưu latitude và longitude
    var marker=new Array(); //Mảng lưu các marker
    var image=new Array(); //Mảng lưu các ảnh cho icon
    var photo_id=new Array(); //Mảng lưu id của photo
    var url_thumb=new Array();//Mảng lưu url của thumbnai
    function initialize() {
        lat=<?php echo $media['photo'][0]['latitude'] ?>;
        lon=<?php echo $media['photo'][0]['longitude'] ?>;
        //Set kích thước của div chứa map
        var map_height=$(document).height()-40;
        var map_width=$(document).width()-200;
        latlon=new google.maps.LatLng(lat, lon);
        mapholder=document.getElementById('map_canvas');
        mapholder.style.height=map_height+'px';
        mapholder.style.width=map_width+'px';
      
        var shadow = new google.maps.MarkerImage(
            '<?php echo $this->webroot?>img/shadow.png',
            new google.maps.Size(190, 220),
            new google.maps.Point(0,0),
            new google.maps.Point(95,225));
                
        var myOptions={
          center:latlon,zoom:10,
          mapTypeId:google.maps.MapTypeId.ROADMAP,
          mapTypeControl:false,
          navigationControlOptions:{style:google.maps.NavigationControlStyle.SMALL}
        };
        var map=new google.maps.Map(document.getElementById("map_canvas"),myOptions);

        <?php 
        $id=0;    
        foreach($media['photo'] as $photo): ?>
            <?php $id++; ?>
            var id=<?php echo $id ?>;//Lưu id để gọi mảng
            latlon[id]=new google.maps.LatLng(<?php echo $photo['latitude'].','.$photo['longitude'] ?>);
            image[id]= new google.maps.MarkerImage(
                '<?php echo $photo['url_thumb'] ?>',
                new google.maps.Size(150,150),
                new google.maps.Point(0,0),
                new google.maps.Point(75,205),
                new google.maps.Size(150,150)
                
            );
            photo_id[id]='<?php echo $photo['photo_id'] ?>';
            url_thumb[id]='<?php echo $photo['url_thumb'] ?>';  
            marker[id]=new google.maps.Marker({
                position:latlon[id],
                map:map,
                shadow:shadow,
                icon:image[id],
                //zIndex:id,
                title:"Click to view"});
            clickListener(id,marker,map);
        <?php endforeach ?>
               
      }
      function clickListener(id,marker,map){
            var overlay;        
            overlay = new google.maps.OverlayView();
            overlay.draw = function(){};
            overlay.setMap(map);
            google.maps.event.addListener(marker[id], 'click', function() {
                var over_marker=new Array();//mảng lưu id của các marker bị chồng lên
                var point=overlay.getProjection().fromLatLngToContainerPixel(this.getPosition());
                //console.log(point);
                $('#lightbox ,.preview_wrapper').show('slow');
                for(var i=1;i<=marker.length-1;i++){
                    var point2=overlay.getProjection().fromLatLngToContainerPixel(marker[i].getPosition());
                    if((Math.abs(point2.x-point.x)<20)&&(Math.abs(point2.y-point.y)<20)){
                        over_marker.push(i);//Thêm id vào mảng
                    }
                }
                //console.log(over_marker);
                //Nếu ko bị chồng thì load luôn
                if(over_marker.length==1){
                    $('.preview').load('<?php echo $this->webroot; ?>meshtiles/photo/'+photo_id[over_marker[0]]);
                }
                //Nếu bị chồng thì hiện list các ảnh
                else{
                   for(i=0;i<over_marker.length;i++){
                    var marker_id=over_marker[i];
                    $('.preview').append('<img class="image_thumb" data-media-id="'+photo_id[over_marker[i]]+'"  src="'+url_thumb[over_marker[i]]+'"/>');

                   }
                }
                $('.image_thumb').click(function(){
                    var media_id=$(this).attr('data-media-id');//Lấy id của ảnh
                    $('.preview').html('');
                    $('.preview').load('<?php echo $this->webroot; ?>meshtiles/photo/'+media_id);
                }); 
            });       
      }
    </script>
    <script>
        $(document).ready(function(){
            initialize();
        });
    </script>
    <!-- lightbox -->
    <script type="text/javascript">
        $(document).ready(function(){        
           $('#lightbox').click(function(){
                $('#lightbox ,.preview_wrapper').hide('slow');
                $('.preview').html('');
           });

        });
    </script>
<div id="map_canvas"></div>