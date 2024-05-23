<!doctype html>
<html lang="en">

<head>
    <!-- Required meta tags -->
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no">
    <link rel="icon" href="image/faviconR.jpg" type="image/png">
    <title>pikuprd</title>
    <!-- Bootstrap CSS -->
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
    <script src="https://polyfill.io/v3/polyfill.min.js?features=default"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=<?php echo $_ENV['MAP_KEY']; ?>&callback=initMap&libraries=places&v=weekly"
        defer></script>
    <link href="https://unpkg.com/gijgo@1.9.14/css/gijgo.min.css" rel="stylesheet" type="text/css" />

    <style type="text/css">
        #map {
            height: 500px;
            width: 800px;
            border-radius: 15px;
            margin: 100px auto;
        }
    </style>


</head>

<body>
    <div class="container">

        <h3 id="airport">{{ $airport }}</h3>
        <h3 id="hotel">{{ $hotel }}</h3>
        <div id="map" style="height: 500px; width: 100%;"></div>
        <button class="btn btn-primary" id="waze-button">Abrir en Waze</button>
        <button class="btn btn-info" id="google-maps-button">Abrir en Google Maps</button>
    </div>
    <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/popper.js@1.12.9/dist/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
<script src="https://cdn.jsdelivr.net/npm/bootstrap@4.0.0/dist/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>
    <script>
        const airport = document.getElementById('airport').textContent;
        const hotel = document.getElementById('hotel').textContent;

        let timer;

        function initMap() {
            const directionsRenderer = new google.maps.DirectionsRenderer();
            const directionsService = new google.maps.DirectionsService();
            const map = new google.maps.Map(document.getElementById("map"), {
                zoom: 8,
                center: {
                    lat: 18.7357,
                    lng: -70.1627
                },
            });

            directionsRenderer.setMap(map);

            calculateAndDisplayRoute(directionsService, directionsRenderer, airport, hotel);
        }

        function geocodeAddress(address, callback) {
            const geocoder = new google.maps.Geocoder();
            geocoder.geocode({
                address: address
            }, function(results, status) {
                if (status === "OK") {
                    const location = results[0].geometry.location;
                    const coordinates = {
                        lat: location.lat(),
                        lng: location.lng()
                    };
                    callback(coordinates);
                } else {
                    console.log("Geocode was not successful for the following reason: " + status);
                }
            });
        }

        function calculateAndDisplayRoute(directionsService, directionsRenderer, origin, destination) {
            directionsService.route({
                origin: origin,
                destination: destination,
                travelMode: google.maps.TravelMode.DRIVING,
            }, (response, status) => {
                if (status == "OK") {
                    directionsRenderer.setDirections(response);
                } else {
                    console.log("Directions request failed due to " + status);
                }
            });
        }

        document.getElementById('waze-button').addEventListener('click', function() {
            const wazeUrl = `https://waze.com/ul?ll=${encodeURIComponent(hotel)}&navigate=yes`;
            window.open(wazeUrl, '_blank');
        });

        document.getElementById('google-maps-button').addEventListener('click', function() {
            const googleMapsUrl =
                `https://www.google.com/maps/dir/?api=1&origin=${encodeURIComponent(airport)}&destination=${encodeURIComponent(hotel)}&travelmode=driving`;
            window.open(googleMapsUrl, '_blank');
        });

        // Load the map
        initMap();
    </script>
</body>

</html>
