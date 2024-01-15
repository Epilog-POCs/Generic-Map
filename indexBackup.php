<!DOCTYPE html>
<html>
  <head>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/css/bootstrap.min.css" integrity="sha384-xOolHFLEh07PJGoPkLv1IbcEPTNtaed2xpHsD9ESMhqIYd0nLMwNLD69Npy4HI+N" crossorigin="anonymous">
    <link rel="stylesheet" href="css/styles.css">
  </head>
  <body>
    <?php include "modal.php"?>
    <div id="tooltipCentro" class="tooltip"></div>
    <div id="mapaPrincipal"></div>
    <div id="logo">
      <img src="logo.jpg" alt="">
    </div>
    <div id="iconos">
      <?php include "iconos.php"?>
    </div>
    <div id="colores">
      <?php include "colores.php"?>
    </div>
    
    <div id="formulario" class="container">
      <?php include "formulario.php"?>
    </div>
    <script src="https://cdn.jsdelivr.net/npm/jquery@3.5.1/dist/jquery.slim.min.js" integrity="sha384-DfXdz2htPH0lsSSs5nCTpuj/zy4C+OGpamoFVy38MVBnE+IbbVYUew+OrCXaRkfj" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@4.6.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-Fy6S3B9q64WdZWQUiU+q4/2Lc9npb8tCaSX9FK7E8HnRr0Jz8D6OP9dO5Vg3Q9ct" crossorigin="anonymous"></script>
    <script src="https://maps.googleapis.com/maps/api/js?key=AIzaSyBeamv0IUPzJTUTmP1STnEC8PRSx90ErLY&v=3.exp&libraries=places"></script>
    <script src="https://code.jquery.com/jquery-3.7.0.min.js" integrity="sha256-2Pmvv0kuTBOenSvLm6bvfBSSHrUJ+3A7x6P5Ebd07/g=" crossorigin="anonymous"/></script>
  <script src="https://code.highcharts.com/highcharts.js"></script>
  <script src="https://code.highcharts.com/modules/data.js"></script>
  <script src="https://code.highcharts.com/modules/drilldown.js"></script>
  <script src="js/turf.js"></script>
  <script src="js/mapas.js"></script>
  <script src="js/coordenadas.js"></script>
  <script>
    var estados=[];
    var estadosx=[];
    var tipos=['Distribuidor', 'Subdistribuidor', 'Agrícola', 'Tienda', 'Vendedor', 'Otro']
    var colores=['#ea4f50', '#ef7543', '#f29531', '#f3b306', '#bfb905', '#83bc07', '#1abc0b']
    var markers=[]

    function rnd(min, max) { // min and max included 
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

    var mapaPrincipal;
    function iniciarMapa(){
      mapaPrincipal = new google.maps.Map($("#mapaPrincipal")[0], {
        styles: mapStyles[25],
        center: new google.maps.LatLng(19.400426510597388, -99.14631760218009),
        zoom: 5.5,
        minZoom: 5.5, maxZoom: 9,
        mapTypeId: google.maps.MapTypeId.ROADMAP,
        disableDefaultUI: true,
        mapTypeControl: false,
        panControl: true,
        gestureHandling: 'greedy',
        zoomControl: true,
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
      /*google.maps.event.addListener(mapaPrincipal, 'click', function(event) {
            console.log(`${event.latLng.lat()},${event.latLng.lng()}`)
            var marker = new google.maps.Marker({
               position: event.latLng, 
               map: mapaPrincipal
            });
         });*/

      google.maps.event.addListener(mapaPrincipal, 'zoom_changed', function() {
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
      });
      mapaPrincipal.controls[google.maps.ControlPosition.RIGHT_CENTER].push($("#colores")[0]);
      mapaPrincipal.controls[google.maps.ControlPosition.TOP_RIGHT].push($("#logo")[0]);
      mapaPrincipal.controls[google.maps.ControlPosition.BOTTOM_CENTER].push($("#iconos")[0]);
      mapaPrincipal.controls[google.maps.ControlPosition.LEFT_TOP].push($("#formulario")[0]);
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
    }




    var estados;
    var municipios;
    iniciarMapa();
    $.getJSON('json/mexicoHigh.json', function (data) {
      estados=data.features;
      pintarEstados(estados)
    });
    $.getJSON( "json/municipiosMexico.json", function( data ) {
      municipios=data.features;
    })


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
        });



        google.maps.event.addListener(estado,"mouseout",function(){
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


    function cambirx(e){
      if(e=="puntos"){
        $(".quitar").hide();
      }else{
        $(".quitar").show();
      }
    }

    function aleatorizarEstados(){    
      $.each(estadosx, function(key,value) {
        value.setOptions({fillColor: colores[rnd(0,6)]});
      });

      $.each(markers, function(key,value) {
        var icon=[rnd(0,5)]
        var color=[rnd(0,6)]
        value.setOptions({icon: `img/icons/${icon}-${color}.png`});
      })
    }
    /*function filtrarEstado(estado){
         var ejemplo=municipios.filter((x) => x.properties.NAME_1 == estado);
         $.each(ejemplo, function (key, val) {
            console.log(val)
            var color=colores[rnd(0,6)];
            var daniel=swapCoordinates(val)
            const estado = new google.maps.Polygon({
               paths: daniel,
               geodesic: false,
               fillColor: color,
               fillOpacity:1, 
               strokeOpacity: 1.0,
               strokeWeight: 2,
            });
            estado.setMap(mapaPrincipal);
            estado.addListener("click", () => {
               alert(val.properties.name)

            });
            google.maps.event.addListener(estado,"mouseover",function(){
               this.setOptions({fillColor: "black"});
            }); 

            google.maps.event.addListener(estado,"mouseout",function(){
               this.setOptions({fillColor: color});
            });
            estados.push(estado)

         })
      }*/





    $.each(coordenadas, function (key, val) {
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





  </script>
  </body>
</html>