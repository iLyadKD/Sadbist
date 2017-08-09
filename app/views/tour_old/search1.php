<!DOCTYPE html>
<html class="no-js">
	<?php
	$include["css"][] = "owl.theme";
	$include["css"][] = "owl.carousel";
	$this->load->view("common/css", $include);

	?>  

<body>
<div class="wrapper">
<div class="cartsec">	
    <div class="carto">
      <div class="cartcount">2</div>
    </div>
  </div>

  <!---------header only------------>
    <?php
        $this->load->view("common/header");
        $this->load->view("common/notification");
    ?>

<div class="clearfix"></div>

<div class="middle_content">

<div class="container nopadding">
    
<?php $this->load->view("package/modify_search"); ?>


</div>

    <div class="container nopadding">
    
        <div class="col-md-3 col-sm-3 col-xs-12 nopadding">
        <div class="filter_section">
            <div class="filters-summary">
            <h5>NEW YORK - VIENA</h5>
            <p class="total-count"></p>
            </div>
            
             <div id="accordion-first" class="clearfix">
                         <form name="fliter" id="fliter" method="post">
						<div class="accordion" id="accordion2">
                        
                         
                          <div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseTwo">
                               Package Type<em class="icon-fixed-width fa fa-angle-down"></em>
                              </a>
                            </div>
                            <div id="collapseTwo" class="accordion-body collapse in">
							
							<div class="accordion-inner">
								<?php if($type){ foreach ($type as $type_row) { $ptype = json_decode($type_row->package_type);?>
								<div class="check_box">
									<input type="checkbox" id="n_<?php echo $type_row->type_id ?>" name="type[]" value="<?php echo $type_row->type_id ?>" />
									<label for="n_<?php echo $type_row->type_id ?>"> <span><?php echo $ptype->english; ?></span> </label>
								</div>
								<?php } } ?>
							</div>
							
                            </div>
                          </div>

                          <div class="clearfix"></div>
                          
                          <div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapseThree">
                               Price Range<em class="icon-fixed-width fa fa-angle-down"></em>
                              </a>
                            </div>
                            <div id="collapseThree" class="accordion-body collapse in">
                              <div class="accordion-inner">
               
                      <div class="check_box">
                        <input type="checkbox" id="range_1" name="fprice[]" value="1-100">
                        <label for="range_1"> <span>Up to  <?php echo currency_type(100);?> </span> </label>
                      </div>
                      <div class="check_box">
                        <input type="checkbox" id="range_2" name="fprice[]"  value="100-500">
                        <label for="range_2"> <span><?php echo currency_type(100);?> to <?php echo currency_type(500);?></span> </label>
                      </div>
                      <div class="check_box">
                        <input type="checkbox" id="range_3" name="fprice[]" value="500-1000" >
                        <label for="range_3"> <span><?php echo currency_type(500);?> to <?php echo currency_type(1000);?></span> </label>
                      </div>
                      <div class="check_box">
                        <input type="checkbox" id="range_4" name="fprice[]" value="1000-5000" >
                        <label for="range_4"> <span><?php echo currency_type(1000);?> to <?php echo currency_type(5000);?> </span> </label>
                      </div>
                      <div class="check_box">
                        <input type="checkbox" id="range_5" name="fprice[]" value="5000-10000" >
                        <label for="range_5"> <span><?php echo currency_type(5000);?> to <?php echo currency_type(10000);?> </span> </label>
                      </div>
                      <div class="check_box">
                        <input type="checkbox" id="range_6" name="fprice[]" value="10000-1000000000" >
                        <label for="range_6"> <span><?php echo currency_type(10000);?> above </span> </label>
                      </div>
            
                  </div>
                            </div>
                          </div>
                          <div class="clearfix"></div>
                          
                          <div class="accordion-group">
                            <div class="accordion-heading">
                              <a class="accordion-toggle collapsed" data-toggle="collapse" data-parent="#accordion2" href="#collapsefour">
                               Durations<em class="icon-fixed-width fa fa-angle-down"></em>
                              </a>
                            </div>
                            <div id="collapsefour" class="accordion-body collapse in">
                              <div class="accordion-inner">
								<div class="check_box">
								  <input type="checkbox" id="a_1"  name="fduration[]" value="1-3" />
								  <label for="a_1"> <span>1 to 3 nights</span> </label>
								</div>
								<div class="check_box">
								  <input type="checkbox" id="a_2"  name="fduration[]" value="4-5" />
								  <label for="a_2"> <span>4 to 5 nights </span> </label>
								</div>
								<div class="check_box">
								  <input type="checkbox" id="a_3" name="fduration[]" value="6-8" />
								  <label for="a_3"> <span>6 to  8 nights</span> </label>
								</div>
								<div class="check_box">
								  <input type="checkbox" id="a_4" name="fduration[]" value="9-10" />
								  <label for="a_4"> <span>9 to  10 nights</span> </label>
								</div>
								<div class="check_box">
								  <input type="checkbox" id="a_5" name="fduration[]" value="11-20" />
								  <label for="a_5"> <span>11 nights and above</span> </label>
								</div>
							  </div>
                            </div>
                          </div>
						  <input type="hidden" name="order_by" id="order_by" value="1"> 
						<input type="hidden" name="order_by_value" id="order_by_value" value="0"> 
                         </form>
                        </div><!-- end accordion -->
                    </div>
                        
            </div>
             <!-- End Filter Block-->
        </div>
        
        <div class="col-md-9 col-sm-9 col-xs-12 nopadding_right">
          
        
        
