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

?>
<h2>IM from <?php echo $this->fromAgentName; ?></h2>
<table class='message_detail<?php echo $this->pageclass_sfx; ?>'>
<?php foreach($this->messagedetails AS $timestamp => $messagedetail): ?>
<tr class='message_detail_row<?php echo $this->pageclass_sfx; ?>'>
	<td valign='top' class='message_detail_cell<?php echo $this->pageclass_sfx; ?>'><nobr><?php echo date(JText::_('OS_DATE')." ".JText::_('OS_TIME'),$timestamp); ?></nobr></td>
	<td valign='top' class='message_detail_cell<?php echo $this->pageclass_sfx; ?>'>&nbsp;&raquo;&nbsp;</td>
	<td valign='top' class='message_detail_cell<?php echo $this->pageclass_sfx; ?>'><?php echo $messagedetail; ?></td>
</tr>
<?php endforeach; ?>
</table>
