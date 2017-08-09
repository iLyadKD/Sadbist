<style>
	.modal_change {
		position: relative;
		font-size: 11px;
		font-weight: normal;
		text-align: center;
		cursor: pointer;
		background-image: none;
		border: 1px solid transparent;
		border-radius: 4px;
		right: 200px;
		bottom: 40px;
		font-color: blue;
		color: #7A4939;
	}
	.com_table th {
		font-size: 16px;
		
	}
	.action_companion {
		cursor: pointer;
	}
	#second_div{
		position: relative;
		text-align: center;
		bottom: 25px;
		font-style: italic;
	}
	
	
	/*.pwd_reset_btn {
		-moz-border-bottom-colors: none;
		-moz-border-left-colors: none;
		-moz-border-right-colors: none;
		-moz-border-top-colors: none;
		background: #ec4714 none repeat scroll 0 0;
		border-radius: 3px;
		border: none;
		box-shadow: inset 0px 1px 0px #E8A793;
		color: #fff;
		font-size: 12px;
		height: 25px;
		margin: 10px 10px 10px 280px;
		width: 70px;
		outline: medium none;
		padding: 5px 18px;
		text-align: center;
	}*/
</style>

<?php
if($this->data["user_id"] === null)
{
?>
<!--Login Modal -->
<div class="modal fade b2b_login_model" role="dialog">
	<div class="modal-dialog modal-md"> 
		<!-- Modal content-->
		<div class="modal-content">
			<div class="modal-header">
				<button type="button" class="close b2b_login_close_btn" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo $this->lang->line("sign_in"); ?></h4>
			</div>
			<div class="modal-body"> <img alt="" width="130" height="64" src="<?php echo asset_url('images/logo.png'); ?>" />
				<div class="mn-main">
				
					<form action = "javascript:void(0);" class="user_login" method="post">
						<div class="col-md-12">
							<div class="login_error"></div>
							<div class="mn-login form-group">
								<label class="control-label"><?php echo $this->lang->line("email_address"); ?></label>
								<div class="controls">
									<input value="" class="form-control" name="email_id" type="text" data-rule-required="true" data-rule-email="true"  data-msg-required="<?php echo $this->lang->line("email_address_required"); ?>"placeholder="<?php echo $this->lang->line("email_address"); ?>" data-msg-email="<?php echo $this->lang->line("email_address_valid_error"); ?>" />
								</div>
							</div>
							<div class="mn-login form-group">
								<label class="control-label"><?php echo $this->lang->line("password"); ?></label>
								<div class="controls">
									<input value="" class="form-control" type="password" name="password" placeholder="********" data-rule-required="true" data-rule-minlength="6" data-msg-required="<?php echo $this->lang->line("password_required"); ?>" data-msg-minlength="<?php echo $this->lang->line("password_length_error"); ?>">
								</div>
							</div>
							<div class="mn-login"> <span class="remember">
								<!-- <input type="checkbox" id="check">
								<label for="check">Remember Me</label> -->
								</span> <span class="forgot-password fget_pwd"><a href="javascript:void(0);"><?php echo $this->lang->line("forgot_password"); ?></a></span> </div>
							<div class="mn-login">
								<button class="btn-1" type="submit" ><?php echo $this->lang->line("sign_in"); ?></button>
								<input type="hidden" id="booknow" value=0 >
							</div>
						</div>
					</form>


				</div>
			</div>
			<div class="form-footer">

			<a href="<?php echo base_url("b2c/register"); ?>"><?php echo $this->lang->line("do_not_have_an_account"); ?><?php echo $this->lang->line("sign_up"); ?></a> 

			</div>
		</div>
	</div>
</div>





<!-- Forgot Password -->
<div class="modal fade b2b_forgot_pwd_model" role="dialog">
	<div class="modal-dialog modal-md"> 
		<!-- Modal content-->
		<div class="modal-content forgot_pwd1">
			<div class="modal-header">
				<button type="button" class="close" data-dismiss="modal">&times;</button>
				<h4 class="modal-title"><?php echo $this->lang->line("forgot_password"); ?></h4>
			</div>
			<div class="modal-body modal-body-1"><img src="<?php echo asset_url('images/logo.png'); ?>" alt="" width="130" height="64" />
				<div class="mn-main">
					<form action = "javascript:void(0);" class="b2c_forgot_password" method="post" id="form_pwd_reset">	
					<div class="col-md-12">
						<div class="mn-login">
							<label><?php echo $this->lang->line("email_address"); ?></label>
							<input class="input-1 reset_email" type="email" name="email" data-rule-required="true" data-rule-email="true" placeholder="<?php echo $this->lang->line("email_address"); ?>" data-msg-required="<?php echo $this->lang->line("email_address_required"); ?>" data-msg-email="<?php echo $this->lang->line("email_address_valid_error"); ?>">
						</div>
						<div class="mn-login">
						<span class="ajaxloader"></span>
						</div>
						<div class="mn-login">
							<input class="btn-1" type="submit" name="forgot_password" value="<?php echo $this->lang->line("send_reset_link"); ?>">
							
						</div>
					</div>
					<?php echo form_close(); ?>
				</div>
			</div>
		</div>
	</div>
</div>
<?php
}
?>


