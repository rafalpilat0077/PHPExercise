<?php

namespace Tests\Unit;

use PHPUnit\Framework\TestCase;
use App\Http\Controllers\AffiliatesController;

class NearbyAffiliatesTest extends TestCase
{
    /**
     * A basic unit test example.
     *
     * @return void
     */
	# Testing the "get_invitation_table" function of AffiliatesController
    public function test_nearby_affiliates()
    {
		$result = (new AffiliatesController())->get_invitation_table(json_decode('
			[{"latitude": "52.986375", "affiliate_id": 12, "name": "Yosef Giles", "longitude": "-6.043701"},
			{"latitude": "51.92893", "affiliate_id": 1, "name": "Lance Keith", "longitude": "-10.27699"},
			{"latitude": "51.8856167", "affiliate_id": 2, "name": "Mohamed Bradshaw", "longitude": "-10.4240951"}]
		', true), 100);

        $this->assertEquals([
			['id'=>12, 'name'=>'Yosef Giles'],
		],
		$result
		);


		$result = (new AffiliatesController())->get_invitation_table(json_decode('
			[{"latitude": "52.986375", "affiliate_id": 12, "name": "Yosef Giles", "longitude": "-6.043701"},
			{"latitude": "51.92893", "affiliate_id": 1, "name": "Lance Keith", "longitude": "-10.27699"},
			{"latitude": "51.8856167", "affiliate_id": 2, "name": "Mohamed Bradshaw", "longitude": "-10.4240951"},
			{"latitude": "53.1229599", "affiliate_id": 6, "name": "Jez Greene", "longitude": "-6.2705202"}]
		', true), 100);

        $this->assertEquals([
			['id'=>6, 'name'=>'Jez Greene'],
			['id'=>12, 'name'=>'Yosef Giles']
		],
		$result
		);

		$result = (new AffiliatesController())->get_invitation_table(json_decode('
			[{"latitude": "52.986375", "affiliate_id": 12, "name": "Yosef Giles", "longitude": "-6.043701"},
			{"latitude": "51.92893", "affiliate_id": 1, "name": "Lance Keith", "longitude": "-10.27699"},
			{"latitude": "51.8856167", "affiliate_id": 2, "name": "Mohamed Bradshaw", "longitude": "-10.4240951"},
			{"latitude": "53.1229599", "affiliate_id": 6, "name": "Jez Greene", "longitude": "-6.2705202"},
			{"latitude": "53.1302756", "affiliate_id": 5, "name": "Sharna Marriott", "longitude": "-6.2397222"}]
		', true), 100);

        $this->assertEquals([
			['id'=>5, 'name'=>'Sharna Marriott'],
			['id'=>6, 'name'=>'Jez Greene'],
			['id'=>12, 'name'=>'Yosef Giles']
		],
		$result
		);

		$result = (new AffiliatesController())->get_invitation_table(json_decode('
			[{"latitude": "52.986375", "affiliate_id": 12, "name": "Yosef Giles", "longitude": "-6.043701"},
			{"latitude": "51.92893", "affiliate_id": 1, "name": "Lance Keith", "longitude": "-10.27699"},
			{"latitude": "51.8856167", "affiliate_id": 2, "name": "Mohamed Bradshaw", "longitude": "-10.4240951"},
			{"latitude": "53.2451022", "affiliate_id": 4, "name": "Inez Blair", "longitude": "-6.238335"},
			{"latitude": "53.1229599", "affiliate_id": 6, "name": "Jez Greene", "longitude": "-6.2705202"},
			{"latitude": "53.1302756", "affiliate_id": 5, "name": "Sharna Marriott", "longitude": "-6.2397222"}]
		', true), 10);

        $this->assertEquals([
			['id'=>4, 'name'=>'Inez Blair']
		],
		$result
		);





    }
}
