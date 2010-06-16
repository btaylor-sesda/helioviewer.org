/**
 * @fileOverview Contains the class definition for an Viewport class.
 * @author <a href="mailto:keith.hughitt@nasa.gov">Keith Hughitt</a>
 * @author <a href="mailto:patrick.schmiedel@gmx.net">Patrick Schmiedel</a>
 * @author <a href="mailto:jaclyn.r.beck@gmail.com">Jaclyn Beck</a>
 */
/*jslint browser: true, white: true, onevar: true, undef: true, nomen: false, eqeqeq: true, plusplus: true, 
bitwise: true, regexp: true, strict: true, newcap: true, immed: true, maxlen: 120, sub: true */
/*global Class, $, document, window, TileLayerManager, HelioviewerMouseCoordinates, SandboxHelper, Viewport */
"use strict";
var HelioviewerViewport = Viewport.extend(
    /** @lends Viewport.prototype */
    {
    /**
     * @constructs
     * @description Creates a new Viewport
     * @param {Object} options Custom Viewport settings
     * <br>
     * <br><div style='font-size:16px'>Options:</div><br>
     * <div style='margin-left:15px'>
     *       <b>imageScale</b> - The default image scale to display tiles at (should be passed in from Helioviewer).<br>
     *       <b>tileSize</b>   - Size of tiles.<br> 
     *       <b>prefetch</b>   - The radius outside of the visible viewport to prefetch.<br>
     * </div>
     */
    init: function (options) {
        this._super(options);
                        
        // Solar radius in arcseconds, source: Djafer, Thuillier and Sofia (2008)
        this.rsun = 959.705;

        // Initialize tile layers
        this._tileLayerManager = new HelioviewerTileLayerManager(this.api, this.requestDate, this.dataSources, this.tileSize, 
                                                      this.imageScale, this.maxTileLayers, this.tileServers, 
                                                      this.tileLayers, this.urlStringLayers);
        
        this.mouseCoords = new HelioviewerMouseCoordinates(this.imageScale, this.rsun, this.warnMouseCoords);
        this.resize();
        this._initEventHandlers();
    },
    
    /**
     * Returns the horizontal and vertical displacement of the sun from the center of the viewport
     */
    getOffsetFromCenter: function () {
        var sandboxCenter, sunCenter;
        
        sandboxCenter = this.getSandboxCenter();
        sunCenter     = this.getContainerPos();
        
        return {
            x: sunCenter.x - sandboxCenter.x,
            y: sunCenter.y - sandboxCenter.y
        };
    },

    /**
     * @description Zooms To a specified image scale.
     * @param {Float} imageScale The desired image scale
     */
    zoomTo: function (event, imageScale) {
        var sunCenter, originalSandboxWidth, originalSandboxHeight,  
        sandboxWidthScaleFactor, sandboxHeightScaleFactor, originalScale;
        
        originalScale = this.imageScale;
        
        // get offset and sandbox dimensions
        sunCenter             = this.getContainerPos();
        originalSandboxWidth  = this.sandbox.width(); 
        originalSandboxHeight = this.sandbox.height();
        
        this.imageScale = imageScale;

        // scale layer dimensions
        this.scaleLayerDimensions(originalScale / imageScale);
        
        // update sandbox
        this.updateSandbox();
        
        sandboxWidthScaleFactor  = this.sandbox.width()  / originalSandboxWidth;
        sandboxHeightScaleFactor = this.sandbox.height() / originalSandboxHeight;
        
        this._updateTileVisibilityRange();
        
        this.moveTo(sunCenter.x * sandboxWidthScaleFactor, sunCenter.y * sandboxHeightScaleFactor);
        
        // reset the layers
        this._tileLayerManager.adjustImageScale(imageScale, this.tileVisibilityRange);
        
        this.mouseCoords.updateImageScale(imageScale);
        
        // store new value
        $(document).trigger("save-setting", ["imageScale", imageScale]);
    },
    
    /**
     * @description Returns the current solar radius in pixels.
     */
    getRSun: function () {
        return this.rsun / this.imageScale;
    },

    /**
     * @description
     */
    _initEventHandlers: function () {
        $(window).resize($.proxy(this.resize, this));
        $(document).mousemove($.proxy(this.mouseMove, this))
                   .mouseup($.proxy(this.mouseUp, this))
                   .bind("layer-max-dimensions-changed", $.proxy(this.updateMaxLayerDimensions, this))
                   .bind("set-image-scale", $.proxy(this.zoomTo, this))
                   .bind("update-viewport-sandbox", $.proxy(this.updateSandbox, this))
                   .bind("observation-time-changed", $.proxy(this._onObservationTimeChange, this))
                   .bind("recompute-tile-visibility", $.proxy(this.checkTileVisibility, this))
                   .bind("move-viewport", $.proxy(this.moveViewport, this));
        
        $('#center-button').click($.proxy(this.center, this));
        
        this.domNode.mousedown($.proxy(this.mouseDown, this))
                    .dblclick($.proxy(this.doubleClick, this));
        
    },
    
    // 2009/07/06 TODO: Return image scale, x & y offset, fullscreen status?
    toString: function () {
    },    
    toJSON: function () {
    }
});
