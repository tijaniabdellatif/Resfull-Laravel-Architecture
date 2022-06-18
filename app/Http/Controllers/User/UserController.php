<?php

namespace App\Http\Controllers\User;

use App\Http\Controllers\ApiController;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends ApiController
{


    protected $user;

    public function __construct(User $user)
    {
        $this->user = $user;
    }
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     *
     */


    public function index()
    {

         $users = $this->user->all();
        return $this->showAll($users);

    }



    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
          $rules = [
             'name' => "required",
             'email' => 'required|email|unique:users',
             'password' => 'required|min:6|confirmed',

          ];

         $this->validate($request,$rules);




          $data = $request->all();

          $data['password'] = Hash::make($request->password);
          $data['verified'] = User::UNVERIFIED_USER;
          $data['verif_token'] = User::generateVerifCode();
          $data['admin']=User::REGULAR_USER;
          $addedUser = $this->user->create($data);


          return $this->showOne($addedUser,201);
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

       return $this->showOne($user);


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {


        $rules = [

            'email' => 'email|unique:users,email,'.$user->id,
            'password' => 'min:6|confirmed',
             "admin" => 'in:'.User::ADMIN_USER.','.User::REGULAR_USER,

         ];

         $this->validate($request,$rules);

         if($request->has('name')){

            $user->name =$request->name;
         }

         if($request->has('email') && $user->email !== $request->email){

            $user->verified = User::UNVERIFIED_USER;
            $user->verif_token = User::generateVerifCode();
            $user->email = $request->email;


         }

         if($request->has('password')){

              $user->password = Hash::make($request->password);
         }

         if($request->has('admin') && !$user->isVerified()){

            return $this->errorResponse('Only verified users can modify the admin field',409);

          }


          if(!$user->isDirty()){

            return $this->errorResponse('You need to specify different values',422);

          }



          if(!$user->save()){

            return $this->errorResponse('General Error something were wrong',500);
          }else {

           return $this->showOne($user);
          }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {

       $user->delete();

       return $this->showOne($user);


    }
}
