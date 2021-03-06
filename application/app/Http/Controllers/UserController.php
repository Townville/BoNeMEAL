<?php namespace App\Http\Controllers;

use App\Http\Requests;
use App\Http\Controllers\Controller;

use Illuminate\Http\Request;

class UserController extends Controller {

	public function __construct()
	{
		$this->middleware('installed');
		$this->middleware('auth');
		$this->middleware('admin');
	}

	/**
	 * Display a listing of the resource.
	 *
	 * @return Response
	 */
	public function index()
	{
		$users = \App\User::get();
		return view('admin.users.index', compact('users'));
	}

	/**
	 * Show the form for creating a new resource.
	 *
	 * @return Response
	 */
	public function create()
	{
		// To create a user, the user would signup through /auth/register.
		// That's why we will not need a form to create a user.
		abort(404);
	}

	/**
	 * Store a newly created resource in storage.
	 *
	 * @return Response
	 */
	public function store()
	{
		// Notice: See UserController@create()
		abort(404);
	}

	/**
	 * Display the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function show($id)
	{
		abort(404);
	}

	/**
	 * Show the form for editing the specified resource.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function edit($id)
	{
		$user = \App\User::findOrFail($id);
		if($user->role >= 8) {
			\Session::flash('message', trans('app.canNotEditSuperuser'));
			return \Redirect::to('/admin/users');
		}

		return view('admin.users.edit', compact('user'));
	}

	/**
	 * Update the specified resource in storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function update($id)
	{		
		\Validator::extend('role', function($attribute, $value, $parameters)
		{
			return ($value == 0 || $value == 3 || $value == 6) && $value < 8;
		});

		$rules = array(
			'name'       => 'required|min:3',
			'role'      => 'required|numeric|role',
			);
		
		$validator = \Validator::make(\Input::all(), $rules);

		if ($validator->fails()) {
			return redirect('/admin/users/'.$id.'/edit')
			->withErrors($validator);
		} else {
			$user = \App\User::findOrFail($id);

			if($user->role >= 8) {
				\Session::flash('message', trans('app.canNotEditSuperuser'));
				return \Redirect::to('/admin/users');
			}

			$user->name       = \Input::get('name');
			$user->role      = \Input::get('role');
			$user->save();

			\Session::flash('message', trans('app.updatedUser'));
			return \Redirect::to('/admin/users');
		}
	}

	/**
	 * Remove the specified resource from storage.
	 *
	 * @param  int  $id
	 * @return Response
	 */
	public function destroy($id)
	{
		$user = \App\User::findOrFail($id);
		$user->delete();

		\Session::flash('message', trans('app.removedUser'));
		return \Redirect::to('/admin/users');
	}

}
