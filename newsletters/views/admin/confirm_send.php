<div class="box">

	<h3>Send Mail</h3>				
	
	<div class="box-container">	

<strong>Subject:</strong> <?=htmlentities($subject)?></span>

<iframe width="100%" height="300px" src="<?php echo site_url('admin/newsletters/mails/preview/'.$mail_id); ?>"></iframe>



<form action="<?php echo site_url('admin/newsletters/mails/send_mail/'.$mail_id)?>" method="post">

				<fieldset>
				<legend>Newsletter Recipient Groups</legend>
				<h4>Please select the groups to send this message to.</h4>
			

			<?foreach($groups as $group):?>
				<label class="checkbox"<?if(!empty($group->group_description)){echo ' title="'.htmlentities($group->group_description).'"';}?>>
				<input type="checkbox" name="group[]" value="<?=$group->id?>" />
				<strong><?=htmlentities($group->group_name)?></strong>
				 - (<em><?=$this->newsletters->count('recipients',$group->id)?> Users</em>)
				</label><br />
			<?endforeach?>

				<h4>You may add as many additional recipients as you like. Please provide a comma or line separated list of email addresses to send this mail to. Each user will be emailed separately.</h4>
				<textarea name="additional_recipients"></textarea>

				
				<input style="float:right" type="submit" value="Send Mail!" />

				
</form>

</div>
</div>

	


