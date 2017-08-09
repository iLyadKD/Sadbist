<?php

	/**
	 * Assets URL
	 *
	 * Returns asset_url [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	function asset_url($uri = '')
	{
		$asset_url = base_url()."assets".DIRECTORY_SEPARATOR;
		$ci_ins =& get_instance();
		return $asset_url.ltrim($uri, '/');
	}

	/**
	 * relative base URL
	 *
	 * Returns upload_url [. uri_string]
	 *
	 * @uses	CI_Config::_uri_string()
	 *
	 * @param	string|string[]	$uri	URI string or an array of segments
	 * @param	string	$protocol
	 * @return	string
	 */
	function upload_url($uri = '')
	{
		$upload_url = base_url()."upload_files".DIRECTORY_SEPARATOR;
		$ci_ins =& get_instance();
		return $upload_url.ltrim($uri, '/');
	}
	function front_url($uri = "")
	{
		$front_url = strrev(strstr(strrev(trim(base_url(), "/")), "/"));
		return $front_url.ltrim($uri, "/");
	}

	/* unset page chache */
	function unset_page_cache()
	{
		$ci_ins =& get_instance();
		$ci_ins->output->set_header("Cache-Control: no-store, no-cache, must-revalidate, no-transform, max-age=0, post-check=0, pre-check=0");
		$ci_ins->output->set_header("Pragma: no-cache");
	}

	/* set page chache */
	function set_page_cache()
	{
		$ci_ins =& get_instance();
		$ci_ins->output->cache(2);
	}

	function substring_index($subject, $delimiter, $count)
	{
		if($count < 0)
			return implode($delimiter, array_slice(explode($delimiter, $subject), $count));
		else
			return implode($delimiter, array_slice(explode($delimiter, $subject), 0, $count));
	}

	// julian date
	function to_julian_date($year, $month, $day)
	{
		$datearr = gregorian_to_jdf($year, $month, $day);
		return $datearr[0] . "/" . $datearr[1] . "/" . $datearr[2];
	}

	// gregorian date
	function to_gregorian_date($year, $month, $day)
	{
		$datearr = julian_to_gdf($year, $month, $day);
		return $datearr[2] . "-" . $datearr[1] . "-" . $datearr[0];
	}

	/*  julian_to_gdf  --  Perform calculation starting with a Julian date */
	function julian_to_gdf($year, $month, $day)
	{
		$month--;

		//  Update Gregorian day
		$j = persian_to_jd($year, $month, $day);
		$date = jd_to_gregorian($j);
		$weekday = jddayofweek($j);
		return array($date[0], $date[1], $date[2], $weekday);
	}

	/*  gregorian_to_jdf  --  Perform calculation starting with a Gregorian date */
	function gregorian_to_jdf($year, $month, $day)
	{
		$month -= 1;

		//  Update Julian day
		$j = gregorian_to_jd($year, $month + 1, $day) +
			   (floor(0 + 60 * (0 + 60 * 0) + 0.5) / 86400.0);
		//  Update Persian Calendar
		$perscal = jd_to_persian($j);
		$weekday = jddayofweek($j);
		return array($perscal[0], $perscal[1], $perscal[2], $weekday);
	}

	function gregorian_to_jd($year, $month, $day)
	{
		return (GREGORIAN_EPOCH - 1) + (365 * ($year - 1)) + floor(($year - 1) / 4) + (-floor(($year - 1) / 100)) + floor(($year - 1) / 400) + floor((((367 * $month) - 362) / 12) + (($month <= 2) ? 0 : (leap_gregorian($year) ? -1 : -2)) + $day);
	}

	function leap_gregorian($year)
	{
		return (($year % 4) == 0) && (!((($year % 100) == 0) && (($year % 400) != 0)));
	}

	//  JD_TO_PERSIAN  --  Calculate Persian date from Julian day

	function jd_to_persian($jd)
	{
		$jd = floor($jd) + 0.5;

		$depoch = $jd - persian_to_jd(475, 1, 1);
		$cycle = floor($depoch / 1029983);
		$cyear = mod($depoch, 1029983);
		if ($cyear == 1029982)
		{
			$ycycle = 2820;
		}
		else
		{
			$aux1 = floor($cyear / 366);
			$aux2 = mod($cyear, 366);
			$ycycle = floor(((2134 * $aux1) + (2816 * $aux2) + 2815) / 1028522) + $aux1 + 1;
		}
		$year = $ycycle + (2820 * $cycle) + 474;
		if ($year <= 0) {
			$year--;
		}
		$yday = ($jd - persian_to_jd($year, 1, 1)) + 1;
		$month = ($yday <= 186) ? ceil($yday / 31) : ceil(($yday - 6) / 30);
		$day = ($jd - persian_to_jd($year, $month, 1)) + 1;
		return array($year, $month, $day);
	}

	function jd_to_gregorian($jd)
	{
		$wjd = floor($jd - 0.5) + 0.5;
		$depoch = $wjd - GREGORIAN_EPOCH;
		$quadricent = floor($depoch / 146097);
		$dqc = mod($depoch, 146097);
		$cent = floor($dqc / 36524);
		$dcent = mod($dqc, 36524);
		$quad = floor($dcent / 1461);
		$dquad = mod($dcent, 1461);
		$yindex = floor($dquad / 365);
		$year = ($quadricent * 400) + ($cent * 100) + ($quad * 4) + $yindex;
		if (!(($cent == 4) || ($yindex == 4))) {
			$year++;
		}
		$yearday = $wjd - gregorian_to_jd($year, 1, 1);
		$leapadj = (($wjd < gregorian_to_jd($year, 3, 1)) ? 0
													  :
					  (leap_gregorian($year) ? 1 : 2)
				  );
		$month = floor(((($yearday + $leapadj) * 12) + 373) / 367);
		$day = ($wjd - gregorian_to_jd($year, $month, 1)) + 1;

		return array(year, month, day);
	}

	function persian_to_jd($year, $month, $day)
	{
		$epbase = $year - (($year >= 0) ? 474 : 473);
		$epyear = 474 + mod($epbase, 2820);

		return $day + (($month <= 7) ? (($month - 1) * 31) : ((($month - 1) * 30) + 6)) +floor((($epyear * 682) - 110) / 2816) + ($epyear - 1) * 365 + floor($epbase / 2820) * 1029983 +
			(PERSIAN_EPOCH - 1);
	}

	function mod($a, $b)
	{
		return $a - ($b * floor($a / $b));
	}

	function getStaticPageDetails($pId, $lang){

		$CI = get_instance();

		$CI->load->model('Common_Model');

		$name = $CI->Common_Model->getStaticPageDetails($pId, $lang);

		return $name;
	}

	function showCurrency($curr){
  
		//$curr = 'IRR';
   		$url = "http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s=IRR". $curr ."=X";

		$handle = @fopen($url, "r");
		if ($handle)
		{
			$result = fgets($handle, 4096);

			fclose($handle);
		}

		if(isset($result))
		{
			$allData = explode(",", $result); // Get all the contents to an array
			$rate = $allData[1];
			$last_updated = date("Y-m-d H:i:s", strtotime(str_replace("\"", "", $allData[2]." ".$allData[3])));
		}
		else
		{
			$rate = 0;
			$last_updated = date("Y-m-d H:i:s", strtotime("-7 days"));
		}

		$rate = $rate;

		$newrate='0.000031';
		return $newrate;
		//$val = $amount*0.000031;

   		//return array($curr, '0.000031');
  
   }


       
