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
<h1><?php echo JText::_('JOPENSIM_INWORLDSEARCH'); ?></h1>
<?php if($this->searchform === TRUE): ?>
<form name='inworldsearch' action='index.php' method='post'>
<input type='hidden' name='option' value='com_opensim' />
<?php if($this->tmpl == TRUE): ?>
<input type='hidden' name='tmpl' value='component' />
<?php endif; ?>
<input type='hidden' name='view' value='inworldsearch' />
<input type='hidden' name='Itemid' value='<?php echo $this->itemid; ?>' />
<input type='text' name='q' id='q' value='<?php echo $this->searchquery; ?>' /><input type='submit' value='<?php echo JText::_('JOPENSIM_SEARCH'); ?>' />
</form>
<?php endif; ?>
<?php if($this->showcase === TRUE): ?>
<?php echo JText::_('JOPENSIM_SHOWCASE'); ?>
<?php elseif(is_array($this->result)): ?>
<p><?php echo JText::_('JOPENSIM_SEARCHRESULTS'); ?>:</p><br />
<?php if(count($this->results) > 0): ?>
<p>
<?php foreach($this->results AS $type => $lines): ?>
<h3><?php echo JText::_($type); ?></h3>
<div class='jopensim_searchresult_table'>
	<?php foreach($lines AS $line): ?>
	<div class='jopensim_searchresult_tr'>
		<?php echo $line; ?>
	</div>
	<?php endforeach; ?>
</div>
<?php endforeach; ?>
</p>
<?php endif; ?>
<?php else: ?>
<p><?php echo JText::_('JOPENSIM_SEARCHRESULTS_ERROR'); ?></p>
<?php endif; ?>
<?php
//debugprint($_REQUEST,"\$_REQUEST");
//debugprint($_SESSION,"\$_SESSION");
//debugprint($this->result,"\$this->result");
//debugprint($this->results,"\$this->results");
?>
<br /><br /><br /><br />
<center><div class='jopensimsearchfooter'>jOpenSimSearch Version <?php echo $this->jopensimversion; ?> powered by <a href='http://www.jopensim.com'>FoTo50</a></div></center>