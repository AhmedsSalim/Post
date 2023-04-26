<?php

namespace App\Http\Controllers\Dashboard;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;
use Illuminate\Validation\Rule;
use Intervention\Image\Facades\Image;

class UserController extends Controller
{

   public function __construct(){
    $this->middleware(['permission:read_users'])->only('index');
    $this->middleware(['permission:create_users'])->only('create');
    $this->middleware(['permission:update_users'])->only('edit');
    $this->middleware(['permission:delete_users'])->only('destory');


   }// end of construct

    public function index(Request $request)
    {

        // 1 search
        // if ($request->search) {

        //     $users= User::where('first_name' , 'like', '%' . $request->search . '%')->orWhere('last_name', 'like', '%' . $request->search . '%')->get();
        // }else{
        //     $users= User::whereRoleIs('admin')->get();
        // }


        $users= User::whereRoleIs('admin')->where(function($query) use ($request){
            return $query-> when($request->search,function($q) use ($request){
                return $q->where('first_name' , 'like', '%' . $request->search . '%')->orWhere('last_name', 'like', '%' . $request->search . '%');

              });

        })->latest()->paginate(4);

       return view('Dashboard.users.index',compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('Dashboard.users.create');
    }

    /**
     *
     *
     *  a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {

        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => 'required|unique:users',
            'image' => 'image',
            'password' => 'required|confirmed',
            'permissions' => 'required|min:1'
        ]);






                $request_data = $request->except(['password','password_confirmation','permissions','image']);

                $request_data['password'] = bcrypt($request->password);

                if($request->image){
                    Image::make($request->image)->resize(300, null, function ($constraint) {
                        $constraint->aspectRatio();
                    })->save(public_path('uploads/user_images/' . $request->image->hashName()));

                    $request_data['image'] = $request->image->hashName();
                }//end for if


                $user = User::create($request_data);
                $user->attachRole('admin');
                // $user->detachPermissions($request->permissions);
                $user->syncPermissions($request->permissions);



                    session()->flash('success', __('site.added_successfully'));
                    return redirect()->route('dashboard.users.index');




    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function show(User $user)
    {

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function edit(User $user)
    {
        return view('dashboard.users.edit',compact('user'));
    }//end of edit
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, User $user)
    {


        $request->validate([
            'first_name' => 'required',
            'last_name' => 'required',
            'email' => ['required', Rule::unique('users')->ignore($user->id),],
            'image' => 'image',
            'permissions' => 'required|min:1'
        ]);




        $request_data = $request->except(['permissions','image']);
        if($request->image){

            if($user->image != 'default.png'){
                Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
            }
            Image::make($request->image)->resize(300, null, function ($constraint) {
                $constraint->aspectRatio();
            })->save(public_path('uploads/user_images/' . $request->image->hashName()));
            $request_data['image'] = $request->image->hashName();

        }//end for if



        $user->update($request_data);

        $user->syncPermissions($request->permissions);



            session()->flash('success', __('site.updated_successfully'));
            return redirect()->route('dashboard.users.index');


    }//end of update

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\User  $user
     * @return \Illuminate\Http\Response
     */
    public function destroy(User $user)
    {
        if($user->image != 'default.png'){
            Storage::disk('public_uploads')->delete('/user_images/' . $user->image);
        }

        $user->delete();
        session()->flash('success', __('site.deleted_successfully'));
        return redirect()->route('dashboard.users.index');
    }
}
