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
<form action="index.php" method="post" name="adminForm">
<input type='hidden' name='option' value='com_opensim' />
<input type='hidden' name='view' value='maps' />
<input type='hidden' name='task' value='insertuser' />

<input type='image' src='<?php echo $this->mapimagepath; ?>regionimage.php?uuid=<?php echo $this->region; ?>&mapserver=<?php echo $this->mapserver; ?>&mapport=<?php echo $this->mapport; ?>' border='1' alt='' title='' />
</form>
<hr />
<pre>
<?php echo $this->test; ?>
</pre>