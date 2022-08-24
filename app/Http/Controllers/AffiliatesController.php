<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Coordinate\Ellipsoid;

class Affiliate{
	private $lat_long;
	private $id;
	private $name;

	function __construct($json_data){
		$this->lat_long = [
			floatval($json_data['latitude']),
			floatval($json_data['longitude'])
		];
		$this->id = $json_data['affiliate_id'];
		$this->name = $json_data['name'];
	}

	public function get_latlong(){
		return $this->lat_long;
	}
}

class AffiliatesController extends Controller
{

	private function get_km_distance($latlong1, $latlong2){

		$geotools = new \League\Geotools\Geotools();

		$coordinate1 = new Coordinate($latlong1, Ellipsoid::createFromName(Ellipsoid::WGS84));
		$coordinate2 = new Coordinate($latlong2, Ellipsoid::createFromName(Ellipsoid::WGS84));

		$distance = $geotools->distance()->setFrom($coordinate1)->setTo($coordinate2)->in('km')->vincenty();

		return $distance;
	}

    public function nearby_affiliates(Request $request, $range){

		$txt_file = Storage::disk('local')->get('json\affiliates.txt');
		$txt_file = "[".str_replace("\n", ',', $txt_file)."]";
		$json_file = json_decode($txt_file, true);
		$affiliates = $json_file;

		$office_latlong = [53.3340285, -6.2535495];

		foreach($affiliates as $affiliate_data){

			$affiliate = new Affiliate($affiliate_data);
			$distance = $this->get_km_distance($office_latlong, $affiliate->get_latlong());



			//var_dump($affiliate);
			//echo '<br>';

		}


		//dd($json_file);

		return '';
	}
}
