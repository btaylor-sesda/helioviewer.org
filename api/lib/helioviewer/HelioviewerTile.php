<?php
/**
 * @class HelioviewerTile
 * @author Keith Hughitt
 */
require_once('Tile.php');

class HelioviewerTile extends Tile {
    private $observatory;
	private $instrument;
	private $detector;
	private $measurement;
	private $cacheDir = CONFIG::CACHE_DIR;
    private $noImage  = CONFIG::EMPTY_TILE;
		
 	/**
     * constructor
     */
    public function __construct($uri, $x, $y, $zoom, $tileSize, $jp2Width, $jp2Height, $jp2Scale, $format, $obs, $inst, $det, $meas, $display = true) {
		$this->observatory = $obs;
		$this->instrument  = $inst;
		$this->detector    = $det;
		$this->measurement = $meas;
		$this->zoomLevel   = $zoom;
		
		$jp2  = Config::JP2_DIR . $uri;
		$tile = $this->getTileFilepath($jp2, $x, $y, $format);

       // If tile already exists in cache, use it
        if (Config::ENABLE_CACHE && $display) {
            if (file_exists($tile)) {
                $this->displayCachedTile($tile);
                exit();
            }
        }

		$desiredScale = $this->getImageScale($zoom);
		
		parent::__construct($jp2, $tile, $x, $y, $desiredScale, $tileSize, $jp2Width, $jp2Height, $jp2Scale, $format);
			
		$colorTable = $this->getColorTable();
		
		if ($colorTable)
			$this->setColorTable($colorTable);
		
		if ($this->instrument == "LASCO")
			$this->setAlphaMask(true);
			
		$this->buildImage();
		
        if ($display)
            $this->display();
    }
		
    /**
     * getTileFilepath
     * @return
     */
    private function getTileFilepath($jp2, $x, $y, $format) {
        // Base directory
        $filepath = $this->cacheDir . "/";
                
        if (!file_exists($filepath)) {
            mkdir($filepath);
            chmod($filepath, 0777);
        }

        // Base filename
        $exploded = explode("/", $jp2);
        $filename = substr(end($exploded), 0, -4);
		
        // Date information
        $year  = substr($filename, 0, 4);
        $month = substr($filename, 5, 2);
        $day   = substr($filename, 8, 2);

		$fieldArray = array($year, $month, $day, $this->observatory, $this->instrument, $this->detector, $this->measurement);
		
		foreach($fieldArray as $field) {
			$filepath .= $field . "/";
			
	        if (!file_exists($filepath)) {
	        	echo $filepath . "<br>";
	            mkdir($filepath);
	            chmod($filepath, 0777);
	        }
		}    

        // Convert coordinates to strings
        $xStr = "+" . str_pad($x, 2, '0', STR_PAD_LEFT);
        if (substr($x,0,1) == "-")
            $xStr = "-" . str_pad(substr($x, 1), 2, '0', STR_PAD_LEFT);

        $yStr = "+" . str_pad($y, 2, '0', STR_PAD_LEFT);
        if (substr($y,0,1) == "-")
            $yStr = "-" . str_pad(substr($y, 1), 2, '0', STR_PAD_LEFT);

        $filepath .= $filename . "_" . $this->zoomLevel . "_" . $xStr . "_" . $yStr . ".$format";

        return $filepath;
    }
	
	/**
	 * @description Translates a given zoom-level into an image plate scale.
	 */
	private function getImageScale($zoomLevel) {
		$zoomOffset = $zoomLevel - Config::BASE_ZOOM_LEVEL;
        return Config::BASE_IMAGE_SCALE * (pow(2, $zoomOffset));
	}
		
	/**
	 * Gets the filepath for the color look-up table that corresponds to the image.
	 * @return string clut filepath
	 * @param object $detector
	 * @param object $measurement
	 * 
	 * Note (2009/09/15): Would it make sense to return color table when initially looking up image, and pass to tile requests?
	 */
    private function getColorTable() {
        if ($this->detector == "EIT") {
            return Config::WEB_ROOT_DIR . "/images/color-tables/ctable_EIT_" . $this->measurement . ".png";
        }
        else if ($this->detector == "C2") {
            return Config::WEB_ROOT_DIR .  "/images/color-tables/ctable_idl_3.png";
        }
        else if ($this->detector == "C3") {
            return Config::WEB_ROOT_DIR . "/images/color-tables/ctable_idl_1.png";
        }
		else
			return false;       
    }
	
	/**
	 * Displays the image on the page
	 * @TODO: Would it be better to make SubFieldImage->display static and call? Or instantiate
	 * super classes (Tile and SubFieldImage), and then call display normally?
	 */
    public function displayCachedTile($tile) {
        try {
        	$format = substr($tile, -3);
			
            // Cache-Lifetime (in minutes)
            $lifetime = 60;
            $exp_gmt = gmdate("D, d M Y H:i:s", time() + $lifetime * 60) ." GMT";
            header("Expires: " . $exp_gmt);
            header("Cache-Control: public, max-age=" . $lifetime * 60);
    
            // Filename & Content-length
            $exploded = explode("/", $tile);
            $filename = end($exploded);
            
			$stat = stat($tile);
            header("Content-Length: " . $stat['size']);
            header("Content-Disposition: inline; filename=\"$filename\"");    

            if ($format == "png")
                header("Content-Type: image/png");
            else
                header("Content-Type: image/jpeg");
            
            if (!readfile($tile)) {
                throw new Exception("Error displaying $filename\n");
            }
        } catch (Exception $e) {
            $msg = "[PHP][" . date("Y/m/d H:i:s") . "]\n\t " . $e->getMessage() . "\n\n";
            file_put_contents(Config::ERROR_LOG, $msg, FILE_APPEND);
        }
    }

    /**
     * hasAlphaMask
     * @return string
     */
    //private function hasAlphaMask() {
    //    return $this->measurement === "0WL" ? true : false;
    //}
}
?>