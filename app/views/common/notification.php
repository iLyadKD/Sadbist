<?php
if($this->session->flashdata(SESSION_PREPEND."notification_msg") !== false && $this->session->flashdata(SESSION_PREPEND."notification_msg") !== null)
{
	if($this->session->flashdata(SESSION_PREPEND."notification_status") === "info")
	{
		echo '<div class="alert alert-block alert-info alert-dismissable">
				<a href="javascript:void(0)" data-dismiss="alert" class="close alert-close">×</a>
				<h4 class="alert-heading">'.$this->session->flashdata(SESSION_PREPEND."notification_title").'</h4>
				'.$this->session->flashdata(SESSION_PREPEND."notification_msg").'
			</div>';
	}
	elseif($this->session->flashdata(SESSION_PREPEND."notification_status") === "success")
	{
		echo '<div class="alert alert-block alert-success alert-dismissable">
				<a href="javascript:void(0)" data-dismiss="alert" class="close alert-close">×</a>
				<h4 class="alert-heading">'.$this->session->flashdata(SESSION_PREPEND."notification_title").'</h4>
				'.$this->session->flashdata(SESSION_PREPEND."notification_msg").'
			</div>';
	}
	else
	{
		echo '<div class="alert alert-block alert-danger alert-dismissable">
				<a href="javascript:void(0)" data-dismiss="alert" class="close alert-close">×</a>
				<h4 class="alert-heading">'.$this->session->flashdata(SESSION_PREPEND."notification_title").'</h4>
				'.$this->session->flashdata(SESSION_PREPEND."notification_msg").'
			</div>';
	}

?>

<?php	echo "<script>
	setTimeout(function(){ document.getElementsByClassName('alert-close')[0].click(); }, 5000);
	</script>";
}

?>

<div class="alert alert-block alert-danger alert-dismissable"  id="notification_alert" style="display:none;">
	<a href="javascript:void(0)" data-dismiss="alert" class="close alert-close">×</a>
	<h4 class="alert-heading">Error</h4>
	<span id="alert_msg"></span>
</div>


<div class="alert alert-block alert-danger alert-dismissable"  id="notification_alert_person" style="display:none;">
	<a href="javascript:void(0)" data-dismiss="alert" class="close alert-close">×</a>
	<h4 class="alert-heading">Error</h4>
	<span id="alert_msg_person"></span>
</div>



