<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2012 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
// No direct access
 
defined('_JEXEC') or die('Restricted access'); ?>
<script type="text/javascript">
jQuery(function($) {
    $('div.mapzoom')
        .bind('mousewheel', function(event, delta) {
            var dir = delta > 0 ? 'Up' : 'Down',
                vel = Math.abs(delta);
            catchWheel(dir,vel);
            return false;
        });
});

function jopensimToggleRegionTT(divId,divStatus) {
//	alert("Div "+divId+": "+divStatus);
	var regionTT = document.getElementById("jopensim_regiontt_"+divId);
	if(regionTT) {
//		var divStatus = regionTT.style.visibility;
//		alert("Div "+divId+" is da: "+divStatus);
		
		if (divStatus == "visible") {
			regionTT.style.clip="rect(0px, 99999px, 99999px, 0)";
			regionTT.style.display="block";
			regionTT.style.visibility = "visible";
		} else {
			regionTT.style.clip="rect(0px, 0px, 0px, 0px)";
			regionTT.style.display="none";
			regionTT.style.visibility = "hidden";
		}
	}
}
</script>

<h1><?php echo JText::_('MAP_TITLE'); ?></h1>
<?php
if(is_array($this->maptable) && count($this->maptable) > 0) {
	foreach($this->maptable AS $mapitem) {
		echo $mapitem."\n";
	}
} 
 ?>
<input type='hidden' name='cellX' id='cellX' value='<?php echo $this->cellX; ?>' />
<input type='hidden' name='cellY' id='cellY' value='<?php echo $this->cellY; ?>' />
<input type='hidden' name='homeX' id='homeX' value='<?php echo $this->settingsdata['mapcenter_offsetX']; ?>' />
<input type='hidden' name='homeY' id='homeY' value='<?php echo $this->settingsdata['mapcenter_offsetY']; ?>' />
<script type="text/javascript">
var defaultscalefactor = <?php echo $this->settingsdata['map_defaultsize']; ?>;
var scalefactor = defaultscalefactor;
var initMap = false;
var map_minsize = <?php echo $this->settingsdata['map_minsize']; ?>;
var map_maxsize = <?php echo $this->settingsdata['map_maxsize']; ?>;
var map_zoomstep = <?php echo $this->settingsdata['map_zoomstep']; ?>;
var delta;
$(function() {
	scaleMaps(scalefactor);
});

// centerMapPos();
</script>
<input style='position: relative; top:-25px; left:10px; background:none;' type='image' src='<?php echo $this->asseturl; ?>images/home.png' id='homebutton' onClick='centerMapPos();' />
<input type='button' style='position: relative; top:-27px; left:15px;' id='resetbutton' value='reset' onClick='resetMap();' />
<input type='button' style='position: relative; top:-27px; left:15px;' id='resetbutton' value='+' onClick='zoomMap("in");' />
<input type='button' style='position: relative; top:-27px; left:15px;' id='resetbutton' value='-' onClick='zoomMap("out");' />