<div class="col-xs-10 nopadding mbot" id="ordering">
<div class="sorting_list">
<a class="segmented_btn active" data-id="1" data-value="0"  href="javascript:void(0)"><span class="sortby_text pull-left"></span><span>POPULAR</span><i class="fa fa-arrow-down"></i></a>
</div>

<div class="sorting_list">
<a class="segmented_btn first" data-id="2" data-value="0"  href="javascript:void(0)"><span class="sortby_text pull-left"></span><span>PRICE</span><i class="fa fa-arrow-down"></i></a>
</div>

<div class="sorting_list">
<a class="segmented_btn first" data-id="3" data-value="0"  href="javascript:void(0)"><span class="sortby_text pull-left"></span><span>DURATION</span><i class="fa fa-arrow-down"></i></a>
</div>
</div>

<div class="clearfix list-content" id="listings"></div>

</div>
</div>
</div>
  
  <?php
			$this->load->view("common/pop-ups");
			$this->load->view("common/footer");
		?>
		</div>
		

  <?php
  //$include["js"][] = "backslider.js";
  //$include["js"][] = "owl.carousel";
  //$include["js"][] = "webwidget_slideshow_dot.js";
  $this->load->view("common/script", $include);
  ?>

<script>
(function($) {
 "use strict"
 
 // Accordion Toggle Items
   var iconOpen = 'fa fa-angle-down',
        iconClose = 'fa fa-angle-up ';

    $(document).on('show.bs.collapse hide.bs.collapse', '.accordion', function (e) {
        var $target = $(e.target)
          $target.siblings('.accordion-heading')
          .find('em').toggleClass(iconOpen + ' ' + iconClose);
          if(e.type == 'show')
              $target.prev('.accordion-heading').find('.accordion-toggle').addClass('active');
          if(e.type == 'hide')
              $(this).find('.accordion-toggle').not($target).removeClass('active');
    });
    
})(jQuery);

</script>

<script>
    $(document).ready(function () {
       /* $("#range_25").ionRangeSlider({
            type: "double",
            min: 100,
            from:200,
            to:900,
            max: 1000,
            grid: true
        });*/
    });
</script>

<script>
$(document).ready(function() {
    $('#list').click(function(event){event.preventDefault();
	$('#grid').removeClass('active');
	$('#list').addClass('active');
	$('#image_view').removeClass('active');
	$('#listings .hotellist').removeClass('grid-group-item');
	$('#listings .hotellist').removeClass('image-group-item');
	$('#listings .hotellist').addClass('hotel-wrap');});
    
	$('#grid').click(function(event){event.preventDefault();
	$('#grid').addClass('active');
	$('#list').removeClass('active');
	$('#image_view').removeClass('active');
	$('#listings .hotellist').removeClass('hotel-wrap');
	$('#listings .hotellist').removeClass('image-group-item');
	$('#listings .hotellist').addClass('grid-group-item');});
	
	
});
</script>

<script>

$("#show_reg").click(function(){
$("#myModal").hide();
$("#myModal2").show();
});

$("#myModal").click(function(){
$("#myModal2").hide();
}); 

$(".fltview").click(function(){
$("#show_flightdetails").slideToggle();
});


</script> 

<script type="text/javascript">
$(window).load(function() {
    //$("#flexiselDemo1").flexisel();
	});
</script>

<!-----------Package search Module---------START------>

<script>
    var loading_img ="<p class='text-center'><img src='<?php echo asset_url('images/loader/loading.gif')?>' alt'Loading'><br><h3 class='text-center' ></h3>";

    $('#listings').html(loading_img);

    setTimeout(function(){
     // alert();
      var search=$( "#modify_package" ).serialize();
	  console.log(search);
      $.ajax({
        method: "get",
        url: "<?php echo base_url('package/result');?>",
        data: search
      })
      .done(function( msg ) {
           $('#listings').html(msg);
           $(".total-count").html($('#all_count').val());
           var chk_count = $(msg).filter('#chk_count').val();
		   if (chk_count > 0) {
            $("#ordering").show();
           }else {
			$("#ordering").hide();
		   }
		   
        });
    }, 1000);
	
	
    
    function ajaxsearch()
    {
      $('#listings').html(loading_img);
      setTimeout(function(){
        var postData=$( "#modify_package" ).serialize() + '&' +  $( "#fliter" ).serialize();
		$.ajax({
          method: "get",
          url: "<?php echo base_url('package/result');?>",
          data: postData
        })
        .done(function( msg ) {
          $('#listings').html(msg);
		  $(".total-count").html($('#all_count').val());
          var chk_count = $(msg).filter('#chk_count').val();
		   if (chk_count > 0) {
            $("#ordering").show();
           }else {
			$("#ordering").hide();
		   }
        });
      }, 1000);
    }
     $( "#fliter input[type='checkbox']" ).change(function() {
	  ajaxsearch();
    });  
    
    
$("#show_search").click(function(){
    $("#changesearchblock").slideToggle();
});

$(".segmented_btn").click(function(){
	$(".segmented_btn").removeClass('active');
	var order_by = $(this).attr("data-id");
	var order_by_value = $(this).attr("data-value");
	$("#order_by").val(order_by);
	$("#order_by_value").val(order_by_value);
	if(order_by_value==1){
		$(this).attr("data-value",0);
	}else {
		$(this).attr("data-value",1);
	}
	$(this).addClass("active");
	 ajaxsearch();
}); 
</script>

<!-----------Package search area---------END------>


</body>
</html>
