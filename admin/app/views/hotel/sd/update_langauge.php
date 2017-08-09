<?php echo $this->load->view('header')?>
      <!-- BEGIN EDITABLE TABLE widget-->
             <div class="row-fluid">
                 <div class="span12">
                     <!-- BEGIN EXAMPLE TABLE widget-->
                     <div class="widget green">
                         <div class="widget-title">
                             <h4> <?php echo $title;?></h4>
                            <span class="tools">
                                <a href="javascript:;" class="icon-chevron-down"></a>
                                <a href="javascript:;" class="icon-remove"></a>
                            </span>
                         </div>
                         <div class="widget-body">
                             <div>
                                 <div class="clearfix"></div>

                        <div class='box-content'>
                       <form class='form form-horizontal validate-form' style='margin-bottom: 0;' action="<?php echo base_url(); ?>admin/package_type/edit/<?php echo $id; ?>"  method="post" name="frm1" enctype="multipart/form-data"> 
                      <?php 
                    
                      if($langauge) { foreach ($langauge as $rows) { $lang = strtolower($rows->name);  ?>
                              
                           <div class='control-group'>
                            <label class='control-label'  for='validation_current'><?php echo $rows->name; ?></label>
                              <div class='controls'>
                                
                               <input type="text" name="langauge[<?php echo $lang ?>]" id="name" data-rule-minlength='2' placeholder="<?php echo $rows->name; ?>" value="<?php echo $row->$lang; ?>" data-rule-required='true' class='span12'></div>                     
                              
                           </div>
                           <?php }} ?>
                         
                      
                                  
                        <div class="col-sm-9 col-sm-offset-3 ">
                              
                              <button class="btn btn-primary" type="submit">
                                <i class="icon-save"></i>
                                Update Package Type
                              </button>
                            </div>
                            
                        </form>
                          <br> <br>
                          </div>

                      </div>
                    </div>
                  </div>

                
             
            </div>
                         </div>
                     </div>
                     <!-- END EXAMPLE TABLE widget-->
                 </div>
             </div>

         <?php $this->load->view('footer');?>