<!-- Payment installation offer -->
<div id="payment_installation" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <h4 class="modal-title"><?php echo $this->lang->line("installment_payment"); ?></h4>
      </div>
      <div class="modal-body">
        <p><?php echo $this->lang->line("installment_payment_description"); ?></p>
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal"><?php echo $this->lang->line("close_small"); ?></button>
      </div>
    </div>

  </div>
</div>

<!-- B2C companion  -->
<div id="b2c_companion" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="background: url(<?php echo asset_url('images/banner02.jpg'); ?>) !important;">
	<form action = "javascript:void(0);" class="add_companion" method="post">
	  <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <i class="fa fa-user-plus"></i><h4 class="modal-title"> Add companion</h4>
      </div>
      <div class="modal-body">
		<div class="mn-main user_info_div" id="first_div">
					<input type="hidden" name="companion[id]" id="companion_id">
					<div class="col-md-12">
						<div class="col-md-2 mnopad com_sec">
							<label>Salutation</label>
							<select name="companion[salutation]"  class="select2-container select2" data-msg-required="this field is required" data-rule-required="true" id="salutation">
							<option value="0"><?php echo $this->lang->line("mr");?></option>
							<option value="1"><?php echo $this->lang->line("mrs");?></option>
							<option value="2"><?php echo $this->lang->line("miss");?></option>
							</select>
						</div>
						<div class="col-md-3 mnopad com_sec">
							<label><?php echo $this->lang->line('first_name'); ?></label>
							<input id="fname" type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="companion[fname]" class="inputs_group" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>" >
						</div>
						<div class="col-md-3 mnopad com_sec">
							<label><?php echo $this->lang->line('last_name'); ?></label>
							<input id="lname" type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="companion[lname]" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>"  >
						</div>
						<div class="col-md-4 mnopad col-sm-4 mnopad com_sec">
		                  <div class="reginput1">
		                  	<label>نام و نام خانوادگی</label>
		                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="companion[name_fa]" class="inputs_group par_rtl" placeholder="نام و نام خانوادگی" title="نام و نام خانوادگی" id="edit_comp_name_fa" value="">
		                  </div>
		                </div>
		                <div class="clearfix"></div>
						<div class="col-md-3 mnopad col-sm-3 mnopad com_sec">
							<label>Country</label>
		                    <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="companion[nationality]" class="set_country select2" id="edit_comp_nationality" data-href="IR" >
		                    </select>
		                </div>
		                <div class="col-md-2 mnopad col-sm-6 col-xs-12 mnopad national_id com_sec">
		                  <div class="reginput1">
		                  <label><?php echo $this->lang->line('national_id'); ?></label>
		                    <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_valid_error'); ?>" name="companion[national_id]" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>" id="edit_comp_national_id" value="">
		                  </div>
		                </div>
						<div class="col-md-2 mnopad com_sec">
							<label><?php echo $this->lang->line('date_of_birth'); ?></label>
							<input id="dob" type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="companion[dob]" class="inputs_group till_current_date adult"  placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>"  >
						</div>
						<div class="col-md-3 mnopad passport_num com_sec">
							<label><?php echo $this->lang->line('passport_number'); ?></label>
							<input id="passport_no" type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="companion[passport_no]" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group"   data-rule-pattern="^([a-zA-Z0-9]{10,})$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
						</div>
						<div class="col-md-3 mnopad passport_exp com_sec">
							<label><?php echo $this->lang->line("passport_expiry_date"); ?></label>
							<input id="passport_exp" type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="companion[passport_exp]" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult"   >
						</div>
					</div>
					<div class="col-md-12">
						<div class="col-md-2">
							<input  type="hidden" name="companion[user_id]" value="<?php echo $this->data['user_id'];?>">
						</div>
					</div>
				
					
				</div>
		<div class="mn-main" id="second_div"></div>
      </div>
     <div class="modal-footer">
		<div class="btndiv">
			<input class="btn btn-default" style="background-color: #F7C5BE !important;" type="submit"  value="Save"></div>
      </div>
	 <div class="loaderdiv"></div>
	 <div class="modal_change" data-id="list"><i class="fa fa-anchor" aria-hidden="true"></i>&nbsp;List of your companions</div>
	 </form>
    </div>

  </div>
