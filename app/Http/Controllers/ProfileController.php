<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class ProfileController extends Controller
{

    // instagram profile
    // GET /instagram/{username}
	public function instagramProfile( $user ) {

		$json     = APIController::fetchProfile( $user, 'instagram' );

		if( isset( $json->error ))
			return view( 'search.error', [ 'message' => $json->error ] );

		// reverse table stats
		$tableStats = collect($json->json->tableStats);

		$json->json->tableStats = $tableStats->sortByDesc( 'date' )->toArray();

		return view( 'profiles/instagram', [ 'profile' => $json, 'activeNav' => 'instagram' ] );

	}

	// facebook profile
	// GET /facebook/{username}
	public function facebookProfile( $user ) {

		$json     = APIController::fetchProfile( $user, 'facebook' );

		if( isset( $json->error ))
			return view( 'search.error', [ 'message' => $json->error ] );

		// reverse table stats
		$tableStats = collect($json->json->tableStats);

		$json->json->tableStats = $tableStats->sortByDesc( 'date' )->toArray();

		return view( 'profiles/facebook', [ 'profile' => $json, 'activeNav' => 'facebook' ] );

	}

	// twitter profile
	// GET /twitter/{username}
	public function twitterProfile( $user ) {

		$json     = APIController::fetchProfile( $user, 'twitter' );

		if( isset( $json->error ))
			return view( 'search.error', [ 'message' => $json->error ] );

		// reverse table stats
		$tableStats = collect($json->json->tableStats);

		$json->json->tableStats = $tableStats->sortByDesc( 'date' )->toArray();

		return view( 'profiles/twitter', [ 'profile' => $json, 'activeNav' => 'twitter' ] );

	}


}
