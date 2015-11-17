<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Strict//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-strict.dtd">



<html xmlns="http://www.w3.org/1999/xhtml">
  <head>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <title>Sivex OY XML kohteet</title>

    <script src="https://maps.google.com/maps?file=api&amp;v=2&amp;key=ABQIAAAACHCJdlgAEGcD_flKUFEmVhT2yXp_ZAY8_ufC3CFXhHIE1NvwkxTeukKcKHF3ezmjTB0q6gzSBmoIUQ" type="text/javascript"></script>
 

    <script type="text/javascript">
    //<![CDATA[



    var iconBlue = new GIcon(); 
    iconBlue.image = 'http://labs.google.com/ridefinder/images/mm_20_blue.png';
    iconBlue.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconBlue.iconSize = new GSize(12, 20);
    iconBlue.shadowSize = new GSize(22, 20);
    iconBlue.iconAnchor = new GPoint(6, 20);
    iconBlue.infoWindowAnchor = new GPoint(5, 1);
 
    var iconRed = new GIcon(); 
    iconRed.image = 'http://labs.google.com/ridefinder/images/mm_20_red.png';
    iconRed.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
    iconRed.iconSize = new GSize(12, 20);
    iconRed.shadowSize = new GSize(22, 20);
    iconRed.iconAnchor = new GPoint(6, 20);
    iconRed.infoWindowAnchor = new GPoint(5, 1);
 
    var iconGreen = new GIcon(); 
    iconGreen.image = 'http://labs.google.com/ridefinder/images/mm_20_green.png';
    iconGreen.shadow = 'http://labs.google.com/ridefinder/images/mm_20_shadow.png';
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

        map.setCenter(new GLatLng(60.2480743,24.9263055), 11);



        GDownloadUrl("googlemap_k", function(data) {
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

  <legend>
  <h1> <?php echo Yii::t('main', 'KOHTEET KARTALLA'); ?> <i class="glyphicon glyphicon-home"></i></h1>
  </legend>
  <div id="map" style="width: 100%; height: 70%"></div>

  </body>
</html>


