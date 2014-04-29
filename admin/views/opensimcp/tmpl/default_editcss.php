<?php
/*
 * @package Joomla 2.5
 * @copyright Copyright (C) 2005 - 2010 Open Source Matters. All rights reserved.
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html, see LICENSE.php
 *
 * @component jOpenSim
 * @copyright Copyright (C) 2013 FoTo50 http://www.jopensim.com/
 * @license GNU/GPL v2 http://www.gnu.org/licenses/gpl-2.0.html
 */
defined('_JEXEC') or die('Restricted access'); ?>
<p><?php echo $this->cssmsg; ?></p>
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='opensim' />
<input type='hidden' name='task' value='' />
<textarea name='csscontent' style='width:100%;height:500px' cols='110' rows='25' class='inputbox'><?php echo $this->csscontent; ?></textarea>
</form>
<?php echo JText::_('JOPENSIM_EDITCSS').": ".$this->cssfile; ?>
