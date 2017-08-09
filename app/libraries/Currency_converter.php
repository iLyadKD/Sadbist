<?php if ( ! defined("BASEPATH")) exit("No direct script access allowed"); 

class Currency_Converter {
	protected $ci;
	public function __construct()
	{
	   $this->ci =& get_instance();
	}   

	public function convert($from_currency, $to_currency, $from_db = 0, $amount = 1, $decimal = 2, $update_interval = 1)
	{
		
		if($from_currency != $to_currency)
		{
			$rate = 0;

			if($from_currency === "PDS")
				$from_currency = "GBP";
			
			if($from_db === 1 && $from_currency === "USD")
			{
				$query = "SELECT * FROM `currencies` WHERE `code` = '$to_currency'";
				$result = $this->ci->db->query($query);
				$find = 0;

				foreach ($query->result() as $row)
				{
					$find = 1;
					$last_updated = $row->last_updated;
					$now = date("Y-m-d H:i:s");
					$dt_start = new DateTime($now);
					$dt_end = new DateTime($last_updated);
					$dt_diff = $dt_start->diff($dt_end);

					if(((int)$dt_diff->y >= 1) || ((int)$dt_diff->m >= 1) || ((int)$dt_diff->d >= 1) || ((int)$dt_diff->h >= $update_interval) || ((double)$row->value === 0))
					{
						$rates_info = $this->new_rate($from_currency, $to_currency);
						$rate = $rates_info["value"];
						if($rate !== 0)
						{
							$data = array(
								"value" => $rate,
								"last_updated" => date("Y-m-d H:i:s")
							 );

							 $this->ci->db->where("id", $row->id);
							 $this->ci->db->update("currencies",$data);     
						}
					}
					else
						$rate = $row->value;
				}

				if($find === 0)
				{
					$rates_info = $this->new_rate($from_currency, $to_currency);
					$rate = $rates_info["value"];
					if($rate !== 0)
					{
						$data = array(
							"code" => $to_currency,
							"value" => $rate,
							"last_updated" => date("Y-m-d H:i:s")
						);

						$this->ci->db->insert("currencies",$data);
					}
				}

				$value = (double)$rate * (double)$amount;

				return number_format((double)$value, $decimal, ".", "");
			}
			else
			{
				$rates_info = $this->new_rate($from_currency, $to_currency);
				$rate = $rates_info["value"];
				$value = (double)$rate * (double)$amount;

				return number_format((double)$value, $decimal, ".", "");
			}
		}
		else
			return number_format((double)$amount, $decimal, ".", "");
	}

	public function new_rate($from_currency, $to_currency)
	{
		$url = "http://finance.yahoo.com/d/quotes.csv?e=.csv&f=sl1d1t1&s=". $from_currency . $to_currency ."=X";

		$handle = @fopen($url, "r");
		if ($handle)
		{
			$result = fgets($handle, 4096);
			fclose($handle);
		}

		if(isset($result))
		{
			$allData = explode(",", $result); /* Get all the contents to an array */
			$rate = $allData[1];
			$last_updated = date("Y-m-d H:i:s", strtotime(str_replace("\"", "", $allData[2]." ".$allData[3])));
		}
		else
		{
			$rate = 0;
			$last_updated = date("Y-m-d H:i:s", strtotime("-7 days"));
		}
		return array("value" => $rate, "last_updated" => $last_updated);
	}

	public function currency_val($curr)
	{
		$query = "SELECT `value` FROM `currencies` WHERE `status` = '1' AND `code` = '$curr'";
		$result = $this->ci->db->query($query);
		return $result->num_rows() > 0 ? $result->row()->value : 1;
	}

}