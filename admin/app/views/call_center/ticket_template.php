<style>
	table {
		border-collapse: collapse;
		border-spacing: 0;
		margin: 0 10px;
		text-align: left;
		border: 1px solid #151515
	}
	tr {
		height: 30px;
		padding: 3px 0;
	}
	td {
		border: 1px solid #151515;
		font-size: 10px;
		padding: 3px 7px 2px;
	}
	.colored {
		background-color: #D9D9D9;
		border: 1px solid #151515;
		font-family: trebuchet MS;
		font-size: 8px;
		padding-bottom: 4px;
		padding-left: 6px;
		padding-top: 5px;
		text-align: center;
	}
	.center{
		text-align: center;
	}
	.bolder{
		font-size: 10px;
		font-weight: bold;
	}

	.smaller{
		font-size: 7px;
	}
	.hide-overflow{
		overflow-x: hidden;
	}
	
</style>


<?php foreach ($tickets as $key => $ticket) { ?>

<table>
	<tr>
		<td width="20%" rowspan="5" class="colored">
			<img src="<?php echo K_PATH_IMAGES ?>logo_farsi.png" alt="logo_1">
			<br>
			<span class="smaller">در صورت داشتن سوال یا پیگیری 
			<br>میتوانید با شماره 02153423000
			<br>تماس حاصل فرمایید</span>
			<br>
			<br>
			<br>
			<?php if ($ticket->type == 1) {?>
				<span class="bolder">کوپن پرواز رفت</span>
			<?php }else{ ?>
				<span class="bolder">کوپن پرواز برگشت</span>
			<?php } ?>
			<br>
			<br>
		</td> 
		<td width="20%" class="colored">Passenger Name<br><span class="center">نام مسافر</span></td>
		<td width="53%" colspan="6"><span class="center"><?php echo $ticket->passenger_name . ($ticket->passenger_name_fa != '' ? ' - '.$ticket->passenger_name_fa : '') ?></span></td>
		<td width="7%"></td>
	</tr>

  	<tr>
		
		<td>Booking Ref. / تاييديه <br><span class="center"><?php echo $ticket->booking_ref ?></span></td>
		<td colspan="3">NO / شماره <br><span class="center"><?php echo $ticket->number ?></span></td>
		<td colspan="4">REMARK / توضيحات <br><span class="center"><?php echo $ticket->remark ?></span></td> 
	</tr>

  	<tr>
		<td>From / مبدأ <br><span class="center"><?php echo $ticket->from ?></span></td>
		<td width="10.57%" class="colored">Date<br>تاريخ</td>
		<td width="9.35%" class="colored">Carrier<br>هواپیمایی</td>
		<td width="6.57%" class="colored">Flight<br>پرواز</td>
		<td width="8.4%" class="colored">Class<br>كلاس</td>
		<td width="8.57%" class="colored">Time<br>ساعت</td>
		<td width="8.37%" class="colored">Allow<br>بارمجاز</td>
		<td width="8.17%" class="colored">Status<br>وضعيت</td>
	</tr>

  	<tr>
		<td>To / مقصد <br><span class="center"><?php echo $ticket->to ?></span></td>
		<td><br><span class="center"><?php echo $ticket->date ?> <br> <?php echo $ticket->date_fa ?></span></td>
		<td><br><span class="center"><?php echo $ticket->carrier ?></span></td>
		<td><br><span class="center"><?php echo $ticket->flight ?></span></td>
		<td><br><span class="center hide-overflow"><?php echo $ticket->class ?></span></td>
		<td><br><span class="center"><?php echo $ticket->time ?></span></td>
		<td><br><span class="center"><?php echo $ticket->allow ?></span></td>
		<td><br><span class="center"><?php echo $ticket->status ?></span></td>
	</tr>

  	<tr>
		<td class="colored"></td>
		<td class="colored"></td>
		<td class="colored"></td>
		<td class="colored"></td>
		<td class="colored"></td>
		<td class="colored"></td>
		<td class="colored"></td>
		<td class="colored"></td>
	</tr>

  	<tr>
  		<td class="center">محل مهر و امضاء 
  		<br>
  		<br>
  		</td>
		<td>Fare / <span>كرايه بار</span> <br><span class="center"><?php echo $ticket->fare ?></span></td>
		<td colspan="7">Issued Date / <span>تاريخ صدور</span> 
		<br>
		<span class="center"><?php echo $ticket->issued_date ?> <br> <?php echo $ticket->issued_date_fa ?></span></td>
	</tr>

</table>
<br>
<br>

<?php } ?>

