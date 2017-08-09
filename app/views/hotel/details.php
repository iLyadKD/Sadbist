<!DOCTYPE html>
<html class="no-js">
  <?php
  $include["css"][] = "owl.carousel";
  $this->load->view("common/head", $include);
  ?>
<body>
<div id="wrapper">
  <?php
      $this->load->view("common/header");
      $this->load->view("common/notification");
  ?>
  <div class="flight_block_modify result_bg">
    <div class="container nopadding">
      <div class="row">
        <div class="col-xs-12 nopadding">
          <div class="sortblock">
            <div class="col-md-6 col-xs-12 border-rt">
              <div class="checkin2"> <span class="loc2">Hotels in London City & Airports</span>
                <p><i class="fa fa-map-marker"></i> 151 Sussex Gardens Hyde Park London W2 2RY United Kingdom</p>
                <div class="clearfix"></div>
              </div>
            </div>
            <div class="col-md-2 col-xs-6 border-rt">
              <div class="checkin2">
                <p><?php echo $this->lang->line("hotel_details_rating"); ?></p>
                <br/>
                <img src="<?php echo base_url('assets/images/star_rating_4.png')?>" alt=""/> </div>
            </div>
            <div class="col-md-2 col-sm-3 col-xs-6 pull-right"> <a href="#" data-toggle="collapse" data-target="#show_search" class="modify_search_btn"><?php echo $this->lang->line("hotel_details_modify_search"); ?></a></div>
          </div>
        </div>
      </div>
    </div>
    <div id="show_search" class="modify_search collapse">
      <div class="container nopadding">
        <div class="col-xs-12 nopadding">
          <div class="clearfix"></div>
          <div class="col-xs-12 modify_types">
            <div class="row">
              <div class="col-xs-12 mn_nopadding_left">
                <div class="col-xs-12 col-md-4 col-sm-4 mn_nopadding_right">
                  <label class="mod_label"><?php echo $this->lang->line("hotel_details_hotel_name"); ?></label>
                  <input type="text" class="mod_inputs_group" value="Hotel Name Optional" />
                </div>
                <div class="col-xs-12 col-md-3 col-sm-4 mn_nopadding_right">
                  <label class="mod_label"><?php echo $this->lang->line("hotel_details_check_in"); ?></label>
                  <input type="text" class="mod_inputs_group cal_back" value="12-02-2015" />
                </div>
                <div class="col-xs-12 col-md-3 col-sm-4 mn_nopadding_right">
                  <label class="mod_label"><?php echo $this->lang->line("hotel_details_check_out"); ?></label>
                  <input type="text" class="mod_inputs_group cal_back" value="12-02-2015" />
                </div>
                <div class="col-xs-12 col-md-2 col-sm-3 mn_nopadding_right">
                  <label class="mod_label"><?php echo $this->lang->line("hotel_details_rooms_colan"); ?></label>
                  <select class="custom-select mod_inputs_group">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
              </div>
              <div class="clearfix"></div>
              <div class="mn-margin"></div>
              <div class="col-md-12 col-sm-12 col-xs-12 mn_nopadding_left">
                <div class="col-xs-12 col-md-2 col-sm-3 mn_nopadding_right">
                  <label class="mod_label"><?php echo $this->lang->line("hotel_details_adult_colan"); ?></label>
                  <select class="custom-select mod_inputs_group">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
                <div class="col-xs-12 col-md-2 col-sm-3 mn_nopadding_right">
                  <label class="mod_label"><?php echo $this->lang->line("hotel_details_children_colan"); ?></label>
                  <select class="custom-select mod_inputs_group">
                    <option>1</option>
                    <option>2</option>
                  </select>
                </div>
                <div class="col-xs-12 col-sm-3 col-md-2 mn_nopadding_right pull-right">
                  <label class="mod_label">&nbsp;</label>
                  <input type="submit" class="search_btn" value="<?php echo $this->lang->line("hotel_details_search"); ?>" />
                </div>
              </div>
            </div>
          </div>
        </div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>
  <div class="middle_content">
    <div class="container nopadding">
      <div class="row">
        <div class="clearfix"></div>
        <div class="col-md-12">
          <div class="filtrsrch">
            <div class="col-md-8 col-sm-9 nopadding">
              <div id="sync1" class="owl-carousel detowl">
                <div class="item">
                  <div class="bighotl"> <img src="<?php echo base_url('assets/images/item1.jpg')?>" alt="" /> </div>
                </div>
                <div class="item">
                  <div class="bighotl"> <img src="<?php echo base_url('assets/images/item2.jpg')?>" alt="" /> </div>
                </div>
                <div class="item">
                  <div class="bighotl"> <img src="<?php echo base_url('assets/images/item5.jpg')?>" alt="" /> </div>
                </div>
                <div class="item">
                  <div class="bighotl"> <img src="<?php echo base_url('assets/images/item7.jpg')?>" alt="" /> </div>
                </div>
              
              </div>
              <div id="sync2" class="owl-carousel syncslide">
                <div class="item" >
                  <div class="thumbimg"> <img src="<?php echo base_url('assets/images/item1.jpg')?>" alt="" /> </div>
                </div>
                <div class="item" >
                  <div class="thumbimg"> <img src="<?php echo base_url('assets/images/item2.jpg')?>" alt="" /> </div>
                </div>
                <div class="item" >
                  <div class="thumbimg"> <img src="<?php echo base_url('assets/images/item5.jpg')?>" alt="" /> </div>
                </div>
                <div class="item" >
                  <div class="thumbimg"> <img src="<?php echo base_url('assets/images/item7.jpg')?>" alt="" /> </div>
                </div>
              </div>
            </div>
            <div class="col-md-4 col-sm-3 nopadding">
              <div class="col-md-12 col-50 nopadding">
                <div class="insidemaindets">
                  <div class="rowsdt">
                    <h4 class="dethtlname">Mabely Grand Hotel</h4>
                    <span class="starimg"> <img alt="" src="<?php echo base_url('assets/images/star_rating_4.png')?>"> </span> <span class="addre"> 56 D, Electronic City,Opp Wipro Gate No.5, Opp Wipro Gate No.5 </span> </div>
                  <div class="linbrk"></div>
                  <div class="rowsdtboo"> <a class="detsbook"><?php echo $this->lang->line("hotel_details_book_now"); ?></a> </div>
                </div>
              </div>
              <div class="col-md-12">
                <div class="mapzall">
                  <iframe src="https://www.google.com/maps/embed?pb=!1m16!1m12!1m3!1d124475.54439465133!2d77.59769891074853!3d12.852268402696302!2m3!1f0!2f0!3f0!3m2!1i1024!2i768!4f13.1!2m1!1smap!5e0!3m2!1sen!2sin!4v1445073790827" width="100%" height="150" frameborder="0" style="border:0" allowfullscreen></iframe>
                </div>
              </div>
            </div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12">
          <div class="col-md-8 nopadding_left">
            <h1 class="bookingavailbility1"><?php echo $this->lang->line("hotel_details_overview"); ?></h1>
            <div class="clearfix"></div>
            <div class="mn-overview">
              <h4>Lorem Ipsum is simply dummy text</h4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
              <h4>Lorem Ipsum is simply</h4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s</p>
              <h4>Lorem Ipsum is simply dummy text</h4>
              <p>Lorem Ipsum is simply dummy text of the printing and typesetting industry. Lorem Ipsum has been the industry's standard dummy text ever since the 1500s, when an unknown printer took a galley of type and scrambled it to make a type specimen book. </p>
            </div>
          </div>
          <div class="col-md-4 nopadding_right mn_amenites">
            <h1 class="bookingavailbility1"><?php echo $this->lang->line("hotel_details_amenities"); ?></h1>
            <div class="mn-overview">
              <ul>
                <li><i class="fa fa-angle-double-right"></i> Air conditioning</li>
                <li><i class="fa fa-angle-double-right"></i>ATM</li>
                <li><i class="fa fa-angle-double-right"></i>Bar/Lounge</li>
                <li><i class="fa fa-angle-double-right"></i>Bathroom</li>
                <li><i class="fa fa-angle-double-right"></i>Business Center</li>
                <li><i class="fa fa-angle-double-right"></i>Climate control</li>
                <li><i class="fa fa-angle-double-right"></i>Coffee</li>
                <li><i class="fa fa-angle-double-right"></i>Concierge</li>
                <li><i class="fa fa-angle-double-right"></i>Cribs Available</li>
                <li><i class="fa fa-angle-double-right"></i>Desk</li>
                <li><i class="fa fa-angle-double-right"></i>Dry cleaning service</li>
                <li><i class="fa fa-angle-double-right"></i>Express check in</li>
                <li><i class="fa fa-angle-double-right"></i>Free car parking</li>
                <li><i class="fa fa-angle-double-right"></i>Free newspaper</li>
                <li><i class="fa fa-angle-double-right"></i>Free Wifi</li>
                <li><i class="fa fa-angle-double-right"></i>Hairdryer</li>
                <li><i class="fa fa-angle-double-right"></i>Housekeeping</li>
                <li><i class="fa fa-angle-double-right"></i>Indoor pool</li>
                <li><i class="fa fa-angle-double-right"></i>Refrigerator</li>
                <li><i class="fa fa-angle-double-right"></i>Restaurant(s)</li>
                <li><i class="fa fa-angle-double-right"></i>Room Service</li>
                <li><i class="fa fa-angle-double-right"></i>Surcharge</li>
              </ul>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="clearfix"></div>
        <div class="col-md-12" id="overview">
          <div class="col-md-8 nopadding_left" id="hotelRooms">
            <div style="width:100% !important; display:inline-block; margin-top:2%; overflow:auto">
              <h1 class="bookingavailbility1"><?php echo $this->lang->line("hotel_details_choose_room_class"); ?></h1>
              <table style="vertical-align:middle;" class=" table  table-bordered">
                <tbody>
                  <tr style="background-color:#00ab01; border:none;">
                    <th style="color:#fff; border:none !important;"><?php echo $this->lang->line("hotel_details_room_type"); ?></th>
                    <th style="color:#fff; border:none !important"><?php echo $this->lang->line("hotel_details_night"); ?></th>
                    <th style="color:#fff; border:none !important"><?php echo $this->lang->line("hotel_details_total_cost"); ?></th>
                    <th style="color:#fff; border:none !important"><?php echo $this->lang->line("hotel_details_per_room_cost"); ?></th>
                    <th style="color:#fff; border:none !important"><?php echo $this->lang->line("hotel_details_status"); ?></th>
                    <th style="color:#fff; border:none !important">&nbsp;</th>
                  </tr>
                  <tr>
                    <td> SINGLE WITHOUT WINDOW </td>
                    <td>1</td>
                    <td>$1934</td>
                    <td>$1934</td>
                    <td><span class="havail">Available</span><br>
                      <span id="det_room" class="detail_room"><?php echo $this->lang->line("hotel_details_detail"); ?></span></td>
                    <td><input type="button" class="abook" value="<?php echo $this->lang->line("hotel_details_book"); ?>"></td>
                  </tr>
                  <tr style="background-color:#f5f5f5;">
                    <td> SINGLE WITHOUT WINDOW </td>
                    <td>1</td>
                    <td>$1934</td>
                    <td>$1934</td>
                    <td><span class="havail">Available</span><br>
                      <span id="det_room" class="detail_room"><?php echo $this->lang->line("hotel_details_detail"); ?></span></td>
                    <td><input type="button" class="abook" value="<?php echo $this->lang->line("hotel_details_book"); ?>"></td>
                  </tr>
                </tbody>
              </table>
              <div class="popbox" id="pop1"> <strong><?php echo $this->lang->line("hotel_details_policies_colan"); ?></strong>Integer egestas, orci id condimentum eleifend, nibh nisi pulvinar eros, vitae ornare massa neque ut orci. Nam aliquet lectus sed odio eleifend, at iaculis dolor egestas. Nunc elementum pellentesque augue sodales porta. Etiam aliquet rutrum turpis, </div>
            </div>
            <div class="clearfix"></div>
          </div>
          <div class="col-md-4 mn_hotels">
            <h1 class="bookingavailbility1"><?php echo $this->lang->line("hotel_details_near_the_hotel"); ?></h1>
            <div class="clearfix"></div>
            <div class="mn_near">
              <ul>
                <li>5 kms to city centre</li>
                <li>27 kms to the airport (london heathrow airport)</li>
                <li>30 kms to the airport (london luton airport)</li>
                <li>10 minute walk to the nearest metro station (golders green)</li>
              </ul>
            </div>
            <div class="clearfix"></div>
            <h1 class="bookingavailbility1"><?php echo $this->lang->line("hotel_details_address"); ?></h1>
            <div class="clearfix"></div>
            <div class="mn-overview">
              <p>155-159 Golders Green Road, London Nw11 9Bx, United Kingdom </p>
            </div>
            <div class="clearfix"></div>
          </div>
        </div>
        <div class="clearfix"></div>
      </div>
    </div>
  </div>
  <div class="clearfix"></div>

