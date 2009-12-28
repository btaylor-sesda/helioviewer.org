<?php 
    require_once('api/Config.php');
    new Config("settings/Config.ini");
    error_reporting(E_ALL | E_STRICT);
    $errorLog = HV_ERROR_LOG;
    if(!file_exists($errorLog))
        touch($errorLog);
?>
<!DOCTYPE html>
<html lang="en">
    <head>
        <?php printf("<!-- Helioviewer rev. %s, %s-->\n", HV_BUILD_NUM, HV_LAST_UPDATE);?>
        <title>Helioviewer - Solar and heliospheric image visualization tool</title>
        <link rel="shortcut icon" href="favicon.ico" />
        <meta http-equiv="X-UA-Compatible" content="chrome=1">
        <meta charset="utf-8" />
        <meta http-equiv="Cache-Control" content="No-Cache" />
        <meta name="description" content="Helioviewer - Solar and heliospheric image visualization tool" />
        <meta name="keywords" content="Helioviewer, hv, jpeg 2000, jp2, solar image viewer, sun, solar, heliosphere, solar physics, viewer, visualization, space, astronomy, SOHO, EIT, LASCO, SDO, MDI, coronagraph, " />

        <!--[if IE]>
        <script src="http://html5shiv.googlecode.com/svn/trunk/html5.js"></script>
        <![endif]-->
        
        <!-- Twitter Jetpack -->
        <!-- <link rel="jetpack" href="lib/jetpack/helioviewer-twitter-jetpack.js" name="Helioviewer.org Twitter Jetpack">-->

        <!-- YUI CSS Reset -->
        <link rel="stylesheet" type="text/css" href="http://yui.yahooapis.com/combo?2.8.0/build/reset-fonts/reset-fonts.css"> 
        
        <!-- jQuery -->
        <!-- <script src="http://ajax.googleapis.com/ajax/libs/jquery/1.3.2/jquery.min.js" type="text/javascript"></script>-->
        <script src="lib/jquery/jquery-1.4a2.js" type="text/javascript"></script>
        <script src="http://ajax.googleapis.com/ajax/libs/jqueryui/1.7.1/jquery-ui.min.js" type="text/javascript"></script>
        <script src="lib/jquery/jquery-class-support/jquery.class.js" type="text/javascript"></script>
        <script src="lib/jquery/jquery-qtip-nightly/jquery.qtip.js" type="text/javascript"></script>
        <script src="lib/jquery/jquery-qtip-nightly/jquery.qtip.tips.js" type="text/javascript"></script>
        <link rel="stylesheet" type="text/css" href="lib/jquery/jquery-qtip-nightly/jquery.qtip.css">
        <!--<script src="lib/jquery/jquery-qtip-1.0.0-rc3091551/jquery.qtip-1.0.0-rc3.min.js" type="text/javascript"></script>-->
        
        
        <!-- TODO: move jquery-dynaccordion to /lib/helioviewer with rest of custom code -->
        <!-- TODO: Include compiled versions of Kakadu? -->
        <script src="lib/jquery/jquery-dynaccordion/ui.dynaccordion.js" type="text/javascript"></script>
        <link rel="stylesheet" href="lib/jquery/jquery.ui-1.7.1/css/dot-luv-modified/jquery-ui-1.7.1.custom.css" type="text/css" />    

        <!-- Mousewheel support -->
        <script src="lib/jquery/jquery.mousewheel.3.0.2/jquery.mousewheel.min.js" type="text/javascript"></script>

        <!-- jGrowl -->
        <script src="lib/jquery/jGrowl/jquery.jgrowl_minimized.js" type="text/javascript"></script>
        <link rel="stylesheet" href="lib/jquery/jGrowl/jquery.jgrowl.css" type="text/css" />    

        <!-- imgAreaSelect jQuery plugin -->
        <script src="lib/jquery/imgareaselect-0.8/jquery.imgareaselect-0.8.js" type="text/javascript"></script>

        <!-- CookieJar -->
        <script type="text/javascript" src="lib/Cookiejar/jquery.json.js"></script>
        <script type="text/javascript" src="lib/Cookiejar/jquery.cookie.js"></script>
        <script type="text/javascript" src="lib/Cookiejar/jquery.cookiejar.pack.js"></script>

        <!-- date.js -->
        <script src="lib/date.js/date-en-US.js" type="text/javascript"></script>
        
        <!-- Simile -->
        <!--<script src="http://static.simile.mit.edu/timeline/api-2.2.0/timeline-api.js" type="text/javascript"></script>-->
        
        <!-- Pixastic -->
        <!-- jQuery("img.tile[src!=images/transparent_512.gif]"); -->
        <!--<script src="lib/pixastic/pixastic.custom.js" type="text/javascript"></script>-->

        <!-- ShadowBox -->
        <link rel="stylesheet" type="text/css" href="lib/shadowbox/shadowbox.css">
        <script type="text/javascript" src="lib/shadowbox/shadowbox.js"></script>
        <script type="text/javascript" src="lib/shadowbox/adapters/shadowbox-jquery.js"></script>
        <script type="text/javascript" src="lib/shadowbox/players/shadowbox-iframe.js"></script>
           <script type="text/javascript" src="lib/shadowbox/players/shadowbox-html.js"></script>
           <script type="text/javascript" src="lib/shadowbox/players/shadowbox-img.js"></script>        
        <script type="text/javascript" src="lib/shadowbox/languages/shadowbox-en.js"></script>
        <script type="text/javascript">
            Shadowbox.init({
                overlayOpacity: 0.5
            });
        </script>
        
        <!-- Helioviewer-Specific -->
        <script src="lib/helioviewer/HelperFunctions.js" type="text/javascript"></script>
        <script src="lib/helioviewer/Viewport.js" type="text/javascript"></script>
        <script src="lib/helioviewer/ViewportHandlers.js" type="text/javascript"></script>
        <script src="lib/helioviewer/TreeSelect.js" type="text/javascript"></script>
        <script src="lib/helioviewer/Layer.js" type="text/javascript"></script>
        <script src="lib/helioviewer/TileLayer.js" type="text/javascript"></script>
        <script src="lib/helioviewer/EventLayer.js" type="text/javascript"></script>
        <script src="lib/helioviewer/EventMarker.js" type="text/javascript"></script>
        <script src="lib/helioviewer/ZoomControls.js" type="text/javascript"></script>
        <script src="lib/helioviewer/MessageConsole.js" type="text/javascript"></script>
        <script src="lib/helioviewer/LayerManager.js" type="text/javascript"></script>
        <script src="lib/helioviewer/EventLayerAccordion.js" type="text/javascript"></script>
        <script src="lib/helioviewer/TileLayerManager.js" type="text/javascript"></script>
        <script src="lib/helioviewer/EventLayerManager.js" type="text/javascript"></script>
        <script src="lib/helioviewer/TileLayerAccordion.js" type="text/javascript"></script>
        <script src="lib/helioviewer/Time.js" type="text/javascript"></script>
        <script src="lib/helioviewer/TimeControls.js" type="text/javascript"></script>
        <script src="lib/helioviewer/MediaSettings.js" type="text/javascript"></script>
        <script src="lib/helioviewer/MovieBuilder.js" type="text/javascript"></script>
        <script src="lib/helioviewer/ScreenshotBuilder.js" type="text/javascript"></script>
        <script src="lib/helioviewer/ImageSelectTool.js" type="text/javascript"></script>
        <script src="lib/helioviewer/IconPicker.js" type="text/javascript"></script>
        <script src="lib/helioviewer/UserSettings.js" type="text/javascript"></script>
        <script src="lib/helioviewer/Helioviewer.js" type="text/javascript"></script>
        <!--<script src="lib/helioviewer/build/helioviewer-all-min.js" type="text/javascript"></script>-->

        <!-- Helioviewer.org custom styles -->
        <link rel="stylesheet" type="text/css" href="styles/main.css" />
        <link rel="stylesheet" type="text/css" href="styles/viewport.css" />
        <link rel="stylesheet" type="text/css" href="styles/events.css" />
        <link rel="stylesheet" type="text/css" href="styles/dialogs.css" />
        <link rel="stylesheet" type="text/css" href="styles/tooltips.css" />
        <link rel="stylesheet" type="text/css" href="styles/timenav.css" />
        <link rel="stylesheet" type="text/css" href="styles/accordions.css" />
        <link rel="stylesheet" type="text/css" href="styles/sliders.css" />
        
        <!-- Theme Modifications -->
        <link rel="stylesheet" type="text/css" href="styles/dot-luv.css">
        
        <!--[if IE]>
            <link href="styles/main-ie.css" rel="stylesheet" type="text/css" />
        <![endif]-->
        
        <script type="text/javascript">
            $(function () {
                <?php
                    // View
                    $view = array();

                    //API Example: helioviewer.org/?date=2003-10-05T00:00:00Z&imageScale=2.63&imageLayers=[SOHO,EIT,EIT,171,1,70],[SOHO,LASCO,C2,white light,0,100]
                    if (isset($_GET['imageLayers'])) {
                        $imageLayersString = ($_GET['imageLayers'][0] == "[") ? substr($_GET['imageLayers'],1,-1) : $_GET['imageLayers'];
                        $imageLayers = split("\],\[", $imageLayersString);
                        $view['imageLayers'] = $imageLayers;
                    }
                
                    if (isset($_GET['date']))
                        $view['date'] = $_GET['date'];
                        
                    if (isset($_GET['imageScale']))
                        $view['imageScale'] = $_GET['imageScale'];
                        
                    // Convert to JSON
                    printf("var view = %s;\n", json_encode($view));
               
                    echo "\t\t\t\t";

                    // Default settings
                    $settings = array(
                        'version'           => HV_BUILD_NUM,
                        'defaultZoomLevel'  => HV_DEFAULT_ZOOM_LEVEL,
                        'defaultObsTime'    => HV_DEFAULT_OBS_TIME,
                        'minZoomLevel'      => HV_MIN_ZOOM_LEVEL,
                        'maxZoomLevel'      => HV_MAX_ZOOM_LEVEL,
                        'baseZoom'          => HV_BASE_ZOOM_LEVEL,
                        'baseScale'         => HV_BASE_IMAGE_SCALE,
                        'prefetchSize'      => HV_PREFETCH_SIZE,
                        'timeIncrementSecs' => HV_DEFAULT_TIMESTEP,
                        'tileServer1'       => HV_TILE_SERVER_1,
                        'tileServer2'       => HV_TILE_SERVER_2,
                        'backupServer'      => HV_BACKUP_SERVER,
                        'backupEnabled'     => HV_BACKUP_ENABLED,
                        'distributed'       => HV_DISTRIBUTED_TILING_ENABLED,
                        'rootURL'           => HV_WEB_ROOT_URL
                    );

                    echo "var defaults = " . json_encode($settings) . ";\n";
                    echo "\t\t\t\t";
                    printf ("var api = '%s';\n", HV_API_BASE_URL);
                ?>
                var helioviewer = new Helioviewer('#helioviewer-viewport', api, view, defaults );
            });
        </script>

    </head>
    <body>
        <div id="minHeight"></div>
        
        <!-- Loading Indicator -->
        <div id="loading" style="display: none">Loading...</div>

        <!-- Header and Content -->
        <div id="outsideBox">

            <!-- Left Column -->
            <div id="left-col">
                <div id="left-col-header">
                    <img src="images/logos/simple.png" id="helioviewer-logo-main" alt="Helioviewer.org Logo" style="margin-top:24px; margin-left: 9px;">
                </div>
                <br><br>
                <div class="section-header" style="margin-left:5px; margin-top: 15px;">Observation</div> 
                <div id="observation-controls" class="ui-widget ui-widget-content ui-corner-all">
                    <!--  Observation Date -->
                    <div style="margin-bottom: 4px; position: relative;">
                        <div style="width: 78px; margin-top:3px; float: left; font-weight: 600;">Date:</div>
                        <input type="text" id="date" name="date" value="">
                    </div>

                    <!-- Observation Time -->
                    <div style="margin-bottom: 8px;">
                        <div style="float: left; width: 78px; font-weight: 600;">Time:</div>
                        <input type="text" id="time" name="time" value="" style="width:80px">
                    </div>
                    
                    <!-- Time Navigation Buttons & Time Increment selector -->
                    <div>
                        <div style="float: left; width: 78px; font-weight: 600;">Time-step:</div>
                        <select id="timestep-select" name="time-step"></select>
                        <span id="timeBackBtn" class="ui-icon ui-icon-circle-arrow-w" title="Move the Observation Date/Time backward one time-step"></span>
                        <span id="timeForwardBtn" class="ui-icon ui-icon-circle-arrow-e" title="Move the Observation Date/Time forward one time-step"></span>
                    </div>
                </div>

                <br><br>
                <div id="tileLayerAccordion"></div>
                <br><br>
                <div id="eventAccordion"></div>
                <br /><br />
            
            </div>

            <!-- Right Column -->
            <div id="right-col">
                <div id="right-col-header"></div>
            </div>

            <!-- Middle Column -->
            <div id="middle-col">
                <div id="middle-col-header">
                </div>
                <!-- End middle-col-header -->

                <!-- Viewport -->
                <div id="helioviewer-viewport-container-outer" class="ui-widget ui-widget-content ui-corner-all">
                    <div id="helioviewer-viewport-container-inner" class="centered" style="top:3%; width:97%; height:94%">
                        <div id="helioviewer-viewport"></div>

                            <!-- UI COMPONENTS -->

                            <!--  Zoom Controls -->
                            <div id="zoomControls"></div>
                            
                            <!-- Center button -->
                            <div id="center-button" title="Center the image on the screen.">
                                <span>center</span>
                            </div>
                            
                            <!--Social buttons -->
                            <div id="social-buttons"> 
                                <!-- Link button -->
                                <div id="link-button" class="text-btn">
                                    <span class="ui-icon ui-icon-link" style="float: left;"></span>
                                    <span style="line-height: 1.6em">Link</span>
                                </div>
                                
                                <!-- Email button -->
                                <!--<div id="email-button" class="text-btn">
                                    <span class="ui-icon ui-icon-mail-closed" style="float: left;"></span>
                                    <span style="line-height: 1.6em">Email</span>
                                </div>-->
                                
                                <!-- Movie button -->
                                <!--<div id="movie-button" class="text-btn">
                                    <span class="ui-icon ui-icon-video" style="float: left;"></span>
                                    <span style="line-height: 1.6em">Movie</span>
                                </div>-->                            
                                
                                <!-- Screenshot button -->
                                <!--<div id="screenshot-button" class="text-btn">
                                    <span class="ui-icon ui-icon-image" style="float: left;"></span>
                                    <span style="line-height: 1.6em">Screenshot</span>
                                </div>-->        
    
                                <!-- Select region button -->
                                <!--<div id="select-region-button" class="text-btn">
                                    <span class='ui-icon ui-icon-scissors' style="float: left;"></span>
                                    <span style="line-height: 1.6em">Select Region</span>
                                </div>-->

                                <!-- Media settings button -->                                
                                <!--<div id="settings-button" class="text-btn">
                                    <span class='ui-icon ui-icon-gear' style="float: left;"></span>
                                    <span style="line-height: 1.6em">Media Settings</span>
                                </div>-->
                                    
                                <!-- JHelioviewer -->
                                <div id="jhelioviewer-button" class="text-btn">
                                    <span class="ui-icon ui-icon-arrowthickstop-1-s" style="float: left;"></span>
                                    <span style="line-height: 1.6em">JHelioviewer</span>
                                </div> 
                            </div>
                            
                            <!-- Fullscreen toggle -->
                            <div id='fullscreen-btn' title="Toggle fullscreen display.">
                                <div class='ui-icon ui-icon-arrow-4-diag'></div>
                            </div>

                    </div>
                </div>
            </div>
            <!-- Timeline -->
            <!--
            <div style="text-align: center;">
                <div id="timeline" style="height: 150px; width: 70%; margin-left: auto; margin-right: auto; border: 1px solid #000"></div>
            </div>
            -->
            <div id="clearfooter"></div>
        </div>
        <!-- end outer div -->

        <!-- Footer -->
        <div id="footer">
            <div id="footer-container-outer">
                <div id="footer-container-inner">
                    <!-- Meta links -->
                    <div id="footer-links">
                        <!--<a href="help/" class="light" target="_blank">Help</a>-->
                        <a id="helioviewer-about" class="light" href="dialogs/about.php">About</a>
                        <a id="helioviewer-usage" class="light" href="dialogs/usage.php">Usage Tips</a>
                        <a href="http://helioviewer.org/wiki/" class="light" target="_blank">Wiki</a>
                        <a href="api/" class="light" target="_blank">API</a>
                        <a href="mailto:webmaster@helioviewer.org" class="light">Contact</a>
                        <a href="https://bugs.launchpad.net/helioviewer.org/" class="light" style="margin-right:2px;" target="_blank">Report Bug</a>    
                    </div>
                </div>
            </div>
        </div>
        
        <!-- About dialog -->
        <div id='about-dialog'></div>
        
        <!-- Usage Dialog -->
        <div id='usage-dialog'></div>
    </body>
</html>
