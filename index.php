<!DOCTYPE html>
<html>
  <head>
    <script src="https://kit.fontawesome.com/e49110df86.js"></script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <?php include "includes/modalEstado.php"?>
    <?php include "includes/modalPunto.php"?>
    <?php include "includes/modalWait.php"?>
    <div id="tooltipCentro" class="tooltip"></div>
    <div id="mapaPrincipal"></div>
    <div id="logo">
      <?php include "includes/logo.php"?>
    </div>
    <div id="canales">
      <?php include "includes/canales.php"?>
    </div>
    <div id="colores">
      <?php include "includes/colores.php"?>
    </div>  
    <div id="logo">
      <img src="img/logoEpilog.png" alt="">
    </div> 
    <div id="formulario" class="container">
      <?php include "includes/formulario.php"?>
    </div>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"/></script>

  <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>

  <script>

    function rnd(min, max) { // min and max included 
      return Math.floor(Math.random() * (max - min + 1) + min)
    }
  </script>

  <script src="js/turf.js"></script>
  <script src="js/mapas.js"></script>
  <script src="js/coordenadas.js"></script>
  <script>

    var markers = [];
    var whites = [];

    var poligonosEstados = [];

    var whiteMarker = "M-287,395c0,2.7-3.7,7.6-5.3,9.6c-0.4,0.5-1.1,0.5-1.5,0c-1.6-2-5.3-6.9-5.3-9.6c0-3.3,2.7-6,6-6S-287,391.7-287,395z"

    var house = [
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6	C-287,391.7-289.7,389-293,389z M-289,395h-0.5l0,2.5c0,0,0,0.1,0,0.1v0.3c0,0.3-0.3,0.6-0.6,0.6h-0.3c0,0,0,0-0.1,0c0,0,0,0-0.1,0	h-0.5h-0.4c-0.3,0-0.6-0.3-0.6-0.6v-0.4v-1c0-0.3-0.2-0.5-0.5-0.5h-1c-0.3,0-0.5,0.2-0.5,0.5v1v0.4c0,0.3-0.3,0.6-0.6,0.6h-0.4h-0.5	c0,0,0,0-0.1,0c0,0,0,0-0.1,0h-0.3c-0.3,0-0.6-0.3-0.6-0.6v-1.8c0,0,0,0,0,0V395h-0.5c-0.3,0-0.5-0.2-0.5-0.5c0-0.1,0-0.3,0.2-0.4	l4-3.5c0.1-0.1,0.2-0.1,0.3-0.1c0.1,0,0.2,0,0.3,0.1l4,3.5c0.1,0.1,0.2,0.2,0.2,0.4C-288.5,394.8-288.7,395-289,395z",
      "M-292.4,395.2v0.6c0,0.2-0.1,0.3-0.3,0.3h-0.6c-0.2,0-0.3-0.1-0.3-0.3v-0.6c0-0.2,0.1-0.3,0.3-0.3h0.6	C-292.5,394.9-292.4,395-292.4,395.2z M-294.5,392.5h-0.6c-0.2,0-0.3,0.1-0.3,0.3v0.6c0,0.2,0.1,0.3,0.3,0.3h0.6	c0.2,0,0.3-0.1,0.3-0.3v-0.6C-294.2,392.7-294.3,392.5-294.5,392.5z M-294.5,394.9h-0.6c-0.2,0-0.3,0.1-0.3,0.3v0.6	c0,0.2,0.1,0.3,0.3,0.3h0.6c0.2,0,0.3-0.1,0.3-0.3v-0.6C-294.2,395-294.3,394.9-294.5,394.9z M-292.7,392.5h-0.6	c-0.2,0-0.3,0.1-0.3,0.3v0.6c0,0.2,0.1,0.3,0.3,0.3h0.6c0.2,0,0.3-0.1,0.3-0.3v-0.6C-292.4,392.7-292.5,392.5-292.7,392.5z	 M-290.9,394.9h-0.6c-0.2,0-0.3,0.1-0.3,0.3v0.6c0,0.2,0.1,0.3,0.3,0.3h0.6c0.2,0,0.3-0.1,0.3-0.3v-0.6	C-290.7,395-290.8,394.9-290.9,394.9z M-287,395c0,2.7-3.7,7.6-5.3,9.6c-0.4,0.5-1.1,0.5-1.5,0c-1.6-2-5.3-6.9-5.3-9.6	c0-3.3,2.7-6,6-6S-287,391.7-287,395z M-289.5,391.7c0-0.5-0.4-0.9-0.9-0.9h-5.3c-0.5,0-0.9,0.4-0.9,0.9v7.6c0,0.5,0.4,0.9,0.9,0.9	h1.8v-1.5c0-0.5,0.4-0.9,0.9-0.9s0.9,0.4,0.9,0.9v1.5h1.8c0.5,0,0.9-0.4,0.9-0.9V391.7z M-290.9,392.5h-0.6c-0.2,0-0.3,0.1-0.3,0.3	v0.6c0,0.2,0.1,0.3,0.3,0.3h0.6c0.2,0,0.3-0.1,0.3-0.3v-0.6C-290.7,392.7-290.8,392.5-290.9,392.5z",
      "M-290,397.3c0,0.3-0.3,0.6-0.6,0.6s-0.6-0.3-0.6-0.6c0-0.3,0.3-0.6,0.6-0.6S-290,397-290,397.3z M-294.8,396.7	c-0.3,0-0.6,0.3-0.6,0.6c0,0.3,0.3,0.6,0.6,0.6s0.6-0.3,0.6-0.6C-294.1,397-294.4,396.7-294.8,396.7z M-287,395	c0,2.7-3.7,7.6-5.3,9.6c-0.4,0.5-1.1,0.5-1.5,0c-1.6-2-5.3-6.9-5.3-9.6c0-3.3,2.7-6,6-6S-287,391.7-287,395z M-288.5,396.9	c0-0.2-0.2-0.4-0.4-0.4v-0.8v-0.4V395c0-0.2-0.1-0.4-0.2-0.6l-1-1c-0.2-0.2-0.4-0.2-0.6-0.2h-0.7v-0.6c0-0.3-0.3-0.6-0.6-0.6h-4.2	c-0.3,0-0.6,0.3-0.6,0.6v4.2c0,0.3,0.3,0.6,0.6,0.6h0.2c0,0.7,0.6,1.3,1.3,1.3s1.3-0.6,1.3-1.3h1.7c0,0.7,0.6,1.3,1.3,1.3	s1.3-0.6,1.3-1.3h0.4C-288.7,397.3-288.5,397.1-288.5,396.9z M-291.4,394v1.3h1.7V395l-1-1H-291.4z",
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6	C-287,391.7-289.7,389-293,389z M-290.1,398.1c0,0.2-0.2,0.4-0.4,0.4h-5.1c-0.2,0-0.4-0.2-0.4-0.4v-0.8h5.8V398.1z M-290.1,396.7	h-5.8v-1h5.8V396.7z M-290.1,395.3h-5.8v-1.2h5.8V395.3z M-288.5,398.4h-0.7c-0.2,0-0.4-0.2-0.4-0.4v-4c0-0.3-0.2-0.5-0.5-0.5h-5.8	c-0.3,0-0.5,0.2-0.5,0.5v4c0,0.2-0.2,0.4-0.4,0.4h-0.7c-0.2,0-0.4-0.2-0.4-0.4v-4.8c0-0.4,0.2-0.8,0.6-0.9l4.1-1.6	c0.1,0,0.2,0,0.4,0l4.1,1.6c0.4,0.1,0.6,0.5,0.6,0.9v4.8C-288.2,398.3-288.3,398.4-288.5,398.4z",
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6	C-287,391.7-289.7,389-293,389z M-293,389.9c0.6,0,1.1,0.5,1.1,1.1c0,0.6-0.5,1.1-1.1,1.1c-0.6,0-1.1-0.5-1.1-1.1	C-294.1,390.4-293.6,389.9-293,389.9z M-289.8,397c-0.3,0.2-0.8,0.1-1-0.2l-0.6-1.1v5c0,0.4-0.3,0.7-0.7,0.7s-0.7-0.3-0.7-0.7v-2.9	h-0.4v2.9c0,0.4-0.3,0.7-0.7,0.7s-0.7-0.3-0.7-0.7v-5l-0.6,1.1c-0.2,0.3-0.6,0.4-1,0.2c-0.3-0.2-0.4-0.6-0.2-1l1.3-2.2	c0.4-0.6,1.1-1,1.8-1h0.7c0.8,0,1.5,0.4,1.8,1l1.3,2.2C-289.3,396.3-289.4,396.8-289.8,397z",
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6	C-287,391.7-289.7,389-293,389z M-289.2,397.6v1.1c0,0.6-0.5,1.1-1.1,1.1h-5.5c-0.6,0-1.1-0.5-1.1-1.1v-1.1v-2.2c0.1,0,0.1,0,0.2,0	h0c0.1,0,0.2,0,0.3,0c0.2,0,0.4,0,0.6-0.1v2.3h5.5v-2.3c0.2,0.1,0.4,0.1,0.6,0.1c0.1,0,0.2,0,0.3,0h0c0.1,0,0.1,0,0.2,0V397.6z	 M-289.5,394.8c-0.1,0-0.1,0-0.2,0c-0.4,0-0.8-0.2-1.1-0.5c-0.3,0.3-0.7,0.5-1.1,0.5c-0.4,0-0.8-0.2-1.1-0.5	c-0.3,0.3-0.7,0.5-1.1,0.5s-0.8-0.2-1.1-0.5c-0.3,0.3-0.7,0.5-1.1,0.5c-0.1,0-0.1,0-0.2,0c-0.9-0.1-1.4-1.2-0.9-2l1-1.5	c0.1-0.1,0.2-0.2,0.4-0.2h6.1c0.2,0,0.3,0.1,0.4,0.2l1,1.5h0C-288.1,393.6-288.5,394.7-289.5,394.8z"
    ]
    var iconsPath = [
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6C-287,391.7-289.7,389-293,389z M-289.8,395.6h-0.4l0,2c0,0,0,0.1,0,0.1v0.2c0,0.3-0.2,0.5-0.5,0.5h-0.2c0,0,0,0,0,0c0,0,0,0-0.1,0h-0.4h-0.3c-0.3,0-0.5-0.2-0.5-0.5v-0.3v-0.8c0-0.2-0.2-0.4-0.4-0.4h-0.8c-0.2,0-0.4,0.2-0.4,0.4v0.8v0.3c0,0.3-0.2,0.5-0.5,0.5h-0.3h-0.4c0,0,0,0-0.1,0c0,0,0,0,0,0h-0.2c-0.3,0-0.5-0.2-0.5-0.5v-1.4c0,0,0,0,0,0v-0.9h-0.4c-0.2,0-0.4-0.2-0.4-0.4c0-0.1,0-0.2,0.1-0.3l3.2-2.8c0.1-0.1,0.2-0.1,0.3-0.1s0.2,0,0.3,0.1l3.2,2.8c0.1,0.1,0.2,0.2,0.1,0.3C-289.4,395.4-289.6,395.6-289.8,395.6z",
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6	C-287,391.7-289.7,389-293,389z M-290.9,396.7c0,0.1-0.1,0.3-0.3,0.3h-3.7c-0.1,0-0.3-0.1-0.3-0.3v-0.6h4.2V396.7z M-290.9,395.8	h-4.2v-0.7h4.2V395.8z M-290.9,394.7h-4.2v-0.9h4.2V394.7z M-289.5,396.7c0,0.1-0.1,0.3-0.3,0.3h-0.5c-0.1,0-0.3-0.1-0.3-0.3v-2.9	c0-0.2-0.2-0.3-0.3-0.3h-4.2c-0.2,0-0.3,0.2-0.3,0.3v2.9c0,0.1-0.1,0.3-0.3,0.3h-0.5c-0.1,0-0.3-0.1-0.3-0.3v-3.4	c0-0.3,0.2-0.5,0.4-0.6l2.9-1.2c0.1,0,0.2,0,0.3,0l2.9,1.2c0.3,0.1,0.4,0.4,0.4,0.6V396.7z",
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6	C-287,391.7-289.7,389-293,389z M-290.2,396.5v0.8c0,0.4-0.4,0.8-0.8,0.8h-4c-0.4,0-0.8-0.4-0.8-0.8v-0.8v-1.6c0,0,0.1,0,0.1,0h0	c0.1,0,0.1,0,0.2,0c0.2,0,0.3,0,0.4-0.1v1.7h4v-1.6c0.1,0,0.3,0.1,0.4,0.1c0.1,0,0.1,0,0.2,0h0c0.1,0,0.1,0,0.2,0V396.5z	 M-290.4,394.6c0,0-0.1,0-0.1,0c-0.3,0-0.6-0.1-0.8-0.4c-0.2,0.2-0.5,0.4-0.8,0.4c-0.3,0-0.6-0.1-0.8-0.4c-0.2,0.2-0.5,0.4-0.8,0.4	s-0.6-0.1-0.8-0.4c-0.2,0.2-0.5,0.4-0.8,0.4c0,0-0.1,0-0.1,0c-0.7-0.1-1-0.9-0.6-1.5l0.7-1.1c0.1-0.1,0.2-0.2,0.3-0.2h4.4	c0.1,0,0.2,0.1,0.3,0.2l0.7,1.1h0C-289.4,393.7-289.7,394.5-290.4,394.6z",
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6	C-287,391.7-289.7,389-293,389z M-293,390.4c0.4,0,0.8,0.4,0.8,0.8s-0.4,0.8-0.8,0.8c-0.4,0-0.8-0.4-0.8-0.8S-293.4,390.4-293,390.4	z M-290.6,395.6c-0.2,0.1-0.6,0.1-0.7-0.2l-0.5-0.8v3.7c0,0.3-0.2,0.5-0.5,0.5c-0.3,0-0.5-0.2-0.5-0.5v-2.1h-0.3v2.1	c0,0.3-0.2,0.5-0.5,0.5c-0.3,0-0.5-0.2-0.5-0.5v-3.7l-0.5,0.8c-0.1,0.2-0.5,0.3-0.7,0.2c-0.2-0.1-0.3-0.5-0.2-0.7l1-1.6	c0.3-0.5,0.8-0.8,1.4-0.8h0.5c0.6,0,1.1,0.3,1.4,0.8l1,1.6C-290.3,395.2-290.4,395.5-290.6,395.6z",
      "M-293,389c-3.3,0-6,2.7-6,6c0,2.7,3.7,7.6,5.3,9.6c0.4,0.5,1.1,0.5,1.5,0c1.6-2,5.3-6.9,5.3-9.6	C-287,391.7-289.7,389-293,389z M-292.3,396.1v0.9c0,0.3-0.2,0.6-0.6,0.6h-2.6c-0.3,0-0.6-0.2-0.6-0.6v-0.9v-1.8h0.7v1.8h2.2v-1.8	h0.7V396.1z M-290,397.2c0,0.2-0.2,0.4-0.4,0.4s-0.4-0.2-0.4-0.4v-2.9h0.7V397.2z M-289.7,393.9h-6.5c-0.2,0-0.4-0.2-0.4-0.4	c0-0.1,0-0.2,0.1-0.2l0.9-1.3c0.1-0.2,0.3-0.2,0.5-0.2h4.6c0.2,0,0.4,0.1,0.5,0.2l0.9,1.3c0,0.1,0.1,0.2,0.1,0.2	C-289.3,393.7-289.5,393.9-289.7,393.9z"
    ]

    function randomBetween(min, max) { // min and max included 
      return Math.floor(Math.random() * (max - min + 1) + min)
    }

    function swapCoordinates(GeoJSON) {
      for (var i = 0; i < GeoJSON.geometry.coordinates.length; i++) {
        var type=GeoJSON.geometry.type
        if (type == "Polygon") {
          var paths = [];
          for (var j = 0; j < GeoJSON.geometry.coordinates[i].length; j++) {
            if (!paths[i]) {
              paths[i] = [];
            }
            paths[i].push({
              lat: GeoJSON.geometry.coordinates[i][j][1],
              lng: GeoJSON.geometry.coordinates[i][j][0]
            });
          }
        } else if (type == "MultiPolygon") {
          var objectPolygon = [];
          for (var j = 0; j < GeoJSON.geometry.coordinates[i].length; j++) {
            innerCoords = [];
            length = GeoJSON.geometry.coordinates[i][j].length;
            clockwise = turf.booleanClockwise(GeoJSON.geometry.coordinates[i][j]);
            if (!clockwise) {
              holes = true;
            }else{
              holes=false
            }
            for (var k = 0; k < length; k++) {
              coordinates = {
                lat: GeoJSON.geometry.coordinates[i][j][k][1],
                lng: GeoJSON.geometry.coordinates[i][j][k][0]
              };
              if (clockwise) {
                objectPolygon.push(coordinates);
              }                     else {
                innerCoords.push(coordinates);
              }
            }
          }
          if (!paths) {
            paths = [];
          }
          if (holes) {
            paths.push([objectPolygon, innerCoords]);
          } else {
            paths.push(objectPolygon);
          }
        }
      }
      return paths;
    }

    function iniciarMapa(){
      mapaPrincipal = new google.maps.Map($("#mapaPrincipal")[0], {
        styles: mapStyles[25],
        center: new google.maps.LatLng(19.400426510597388, -99.14631760218009),
        zoom: 5,
        minZoom: 5, maxZoom: 10,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        mapTypeControl: false,
        panControl: true,
        gestureHandling: 'greedy',
        zoomControl: false,
        scaleControl: false,
        streetViewControl: true,
        zoomControlOptions: {
          style: google.maps.ZoomControlStyle.MEDIUM,
          position: google.maps.ControlPosition.RIGHT_BOTTOM
        },
        panControlOptions: {
          style: google.maps.ZoomControlStyle.MEDIUM,
          position: google.maps.ControlPosition.LEFT_BOTTOM
        }
      });

      mapaPrincipal.controls[google.maps.ControlPosition.RIGHT_CENTER].push($("#colores")[0]);
      mapaPrincipal.controls[google.maps.ControlPosition.BOTTOM_CENTER].push($("#canales")[0]);
      mapaPrincipal.controls[google.maps.ControlPosition.LEFT_TOP].push($("#formulario")[0]);
      mapaPrincipal.controls[google.maps.ControlPosition.BOTTOM_LEFT].push($("#logo")[0]);


      var autocomplete = new google.maps.places.Autocomplete($('#lugarBuscadoMapaPrincipal')[0]);
      autocomplete.bindTo('bounds', mapaPrincipal);
      google.maps.event.addListener(autocomplete, 'place_changed', function(e) {
        var x = autocomplete.gm_accessors_.place;
        var formattedPrediction;
        $.each(x, function(key, item) {
          if (item.formattedPrediction) {
            formattedPrediction = item.formattedPrediction
          }
        });
        $("#lugarBuscadoModalBuscar").val(formattedPrediction)
        var place = autocomplete.getPlace();
        if (!place.geometry) {
          return;
        }
        if (place.geometry.viewport) {
          mapaPrincipal.fitBounds(place.geometry.viewport);
        } else {
          mapaPrincipal.panTo(place.geometry.location);
          setTimeout(mapaPrincipal.setZoom(17), 1000);
        }
      });
      google.maps.event.addListener(mapaPrincipal, 'zoom_changed', function() {
        $.each(poligonosEstados, function (key, val) {
          this.setOptions({fillOpacity: -0.18 * mapaPrincipal.getZoom() +1.9 })          
        })
        $.each(markers, function (key, val) {
          var temp = this.getIcon();
          temp.scale =  0.5 * mapaPrincipal.getZoom() - 2
          this.setIcon(temp);
        })
        $.each(whites, function (key, val) {
          var temp = this.getIcon();
          temp.scale =  0.5 * mapaPrincipal.getZoom() - 2
          this.setIcon(temp);
        })
      })
      /*var zoom = mapaPrincipal.getZoom();
        for (i = 0; i < markers.length; i++) {
          markers[i].setVisible(zoom >=8);
        }
        for (i = 0; i < estadosx.length; i++) {
          estadosx[i].setVisible(zoom <8);
        }
        zoomLevel = mapaPrincipal.getZoom();
        alert(zoomLevel)
        if (zoomLevel >= minFTZoomLevel) {
               FTlayer.setMap(map);
            } else {
               FTlayer.setMap(null);
            }
    });

    }*/

    }

    function aleatorizarEstados(){  
      var interno = $("#aleatorizar").html()      
      $("#aleatorizar").html(`<i class="fas fa-spinner fa-lg fa-spin"></i>`)
      setTimeout(function() {
        $.each(poligonosEstados, function(key,value) {
          value.setOptions({fillColor: colores[rnd(0,6)]});
        });

        $.each(whites, function(key,value) {
          var temp = value.getIcon();
          console.log(colores[rnd(0,6)])
          temp.fillColor =  colores[rnd(0,6)]
          this.setIcon(temp);
        });

        $("#aleatorizar").html(interno)
      }, 1500,this);
    }

    function pintarEstados(estados){
      $.each(estados, function (key, estado) { 
        console.log(estado.properties.name)
        var color=colores[randomBetween(0,6)];
        var poligon = new google.maps.Polygon({
          nombre:estado.properties.name,
          estadox:estado,
          paths: swapCoordinates(estado),
          geodesic: false,
          mainColor: color,
          fillColor: color,
          fillOpacity:1, 
          strokeOpacity: 1.0,
          strokeWeight: 0.5,
          map: mapaPrincipal
        });

        google.maps.event.addListener(poligon,"mouseover",function(){
          this.setOptions({fillColor: "#2d2d2d"});
        })

        google.maps.event.addListener(poligon,"mouseout",function(){
          this.setOptions({fillColor: this.mainColor});
        })

        google.maps.event.addListener(poligon,"click",function(){   
          $("#modalEstado").modal("show")  
          $("#modalEstado .modal-title").html(this.nombre)
          $("#iframeEstado").attr("src",`http://34.168.174.90/Epilog-POCs/Graficas/estados.php?estado=${this.nombre}`)
        })

        poligonosEstados.push(poligon)

      })
    }

    function pintarCoordenadas(){
      $.each(coordenadas, function (key, val) {
        var icon=rnd(0,4)
        var color=colores[rnd(0,6)]

        var marker = new google.maps.Marker({
          icon: { 
            anchor: new google.maps.Point(-293,405),
            path: whiteMarker,
            fillColor: "#ffffff",
            fillOpacity: 1,
            strokeWeight: 0,
            scale: 0.5
          },          
          position: new google.maps.LatLng(val.latitud,val.longitud),
          map: mapaPrincipal
        }); 

        var white = new google.maps.Marker({          
          icon: {
            anchor: new google.maps.Point(-293,405),
            path: house[rnd(0,5)],
            fillColor: color,
            fillOpacity: 1,
            strokeWeight: 0,
            scale: 0.5
          },
          position: marker.getPosition(),
          map: mapaPrincipal,
        });
        white.bindTo("position", marker);

        white.addListener("mouseover",function(){
          this.setOptions({fillColor: "#2d2d2d"});
        })

        white.addListener("mouseout",function(){
          this.setOptions({fillColor: this.mainColor});
        })

        white.addListener('click', function() {
          //1var e = this
          //this.setAnimation(google.maps.Animation.BOUNCE);
          //setTimeout(function() {
          //e.setAnimation(null);
          $("#modalPunto").modal("show")  
          $("#modalPunto .modal-title").html("Detalle del punto")
          //}, 1500,this);
        });
        markers.push(marker)
        whites.push(white)
      })
    }


    var colores=['#133974', '#413a7b', '#613983', '#7d368b', '#983092', '#b2249a', '#cd05a2'] //Morados
    var colores=['#ea4f50', '#ef7543', '#f29531', '#f3b306', '#bfb905', '#83bc07', '#1abc0b'] //Semaforo
    $(document).ready(function(){    
      $.each(colores, function (key, val) {
        $(`.color-${key}`).css("background-color", colores[key])
        
      })
      
      
      
      $.getJSON('json/mexicoHigh.json', function (data) {
        var estados = data.features
        pintarEstados(estados)
        pintarCoordenadas()

      });
    })




    /*google.maps.event.addListener(mapaPrincipal, 'click', function(event) {
            console.log(`${event.latLng.lat()},${event.latLng.lng()}`)
            var marker = new google.maps.Marker({
               position: event.latLng, 
               map: mapaPrincipal
            });
         });*/

    /*google.maps.event.addListener(mapaPrincipal, 'zoom_changed', function() {
        var zoom = mapaPrincipal.getZoom();
        for (i = 0; i < markers.length; i++) {
          markers[i].setVisible(zoom >=8);
        }
        for (i = 0; i < estadosx.length; i++) {
          estadosx[i].setVisible(zoom <8);
        }
        //zoomLevel = mapaPrincipal.getZoom();
        //alert(zoomLevel)
        /*if (zoomLevel >= minFTZoomLevel) {
               FTlayer.setMap(map);
            } else {
               FTlayer.setMap(null);
            }*/
    /* });

    }






    function pintarEstados(){
      $.each(estados, function (key, val) {
        var color=colores[rnd(0,6)];
        var daniel=swapCoordinates(val)
        const estado = new google.maps.Polygon({
          paths: daniel,
          geodesic: false,
          fillColor: color,
          fillOpacity:0.6, 
          strokeOpacity: 1.0,
          strokeWeight: 0.5,
        });
        estado.setMap(mapaPrincipal);



        google.maps.event.addListener(estado,"click",function(){
          $("#titulomodal").html(val.properties.name)

          //filtrarEstado(val.properties.name)

          var bounds= new google.maps.LatLngBounds();
          var paths = this.getPaths();
          paths.forEach(function(path){
            var ar = path.getArray();
            for(var i=0, l = ar.length; i <l; i++){
              bounds.extend(ar[i]);
            }
          })
          mapaPrincipal.fitBounds(bounds)
          setTimeout(function() {
            $("#verStats").trigger("click")
          }, 1500);
        }); 

        var infowindow = new google.maps.InfoWindow();
        infowindow.opened = false;

        google.maps.event.addListener(estado,"mouseover",function(){
          $(".gm-style-iw").next("div").hide();
          this.setOptions({fillOpacity: 1});
          infowindow.setContent(`<div style='width:200px; height:80px'><b>${val.properties.name}</b><br>Ventas: xxxxxx Kilolitros (15%)<br>Inventario: xxxxx Kilolitros (8%)<br>Pariticapentes: xxxxxx (14%)<br>Puntos: xxxxxx (8%)<br</div>`);
          var bounds= new google.maps.LatLngBounds();
          var paths = this.getPaths();
          paths.forEach(function(path){
            var ar = path.getArray();
            for(var i=0, l = ar.length; i <l; i++){
              bounds.extend(ar[i]);
            }
          })
          infowindow.setPosition(bounds.getCenter());
          infowindow.open(mapaPrincipal);
        });

        google.maps.event.addListener(estado,"mousemove",function(){
          /*var point = fromLatLngToPoint(this.getPosition(), mapaPrincipal);
               var contenido = "<h3 class='truncate' style='color:white'><b>" + 11111 + ":   </b><span style='color:white'>" + 11111 + "</span></h3><hr><table class='tablaTooltip'><tr><td style='width:50%'><div class='logoEmpresa'><img src='cent/barrio.jpg'></div></td><td><div class='tituloSecundario'><ul class='qq' style='font-size:12px'><li><span style='color:white'>Ventas:</span><br><span class='textoli'>" + 11111 + "<i class='fas fa-caret-up'></i></span></li><li><span style='color:white'>Volumen:</span><br><span class='textoli'>" + 11111 + "<i class='fas fa-caret-down'></i></span></li><li><span style='color:white'>Margen:</span><br><span class='textoli'>" + 11111 + "<i class='fas fa-caret-up'></i></span></li></ul></div></td></tr></table><hr><div id='espacioIconosh'>" + 11111 + "</div>";
               $('#tooltipCentro').html(contenido).css({
                  'left': point.x + $("#mapaPrincipal").offset().left - 15,
                  'top': point.y - 250,
                  'position': 'absolute'
               }).show();*/
    /*});



    /*  google.maps.event.addListener(estado,"mouseout",function(){
          this.setOptions({fillOpacity: 0.5});
          infowindow.close();
          infowindow.opened = false;
        });

        estadosx.push(estado)

      });
    }

    function fromLatLngToPoint(latLng, map) {
      var topRight = map.getProjection().fromLatLngToPoint(map.getBounds().getNorthEast());
      var bottomLeft = map.getProjection().fromLatLngToPoint(map.getBounds().getSouthWest());
      var scale = Math.pow(2, map.getZoom());
      var worldPoint = map.getProjection().fromLatLngToPoint(latLng);
      return new google.maps.Point((worldPoint.x - bottomLeft.x) * scale, (worldPoint.y - topRight.y) * scale);
    }







    /* $.each(coordenadas, function (key, val) {
      var icon=[rnd(0,5)]
      var color=[rnd(0,6)]

      var marker = new google.maps.Marker({
        icon: `img/icons/${icon}-${color}.png`,
        position: new google.maps.LatLng(val.latitud,val.longitud),
        map: mapaPrincipal
      }); 

      marker.addListener('click', function() {
        this.setAnimation(google.maps.Animation.BOUNCE);
        setTimeout(function() {
          markers[key].setAnimation(null);
          var t=tipos[rnd(0,5)]
          var icon=[rnd(0,5)]
          var color=[rnd(0,6)]
          $("#titulomodal").html( `<img src='img/icons/${icon}-${color}.png'> ${t} #REF`)
          $("#verStats").trigger("click")
        }, 1500);
      });
      markers.push(marker)
    })
*/




  </script>
  <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeamv0IUPzJTUTmP1STnEC8PRSx90ErLY&v=3.exp&libraries=places&callback=iniciarMapa"></script>
  </body>
</html>