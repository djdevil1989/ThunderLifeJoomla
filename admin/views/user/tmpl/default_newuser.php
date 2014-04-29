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
<span class='com_opensim_title'><?php echo $this->ueberschrift; ?></span><?php echo $this->zusatztext; ?><br />
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='user' />
<input type='hidden' name='task' value='insertuser' />
<table>
<tr>
	<td><?php echo JText::_('FIRST_NAME'); ?></td>
	<td><input type='text' name='firstname' id='firstname' value='<?php echo $this->firstname; ?>' /></td>
</tr>
<tr>
	<td><?php echo JText::_('LAST_NAME'); ?></td>
	<td><input type='text' name='lastname' id='lastname' value='<?php echo $this->lastname; ?>' /></td>
</tr>
<tr>
	<td><?php echo JText::_('EMAIL'); ?></td>
	<td><input type='text' name='email' id='email' value='<?php echo $this->email; ?>' class="required validate-email" /></td>
</tr>
<tr>
	<td><?php echo JText::_('PASSWORD'); ?></td>
	<td><input type='password' name='pwd1' id='pwd1' /></td>
</tr>
<tr>
	<td><?php echo JText::_('PASSWORD2'); ?></td>
	<td><input type='password' name='pwd2' id='pwd2' /></td>
</tr>
</table>
</form>
