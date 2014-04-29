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
JHTML::_('behavior.tooltip');
$rowclass = "even";
?>
<h1 class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EVENTLIST'); ?></h1>
<?php echo JText::sprintf('JOPENSIM_EVENTS_TIMEZONEDISPLAY',$this->usertimezone); ?>

<table cellpadding='5' class='<?php echo $this->pageclass_sfx; ?>'>
<tr class='<?php echo $this->pageclass_sfx; ?>'>
	<th class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EVENT_NAME'); ?></th>
	<th class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EVENTCATEGORY'); ?></th>
	<th class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EVENT_RUNBY'); ?></th>
	<th class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EVENT_LOCATION'); ?></th>
	<th class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EVENT_DATE'); ?></th>
	<th class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EVENT_TIME'); ?></th>
	<th class='<?php echo $this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EVENT_DURATION'); ?></th>
	<th class='<?php echo $this->pageclass_sfx; ?>'>&nbsp;</th>
</tr>
<?php if(is_array($this->eventlist['events']) && count($this->eventlist['events']) > 0): ?>
<?php
foreach($this->eventlist['events'] AS $event) {
	if($rowclass == "even") $rowclass = "odd";
	else $rowclass = "even";
?>
<tr class='<?php echo $this->pageclass_sfx; ?>'>
	<td class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'><?php echo JHTML::tooltip($event['description'],JText::_('JOPENSIM_EVENT_DESCRIPTION'),'',$event['name']); ?></td>
	<td class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'><?php echo $event['categotryname']; ?></td>
	<td class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'><?php echo $event['ownername']; ?></td>
	<td class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'><?php echo ($event['surl']) ? "<a href='secondlife://".$event['surl']."'>".$event['simname']."</a>":$event['simname']; ?></td>
	<td class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'><?php echo $event['userdate']; ?></td>
	<td class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'><?php echo $event['usertime']; ?></td>
	<td class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'><?php echo $this->duration[$event['duration']]; ?></td>
	<td class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'>
<?php if($event['editflag'] == 1): ?>
	<a href='index.php?option=com_opensim&view=events&layout=eventlist&task=deleteevent&eventid=<?php echo $event['eventid']; ?>' onClick='return confirm("<?php echo JText::_('JOPENSIM_EVENT_DELETE_SURE'); ?>");'><img src='<?php echo $this->assetpath; ?>images/delete.gif' width='20' height='20' alt='<?php echo JText::_('JOPENSIM_EVENT_DELETE'); ?>' border='0' title='<?php echo JText::_('JOPENSIM_EVENT_DELETE'); ?>' /></a>
<?php else: ?>
	&nbsp;
<?php endif; ?>
	</td>
</tr>
<?php
}
?>
<?php else: ?>
<tr class='<?php echo $this->pageclass_sfx; ?>'>
	<td colspan='8' class='jopensim_eventlist_<?php echo $rowclass.$this->pageclass_sfx; ?>'><?php echo JText::_('JOPENSIM_EMPTYEVENTLIST'); ?></td>
</tr>
<?php endif; ?>
</table>

<?php
// debugprint($this->eventlist,"eventlist");
?>