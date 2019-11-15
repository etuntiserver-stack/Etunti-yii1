


<?php
$asetuksetForAll = AsetuksetForAll::model()->findByPk(1);
?>
<html xmlns="https://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Sivex OY XML kohteet</title>

    <script src="https://maps.google.com/maps?file=api&amp;v=2&amp;key=<?=$asetuksetForAll->googlemaps_apikey?>" type="text/javascript"></script>
 
<?php
$center = '';
$valCenter = '';
if(isset($asetuksetForAll->googlemaps_apikey) and !empty($asetuksetForAll->googlemaps_apikey)){

  if(isset($_GET['center']) and !empty($_GET['center']) and $_GET['center'] != 'null'){
	$valCenter = $_GET['center'];
	$cityclean = str_replace (" ", "+", $_GET['center']);
	$json_url = 'https://maps.googleapis.com/maps/api/geocode/json?address='.$cityclean.'&language=fi&sensor=true&key='.$asetuksetForAll->googlemaps_apikey;
	$json = file_get_contents($json_url);
	$obj = json_decode($json);
	//print_r($obj);
	if( isset($obj->results[0]->geometry->location->lat) ){
		$get_osoite = $obj->results[0]->geometry->location->lat.",".$obj->results[0]->geometry->location->lng;
		$center = $get_osoite;
	} else {
		die('Ei onnistunut siirrää '.$cityclean.'<br> Virhe: '.$json);
	}
  }
}

if(isset($_GET['tila']) and !empty($_GET['tila'])){
	echo '<input type="hidden" id="getThistila" value="'.$_GET['tila'].'">';
} else {
	echo '<input type="hidden" id="getThistila" value="">';
}
?>


    <script type="text/javascript">
    //<![CDATA[

  function codeAddress() {
    var address = document.getElementById("kivikonkaari").value;
    geocoder.geocode( { 'address': address}, function(results, status) {
      if (status == google.maps.GeocoderStatus.OK) {
        map.setCenter(results[0].geometry.location);
        var marker = new google.maps.Marker({
            map: map,
            position: results[0].geometry.location
        });
      } else {
        alert("Geocode was not successful for the following reason: " + status);
      }
    });
  }



    var iconBlue = new GIcon(); 
    iconBlue.image = '/img/mm_20_blue.png';
    iconBlue.shadow = '/img/mm_20_shadow.png';
    iconBlue.iconSize = new GSize(12, 20);
    iconBlue.shadowSize = new GSize(22, 20);
    iconBlue.iconAnchor = new GPoint(6, 20);
    iconBlue.infoWindowAnchor = new GPoint(5, 1);
 
    var iconRed = new GIcon(); 
    iconRed.image = '/img/mm_20_red.png';
    iconRed.shadow = '/img/mm_20_shadow.png';
    iconRed.iconSize = new GSize(12, 20);
    iconRed.shadowSize = new GSize(22, 20);
    iconRed.iconAnchor = new GPoint(6, 20);
    iconRed.infoWindowAnchor = new GPoint(5, 1);
 
    var iconGreen = new GIcon(); 
    iconGreen.image = '/img/mm_20_green.png';
    iconGreen.shadow = '/img/mm_20_shadow.png';
    iconGreen.iconSize = new GSize(12, 20);
    iconGreen.shadowSize = new GSize(22, 20);
    iconGreen.iconAnchor = new GPoint(6, 20);
    iconGreen.infoWindowAnchor = new GPoint(5, 1)
 
    var customIcons = [];
    customIcons["restaurant"] = iconBlue;
    customIcons["bar"] = iconRed;
    customIcons["cafe"] = iconGreen;
 
    function load() {
      if (GBrowserIsCompatible()) {
        var map = new GMap2(document.getElementById("map"));
        map.addControl(new GSmallMapControl());
        map.addControl(new GMapTypeControl());

        var centerUusi = "<?php echo $center; ?>".split(",");


  var hesariLAT = '60.2480743';
  var hesariLNG = '24.9263055';


if(!centerUusi[0]){
        map.setCenter(new GLatLng(hesariLAT,hesariLNG), 11);
} else {
        map.setCenter(new GLatLng(centerUusi[0],centerUusi[1]), 11);
//alert(centerUusi)
}

	var tila = '';
	if(document.getElementById("getThistila").value !== '')
	tila = '?tila='+document.getElementById("getThistila").value;


        GDownloadUrl(location.protocol + "//" + location.host + "/index.php/kohteet/googlemap_k"+tila, function(data) {
          var xml = GXml.parse(data);
          var markers = xml.documentElement.getElementsByTagName("marker");
          for (var i = 0; i < markers.length; i++) {
            var name = markers[i].getAttribute("name");
            var address = markers[i].getAttribute("address");
            var type = markers[i].getAttribute("type");
            var point = new GLatLng(parseFloat(markers[i].getAttribute("lat")),
                                    parseFloat(markers[i].getAttribute("lng")));
            var marker = createMarker(point, name, address, type);
            map.addOverlay(marker);
          }
        });
      }
    }
 
    function createMarker(point, name, address, type) {
      var marker = new GMarker(point, customIcons[type]);
      var html = "<b>" + name + "</b> <br/>" + address;
      GEvent.addListener(marker, 'mouseover', function() {
        marker.openInfoWindowHtml(html);
      });
      return marker;
    }
    //]]>
  </script>
 
  </head>
 
  <body onload="load()" onunload="GUnload()">

        <!-- begin: .tray-center -->
<?php if(!isset($_GET['nomenu'])) : ?>
        <div class="tray-center">

  <div class="pull-right">
    <form class="form-inline" method="GET">
    <input type="text" class="form-control input-sm form-group" name="center" placeholder="<?php echo Yii::t('main', 'Osoite'); ?>" value="<?php echo $valCenter; ?>">
    <span class="form-group input-group-btn">
    <input type="submit"  class="btn btn-sm btn-primary" value="ok">
    </span>
    </form>
  </div>
  <h2 class="myBgColors p10"> <i class="fa fa-map"></i>  <?php echo Yii::t('main', 'KOHTEET KARTALLA'); ?> </h2>


        <!-- loppu: .tray-center -->
        </div>
<?php endif; ?>

  <div id="map" style="width: 100%; height: 100%"></div>

  </body>
</html>


