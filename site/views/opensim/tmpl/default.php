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
<style type="text/css">
body, html {
<?php if(!$this->settingsdata['loginscreen_image']): ?>
 <?php if($this->settingsdata['loginscreen_color']): ?>
	background-color:#<?php echo $this->settingsdata['loginscreen_color']; ?>;
 <?php endif; ?>
<?php else: ?>
	background: url("<?php echo $this->settingsdata['loginscreen_image']; ?>") center no-repeat fixed !important;
	background-color:transparent !important;
<?php endif; ?>
	margin:0px;
	padding:0px;
	width:99%;
	height:99%;
	z-index:20;
}
#all {
	background:none;
	background-color:transparent !important;
}
</style>
<div style='width:100%;height:100%;text-align:center;vertical-align:middle;background-color:transparent;'>
<?php if (array_key_exists("loginscreen_boxes",$this->settingsdata) && ($this->settingsdata['loginscreen_boxes'] & 1) == 1): ?>
<div id='jopensim_gridbox' class='welcomebox_gridstatus' style='border:<?php echo $this->settingsdata['loginscreen_msgbox_border']; ?>;background:#<?php echo $this->settingsdata['loginscreen_msgbox_color']; ?>;color:#<?php echo $this->settingsdata['loginscreen_text_color']; ?>'>
<?php if(array_key_exists("gridboxlines",$this->gridstatus) && $this->gridstatus['gridboxlines'] > 0): ?>
<div class='welcomebox_gridstatus_title'><?php echo JText::_('GRIDSTATUS'); ?></div>
<div class='welcomebox_gridstatus_content'>
<table width='95%' border='0' class='jOpenSim_gridstatustable'>
<?php if($this->gridstatus['gridboxlines'] & 1): ?>
<tr class='jOpenSim_gridstatusrow'>
	<td align='left' class='jOpenSim_gridstatuscell'><?php echo JText::_('LABEL_GRIDSTATUS'); ?>:</td>
	<td align='right' style='text-align:right;' class='jOpenSim_gridstatuscell'><?php echo $this->gridstatus['statusmsg']; ?></td>
</tr>
<?php endif; ?>
<?php if($this->gridstatus['status'] == "online"): ?>
<?php if($this->gridstatus['gridboxlines'] & 2): ?>
<tr class='jOpenSim_gridstatusrow'>
	<td align='left' class='jOpenSim_gridstatuscell'><?php echo JText::_('LABEL_TOTALREGIONS'); ?>:</td>
	<td align='right' style='text-align:right;' class='jOpenSim_gridstatuscell'><?php echo $this->gridstatus['totalregions']; ?></td>
</tr>
<?php endif; ?>
<?php if($this->gridstatus['gridboxlines'] & 4): ?>
<tr class='jOpenSim_gridstatusrow'>
	<td align='left' class='jOpenSim_gridstatuscell'><?php echo JText::sprintf('LABEL_LASTXDAYS',$this->gridstatus['days']); ?>:</td>
	<td align='right' style='text-align:right;' class='jOpenSim_gridstatuscell'><?php echo $this->gridstatus['lastonline']; ?></td>
</tr>
<?php endif; ?>
<?php if($this->gridstatus['gridboxlines'] & 8): ?>
<tr class='jOpenSim_gridstatusrow'>
	<td align='left' class='jOpenSim_gridstatuscell'><?php echo JText::_('LABEL_ONLINENOW'); ?>:</td>
	<td align='right' style='text-align:right;' class='jOpenSim_gridstatuscell'><?php echo $this->gridstatus['online']; ?></td>
</tr>
<?php endif; ?>
<?php endif; ?>
</table>
</div>
<?php endif; ?>
</div>
<?php endif; ?>
<?php if (($this->settingsdata['loginscreen_boxes'] & 2) == 2): ?>
<div id='jopensim_messagebox' class='welcomebox_messages' style='border:<?php echo $this->settingsdata['loginscreen_msgbox_border']; ?>;background:#<?php echo $this->settingsdata['loginscreen_msgbox_color']; ?>;color:#<?php echo $this->settingsdata['loginscreen_text_color']; ?>'>
<div class='welcomebox_messages_title' style='background:#<?php echo $this->settingsdata['loginscreen_msgbox_title_background']; ?>;color:#<?php echo $this->settingsdata['loginscreen_msgbox_title_text']; ?>'><?php echo $this->settingsdata['loginscreen_msgbox_title']; ?></div>
<div class='welcomebox_messages_content'><p><?php echo nl2br($this->settingsdata['loginscreen_msgbox_message']); ?></p></div>
</div>
<?php endif; ?>
<?php if (($this->settingsdata['loginscreen_boxes'] & 4) == 4): ?>
<?php if(is_array($this->regions) && count($this->regions) > 0): ?>
<div id='jopensim_regionbox' class='welcomebox_regions' style='border:<?php echo $this->settingsdata['loginscreen_msgbox_border']; ?>;background:#<?php echo $this->settingsdata['loginscreen_msgbox_color']; ?>;color:#<?php echo $this->settingsdata['loginscreen_text_color']; ?>'>
<div class='welcomebox_regions_content'><div class='welcomebox_regions_title'>Regions:</div>

<table class='jOpenSim_regiontable'>
<?php foreach($this->regions AS $key => $region): ?>
<tr class='jOpenSim_regionrow'>
	<td class='jOpenSim_regioncell'><a style="cursor:pointer" onclick="document.location.href='secondlife://<?php echo urlencode($region['regionName'])." (".$key.")"; ?>'"><?php echo $region['regionName']; ?></a></td>
</tr>
<?php endforeach; ?>
</table>
</div>
</div>
<?php endif; ?>
<?php endif; ?>
</div>
