<?php

namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){

        $user = UserModel::with('level')->get();
        return view('user', ['data' => $user]); 
        
        // $user = UserModel::with('level')->get();
        // dd($user);

        // $user = UserModel::all(); 
        // return view('user', ['data' => $user]);
    }

    public function tambah() {
        return view ('tambah_user') ;

    }

    public function tambah_simpan(Request $request) {
    UserModel::create([
        'username' => $request->username,
        'nama' => $request->nama,
        'password' => Hash::make($request->password),
        'level_id' => $request->level_id
    ]);

    return redirect('/user');
    }

        public function ubah($id){
        $user = UserModel::find($id);
        return view('user_ubah', ['data' => $user]);
    }
    
        public function ubah_simpan($id, Request $request){
        $user = UserModel::find($id);

        $user->username = $request->username;
        $user->nama = $request->nama;
        $user->password = Hash::make($request->password);
        $user->level_id = $request->level_id;

        $user->save();

        return redirect('/user');
    }

        public function hapus($id){
            $user = UserModel::find($id);
            $user->delete();

            return redirect('/user');
        }

        






            
        

        // $user = UserModel::create([
        //     'username' => 'manager13',
        //     'nama' => 'Manager13',
        //     'password' => Hash::make('12345'),
        //     'level_id' => 2,
        // ]);

        // $user->username = 'manager13';

        // $user->save();

        // $user->wasChanged(); // true
        // $user->wasChanged('username'); // true
        // $user->wasChanged(['username', 'level_id']); // true
        // $user->wasChanged('nama'); // false
        // $user->wasChanged(['nama', 'username']); // true

        // $user = UserModel::firstOrCreate(
        //     [
        //         'username' => 'manager34',
        //         'nama' => 'Manager Tiga Empat',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ],
        // );
        // $user->save();
        
        // return view('user', ['data' => $user]);

        // $user = UserModel::findOr(20, ['username', 'nama'], function () {
        //     abort(404);
        // });
        // $user = UserModel::where('username', 'manager9')->findOrFail();
        // return view('user', ['data' => $user]);
        
        
        // $user = UserModel::firstWhere('level_id', 1);

        // return view('user', ['data' => $user]);
        // $data = [
        //     'level_id' => 2,
        //     'username' => 'manager_tiga',
        //     'nama' => 'Manager 3',
        //     'password' => Hash::make('12345')
        // ];
        // UserModel::create($data);

        // $user = UserModel::find(1); // ambil semua data dari tabel m_user
        //         return view('user', ['data' => $user]);

        // $user = UserModel::firstWhere('level_id',1)->first(); // ambil semua data dari tabel m_user
        // return view('user', ['data' => $user]);
                
        // // coba akses model UserModel
        // $user = UserModel::all(); // ambil semua data dari tabel m_user
        // return view('user', ['data' => $user]);


        // // tambah data user dengan Eloquent Model
        //     $data = [
        //         'username' => 'customer-1',
        //         'nama' => 'Pelanggan',
        //         'password' => Hash::make('12345'),
        //         'level_id' => 2
        //     ];
        //     UserModel::insert($data); // tambahkan data ke tabel m_user

        //     // coba akses model UserModel
        //     $user = UserModel::all(); // ambil semua data dari tabel m_user
        //     return view('user', ['data' => $user]);

        // tambah data user dengan Eloquent Model
                // $data = [
                //     'nama' => 'Pelanggan Pertama',
                // ];
                // UserModel::where('username', 'customer-1')->update($data); // update data user

                // coba akses model UserModel

    }

