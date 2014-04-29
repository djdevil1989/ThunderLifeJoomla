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
<p><a href='index.php?option=com_opensim&view=maps'><?php echo JText::_('JOPENSIM_VIEW_MAP_LIST'); ?></a>
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='maps' />
<input type='hidden' name='task' value='' />
</form>
<?php echo $this->maptable; ?>
