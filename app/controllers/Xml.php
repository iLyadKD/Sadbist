<?php if (!defined('BASEPATH')) exit('No direct script access allowed');   

class XML extends CI_Controller {

	 public function  __construct() {
		parent::__construct();
	}

	// function to convert xml to php array
	public function xml2Array($filename)
	{
		$xml = simplexml_load_file($filename, "SimpleXMLElement", LIBXML_NOCDATA);
		$json = json_encode($xml);
		return json_decode($json,TRUE);
	}

	public function geotree()
	{
		// for($i = 1; $i < 5; $i++)
		{
			if(file_exists("jac_cities/1.xml"))
			{
				// header("content-type: text/xml");
				// echo file_get_contents("jac_cities/1.xml");die;
				$hotel = $this->xml2Array("jac_cities/1.xml");
				$hotel = json_decode(json_encode($hotel,true));

				$continents = $hotel->CONTINENT;
				$data  = array();
				foreach ($continents as $cont_idx => $continent)
				{
					$continent_name = $continent->CONTINENTNAME;
					$countries = isset($continent->COUNTRY) ? (is_object($continent->COUNTRY) ? array($continent->COUNTRY) : $continent->COUNTRY) : array();
					if(count($countries) > 0)
					foreach ($countries as $cntr_idx => $country)
					{
						$country_name = $country->COUNTRYNAME;
						$regions = isset($country->REGION) ? (is_object($country->REGION) ? array($country->REGION) : $country->REGION) : array();
						if(count($regions) > 0)
						foreach ($regions as $rgn_idx => $region)
						{
							$region_name = $region->REGIONNAME;
							$cities = isset($region->CITY) ? (is_object($region->CITY) ? array($region->CITY) : $region->CITY) : array();
							if(count($cities) > 0)
							foreach ($cities as $cty_idx => $city)
							{
								$city_name = $city->CITYNAME;
								$districts = isset($city->DISTRICT) ? (is_object($city->DISTRICT) ? array($city->DISTRICT) : $city->DISTRICT) : array();
								if(count($districts) > 0)
								foreach ($districts as $dst_idx => $district)
								{
									$district_name = $district->DISTRICTNAME;
									$city_centres = isset($district->CityCentreSuburbs) ? (is_object($district->CityCentreSuburbs) ? array($district->CityCentreSuburbs) : $district->CityCentreSuburbs) : array();
									if(count($city_centres) > 0)
									foreach ($city_centres as $cc_idx => $city_centre)
									{
										$cc_name = $city_centre->CityCentreSuburbsName;
										$areas = isset($city_centre->Area) ? (is_object($city_centre->Area) ? array($city_centre->Area) : $city_centre->Area) : array();
										if(count($areas) > 0)
										foreach ($areas as $area_idx => $area)
										{
											$area_name = $area->AreaName;
											$stations = isset($area->Station) ? (is_object($area->Station) ? array($area->Station) : $area->Station) : array();
											if(count($stations) > 0)
											foreach ($stations as $st_idx => $station)
											{
												$station_name = $station->StationName;
												
												$data[] = array("station" => $station_name,
																"area" => $area_name,
																"cc" => $cc_name,
																"district" => $district_name,
																"city" => $city_name,
																"region" => $region_name,
																"country" => $country_name,
																"continent" => $continent_name
																);
											}
											else
												$data[] = array("station" => "",
																"area" => $area_name,
																"cc" => $cc_name,
																"district" => $district_name,
																"city" => $city_name,
																"region" => $region_name,
																"country" => $country_name,
																"continent" => $continent_name
																);
										}
										else
											$data[] = array("station" => "",
															"area" => "",
															"cc" => $cc_name,
															"district" => $district_name,
															"city" => $city_name,
															"region" => $region_name,
															"country" => $country_name,
															"continent" => $continent_name
															);
									}
									else
										$data[] = array("station" => "",
														"area" => "",
														"cc" => "",
														"district" => $district_name,
														"city" => $city_name,
														"region" => $region_name,
														"country" => $country_name,
														"continent" => $continent_name
														);
								}
								else
									$data[] = array("station" => "",
													"area" => "",
													"cc" => "",
													"district" => "",
													"city" => $city_name,
													"region" => $region_name,
													"country" => $country_name,
													"continent" => $continent_name
													);
							}
							else
								$data[] = array("station" => "",
												"area" => "",
												"cc" => "",
												"district" => "",
												"city" => "",
												"region" => $region_name,
												"country" => $country_name,
												"continent" => $continent_name
												);
						}
						else
							$data[] = array("station" => "",
											"area" => "",
											"cc" => "",
											"district" => "",
											"city" => "",
											"region" => "",
											"country" => $country_name,
											"continent" => $continent_name
											);
					}
					else
						$data[] = array("station" => "",
										"area" => "",
										"cc" => "",
										"district" => "",
										"city" => "",
										"region" => "",
										"country" => "",
										"continent" => $continent_name
										);
				}
			}
			var_dump($data);
			// $this->db->insert_batch("dump_jac_cities", $data);
		}
	}

}