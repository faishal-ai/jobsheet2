<?php

namespace App\Http\Controllers;

use App\Models\UserModel;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class UserController extends Controller
{
    public function index(){
        $user = UserModel::where('level_id', 2)->count();
        return view('user', ['data' => $user]);

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
}
