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

         $users = $this->users->all();
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
    public function show($id)
    {


        $aUser = $this->user->findOrFail($id);

       return $this->showOne($aUser);


    }


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        $aUser = User::findOrFail($id);

        $rules = [

            'email' => 'email|unique:users,email,'.$aUser->id,
            'password' => 'min:6|confirmed',
            "admin" => 'in:'.User::ADMIN_USER.','.User::REGULAR_USER,

         ];

         $this->validate($request,$rules);

         if($request->has('name')){

            $aUser->name =$request->name;
         }

         if($request->has('email') && $aUser->email !== $request->email){

            $aUser->verified = User::UNVERIFIED_USER;
            $aUser->verif_token = User::generateVerifCode();
            $aUser->email = $request->email;


         }

         if($request->has('password')){

              $aUser->password = Hash::make($request->password);
         }

         if($request->has('admin') && !$aUser->isVerified()){

            return $this->errorResponse('Only verified users can modify the admin field',409);

          }


          if(!$aUser->isDirty()){

            return $this->errorResponse('You need to specify different values',422);

          }



          if(!$aUser->save()){

            return $this->errorResponse('General Error something were wrong',500);
          }else {

           return $this->showOne($aUser);
          }



    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        $aUser = User::findOrFail($id);



       if(!$aUser->delete()){
        return response()->json(
            [
                'error' => 'General Error something were wrong',
                'status' => 500
            ]

            ,500);

       }else {

        return response()->json([

               'data' => $aUser
        ],200);
      }



    }
}