<?php 
  $this->load->view('common/pop-ups');
  $include = array();
  $include['js'][] = "jquery-1.7.min.js";
  $include['js'][] = "jquery-ui.js";
  $include['js'][] = "bootstrap.js";
  $include['js'][] = "menu.js";
  $include['js'][] = "owl.carousel.min.js";
  $this->load->view('common/footer',$include);
?>
</div>
<script>

  $('#myCarousel').carousel({
  interval: 40000
});

$('.carousel .item').each(function(){
  var next = $(this).next();
  if (!next.length) {
    next = $(this).siblings(':first');
  }
  next.children(':first-child').clone().appendTo($(this));

  if (next.next().length>0) {
 
      next.next().children(':first-child').clone().appendTo($(this)).addClass('rightest');
      
  }
  else {
      $(this).siblings(':first').children(':first-child').clone().appendTo($(this));
     
  }
});
</script> 
<script>
  $(function() {
    // run the currently selected effect
    function runEffect() {
      // get effect type from
      var selectedEffect = $( "#effectTypes" ).val();
 
      // most effect types need no options passed by default
      var options = {};
      // some effects have required parameters
      if ( selectedEffect === "scale" ) {
        options = { percent: 0 };
      } else if ( selectedEffect === "size" ) {
        options = { to: { width: 200, height: 60 } };
      }
 
      // run the effect
      $( "#pop1" ).toggle( selectedEffect, options, 500 );
    };
 
    // set effect from select menu value
    $( "#det_room" ).click(function() {
      runEffect();
    });
  });
  </script> 
