@extends('layouts.app')

@section('content')
    <title>{{$field['fieldName']}}</title>
    <meta http-equiv="content-type" content="text/html; charset=utf-8"/>
    <meta http-equiv='imagetoolbar' content='no'/>
    <style type="text/css"> v\:* {behavior:url(#default#VML);}
        html, body { overflow: hidden; padding: 0; height: 100%; width: 100%; font-family: 'Lucida Grande',Geneva,Arial,Verdana,sans-serif; }
        body { margin: 10px; background: #fff; }
        h1 { margin: 0; padding: 6px; border:0; font-size: 20pt; }
        #header { height: 43px; padding: 0; background-color: #eee; border: 1px solid #888; }
        #subheader { height: 12px; text-align: right; font-size: 10px; color: #555;}
        #map { height: 70%; border: 1px solid #888; }
	#marker-selection{
		 background-color: white;
  		 position:fixed;
  		 bottom:0px;
         left:0px;
  		 height:35px;
		 border:1px solid black;
		 width: 100%;
 		 text-align:center;
		}
    </style>
<script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyC0k-EWY_XwMqvQvg0bwdELarriUrykXZ4'></script>



    <script>
        //<![CDATA[

        /*
         * Constants for given map
         * TODO: read it from tilemapresource.xml
         */

        var mapBounds = new GLatLngBounds(new GLatLng({{$field['y_min']}}, {{$field['x_min']}}), new GLatLng({{$field['y_max']}}, {{$field['x_max']}}));
        var mapMinZoom = 15;
        var mapMaxZoom = 24;

        var opacity = 1;
        var map;
        var hybridOverlay;

        // marker icons sizes

        var icon_height = 38;
        var icon_width  = 34;

        /* Markers cons
         * ============
         */ 
          var markers_json;
          var markers= new Array();
            var getMarkers ; // Get markers from server
          var marker_icon ='{{url('img/rice.png') }}';


	/*
	 * Polygon constants
	 * ===================
     */
    var getPolygons; // Get Polygons from server
    var savedPolygons = [];
    var polygonCenterMarkers = []; // Center Point of polygons

    var global = this;
	var PolygonMarkers = []; //Array for Map Markers
	var PolygonPoints = []; //Array for Polygon Node Markers
	var bounds = new GLatLngBounds; //Polygon Bounds
	var Polygon; //Polygon overlay object
	var polygon_resizing = false; //To track Polygon Resizing

    var polygon_icon =  '{{url('img/field.png') }}';




	//Polygon Marker/Node icons
	var redpin = new GIcon(); //Red Pushpin Icon
	redpin.image = "http://maps.google.com/mapfiles/ms/icons/red-pushpin.png";
	redpin.iconSize = new GSize(32, 32);
	redpin.iconAnchor = new GPoint(10, 32);
	var bluepin = new GIcon(); //Blue Pushpin Icon
	bluepin.image = "http://maps.google.com/mapfiles/ms/icons/blue-pushpin.png";
	bluepin.iconSize = new GSize(32, 32);
	bluepin.iconAnchor = new GPoint(10, 32);

	/*
         * Create a Custom Opacity GControl
         * http://www.maptiler.org/google-maps-overlay-opacity-control/
         */

        var CTransparencyLENGTH = 58;
        // maximum width that the knob can move (slide width minus knob width)

        function CTransparencyControl( overlay ) {
            this.overlay = overlay;
            this.opacity = overlay.getTileLayer().getOpacity();
        }
        CTransparencyControl.prototype = new GControl();

        // This function positions the slider to match the specified opacity
        CTransparencyControl.prototype.setSlider = function(pos) {
            var left = Math.round((CTransparencyLENGTH*pos));
            this.slide.left = left;
            this.knob.style.left = left+"px";
            this.knob.style.top = "0px";
        }

        // This function reads the slider and sets the overlay opacity level
        CTransparencyControl.prototype.setOpacity = function() {
            // set the global variable
            opacity = this.slide.left/CTransparencyLENGTH;
            this.map.clearOverlays();
            this.map.addOverlay(this.overlay, { zPriority: 0 });
            if (this.map.getCurrentMapType() == G_HYBRID_MAP) {
                this.map.addOverlay(hybridOverlay);
            }
        }

        // This gets called by the API when addControl(new CTransparencyControl())
        CTransparencyControl.prototype.initialize = function(map) {
            var that=this;
            this.map = map;

            // Is this MSIE, if so we need to use AlphaImageLoader
            var agent = navigator.userAgent.toLowerCase();
            if ((agent.indexOf("msie") > -1) && (agent.indexOf("opera") < 1)){this.ie = true} else {this.ie = false}

            // create the background graphic as a <div> containing an image
            var container = document.createElement("div");
            container.style.width="70px";
            container.style.height="21px";

            // Handle transparent PNG files in MSIE
            if (this.ie) {
                var loader = "filter:progid:DXImageTransform.Microsoft.AlphaImageLoader(src='http://www.maptiler.org/img/opacity-slider.png', sizingMethod='crop');";
                container.innerHTML = '<div style="height:21px; width:70px; ' +loader+ '" ></div>';
            } else {
                container.innerHTML = '<div style="height:21px; width:70px; background-image: url(http://www.maptiler.org/img/opacity-slider.png)" ></div>';
            }

            // create the knob as a GDraggableObject
            // Handle transparent PNG files in MSIE
            if (this.ie) {
                var loader = "progid:DXImageTransform.Microsoft.AlphaImageLoader(src='http://www.maptiler.org/img/opacity-slider.png', sizingMethod='crop');";
                this.knob = document.createElement("div");
                this.knob.style.height="21px";
                this.knob.style.width="13px";
                this.knob.style.overflow="hidden";
                this.knob_img = document.createElement("div");
                this.knob_img.style.height="21px";
                this.knob_img.style.width="83px";
                this.knob_img.style.filter=loader;
                this.knob_img.style.position="relative";
                this.knob_img.style.left="-70px";
                this.knob.appendChild(this.knob_img);
            } else {
                this.knob = document.createElement("div");
                this.knob.style.height="21px";
                this.knob.style.width="13px";
                this.knob.style.backgroundImage="url(http://www.maptiler.org/img/opacity-slider.png)";
                this.knob.style.backgroundPosition="-70px 0px";
            }
            container.appendChild(this.knob);
            this.slide=new GDraggableObject(this.knob, {container:container});
            this.slide.setDraggableCursor('pointer');
            this.slide.setDraggingCursor('pointer');
            this.container = container;

            // attach the control to the map
            map.getContainer().appendChild(container);

            // init slider
            this.setSlider(this.opacity);

            // Listen for the slider being moved and set the opacity
            GEvent.addListener(this.slide, "dragend", function() {that.setOpacity()});
            //GEvent.addListener(this.container, "click", function( x, y ) { alert(x, y) });

            return container;
        }

        // Set the default position for the control
        CTransparencyControl.prototype.getDefaultPosition = function() {
            return new GControlPosition(G_ANCHOR_TOP_RIGHT, new GSize(7, 47));
        }

        /*
         * Full-screen Window Resize
         */

        function getWindowHeight() {
            if (self.innerHeight) return self.innerHeight;
            if (document.documentElement && document.documentElement.clientHeight)
                return document.documentElement.clientHeight;
            if (document.body) return document.body.clientHeight;
            return 0;
        }

        function getWindowWidth() {
            if (self.innerWidth) return self.innerWidth;
            if (document.documentElement && document.documentElement.clientWidth)
                return document.documentElement.clientWidth;
            if (document.body) return document.body.clientWidth;
            return 0;
        }

        function resize() {
            var map = document.getElementById("map");
            var header = document.getElementById("header");
            var subheader = document.getElementById("subheader");
            map.style.height = (getWindowHeight()-180) + "px";
            map.style.width = (getWindowWidth()-20) + "px";
            header.style.width = (getWindowWidth()-20) + "px";
            subheader.style.width = (getWindowWidth()-20) + "px";
            // map.checkResize();
        }

      // var  tile_url = "{{ URL::to($field['fieldFolder']) }}" + "/" + zoom+"/"+tile.x+"/"+y+".png";

        selection = "natural";
        
        function select(selection_name){
            selection = selection_name;
            load();
        }

        /*
         * Main load function:
         */

        function load() {

            if (GBrowserIsCompatible()) {

                // Bug in the Google Maps: Copyright for Overlay is not correctly displayed
                var gcr = GMapType.prototype.getCopyrights;
                GMapType.prototype.getCopyrights = function(bounds,zoom) {
                    return [""].concat(gcr.call(this,bounds,zoom));
                }

                map = new GMap2( document.getElementById("map"), { backgroundColor: '#fff' } );

                map.addMapType(G_PHYSICAL_MAP);
                map.setMapType(G_PHYSICAL_MAP);

                map.setCenter( mapBounds.getCenter(), map.getBoundsZoomLevel( mapBounds ));

                hybridOverlay = new GTileLayerOverlay( G_HYBRID_MAP.getTileLayers()[1] );
                GEvent.addListener(map, "maptypechanged", function() {
                    if (map.getCurrentMapType() == G_HYBRID_MAP) {
                        map.addOverlay(hybridOverlay);
                    } else {
                        map.removeOverlay(hybridOverlay);
                    }
                } );


                var tilelayer = new GTileLayer(GCopyrightCollection(''), mapMinZoom, mapMaxZoom);
                var mercator = new GMercatorProjection(mapMaxZoom+1);
                tilelayer.getTileUrl = function(tile,zoom) {
                    if ((zoom < mapMinZoom) || (zoom > mapMaxZoom)) {
                        return "http://www.maptiler.org/img/none.png";
                    }
                    var ymax = 1 << zoom;
                    var y = ymax - tile.y -1;
                    var tileBounds = new GLatLngBounds(
                            mercator.fromPixelToLatLng( new GPoint( (tile.x)*256, (tile.y+1)*256 ) , zoom ),
                            mercator.fromPixelToLatLng( new GPoint( (tile.x+1)*256, (tile.y)*256 ) , zoom )
                    );                        


            		if (mapBounds.intersects(tileBounds)) {
                        if (selection === "natural") {
                            return "{{ URL::to($field['fieldFolder']) }}" + "/" + zoom+"/"+tile.x+"/"+y+".png";
                        }
                        return "{{ URL::to($field['fieldFolder']) }}" + "/processes/"+selection+"/" + zoom+"/"+tile.x+"/"+y+".png"; 
                        
                    } else {
                        return "http://www.maptiler.org/img/none.png";
                    }
                }
 
                // IE 7-: support for PNG alpha channel
                // Unfortunately, the opacity for whole overlay is then not changeable, either or...
                tilelayer.isPng = function() { return true;};
                tilelayer.getOpacity = function() { return opacity; }

                overlay = new GTileLayerOverlay( tilelayer );
                map.addOverlay(overlay);

                map.addControl(new GLargeMapControl());
                map.addControl(new GHierarchicalMapTypeControl());
                map.addControl(new CTransparencyControl( overlay ));



                map.enableContinuousZoom();
                map.enableScrollWheelZoom();

                map.setMapType(G_HYBRID_MAP);


            GEvent.addListener(map, 'click', function(overlay,clickPoint, overlaypoint) {
		    if(document.getElementById('marker').checked) {
		if(clickPoint){
                  var point = new GLatLng( clickPoint.lat(), clickPoint.lng() ); 

                  var marker = new GMarker(point);
                  marker.f.image = marker_icon;
                  marker.f.iconSize.height = icon_height;
                  marker.f.iconSize.width = icon_width;
                  map.addOverlay(marker);

		          marker.lat = clickPoint.lat();
		          marker.lng = clickPoint.lng();
                  marker.id = null;
                  markers.push(marker)
                  GEvent.addListener(marker, "click", function() {
                        marker.openInfoWindowHtml(
                                '<div >'+
                                '{{ trans("showfield.title")  }}:<input type="text" id="title">'+
                                '<br>{{ trans("showfield.description")}}:<br>'+
                                '<textarea type="text" id="description" rows="5" cols="50"  name="description"></textarea>'+
                                '<br><button onclick="removeMarkerNote('+ marker.id+')">{{ trans("showfield.delete") }} </button>'+
				'<button onclick="saveMarkerNote('+marker.lat+','+marker.lng+','+{{$field['id']}}+')">{{trans("showfield.save")  }}</button>');
                  });
		}
             }

            });
	/*
	 * Polygon initialization 
	 * =======================
	 */

        var ui = new GMapUIOptions(); //Map UI options
        ui.maptypes = { normal:true, satellite:true, hybrid:true, physical:false }
        ui.zoom = {scrollwheel:true, doubleclick:true};
        ui.controls = { largemapcontrol3d:true, maptypecontrol:true, scalecontrol:true };
        map.setUI(ui); //Set Map UI options

        //Add Shift+Click event to add Polygon markers
        GEvent.addListener(map, "click", function(overlay, point, overlaypoint) {
            var p = (overlaypoint) ? overlaypoint : point;
            //Add polygon marker if overlay is not an existing marker and shift key is pressed
            if (document.getElementById('polygon').checked && !checkPolygonMarkers(overlay)) { addMarker(p); }
        });

		//event listener
    

            /*
             * --------------------------
             *  Add saved markers in Map
             *  --------------------------
             */
         (getMarkers= function(map) {
          
             var base_url = window.location.origin;

            var http = new XMLHttpRequest();
            http.onreadystatechange = function() {//Call a function when the state changes.
                    if(http.readyState == 4 && http.status == 200) {

                       markers_json = JSON.parse(http.responseText);
                       
                       for(var i=0 ; i<markers_json.length ;i++)
                        {

                        //create marker
                        var marker =  new GMarker(new GLatLng(markers_json[i].lat, markers_json[i].lng));
                        marker.f.image = marker_icon;
                        marker.f.iconSize.height = icon_height;
                        marker.f.iconSize.width = icon_width;
                        marker.title = markers_json[i].title;
                        marker.description = markers_json[i].description;            
                        marker.id = markers_json[i].id;
                        markers.push(marker);
                        //add marker to map
                        map.addOverlay(marker);


                        //pass i in the closure
                        (function(i) {


                            // toggle on click
                            GEvent.addListener(marker, "click", function() {
                             markers[i].openInfoWindowHtml(
                                '<div >'+
                                '{{ trans("showfield.title") }}:<input type="text" id="title" value="'+markers[i].title +'" readonly>'+
                                '<br>{{ trans("showfiels.description")}}:<br>'+
                                '<textarea id="description" rows="5" cols="50"  name="description" readonly>'+markers[i].description+'</textarea>'+
                                '<br><button onclick="removeMarkerNote('+ markers[i].id+')" >{{ trans("showfield.delete") }}</button>');
                            });
                        })(i);
                       }

                    }
               }

            http.open("GET", base_url + '/api/markers/{{$field['id']}}');
            http.setRequestHeader("Content-type", "application/json;charset=UTF-8");

            http.send();
             })(map);

            /* ---------------------------- 
             * Get Polygons from serever
             * ----------------------------
             */

          (getPolygons = function(map) {
                var base_url = window.location.origin;

                var http = new XMLHttpRequest();
                http.onreadystatechange = function() {

                    if(http.readyState == 4 && http.status == 200) {
                        // Create polygons
                        var re_polygons =  JSON.parse(http.responseText)
                               for (var i =0 ; i<re_polygons.length ; i++)
                               {
                                   var re_poly_markers = JSON.parse(re_polygons[i].polygon_data);
                                   var latlngPoints = [];
                                   for (var j =0; j<re_poly_markers.length - 1 ; j++)
                                   {
                                       console.log(i + " " +j)
                                      var latlng =  new GLatLng(re_poly_markers[j].lat,re_poly_markers[j].lng);
                                      latlngPoints.push(latlng);
                                   }
                                   drawPolygonFor(latlngPoints);
                                   var center_marker = re_poly_markers.pop();



                                    //Render Center Marker
                                     var marker =  new GMarker(new GLatLng(center_marker.lat, center_marker.lng));
                                     marker.f.image = polygon_icon;
                                     marker.f.iconSize.height = icon_height;
                                     marker.f.iconSize.width = icon_width;
                                     marker.title = center_marker.title;
                                     marker.description = center_marker.description;            
                                    marker.id = re_polygons[i].id;
                                     polygonCenterMarkers.push(marker);
                               console.log(center_marker.title , center_marker.description); 
                                   //add marker to map
                                   map.addOverlay(marker);
                            var l = polygonCenterMarkers.length -1;
console.log(polygonCenterMarkers);
                        //pass i in the closure
                        (function(l) {


                            // toggle on click
                            GEvent.addListener(marker, "click", function() {
                             polygonCenterMarkers[l].openInfoWindowHtml(
                                '<div >'+
                                '{{ trans("showfield.title") }}:<input type="text" id="title" value="'+polygonCenterMarkers[l].title +'" readonly>'+
                                '<br>{{ trans("showfield.description")}}:<br>'+
                                '<textarea id="description" rows="5" cols="50"  name="description" readonly>'+polygonCenterMarkers[l].description+'</textarea>'+
                                '<br><button onclick="removePolygonNote('+ polygonCenterMarkers[l].id+')" >{{ trans("showfield.delete")}} </button>');
                            });
                        })(i);
        
                       }
                    }
                }
                http.open("GET", base_url + '/api/polygons/{{$field['id']}}');
                http.setRequestHeader("Content-type", "application/json;charset=UTF-8");

                http.send();
             })(map);


            resize();
            }
        }

        onresize=function(){ resize(); };


        function removePolygonNote(id) {
            for ( var i =0 ; i<polygonCenterMarkers.length ; i++) {
                if(polygonCenterMarkers[i].id == id) {
                   var base_url = window.location.origin;
                   polygonCenterMarkers[i].closeInfoWindow();
                    polygonCenterMarkers[i].remove();
                  var http = new XMLHttpRequest();
                  http.onreadystatechange = function() {//Call a function when the state changes.
                    if(http.readyState == 4 && http.status == 200) {
                                  console.log('deleted');
                                  location.reload();
                               }
                  }

                 http.open("DELETE", base_url + '/api/polygons/'+polygonCenterMarkers[i].id );
                 http.setRequestHeader("Content-type", "application/json;charset=UTF-8");

                 http.send();
                }
            }

        }





    //Remove Marker     
        function removeMarkerNote(id) {
            if(id) {
            for(var i = 0 ; i< markers.length ; i++){
                if(markers[i].id === id) {
                  var base_url = window.location.origin;
                    markers[i].closeInfoWindow();
                    markers[i].remove();
                  var http = new XMLHttpRequest();
                  http.onreadystatechange = function() {//Call a function when the state changes.
                    if(http.readyState == 4 && http.status == 200) {
                                  console.log('deleted');
                               }
                  }

                 http.open("DELETE", base_url + '/api/markers/'+markers[i].id );
                 http.setRequestHeader("Content-type", "application/json;charset=UTF-8");

                 http.send();
            
                  }
            }
            } else {
                for (var i =0 ; i<markers.length ; i++ ) {
                    if(markers[i].id == null) {
                        markers[i].closeInfoWindow();
                        markers[i].remove();
                    }
                }
            }
        }
        

        function saveMarkerNote(lat, lng, id) {
            
            var base_url = window.location.origin;

            var title = document.getElementById('title').value;
            var description = document.getElementById('description').value;
            
            var http = new XMLHttpRequest();
            var params = {
                    field_id: id,
                    lat: lat,
                    lng: lng,
                    title: title,
                    description: description
            }
            http.onreadystatechange = function() {//Call a function when the state changes.
                    if(http.readyState == 4 && http.status == 200) {
                               console.log(http.responseText);
                               for(var i =0; i<markers.length ; i++)
                               {
                                    markers[i].closeInfoWindow();
                                    markers[i].remove();
                                    console.log('loop');
                               }
                               
                               markers = [];
                               getMarkers(map);
                           }
                    }

            http.open("POST", base_url + '/api/createMarker');
            http.setRequestHeader("Content-type", "application/json;charset=UTF-8");


            http.send(JSON.stringify(params));
            
        }

/*
 * Function For Handling Polygon
 *===================================
 */


// Adds a new Polygon boundary marker
function addMarker(point) {
    var markerOptions = { icon: bluepin, draggable: true };
    var marker = new GMarker(point, markerOptions);
    PolygonMarkers.push(marker); //Add marker to PolygonMarkers array
    map.addOverlay(marker); //Add marker on the map
    GEvent.addListener(marker,'dragstart',function(){ //Add drag start event
        marker.setImage(redpin.image);
        polygon_resizing = true;
    });
    GEvent.addListener(marker,'drag',function(){ drawPolygon(); }); //Add drag event
    GEvent.addListener(marker,'dragend',function(){   //Add drag end event
        marker.setImage(bluepin.image);
        polygon_resizing = false;
        drawPolygon();
        fitPolygon();
    });
    GEvent.addListener(marker,'dblclick',function(point) { //Add Ctrl+Click event to remove marker
         removeMarker(point); 
    });
    drawPolygon();

    //If more then 2 nodes then automatically fit the polygon
    if(PolygonMarkers.length > 2) fitPolygon();
}

// Removes a Polygon boundary marker
function removeMarker(point) {
    if(PolygonMarkers.length == 1){ //Only one marker in the array
        map.removeOverlay(PolygonMarkers[0]);
        map.removeOverlay(PolygonMarkers[0]);
        PolygonMarkers = [];
        if(Polygon){map.removeOverlay(Polygon)};
    }
      else //More then one marker
      {
        var RemoveIndex = -1;
        var Remove;
        //Search for clicked Marker in PolygonMarkers Array
        for(var m=0; m<PolygonMarkers.length; m++)
        {
            if(PolygonMarkers[m].getPoint().equals(point))
            {
                RemoveIndex = m; Remove = PolygonMarkers[m]
                break;
            }
        }
        //Shift Array elemeents to left
        for(var n=RemoveIndex; n<PolygonMarkers.length-1; n++)
        {
            PolygonMarkers[n] = PolygonMarkers[n+1];
        }
        PolygonMarkers.length = PolygonMarkers.length-1 //Decrease Array length by 1
        map.removeOverlay(Remove); //Remove Marker
        drawPolygon(); //Redraw Polygon
      }
}

//Draw Polygon from the PolygonMarkers Array
function drawPolygon()
{
    PolygonPoints.length=0;
    for(var m=0; m<PolygonMarkers.length; m++)
    {
        PolygonPoints.push(PolygonMarkers[m].getPoint()); //Add Markers to PolygonPoints node array
    }
    //Add first marker in the end to close the Polygon
    PolygonPoints.push(PolygonMarkers[0].getPoint());
    if(Polygon){ map.removeOverlay(Polygon); } //Remove existing Polygon from Map
    var fillColor = (polygon_resizing) ? 'red' : 'blue'; //Set Polygon Fill Color
    Polygon = new GPolygon(PolygonPoints, '#FF0000', 2, 1, fillColor, 0.2); //New GPolygon object
    map.addOverlay(Polygon); //Add Polygon to the Map

    //TO DO: Function Call triggered after Polygon is drawn
}

//Draw polygon for loaded set of markers
function drawPolygonFor(markers)
{
   //  var polgon_points.length = 0; 
   //  for (var i =0 ; i < markers.length ;i++)
   //  {
   //      polygon_points.push(markers[i].
   //  }
    var fillColor = (polygon_resizing) ? 'red' : 'blue'; //Set Polygon Fill Color
    var Polygon = new GPolygon(markers , '#FF0000', 2, 1, fillColor, 0.2); //New GPolygon object
    map.addOverlay(Polygon); //Add Polygon to the Map


}



//Fits the Map to Polygon bounds
function fitPolygon(){
    bounds = Polygon.getBounds();
    //map.setCenter(bounds.getCenter(), map.getBoundsZoomLevel(bounds));
}
//check is the marker is a polygon boundary marker
function checkPolygonMarkers(marker) {
    var flag = false;
    for (var m = 0; m < PolygonMarkers.length; m++) {
        if (marker == PolygonMarkers[m])
        { flag = true; break; }
    }
    return flag;
}



function savePolygonShape() {
    console.log('save polugon');
    console.log(PolygonMarkers);
    if (PolygonMarkers.length<2) {
        alert("{{ trans('errors.polygon_save') }}");
    } else {
    var poly_markers = [];
    for(var i = 0 ; i< PolygonMarkers.length ; i++)
    {
        var polygon_point = {
            lat:PolygonMarkers[i].getPoint().lat(),
            lng:PolygonMarkers[i].getPoint().lng()
       }
        poly_markers.push(polygon_point);
    }


    var center_point = getPolygonCenter();

    center_point.title = document.getElementById('polygon-title').value;
    center_point.description = document.getElementById('polygon-description').value;
console.log('title just b save ' + center_point.title)
    poly_markers.push(center_point);

    var base_url = window.location.origin;

    var params = { 
        polygon: poly_markers,
        field_id:"{{$field['id']}}",
          
    };

            var http = new XMLHttpRequest();
            http.onreadystatechange = function() {//Call a function when the state changes.
                    if(http.readyState == 4 && http.status == 200) {
                              console.log('polygon saved') 
                              location.reload();
                      }
                           
                    }

            http.open("POST", base_url + '/api/polygons');
            http.setRequestHeader("Content-type", "application/json;charset=UTF-8");

            console.log(JSON.stringify(params));
            http.send(JSON.stringify(params));
    }
}

/*
 * Find center point of polygon
 */
function getPolygonCenter() {
    var x_min = x_max = PolygonMarkers[0].getPoint().lat();
    var y_min = y_max = PolygonMarkers[0].getPoint().lng();

    for (var i = 1 ; i<PolygonMarkers.length; i++) {
        if(PolygonMarkers[i].getPoint().lat() < x_min) {
            x_min = PolygonMarkers[i].getPoint().lat(); 
        } 

        if(PolygonMarkers[i].getPoint().lat()> x_max) {
            x_max = PolygonMarkers[i].getPoint().lat();
        }

        if(PolygonMarkers[i].getPoint().lng() < y_min) {
            y_min = PolygonMarkers[i].getPoint().lng()
        }

        if(PolygonMarkers[i].getPoint().lng() > y_max) {
            y_max = PolygonMarkers[i].getPoint().lng();
        }
    }
    var x_center = x_min + ((x_max - x_min)/2);
    var y_center =  y_min + ((y_max - y_min)/2);
    var center = {
        lat: x_center,
        lng: y_center
    }
    console.log(center);
    return center;
}



//////////////////[ Key down event handler ]/////////////////////
//Event handler class to attach events
var EventUtil = {
      addHandler: function(element, type, handler){
            if (element.addEventListener){
                    element.addEventListener(type, handler, false);
            } else if (element.attachEvent){
                    element.attachEvent("on" + type, handler);
            } else {
                    element["on" + type] = handler;
            }
      }
};

// Attach Key down/up events to document
EventUtil.addHandler(document, "keydown", function(event){keyDownHandler(event)});
EventUtil.addHandler(document, "keyup", function(event){keyUpHandler(event)});

//Checks for shift and Ctrl key press
function keyDownHandler(e)
{
      if (!e) var e = window.event;
      var target = (!e.target) ? e.srcElement : e.target;
      if (e.keyCode == 16 && !global.shiftKey) { //Shift Key
            global.shiftKey = true;
      }
      if (e.keyCode == 17 && !global.ctrlKey) { //Ctrl Key
            global.ctrlKey = true;
      }
   
}
//Checks for shift and Ctrl key release
function keyUpHandler(e){
      if (!e) var e = window.event;
      if (e.keyCode == 16 && global.shiftKey) { //Shift Key
            global.shiftKey = false;
      }
      if (e.keyCode == 17 && global.ctrlKey) { //Ctrl Key
            global.ctrlKey = false;
      }
   
}



        //]]>
    </script>
<style>
/* The Modal (background) */
.modal {
    display: none; /* Hidden by default */
    position: fixed; /* Stay in place */
    z-index: 1; /* Sit on top */
    padding-top: 100px; /* Location of the box */
    left: 0;
    top: 0;
    width: 100%; /* Full width */
    height: 100%; /* Full height */
    overflow: auto; /* Enable scroll if needed */
    background-color: rgb(0,0,0); /* Fallback color */
    background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

/* Modal Content */
.modal-content {
    background-color: #fefefe;
    margin: auto;
    padding: 20px;
    border: 1px solid #888;
    width: 80%;
}

/* The Close Button */
.close {
    color: #aaaaaa;
    float: right;
    font-size: 28px;
    font-weight: bold;
}

.close:hover,
.close:focus {
    color: #000;
    text-decoration: none;
    cursor: pointer;
}
</style>
</head>

    <body onload="load()">

<!-- Modal -->
<div id="myModal" class="modal">
    <div class="modal-content">
            <span class="close"> x </span>
          <h4 class="modal-title">{{ trans('showfield.savepolygon') }}</h4>
        <div class="">
            <label>{{ trans('showfield.areatitle') }}</label>
            <input id="polygon-title" type="text" class="form-control"></input><br>
            <label>{{ trans('showfield.areadescription') }}</label>
            <textarea  id="polygon-description"  type="text" class="form-control" rows="5"></textarea><br>
        </div>
        <div class="">
          <button type="button" class="btn btn-default"   onclick="savePolygonShape()">{{ trans('showfield.save') }}</button>
        </div>
    </div>
</div>
<div class="row">
    <div class="col-xs-2"> 
     <div style="" onclick="goBack()" class="btn-info btn">{{ trans('showfield.back') }}</div>
    </div>
<div class="col-xs-5"> 
<div class="dropdown">
  <button class="btn btn-info dropdown-toggle form-control" type="button" id="dropdownMenu1" data-toggle="dropdown" aria-haspopup="true" aria-expanded="true">
    {{ trans('showfield.processes') }}
    <span class="caret"></span>
  </button>

 <ul class="dropdown-menu" aria-labelledby="dropdownMenu1">
    <li style="" onclick="select('natural')" class=""><a>{{ trans('showfield.original') }}</a></li>
    @foreach ($processes as $process)
    <li style="" onclick="select('{{$process}}')" class=""> 
       <a> <?=str_replace('_',' ',str_replace('_tiles', ' ', $process))?> </a>
    </li>
    @endforeach
 </ul>

</div>
</div>
    <form action="/api/download/{{ $field['user_id']}}/{{$field['fieldName']}}/{{$field['date']}}" method="get" >
    <input type="submit" style="float:right;margin-right:40px;" class="btn btn-info" value="{{trans('general.download')}}"> </input>
    </form>
</div>
<br>
    <div style="" id="map"></div>
   <div id="marker-selection">
    <form>
     <label class="radio-inline">
       <input type="radio" name="optradio" id="marker" checked onclick="hideSaveButton()">{{ trans('showfield.marker') }}</input>
     </label>
     <label class="radio-inline">
       <input type="radio" name="optradio" id="polygon" onclick="showSaveButton()" >{{ trans('showfield.polygon') }}</input>
     </label>
        <div class="btn" id="polygon-save" style="visibility:hidden;"  >{{ trans('showfield.savepolygon') }}</div>
      </form>
     </div>

    <script>
var modal = document.getElementById('myModal');

var btn = document.getElementById("polygon-save");
var span = document.getElementsByClassName("close")[0];

btn.onclick = function() {
        modal.style.display = "block";
}
span.onclick = function() {
        modal.style.display = "none";
}
window.onclick = function(event) {
        if (event.target == modal) {
                    modal.style.display = "none";
                        }
}


       function showSaveButton(){
            document.getElementById('polygon-save').style.visibility = 'visible';
        }
        function hideSaveButton(){
            document.getElementById('polygon-save').style.visibility = 'hidden';
        }
</script>
  
</body>

@endsection
