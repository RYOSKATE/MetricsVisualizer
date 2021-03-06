<div class="uploadData index">
	<h2><?php echo __('Upload Data'); ?></h2>
	<table cellpadding="0" cellspacing="0" class="table table-hover table-condensed">
	<thead>
	<tr>
			<th><?php echo $this->Paginator->sort('id'); ?></th>
			<th><?php echo $this->Paginator->sort('date'); ?></th>
			<th><?php echo $this->Paginator->sort('modelname_id'); ?></th>
			<th><?php echo $this->Paginator->sort('user_id'); ?></th>
			<th><?php echo $this->Paginator->sort('comment'); ?></th>
			<?php if($userData['role']!='reader'){?>
			<th class="actions"><?php echo __('Actions'); ?></th>
			<?php }?>
	</tr>
	</thead>
	<tbody>
	<?php foreach ($uploadData as $uploadData): ?>
	<tr>
		<td><?php echo h($uploadData['UploadData']['id']); ?>&nbsp;</td>
		<td><?php echo h($uploadData['UploadData']['date']); ?>&nbsp;</td>
		<td>
			<?php echo $this->Html->link($uploadData['Modelname']['name'], array('controller' => 'modelnames', 'action' => 'view', $uploadData['Modelname']['id'])); ?>
			<?php //echo h($uploadData['Modelname']['name']); ?>&nbsp;
		</td>
		<td>
			
			<?php 
			if($userData['role']=='admin')
				echo $this->Html->link($uploadData['User']['username'], array('controller' => 'users', 'action' => 'view', $uploadData['User']['id']));
			else 
				echo h($uploadData['User']['username']);
			?>&nbsp;
		</td>
		<td><?php echo h($uploadData['UploadData']['comment']); ?>&nbsp;</td>
		<?php if($userData['role']!='reader'){?>
			<td class="actions">
				<?php echo $this->Html->link(__('View'), array('action' => 'view', $uploadData['UploadData']['id'])); ?>
				<?php //echo $this->Html->link(__('Edit'), array('action' => 'edit', $uploadData['UploadData']['id'])); ?>
				<?php if($userData['role']=='admin' || $uploadData['User']['id']==$userData['id'])
					echo $this->Form->postLink(__('Delete'), array('action' => 'delete', $uploadData['UploadData']['id']), array(), __('Are you sure you want to delete # %s?', $uploadData['UploadData']['id'])); ?>
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
</div>
<?php echo $this->element('manageListFooter'); ?>