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
<h1><?php echo $this->ueberschrift; ?></h1>
<form action="index.php" method="post" name="adminForm2">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='maps' />
<input type='hidden' name='region' value='<?php echo $this->region; ?>' />
<input type='hidden' name='task' value='savedefault' />
<h3><?php echo JText::_('REGION_NAME').": ".$this->regiondata['regionName']; ?></h3>
<?php echo JText::_('REGION_CLICK_LOCATION'); ?>.<br /><br />
<input type='image' width='512' height='512' src='<?php echo $this->assetpath; ?>regionimage.php?uuid=<?php echo $this->regiondata['uuid']; ?>&mapserver=<?php echo $this->regiondata['serverIP']; ?>&mapport=<?php echo $this->regiondata['serverHttpPort'].$this->imgAddLink; ?>&scale=512' border='1' alt='<?php echo JText::_('REGION_CLICK_LOCATION'); ?>' title='<?php echo JText::_('REGION_CLICK_LOCATION'); ?>' class='regionLocationSelector' />
</form>
<?php if($this->imgAddLink): ?>
<br /><br /><img src='<?php echo $this->assetpath; ?>images/entrancecircle.png' width='26' height='26' align='absmiddle' border='0' alt='<?php echo JText::_('CURRENTENTRANCE'); ?>' title='<?php echo JText::_('CURRENTENTRANCE'); ?>' /> = <?php echo JText::_('CURRENTENTRANCE'); ?>
<?php endif; ?>
<hr />
<?php echo JText::_('MAP_OR'); ?>
<hr />
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='region' value='<?php echo $this->region; ?>' />
<input type='hidden' name='view' value='maps' />
<input type='hidden' name='task' value='savemanual' />
<fieldset>
<legend><?php echo JText::_('MANUAL_LOCATION'); ?></legend>
<table cellspacing='0' cellpadding='2'>
<tr>
	<td>X:</td>
	<td><input type='text' size='1' name='loc_x' value='<?php echo (isset($this->locX)) ? $this->locX:""; ?>' /></td>
	<td>&nbsp;Y:</td>
	<td><input type='text' size='1' name='loc_y' value='<?php echo (isset($this->locY)) ? $this->locY:""; ?>' /></td>
	<td>&nbsp;Z:</td>
	<td><input type='text' size='1' name='loc_z' value='<?php echo (isset($this->locZ)) ? $this->locZ:""; ?>' /></td>
	<td>&nbsp;&nbsp;<input type='submit' value='<?php echo JText::_('JSAVE'); ?>' /></td>
</tr>
</table>
</fieldset>
</form>
<hr />
