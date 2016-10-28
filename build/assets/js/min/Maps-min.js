function getLocation(){navigator.geolocation&&navigator.geolocation.getCurrentPosition(showPosition,showError)}function initMap(){map=new google.maps.Map(document.getElementById("map"),{center:{lat:59.911491,lng:10.757933},zoom:6,styles:styleArray,disableDefaultUI:!0,disableDoubleClickZoom:!0})}function showPosition(o){lat=o.coords.latitude,lng=o.coords.longitude,setMap({lat:lat,lng:lng,dist:25})}function showError(o){switch(o.code){case o.PERMISSION_DENIED:log("User denied the request for Geolocation.");break;case o.POSITION_UNAVAILABLE:log("Location information is unavailable.");break;case o.TIMEOUT:log("The request to get user location timed out.");break;case o.UNKNOWN_ERROR:log("An unknown error occurred.")}}function log(o){console.info(o)}function setMap(o){var t=10,a=[],e=[];$.get({url:"/api/nearby/"+o.lat+"/"+o.lng+"/"+o.dist,dataType:"json",success:function(t){console.log(t);for(var e=0;e<t.length;e++){var n=t[e],i={lat:Number(n.lat),lng:Number(n.lng)};image="/assets/img/icons/fish.png",a[e]=new google.maps.Marker({map:map,position:i,title:n.navn})}map.setZoom(9),map.panTo(o)}})}getLocation();var map,lat,lng;$("#map_range").change(function(){setMap({lat:lat,lng:lng,dist:this.value})});