<?php

namespace App\Http\Controllers;

use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Carbon;


class HomeController extends Controller
{
    /**
     * Create a new controller instance.
     *
     * @return void
     */
    public function __construct()
    {
        $this->middleware('auth');
    }

    /**
     * Show the application dashboard.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {


        return view('home');
    }


    public function users(){
        $user =  User::paginate(10);
        $user->created_at = Carbon::now();

        return view('users',compact('user'));
    }

    public function newUser(Request $request){
       $data = [
         'name' => $request['name'],
         'email' => $request['email'],
         'password' => bcrypt($request['password'])
       ];
       return User::create($data);
       return event(new BuildingMenu($user));
    }

    public function getUser(Request $request, $id){
        $user = User::find($id);
        return $user;
    }

    public function editUser(Request $request, $id){
        $data = [
            'name' => $request['name'],
            'email' => $request['email'],
            'password' => bcrypt($request['password'])
        ];
        return  User::where('id',$id)->update($data);
    }

    public function deleteUser(Request $request,$id){
        return User::destroy($id);
    }

}