</div>

<!-- Companion list -->
<div id="list_companion" class="modal fade" role="dialog">
  <div class="modal-dialog">

    <!-- Modal content-->
    <div class="modal-content" style="background: url(<?php echo asset_url('images/banner02.jpg'); ?>) !important;">
      <div class="modal-header">
        <button type="button" class="close" data-dismiss="modal">&times;</button>
        <i class="fa fa-user-plus"></i><h4 class="modal-title"> Companion List</h4>
      </div>
      <div class="modal-body list_body">
       
      </div>
      <div class="modal-footer">
        <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color: #F7C5BE !important;"><?php echo $this->lang->line("close_small"); ?></button>
      </div>
    </div>

  </div>
</div>

<?php 

		$this->load->helper('utility_helper');
					
		$details = getStaticPageDetails(28,$this->data["default_language"]);
		//$details[1];  

	?>

	<div class="modal fade myModal_1" id="myModal_1" role="dialog">
		<div class="modal-dialog modal-md"> 
			<!-- Modal content-->
			<div class="modal-content">
				<div class="modal-header">
					<button type="button" class="close b2b_login_close_btn" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"><?php echo $details[0];  ?></h4>
				</div>
				<div class="modal-body"> <img alt="" width="130" height="64" src="<?php echo asset_url('images/logo.png'); ?>" />
					<div class="mn-main">
					
					<?php echo $details[1];  ?>


					</div>
				</div>

				<div class="modal-footer">

				<a href="<?php echo base_url("b2c/register"); ?>"><button type="button" class="btn btn-default"  style="background-color: #F7C5BE !important;"><?php echo $this->lang->line("register_here"); ?></button></a>
				        <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color: #F7C5BE !important;"><?php echo $this->lang->line("cancel_btn"); ?></button>

				</div>

			</div>
		</div>
	</div>



<div class="modal fade myModal_2" id="myModal_2" role="dialog">
		<div class="modal-dialog modal-md"> 
			<!-- Modal content-->
			<div class="modal-content" style="width: 200% !important; margin-right: -50%;">
				<div class="modal-header">
					<button type="button" class="close b2b_login_close_btn" data-dismiss="modal">&times;</button>
					<h4 class="modal-title"></h4>
				</div>
				<div class="modal-body">
					<div class="mn-main" id="latestNewsDetails">
					
					


					</div>
				</div>

				<div class="modal-footer">

				
				        <button type="button" class="btn btn-default" data-dismiss="modal" style="background-color: #F7C5BE !important;"><?php echo $this->lang->line("cancel_btn"); ?></button>

				</div>


			</div>
		</div>
	</div>

<div class="modal fade" id="lottery-modal" tabindex="-1" role="dialog" aria-labelledby="myModalLabel">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <button type="button" class="close" data-dismiss="modal" aria-label="Close"><span aria-hidden="true">&times;</span></button>
                <h4 class="modal-title" id="myModalLabel">پیگیری نتایج قرعه کشی</h4>
            </div>
            <div class="modal-body">
                <div style="width: 100%; text-align: right !important;">
                    <h4>لطفا شماره تلفن خود را وارد نمایید.</h4>
                    <input type="text" class="form-control" name="lottery-phone" id="lottery-phone" placeholder="09123456789" style="width: 50%; border-radius: 3px !important; border: 1px solid #999 !important;">
                    <div id="lottery-status-message" style="display: none;"></div>
                </div>
            </div>
            <div class="modal-footer" style="text-align: right !important;">
                <button type="button" class="btn btn-primary" style="width: 150px;" id="lottery-status-check-btn">جستجو</button>
                <button type="button" class="btn btn-default" data-dismiss="modal">انصراف</button>
            </div>
        </div>
    </div>
</div>