<?php
// No direct access to this file
defined('_JEXEC') or die('Restricted Access');
$counter = 0;
?>
<?php foreach($this->items as $transaction): ?>
<tr class="row<?php echo $i % 2; ?>">
	<td>
	<?php echo ++$counter; ?>
	</td>
	<td>
	<?php echo date(JText::_('JOPENSIM_MONEY_TIMEFORMAT'),$transaction->time); ?>
	</td>
	<td>
	<?php echo $transaction->sendername; ?>
	</td>
	<td>
	<?php echo $transaction->receivername; ?>
	</td>
	<td>
	<?php echo number_format($transaction->amount,0,JTEXT::_('JOPENSIM_MONEY_SEPERATOR_COMMA'),JTEXT::_('JOPENSIM_MONEY_SEPERATOR_THOUSAND')); ?>
	</td>
	<td>
	<?php echo $transaction->description; ?>
	</td>
</tr>
<?php endforeach; ?>