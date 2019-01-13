<!DOCTYPE html>
<html lang="en">
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.css">
    <meta name="viewport" content="initial-scale=1.0">
    <meta charset="utf-8">
</head>
<body>
<script src="https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/flatpickr/4.2.3/flatpickr.js"></script>

<div class="container">
    <h1>Vælg tidsrum du ønsker at leje cykel</h1>
    <hr>
    
    <form method="post" action="?controller=bikes&action=index">
        <label for="dates"><b>Tidsrum</b></label>
        <input
            type="text"
            id="basicDate"
            name="dates"
            placeholder="Vælg tidsrum"
            data-input>
        
        <button type="submit" name="bikeId" class="registerbtn">Find cykler</button>
    </form>
    
    <?php
    if (isset($xmlFile)) { ?>
        <hr>
        
        <h1>Cykler, som er ledige i det valgte tidsrum</h1>
        
        <div id="map"></div>
    
    <?php }?>


</div>

<script>
  
  $("#basicDate").flatpickr({
    mode: "range",
    enableTime: true,     //date and time
    time_24hr: true,
    altInput: true,       //show format (altFormat) is different than saved format (dateFormat)
    altFormat: "d F, Y H:i",
    dateFormat: "Y-m-d H:i",
    minDate: "today",
    minuteIncrement: 30
  });
  
  function initMap() {
    var map = new google.maps.Map(document.getElementById('map'), {
      center: {lat: 56.16, lng: 10.45},
      zoom: 7,
      mapTypeId: 'terrain'
    });
    var infoWindow = new google.maps.InfoWindow;
    
    downloadUrl('markers.xml', function(data) {
      var xml = data.responseXML;
      var markers = xml.documentElement.getElementsByTagName('marker');
      Array.prototype.forEach.call(markers, function (markerElem) {
        var id = markerElem.getAttribute('id');
        var title = markerElem.getAttribute('title');
        var description = markerElem.getAttribute('description');
        var price = markerElem.getAttribute('price');
        var point = new google.maps.LatLng(
          parseFloat(markerElem.getAttribute('lat')),
          parseFloat(markerElem.getAttribute('lng')));
        
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
  
  
        var marker = new google.maps.Marker({
          map: map,
          position: point
        });
        
        marker.addListener('click', function () {
          infoWindow.setContent(contentString);
          infoWindow.open(map, marker);
        });
      });
    });
  }
  
  function downloadUrl(url,callback) {
    var request = window.ActiveXObject ?
      new ActiveXObject('Microsoft.XMLHTTP') :
      new XMLHttpRequest;
    
    request.onreadystatechange = function() {
      if (request.readyState == 4) {
        request.onreadystatechange = doNothing;
        callback(request, request.status);
      }
    };
    
    request.open('GET', url, true);
    request.send(null);
  }
  
  function doNothing() {}

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyCqA2ckGqG-GwQ26kPlBCYfPKaYk9phClY&callback=initMap"
        async defer></script>

</body>
</html>