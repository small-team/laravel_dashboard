<?php namespace SmallTeam\Dashboard\App\Http\Controllers;

use Illuminate\Foundation\Bus\DispatchesCommands;
use Illuminate\Routing\Controller as BaseController;
use Illuminate\Foundation\Validation\ValidatesRequests;

abstract class Controller extends BaseController {

	use DispatchesCommands, ValidatesRequests;

	public function missingMethod($arr = []) {
		return view('dashboard::errors.404');
	}

}