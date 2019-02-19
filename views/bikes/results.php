<!DOCTYPE html>
<html lang="en">
<head>
  <meta name="viewport" content="initial-scale=1.0">
  <meta charset="utf-8">
</head>
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>

<div class="container">
  
  <h1>Ledige cykler</h1>
  
  <hr>
  
  <div id="map"></div>

</div>

<script>
  function getData() {
      <?php
    
      if (isset($locations)){ ?>
        var data = <?php echo json_encode($locations) ?>;
        initMap(data);
        
      <?php
      } ?>
  }
  
  function initMap(data) {
    var map = new google.maps.Map(document.getElementById('map'), {
      mapTypeId: 'terrain'
    });
    var infoWindow = new google.maps.InfoWindow;
    var bounds  = new google.maps.LatLngBounds();
    
    
    Array.prototype.forEach.call(data, function (markerElem) {
      // bike information
      var id = markerElem['id'];
      var title = markerElem['title'];
      var description = markerElem['description'];
      var price = markerElem['price'];
      var point = new google.maps.LatLng(
        parseFloat(markerElem['address']['lat']),
        parseFloat(markerElem['address']['lon']));
      
      // content for information window on bike
      var contentString = '<div id="content">'+
        '<div id="siteNotice">'+
        '</div>'+
        '<h3>' + title +'</h3>'+
        '<p>' + description + '</p>'+
        '<p>' + price +' kr pr. time</p>'+
        '<p> <a href="?controller=bikes&action=getBike&page=book&id=' + id + '">'+
        'Læs mere</a></p>'+
        '</div>'+
        '</div>';
      
      // create marker
      var marker = new google.maps.Marker({
        map: map,
        position: point
      });
      
      // use marker location to auto-zoom an auto-center map
      var loc = new google.maps.LatLng(marker.position.lat(), marker.position.lng());
      bounds.extend(loc);
      
      // add listener to open information window on selected bike
      marker.addListener('click', function () {
        infoWindow.setContent(contentString);
        infoWindow.open(map, marker);
      });
    });
    
    // auto-zoom and auto-center
    map.fitBounds(bounds);
    map.panToBounds(bounds);
  }


</script>
<script src="https://maps.googleapis.com/maps/api/js?key=&callback=getData"
        async defer></script>

</body>
</html>