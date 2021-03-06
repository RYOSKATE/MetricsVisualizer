<div class="modelNames index">
	<h2><?php echo __('Model Names'); ?></h2>
	<table cellpadding="0" cellspacing="0" class="table table-hover table-condensed">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('name'); ?></th>
			<?php if($userData['role']!='reader'){?>
			<th class="actions"><?php echo __('Actions'); ?></th>
			<?php }?>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($modelNames as $modelName): ?>
	<tr>
		<td><?php echo h($modelName['ModelName']['id']); ?>&nbsp;</td>
		<td><?php echo h($modelName['ModelName']['name']); ?>&nbsp;</td>
			<?php if($userData['role']!='reader'){?>
		<td class="actions">
			<!--<?php echo $this->Html->link(__('View'), array('action' => 'view', $modelName['ModelName']['id'])); ?>-->

			<?php echo $this->Html->link(__('Edit'), array('action' => 'edit', $modelName['ModelName']['id'])); ?>
			<?php echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $modelName['ModelName']['id']), array(), __('Are you sure you want to delete # %s?', $modelName['ModelName']['id'])); ?>
		</td>
			<?php }?>
	</tr>
<?php endforeach; ?>
	</tbody>
	</table>
	<p>
	<?php
	echo $this->Paginator->counter(array(
	'format' => __('Page {:page} of {:pages}, showing {:current} records out of {:count} total, starting on record {:start}, ending on {:end}')
	));
	?>	</p>
	<div class="paging">
	<?php
		echo $this->Paginator->prev('< ' . __('previous '), array('tag' => false), null, array('class' => 'prev disabled'));
		echo $this->Paginator->numbers(array('separator' => ' ','tag' => false));
		echo $this->Paginator->next(__(' next') . ' >', array('tag' => false), null, array('class' => 'next disabled'));
	?>
	</div>
<?php echo $this->element('manageListFooter'); ?>