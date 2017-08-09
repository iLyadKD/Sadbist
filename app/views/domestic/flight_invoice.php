
<!DOCTYPE html PUBLIC "-//W3C//DTD XHTML 1.0 Transitional//EN" "http://www.w3.org/TR/xhtml1/DTD/xhtml1-transitional.dtd">
<html xmlns="http://www.w3.org/1999/xhtml">
<head>
<meta http-equiv="Content-Type" content="text/html; charset=utf-8" />
<title>Untitled Document</title>
</head>

<body>
<table width="100%"  cellpadding="0" cellspacing="0" border="0" style=" font-family:Trebuchet MS, Arial,sans-serif; font-size:13px;line-height:24px;">
  <tbody>
    <tr>
      <td><table width="820" cellpadding="10" cellspacing="0" border="0" bgcolor="#f8f8f8" align="center">
          <tbody>
            <tr>
              <td><table width="100%" bgcolor="#ec4614" border="0" cellpadding="10" cellspacing="0">
                  <tr>
                    <td style="text-align:center;"><img src="images/logo.png" alt=""/></td>
                  </tr>
                </table>
                <table width="100%" bgcolor="#fff" border="0" cellpadding="10" cellspacing="0">
                  <tr>
                    <td style="text-align:left;"><span style="font-size:18px;"><?php echo $this->lang->line("flight_invoice_title"); ?>Invoice</span></td>
                    <td style="text-align:right"> <?php echo $this->lang->line("flight_invoice_invoice_no_dot"); ?> NF24319 | <?php echo $this->lang->line("flight_invoice_service_tax_no_dot"); ?> AADCM5146RST006</td>
                  </tr>
                </table>
                <table width="100%" bgcolor="#e5e5e5" border="0" cellpadding="10" cellspacing="0">
                  <tr>
                    <th style="background:#f5f5f5;border:solid 1px #ccc"><?php echo $this->lang->line("flight_invoice_booked_by"); ?> </th>
                    <th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("flight_invoice_booked_id"); ?></th>
                    <th style="background:#f5f5f5;border:solid 1px #ccc;border-left:none"><?php echo $this->lang->line("flight_invoice_booking_date"); ?></th>
                  </tr>
                  <tr>
                    <td style="background:#fff;border:solid 1px #ccc;border-top:none; text-align:center">Manohar Gunda<br/>
                      (manohar.provab2041@gmail.com)</td>
                    <td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center">NF2202243190337</td>
                    <td style="background:#fff;border:solid 1px #ccc;border-top:none;border-left:none; text-align:center"> Tue, 24 March 15, 12:04 PM</td>
                  </tr>
                </table>
                <table width="100%" border="0" cellpadding="10" cellspacing="0">
                  <tr>
                    <td style="text-align:left; font-size:16px; color:#37c622;padding:10px 0"><?php echo $this->lang->line("flight_invoice_flight_details"); ?></span></td>
                  </tr>
                </table>
                <table cellspacing="0" cellpadding="0" bgcolor="#fff" style="width:100%;padding:0 10px 0px 0px; border:solid 1px #ddd; text-align:center">
                  <tr>
                    <td width="18%" style="padding:13px; font-size:12px; border-right:solid 1px #ddd"><img width="120px" height="60px" align="" src="images/spice-jet-logo.jpg"></td>
                    <td style="padding:3px;font-size:13px; border-right:solid 1px #ddd"><strong style="color:#00496f;">King Fisher<br>
                      SG-1312</strong></td>
                    <td style="padding:5px 15px; font-size:12px;text-align:left"><strong style="font-size:18px">BLR</strong><br>
                      <span style="font-size:14px;">Bangalore<br>
                      Fri, 10 Apr 15, 10:50 hrs</span></td>
                    <td style="padding:5px 0px; font-size:13px;"><img width="25px" height="25px" alt="" src="images/AWT-Plane.png"></td>
                    <td style="text-align:right; padding:5px 5px; font-size:13px;"><strong style="font-size:18px">VTZ</strong><br>
                      <span style="font-size:14px;">Vishakhapatnam<br>
                      Fri, 10 Apr 15, 12:50 hrs</span></td>
                  </tr>
                </table>
                <table cellspacing="0" cellpadding="0" style="width:100%;padding: 0px 0px; text-align:center">
                  <tr>
                    <td style="text-align:left; font-size:16px; color:#37c622;padding:10px 0"><?php echo $this->lang->line("flight_invoice_passengers_details"); ?></span></td>
                  </tr>
                </table>
                <table cellspacing="0" cellpadding="0" bgcolor="#fff" style="width:100%;padding: 0px 10px; border:solid 1px #ddd; text-align:center">
                  <tr>
                    <td style="padding:5px; font-size:12px;text-align:left">01. Naresh Kamireddy</td>
                  </tr>
                  <tr>
                    <td style="padding:5px; font-size:12px;text-align:left">02. Manohar Gunda</td>
                  </tr>
                </table>
                <table width="100%" cellpadding="0px" style="font-weight:normal;background: #00496f;border-color: #bce8f1;margin-top:10px; padding: 5px 10px;color:#999;">
                  <thead>
                    <tr>
                      <td style="font-size: 16px; padding:5px; font-sixe:14px; color:#fff;"><?php echo $this->lang->line("flight_invoice_fare_details"); ?></td>
                    </tr>
                  </thead>
                </table>
                <table width="100%" cellpadding="10px" bgcolor="#fff" style="font-weight:normal;font-size:12px;">
                  <tbody>
                    <tr>
                      <th colspan="0" style="text-align:left;width:50%;"><strong><?php echo $this->lang->line("flight_invoice_fare_charges"); ?></strong></th>
                      <th style="text-align:right;width:50%;"><strong><?php echo $this->lang->line("flight_invoice_passenger"); ?><br>
                        01</strong></th>
                    </tr>
                    <tr>
                      <td width="50%" style="text-align:left;border-top:1px solid #DDD;"><?php echo $this->lang->line("flight_invoice_base_fare"); ?></td>
                      <td width="50%" style="text-align:right;border-top:1px solid #DDD;">1437</td>
                    </tr>
                    <tr>
                      <td width="100%" style="text-align:left;border-top:1px solid #DDD;" colspan="2"><strong><?php echo $this->lang->line("flight_invoice_tax_and_other_charges_colan"); ?></strong></td>
                    </tr>
                    <tr>
                      <td width="50%" style="text-align:left;border-top:1px solid #DDD;"><?php echo $this->lang->line("flight_invoice_passengers_service_fee"); ?></td>
                      <td width="50%" style="text-align:right;border-top:1px solid #DDD;">147</td>
                    </tr>
                    <tr>
                      <td width="50%" style="text-align:left;border-top:1px solid #DDD;"><?php echo $this->lang->line("flight_invoice_user_development_fee"); ?></td>
                      <td width="50%" style="text-align:right;border-top:1px solid #DDD;">344</td>
                    </tr>
                    <tr>
                      <td width="50%" style="text-align:left;border-top:1px solid #DDD;border-bottom:1px solid #DDD;"><strong><?php echo $this->lang->line("flight_invoice_total_fare"); ?>Total Fare</strong></td>
                      <td width="50%" style="text-align:right;border-top:1px solid #DDD;border-bottom:1px solid #DDD;">1,999</td>
                    </tr>
                  </tbody>
                </table>
                <table width="100%" cellspacing="0" cellpadding="10" border="0" bgcolor="#fafafa" align="center" style="font-size:13px;text-align:left;margin:20px 0 0">
                  <tbody>
                    <tr>
                      <th style="border:solid 1px #ddd;border-right:none;"><?php echo $this->lang->line("flight_invoice_total_fare_all_passenger_colan"); ?></th>
                      <th style="border:solid 1px #ddd;">1999/-</th>
                    </tr>
                    <tr>
                      <td style="border:solid 1px #ddd;border-top:none; border-bottom:none;border-right:none;"><?php echo $this->lang->line("flight_invoice_convenience_fee_colan"); ?></td>
                      <td style="border:solid 1px #ddd;border-top:none; border-bottom:none;">200/-</td>
                    </tr>
                    <tr>
                      <th style="background:#ec4614;color:#fff;font-size:12px;border:solid 1px #ddd;border-right:none;"><?php echo $this->lang->line("flight_invoice_grand_total"); ?>Grand Total</th>
                      <th style="background:#ec4614; color:#fff;font-size:12px;border:solid 1px #ddd;">2199/-</th>
                    </tr>
                  </tbody>
                </table>
                <table width="100%" cellspacing="0" cellpadding="10" border="0" bgcolor="#fafafa" align="center" style="font-size:13px;text-align:left; margin:10px 0 0">
                  <tbody>
                    <tr>
                      <th style="background:#00496f; color:#fff;font-size:12px;" colspan="2"><?php echo $this->lang->line("flight_invoice_support_details"); ?></th>
                    </tr>
                    <tr>
                      <td style="border:solid 1px #ddd;border-bottom:none;border-right:none;"><?php echo $this->lang->line("flight_invoice_my_address"); ?></td>
                      <td style="border:solid 1px #ddd;border-bottom:none;"><?php echo $this->lang->line("flight_invoice_support"); ?></td>
                    </tr>
                    <tr>
                      <td style="border:solid 1px #ddd;border-right:none;">Tower A S P Infocity<br>
                        243, Udyog Vihar, Phase 1<br>
                        Gurgaon, Haryana 122016</td>
                      <td style="border:solid 1px #ddd;">18001028747(Tollfree)<br>
                        +911244628747(Fixed Line)<br>
                        service@10020.ir</td>
                    </tr>
                  </tbody>
                </table>
                <table width="100%" cellspacing="0" cellpadding="10" border="0" bgcolor="#fff" style="margin:10px 0 0">
                  <tbody>
                    <tr>
                      <td align="center">10020.ir, Washington DC, USA<br>
                        Tel: +90 999 999 9999 Fax: +9999 999999,<br>
                        E-mail : support@10020.ir</td>
                    </tr>
                  </tbody>
                </table></td>
            </tr>
          </tbody>
        </table></td>
    </tr>
  </tbody>
</table>
</body>
</html>
