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
JHTML::_('behavior.modal');
$row_class = "even";
?>
<h1><?php echo JText::_('INWORLD_DETAILS'); ?></h1>
<?php if($this->ossettings > 0): ?>
<table cellspacing='0' cellpadding='0'>
<tr>
	<td>
	<?php echo $this->topbar; ?>
	</td>
</tr>
<tr>
	<td>
	<p><?php echo JText::_('MESSAGES_DESC'); ?></p>
	<?php if(count($this->messages) > 0): ?>
	<table class='messagetable<?php echo $this->pageclass_sfx; ?>'>
	<tr class='messagetable_head_row<?php echo $this->pageclass_sfx; ?>'>
		<td class='messagetable_head_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('MESSAGEFROM'); ?></td>
		<td class='messagetable_head_cell<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('MESSAGETIME'); ?></td>
		<td class='messagetable_head_cell<?php echo $this->pageclass_sfx; ?>'>&nbsp;</td>
	</tr>
	<?php
	foreach($this->messages AS $message) {
		if($row_class == "odd") $row_class = "even";
		else $row_class = "odd";
	?>
	<tr class='messagetable_row_<?php echo $row_class; ?><?php echo $this->pageclass_sfx; ?>'>
		<td class='messagetable_cell_<?php echo $row_class; ?><?php echo $this->pageclass_sfx; ?>'><?php echo $message['fromAgentName']; ?></td>
		<td class='messagetable_cell_<?php echo $row_class; ?><?php echo $this->pageclass_sfx; ?>'><?php echo date(JText::_('OS_DATE')." ".JText::_('OS_TIME'),$message['timestamp']); ?></td>
		<td class='messagetable_cell_<?php echo $row_class; ?><?php echo $this->pageclass_sfx; ?>'><a class='modal' href='index.php?option=com_opensim&view=inworld&task=messagedetail&imSessionID=<?php echo $message['imSessionID']; ?>&fromAgentID=<?php echo $message['fromAgentID']; ?>&tmpl=component' rel="{handler: 'iframe', size: {x: 400, y: 300}, overlayOpacity: 0.3}"><img src='<?php echo $this->assetpath; ?>images/view.gif' width='20' height='20' alt='<?php echo JText::_('VIEWMESSAGE'); ?>' title='<?php echo JText::_('VIEWMESSAGE'); ?>' /></a></td>
	</tr>
	<?php } ?>
	</table>
	<?php else: ?>
	<?php echo JText::_('NOMESSAGES'); ?>
	<?php endif; ?>
	</td>
</tr>
</table>
<?php endif; ?>
