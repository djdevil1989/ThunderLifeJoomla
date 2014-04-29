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
 
defined('_JEXEC') or die('Restricted access');
$row_class = "even";
?>
<h1><?php echo JText::_('INWORLD_DETAILS'); ?></h1>
<?php if($this->ossettings['addons'] & 4): ?>
<table cellspacing='0' cellpadding='0'>
<tr>
	<td>
	<?php echo $this->topbar; ?>
	</td>
</tr>
<tr>
	<td>
	<?php if(count($this->grouplist) > 0): ?>
	<table class='grouptable<?php echo $this->pageclass_sfx; ?>'>
	<tr class='grouptable_head_row<?php echo $this->pageclass_sfx; ?>'>
		<td class='grouptable_head_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('GROUPNAME'); ?></td>
		<td class='grouptable_head_cell<?php echo $this->pageclass_sfx; ?>'>&nbsp;</td>
	</tr>
	<?php
	foreach($this->grouplist AS $group) {
		if($row_class == "odd") $row_class = "even";
		else $row_class = "odd";
	?>
	<tr class='grouptable_row_<?php echo $row_class; ?><?php echo $this->pageclass_sfx; ?>'>
		<td class='grouptable_cell_<?php echo $row_class; ?><?php echo $this->pageclass_sfx; ?>'><?php echo $group['groupname']; ?></td>
		<td class='grouptable_cell_<?php echo $row_class; ?><?php echo $this->pageclass_sfx; ?>'><a class='modal' id='groupdetailwindow' href='index.php?option=com_opensim&view=inworld&task=groupdetail&groupid=<?php echo $group['groupid']; ?>&tmpl=component' rel="{handler: 'iframe', size: {x: 500, y: 350}, overlayOpacity: 0.3}"><img src='<?php echo $this->assetpath; ?>images/view.gif' width='20' height='20' alt='<?php echo JText::_('VIEWGROUPDETAILS'); ?>' border='0' title='<?php echo JText::_('VIEWGROUPDETAILS'); ?>' /></a></td>
	</tr>
	<?php } ?>
	</table>
	<?php else: ?>
	<?php echo JText::_('NOGROUPS'); ?>!
	<?php endif; ?>
	</td>
</tr>
</table>
<?php endif; ?>