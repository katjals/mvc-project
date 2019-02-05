<!DOCTYPE html>
<html lang="en">
<head>
</head>
<body>

<?php
  if (!isset($bike)){ ?>
    <form method="post" action="?controller=bikes&action=register">
      <div class="container">
        <h1>Opret en ladcykel</h1>
        <p>Opret en ladcykel som andre kan leje.</p>
        <hr>
        
        <label for="title"><b>Beskrivende Titel</b></label>
        <input type="text" placeholder="" name="title" required>
        
        <label for="description"><b>Beskriv den med flere ord</b></label>
        <input type="text" placeholder="" name="description" required>
        
        <label for="price"><b>Pris i kr pr. time</b></label>
        <input type="number" placeholder="" name="price" required>
        
        <label for="postalCode"><b>Cyklens placering</b></label>
        <input id="autocomplete" placeholder=""
               onFocus="geolocate()" type="text" required>
      
        <input type="hidden" id="street_number" name="streetNumber">
        <input type="hidden" id="route" name="streetName">
        <input type="hidden" id="locality" name="city">
        <input type="hidden" id="postal_code" name="postalCode">
        <input type="hidden" id="country" name="country">
        <input type="hidden" id="lat" name="lat" required>
        <input type="hidden" id="lon" name="lon" required>
      
        <hr>
        <button type="submit" name="submit" class="registerbtn" >Opret</button>
      </div>
    </form>
<?php
  } else { ?>
    <form method="post" action="?controller=bikes&action=register">
      <div class="container">
        <h1>Rediger ladcykel</h1>
        <hr>
        <input type="hidden" name="id" value="<?php echo $bike->id; ?>" >
        <input type="hidden" name="addressId" value="<?php echo $address->id; ?>" >
  
        <label for="title"><b>Beskrivende Titel</b></label>
        <input type="text" placeholder="" name="title" value="<?php echo $bike->title; ?>" required>
      
        <label for="description"><b>Beskriv den med flere ord</b></label>
        <input type="text" placeholder="" name="description" value="<?php echo $bike->description; ?>" required>
      
        <label for="price"><b>Pris i kr pr. time</b></label>
        <input type="number" placeholder="" name="price" value="<?php echo $bike->price; ?>" required>
  
        <label for="postalCode"><b>Cyklens placering</b></label>
        <input id="autocomplete" onFocus="geolocate()" type="text"
               placeholder="<?php echo $address->street . ", " . $address->city . ", " . $address->country; ?>" >
  
        <input type="hidden" id="street_number" name="streetNumber">
        <input type="hidden" id="route" name="streetName">
        <input type="hidden" id="locality" name="city">
        <input type="hidden" id="postal_code" name="postalCode">
        <input type="hidden" id="country" name="country">
        <input type="hidden" id="lat" name="lat">
        <input type="hidden" id="lon" name="lon">
      
        <hr>
        <button type="submit" name="submit" class="registerbtn" >Rediger</button>
      </div>
    </form>
<?php
  }
?>




<script>
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
