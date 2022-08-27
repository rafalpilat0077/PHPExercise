<?php

namespace App\Http\Controllers;


use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use League\Geotools\Coordinate\Coordinate;
use League\Geotools\Coordinate\Ellipsoid;

# Class representing an affiliate
class Affiliate{
	private $lat_long;
	private $id;
	private $name;

	# Constructor from passed in json assoc array
	function __construct($json_data){
		$this->lat_long = [
			floatval($json_data['latitude']),
			floatval($json_data['longitude'])
		];
		$this->id = $json_data['affiliate_id'];
		$this->name = $json_data['name'];
	}

	# Accessor functions
	public function get_latlong(){
		return $this->lat_long;
	}
	public function get_id(){
		return $this->id;
	}

	# Function to generate assoc array with ID and Name only
	public function get_id_name_array(){
		return [
			'id'=>$this->id,
			'name'=>$this->name
		];
	}
}

class AffiliatesController extends Controller
{

	# Function takes in 2 arrays representing latitude and longitude.
	# Finds the distance in KM between them, using the Vincenty function in Geotools library
	private function get_km_distance($latlong1, $latlong2){

		$geotools = new \League\Geotools\Geotools();

		$coordinate1 = new Coordinate($latlong1, Ellipsoid::createFromName(Ellipsoid::WGS84));
		$coordinate2 = new Coordinate($latlong2, Ellipsoid::createFromName(Ellipsoid::WGS84));

		$distance = $geotools->distance()->setFrom($coordinate1)->setTo($coordinate2)->in('km')->vincenty();

		return $distance;
	}

	# Comparator function for usort
	private function compare_affiliate_array($affiliate1, $affiliate2){
		return $affiliate1['id'] > $affiliate2['id'];
	}

	# Function generates a sorted array of affiliates within specific range of office (In KM).
	# Affiliates are represented as an assoc array with ID and Name (As required for View)
	public function get_invitation_table($affiliates, $range){
		$office_latlong = [53.3340285, -6.2535495];

		$invitation_table = [];

		foreach($affiliates as $affiliate_data){

			$affiliate = new Affiliate($affiliate_data);
			$distance = $this->get_km_distance($office_latlong, $affiliate->get_latlong());

			if($distance<=$range){
				array_push($invitation_table, $affiliate->get_id_name_array());

			}

		}

		usort($invitation_table, array($this, 'compare_affiliate_array'));

		return $invitation_table;
	}

	# Function to generate "nearby_affiliates" page
    public function nearby_affiliates(Request $request, $range){

		# Reading JSON file from storage
		$txt_file = Storage::disk('local')->get('json\affiliates.txt');
		# Reformating file, as provided file was not in correct json format (i.e. newlines instead of ',', No oppening or closing brackets)
		$txt_file = "[".str_replace("\n", ',', $txt_file)."]";
		# Decoding json
		$affiliates = json_decode($txt_file, true);

		# Generating invitation table.
		$invitation_table = $this->get_invitation_table($affiliates, $range);

		return view('nearby_affiliates')->with(['invitation_table'=>$invitation_table]);
	}
}
