<?php
$this->lang->load('newsletters/newsletters');
$groups = $this->db->where('group_public',1)->get('newsletter_groups')->result();
?>

<h2><?php echo lang('letter_letter_title');?></h2>
<p><?php echo lang('letter_subscripe_desc');?></p>

<?php echo form_open('newsletters/subscribe'); ?>
	<p>
		<label for="email"><?php echo lang('letter_email_label');?>:</label>
		<?php echo form_input(array('name'=>'email', 'value'=>'user@example.com', 'size'=>20, 'onfocus'=>"this.value=''")); ?>
	</p>
	
		<p>
		<label for="name">Name:</label>
		<input name="name" type="text" />
	</p>
	
	<?php if(isset($groups)): ?>
	<?php foreach($groups as $group): ?>
		<p>
			<?php echo form_label(htmlentities($group->group_name),'group_id'); ?>
			<?php echo form_checkbox('group[]',$group->id); ?>
		</p>
	<?php endforeach; ?>
	<?php endif; ?>
		
	<p><?php echo form_submit('btnSignup', lang('newsletters.subscribe')) ?></p>
<?php echo form_close(); ?>