<script>
  (function ($) {
    $('.hotel_detail a').butterScroll({
      scroll  : 500,
      toplink : true
    });
  })(jQuery);
</script> 
<script>
  $(function() {
    // run the currently selected effect
    function runEffect() {
      // get effect type from
      var selectedEffect = $( "#effectTypes" ).val();
 
      // most effect types need no options passed by default
      var options = {};
      // some effects have required parameters
      if ( selectedEffect === "scale" ) {
        options = { percent: 0 };
      } else if ( selectedEffect === "size" ) {
        options = { to: { width: 200, height: 60 } };
      }
 
      // run the effect
      $( "#changesearchblock" ).toggle( selectedEffect, options, 500 );
    };
 
    // set effect from select menu value
    $( "#show_search" ).click(function() {
      runEffect();
    });
  });
  </script> 
<script>
$(document).on("click", "span.forgot-password a", function(){
  $(this).closest("#myModal").find("button.close").click();
});
</script> 
<script>
    
    
  
  var sync1 = $("#sync1");
      var sync2 = $("#sync2");

      sync1.owlCarousel({
        singleItem : true,
        slideSpeed : 1000,
        navigation: true,
        pagination:false,
        afterAction : syncPosition,
        responsiveRefreshRate : 200,
      });

      sync2.owlCarousel({
        items : 7,
        itemsDesktop      : [1199,6],
        itemsDesktopSmall     : [979,6],
        itemsTablet       : [768,4],
        itemsMobile       : [479,2],
        pagination:false,
        responsiveRefreshRate : 100,
        afterInit : function(el){
          el.find(".owl-item").eq(0).addClass("synced");
        }
      });

      function syncPosition(el){
        var current = this.currentItem;
        $("#sync2")
          .find(".owl-item")
          .removeClass("synced")
          .eq(current)
          .addClass("synced")
        if($("#sync2").data("owlCarousel") !== undefined){
          center(current)
        }

      }

      $("#sync2").on("click", ".owl-item", function(e){
        e.preventDefault();
        var number = $(this).data("owlItem");
        sync1.trigger("owl.goTo",number);
      });

      function center(number){
        var sync2visible = sync2.data("owlCarousel").owl.visibleItems;

        var num = number;
        var found = false;
        for(var i in sync2visible){
          if(num === sync2visible[i]){
            var found = true;
          }
        }

        if(found===false){
          if(num>sync2visible[sync2visible.length-1]){
            sync2.trigger("owl.goTo", num - sync2visible.length+2)
          }else{
            if(num - 1 === -1){
              num = 0;
            }
            sync2.trigger("owl.goTo", num);
          }
        } else if(num === sync2visible[sync2visible.length-1]){
          sync2.trigger("owl.goTo", sync2visible[1])
        } else if(num === sync2visible[0]){
          sync2.trigger("owl.goTo", num-1)
        }
      }
</script>
</body>
</html>
