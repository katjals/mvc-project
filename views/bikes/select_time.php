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

  <form method="post" action="?controller=bikes&action=index">
    <div class="container">
      <h1>Vælg tidsrum og område du ønsker at leje en cykel</h1>
      <hr>
  
      <label for="dates"><b>Tidsrum</b></label>
      <input
        type="text"
        id="basicDate"
        name="dates"
        placeholder=""
        data-input required>
      
      <input type="hidden" placeholder="" name="title" required>
      <input type="hidden" placeholder="" name="description" required>
      <input type="hidden" placeholder="" name="price" required>
      
      <label for="postalCode"><b>Område</b></label>
      <input id="autocomplete" placeholder=""
             onFocus="geolocate()" type="text" required>
      
      <input type="hidden" id="street_number" name="streetNumber">
      <input type="hidden" id="route" name="streetName">
      <input type="hidden" id="locality" name="city">
      <input type="hidden" id="postal_code" name="postalCode">
      <input type="hidden" id="country" name="country">
      <input type="hidden" id="lat" name="lat" required>
      <input type="hidden" id="lon" name="lon" required>
  
      <label for="radius"><b>Søgeradius i km</b></label>
      <input type="number" placeholder="" name="radius" value="10" required>
  
  
      <hr>
      <button type="submit" name="submit" class="registerbtn" >Find</button>
    </div>
  </form>



<script>
  
  // timepicker
  
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
  
  var placeSearch, autocomplete;
  var componentForm = {
    street_number: 'short_name',
    route: 'long_name',
    locality: 'long_name',
    country: 'long_name',
    postal_code: 'short_name'
  };
  
  function initAutocomplete() {
    // Create the autocomplete object, restricting the search to geographical
    // location types.
    autocomplete = new google.maps.places.Autocomplete(
      /** @type {!HTMLInputElement} */(document.getElementById('autocomplete')),
      {types: ['geocode']});
    
    // When the user selects an address from the dropdown, populate the address
    // fields in the form.
    autocomplete.addListener('place_changed', fillInAddress);
  }
  
  function fillInAddress() {
    // Get the place details from the autocomplete object.
    var place = autocomplete.getPlace();
    
    for (var component in componentForm) {
      document.getElementById(component).value = '';
    }
    
    // Get each component of the address from the place details
    // and fill the corresponding field on the form.
    for (var i = 0; i < place.address_components.length; i++) {
      var addressType = place.address_components[i].types[0];
      if (componentForm[addressType]) {
        var val = place.address_components[i][componentForm[addressType]];
        document.getElementById(addressType).value = val;
      }
    }
    
    document.getElementById('lat').value = place.geometry.location.lat();
    document.getElementById('lon').value = place.geometry.location.lng();
    
  }
  
  // Bias the autocomplete object to the user's geographical location,
  // as supplied by the browser's 'navigator.geolocation' object.
  function geolocate() {
    if (navigator.geolocation) {
      navigator.geolocation.getCurrentPosition(function(position) {
        var geolocation = {
          lat: position.coords.latitude,
          lng: position.coords.longitude
        };
        var circle = new google.maps.Circle({
          center: geolocation,
          radius: position.coords.accuracy
        });
        autocomplete.setBounds(circle.getBounds());
      });
    }
  }

</script>
<script src="https://maps.googleapis.com/maps/api/js?key=&libraries=places&callback=initAutocomplete"
        async defer></script>
</body>
</html>
