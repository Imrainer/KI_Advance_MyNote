<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Auth;
use Illuminate\Support\Facades\Session;

class AdminControllers extends Controller
{     
// <!--CRUD N PAGE--!>

    public function index (request $request)
    {   $admin = session('id');
        $admin = Admin::where('id', $admin)->first();

        if($request->has('search')){
            $data = Admin::where('name', 'LIKE', '%' .$request->search.'%')->paginate(5);
        } else {
            $data = Admin::paginate(5);
        }

        return view('pages/Admin',compact(['admin','data']));
    }


    function createpage(){
    return view ('');
    }

    function create(request $request){
        
        $validator = $request->validate([
            'name' => 'required',
            'email'=> 'required',
            'phone' => 'required|unique:users,phone',
            'password'=>'required',
            ],[
            'name|required' => 'Nama Harus Diisi',
            'email.required'=> 'Email Harus Diiisi',
            'email.unique'=> 'Email sudah terdaftar, Silahkan gunakan Nomor Telepon lain',
            'phone.required' => 'Nomor Telepon Harus Diisi',
            'phone.unique'=> 'Nomer Telepon sudah terdaftar, Silahkan gunakan Nomor Telepon lain',
            'password.required'=>'Password Harus Diisi']);
        
        
            $data = [
                'nama'=>$request->name,
                'email'=>$request->email,
                'phone'=>$request->phone,
                'password'=>Hash::make($request->password),
            ];
            
            Admin::create($data);    
            return redirect('/dashboard');
    }

    function profileadminpage()
    {  $data = session('admin_id');
    
      $data = Admin::where('admin_id', $data)->first();
        return view('/pages/profil',compact(['data']));
    }
    
    function editprofileadmin(request $request) {
    
        $data = session('admin_id');
    
        $data = Admin::where('admin_id', $data)->first();
        $data->update([
            'name' =>($request->input('name')),
        ]);
    
        return redirect('/profil')->with('Profil berhasil diperbarui');
    }
    
    function editphotoprofil(request $request) {
    
        $data = session('admin_id');
    
        $data = Admin::where('admin_id', $data)->first();

        $data->update([
            'photo' => ($request->file('photo'))
        ]);
    
        if($request->file('photo')) {
            $data['photo'] =  $request->file('photo')->store('admin-photo_profil');
            Admin::where('admin_id',$data['admin_id'])->update(['photo'=>$data['photo']]);
        }
       
        return redirect('/profil')->with('Profil berhasil diperbarui');
    }

    function delete($id)
    {
        $data= Aadmin::find($id);
        $data->delete($id);
        return redirect('/dashboard');
    }
}
