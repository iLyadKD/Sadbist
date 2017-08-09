

 <!-- <span class="cart_icon"><i class="fa fa-cart-plus"></i></span> -->
  <div class="clearfix"></div>
  <section class="mn-reg">
    <div class="container">
      <div class="row">
        <div class="fancy-title">
          <div class="col-md-12 mn_nopadding_left">
            <section>
              <div class="wizard">
                
               <form name="" id="prebook_submit"   action="javascript:void(0);" method="post" >
                  <div class="tab-content col-md-12">
                    <div class="tab-pane active" role="tabpanel" id="step1">
                     
                      <!-- begin panel group -->
                      <div class="panel-group" id="accordion" role="tablist" aria-multiselectable="true"> 
                        
                        <!-- panel 1 -->
                        <div class="panel panel-default"> 
                          <!--wrap panel heading in span to trigger image change as well as collapse --> 
                          
                          <div id="collapseOne" class="panel-collapse collapse in" role="tabpanel" aria-labelledby="headingOne">
                            <div class="panel-body">
                              <div class="col-md-12 nopadding">
                                
              
              
              <!------------------------Single Rooms----------Start----------------->
                             
                              <ul class="timeline">
                                  <li>&nbsp;
                                    
                                    
                                    <div class="timeline-panel">
                                      <div class="col-md-12 mn_nopadding traveller_details">
                                        
                                        <div class="col-md-1 col-sm-4 nopadding">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1"><?php echo $this->lang->line("title"); ?></label>
                                            <select name="salutation"  class="inputs_group" data-msg-required="this field is required" data-rule-required="true">
                                              <option value="0"><?php echo $this->lang->line("mr");?></option>
                                              <option value="1"><?php echo $this->lang->line("mrs");?></option>
                                              <option value="2"><?php echo $this->lang->line("miss");?></option>
                                            </select>
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("first_name");?></label>
                                            <input type="text" autocomplete="off" autofocus="true" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="fname" class="inputs_group" placeholder="<?php echo $this->lang->line('first_name'); ?>" title="<?php echo $this->lang->line('first_name'); ?>"  >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('last_name'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('only_alphabetics_error'); ?>" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="lname" class="inputs_group" placeholder="<?php echo $this->lang->line('last_name'); ?>" title="<?php echo $this->lang->line('last_name'); ?>"  >
                                          </div>
                                        </div>
                                        <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                          <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('date_of_birth'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="dob" class="inputs_group till_current_date adult" readonly="true" placeholder="<?php echo $this->lang->line('date_of_birth'); ?>" title="<?php echo $this->lang->line('date_of_birth'); ?>"  >
                                          </div>
                                        </div>
                                        
                                         <div class="col-md-2 col-sm-2">
                                        <div class="form-group inputtext">
                                          <label class="search_label-1 required"><?php echo $this->lang->line("nationality");?></label>
                                        <select style="width:100% !important;" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="nationality" class="set_country select2"  >
                                        </select>
                                        </div>
                                        </div>
                                        
                                         <div class="col-md-1 col-sm-1 nopadding">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('passport_number'); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="passport_no" placeholder="<?php echo $this->lang->line('passport_number'); ?>" title="<?php echo $this->lang->line('passport_number'); ?>" class="inputs_group"   data-rule-pattern="^([a-zA-Z]([a-zA-Z]+ ?)*)$" data-msg-pattern="<?php echo $this->lang->line('alphanum_error'); ?>">
                                          </div>
                                        </div>
                                         
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line("passport_expiry_date"); ?></label>
                                            <input type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>" name="passport_exp" placeholder="<?php echo $this->lang->line("passport_expiry_date"); ?>" title="<?php echo $this->lang->line("passport_expiry_date"); ?>" class="inputs_group from_date adult" readonly="true"  >
                                          </div>
                                        </div>
                                          <div class="col-md-2 col-sm-2 mn_nopadding_right national_id" style="display: none;">
                                           <div class="form-group inputtext">
                                            <label class="search_label-1 required"><?php echo $this->lang->line('national_id'); ?></label>
                                            <input disabled type="text" autocomplete="off" data-rule-required="true" data-msg-required="<?php echo $this->lang->line("required_star"); ?>"  name="national_id" class="inputs_group" placeholder="<?php echo $this->lang->line('national_id'); ?>" title="<?php echo $this->lang->line('national_id'); ?>"  >
                                          </div>
                                        </div>
                                      </div>
                                      
                                
                                    <div class="clearfix"></div>
                                      <div class="clearfix"></div>
                                      <div class="col-md-12 nopadding_left">
                                        <div class="col-md-2 col-sm-2">
                                          <div class="form-group">
                                            <h4></h4>
                                          </div>
                                        </div>
                                       
                                      </div>
                                            
                                      
                                      <div class="clearfix"></div>
                                    </div>
                                  </li>
                                  
                                </ul>
                              
                              
      
              <!------------------------Single Rooms----------End----------------->
              
              
                              </div>
                            </div>
                          </div>
                        </div>
                               
                      </div>
                      
                      <ul class="list-inline pull-right">
                        <li>
                          <button type="button" class="searchbtn next-step1">Save</button>
                        </li>
                      </ul>
                    </div>
                    </form>
                   <div class="clearfix"></div>
                  </div>
              </div>
            </section>
            <div class="clearfix"></div>
          </div>
        </div>
      </div>
    </div>
  </section>
  <div class="clearfix"></div>
