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
<input type='hidden' name='task' value='applyuserrelation' />
<input type='hidden' name='userid' value='<?php echo $this->userid; ?>' />
<input type='hidden' name='relationmethod' value='<?php echo $this->relationmethod; ?>' />
<h1><?php echo ($this->relationmethod == "insert") ? JText::_('INSERT_RELATION'):JText::_('UPDATE_RELATION'); ?></h1>
<p>Opensim User <b><?php echo $this->opensim_userdata['firstname']." ".$this->opensim_userdata['lastname']; ?></b> &lt; - &gt; <select name='joomlauser'>
	<option value='0'><?php echo JText::_('PLS_CHOOSE_JUSER'); ?></option>
<?php foreach ($this->joomlalist AS $joomlauser): ?>
	<option value='<?php echo $joomlauser['id']; ?>'<?php echo ($joomlauser['id'] == $this->relation) ? " selected='selected'":""; ?>><?php echo $joomlauser['name']." (Username: ".$joomlauser['username'].")"; ?></option>
<?php endforeach; ?>
<?php if($this->relationmethod == "update"): ?>
	<option value='0'>--------------------------------------------------</option>
	<option value='-1'><?php echo JText::_('DELETE_RELATION'); ?></option>
<?php endif; ?>
</select>
</form>
