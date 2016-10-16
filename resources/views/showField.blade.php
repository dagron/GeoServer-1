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
        #map { height: 95%; border: 1px solid #888; }
    </style>
    <script src='http://maps.google.com/maps?file=api&amp;v=2&amp;key=AIzaSyCf7Sg2gH85Dp1bbjyqnYw1M8kHkWAct60'></script>



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
            map.style.height = (getWindowHeight()-80) + "px";
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
                        if (selection === "ndvi") {
                             return "{{ URL::to($field['fieldFolder']) }}" + "/ndvi_tiles/" + zoom+"/"+tile.x+"/"+y+".png"; 
                        }
                        if (selection === "cir") {
                             return "{{ URL::to($field['fieldFolder']) }}" + "/cir_tiles/" + zoom+"/"+tile.x+"/"+y+".png";
                        }
                        if (selection === "nir_red_blue") {
                            return "{{ URL::to($field['fieldFolder']) }}" + "/nir_red_blue_tiles/" + zoom+"/"+tile.x+"/"+y+".png";
                        }
                        if (selection === "nir_green_blue") {
                            return "{{ URL::to($field['fieldFolder']) }}" + "/nir_green_blue_tiles/" + zoom+"/"+tile.x+"/"+y+".png";
                        }
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
            }
            resize();
        }

        onresize=function(){ resize(); };

        //]]>
    </script>

</head>
    <body onload="load()">
    <div style="float:left;margin:5px;" onclick="goBack()" class="btn-info btn">Back</div>
    <div style="float:left;margin:5px;" onclick="select('natural')" class="btn-info btn">Original</div>
    <div style="float:left;margin:5px;" onclick="select('ndvi')" class="btn-info btn">NDVI</div>
    <div style="float:left;margin:5px;" onclick="select('cir')" class="btn-info btn">CIR</div>
    <div style="float:left;margin:5px;" onclick="select('nir_red_blue')" class="btn-info btn">NIR_RED_BLUE</div>
    <div style="float:left;margin:5px;" onclick="select('nir_green_blue')" class="btn-info btn">NIR_GREEN_BLUE</div>

<br><br>

    <div id="map"></div>
</body>
@endsection
