<?php

namespace App\Http\Controllers\WEB;

use Illuminate\Http\Request;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Validator;
use Illuminate\Support\Facades\Hash;
use App\Models\User;
use App\Models\Admin;
use Illuminate\Support\Facades\Auth;

class UserControllers extends Controller
{
    public function index (request $request)
    {   $admin = session('id');
        $admin = Admin::where('id', $admin)->first();

        if($request->has('search')){
            $data = User::where('name', 'LIKE', '%' .$request->search.'%')->paginate(5);
        } else {
            $data = User::paginate(5);
        }

        return view('pages/Dashboard',compact(['admin','data']));
    }

function register(Request $request) {

    $validator = $request->validate([
    'nama' => 'required',
    'sekolah_id'=> 'required',
    'nomor_telepon' => 'required|unique:users,nomor_telepon',
    'password'=>'required',
    'foto' => 'nullable|image|file'
    ],[
    'nama|required' => 'Nama Harus Diisi',
    'sekolah_id.required'=> 'Nama Sekolah Harus Diiisi',
    'nomor_telepon.required' => 'Nomor Telepon Harus Diisi',
    'nomor_telepon.unique'=> 'Nomer Telepon sudah terdaftar, Silahkan gunakan Nomor Telepon lain',
    'password.required'=>'Password Harus Diisi']);


    $data = [
        'nama'=>$request->nama,
        'sekolah_id'=>$request->sekolah_id,
        'nomor_telepon'=>$request->nomor_telepon,
        'password'=>Hash::make($request->password),
      
    ];
    
    User::create($data);    
    return redirect('/dashboard');

}

function edituserpage($user_id)
    {   
        $data = User::find($user_id);
        $sekolah = Sekolah::all();
        return view('/pages/EditUser',compact(['data']),['sekolah'=>$sekolah]);
    }
    
    function edituser(request $request, $user_id) {
    
        $data = User::find($user_id);
        $data->update([
            'nama' =>($request->input('nama')),
            'sekolah_id' =>($request->input('sekolah_id')),
            'foto' => ($request->file('foto'))
        ]);
    
        if($request->file('foto')) {
            $data['foto'] =  $request->file('foto')->store('foto_profil');
            User::where('user_id',$data['user_id'])->update(['foto'=>$data['foto']]);
        }
       
        return redirect('/dashboard');
    }

    function delete($user_id)
    {
        $data= User::find($user_id);
        $data->delete($user_id);
        return redirect('/dashboard');
    }
}
