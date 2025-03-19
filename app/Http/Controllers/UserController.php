<?php
namespace App\Http\Controllers;

use App\Models\LevelModel;
use App\Models\UserModel;
use Illuminate\Database\Eloquent\Relations\BelongsTo;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Yajra\DataTables\Facades\DataTables;

class UserController extends Controller
{
    public function index()
    {
        $breadcrumb = (object) [
            'title' => 'Daftar User',
            'list' => ['Home', 'User']
        ];

        $page = (object) [
            'title' => 'Daftar user yang terdaftar dalam sistem'
        ];

        $activeMenu = 'user'; // Menentukan menu aktif
        $level = LevelModel::all();

        return view('user.index', [
            'breadcrumb' => $breadcrumb,
            'page' => $page,
            'level' => $level,
            'activeMenu' => $activeMenu
        ]);
    }

    public function list(Request $request) 
    { 
        $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
        ->with('level'); 
        
        // Filter data user berdasarkan level_id
        if ($request->level_id) {
            $users->where('level_id', $request->level_id);
        }

        return DataTables::of($users) 
            // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex) 
            ->addIndexColumn()  
            ->addColumn('aksi', function ($user) {  // menambahkan kolom aksi 
                $btn  = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> '; 
                $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> '; 
                $btn .= '<form class="d-inline-block" method="POST" action="'. url('/user/'.$user->user_id).'">' 
                        . csrf_field() . method_field('DELETE') .  
                        '<button type="submit" class="btn btn-danger btn-sm" onclick="return 
    confirm(\'Apakah Anda yakit menghapus data ini?\');">Hapus</button></form>';      
                return $btn; 
            }) 
            ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html 
            ->make(true); 
    }   
    
// Menampilkan halaman formulir untuk menambahkan pengguna
public function create()
{
    // Membuat breadcrumb navigasi
    $breadcrumb = (object) [
        'title' => 'Tambah Pengguna',
        'list'  => ['Beranda', 'Pengguna', 'Tambah']
    ];

    // Informasi halaman
    $page = (object) [
        'title' => 'Tambah Pengguna Baru'
    ];

    $levels = LevelModel::all();// Mengambil semua data level untuk ditampilkan di formulir
    $activeMenu = 'user';// Menentukan menu yang sedang aktif
    

    // Mengembalikan tampilan dengan data yang dibutuhkan
    return view('user.create', ['breadcrumb' => $breadcrumb,'page'=> $page,'level'=> $levels,'activeMenu' => $activeMenu
    ]);
}
// Menyimpan data pengguna baru
public function store(Request $request)
{
    // Validasi input pengguna
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username', // Minimal 3 karakter, unik dalam tabel m_user
        'nama'     => 'required|string|max:100', // Maksimal 100 karakter
        'password' => 'required|min:5', // Minimal 5 karakter
        'level_id' => 'required|integer' // Harus berupa angka
    ]);

    // Menyimpan data ke dalam database
    UserModel::create([
        'username' => $request->username,
        'nama'     => $request->nama,
        'password' => bcrypt($request->password), // Mengenkripsi password sebelum disimpan
        'level_id' => $request->level_id
    ]);

    // Redirect ke halaman daftar pengguna dengan pesan sukses
    return redirect('/user')->with('success', 'Data pengguna berhasil disimpan');
}

// Menampilkan detail pengguna
public function show(string $id)
{
    // Mengambil data pengguna berdasarkan ID, termasuk relasi dengan level
    $user = UserModel::with('level')->find($id);

    // Jika pengguna tidak ditemukan, kembalikan ke halaman daftar pengguna dengan pesan error
    if ($user) {
        return redirect('/user')->with('error', 'Pengguna tidak ditemukan');
    }

    // Breadcrumb untuk navigasi halaman
    $breadcrumb = (object) [
        'title' => 'Detail Pengguna',
        'list'  => ['Beranda', 'Pengguna', 'Detail']
    ];

    // Informasi halaman
    $page = (object) [
        'title' => 'Detail Pengguna'
    ];

    // Menentukan menu yang sedang aktif
    $activeMenu = 'user';

    // Mengembalikan tampilan dengan data pengguna
    return view('user.show', [
        'breadcrumb' => $breadcrumb,'page' => $page,'user' => $user,'activeMenu' => $activeMenu
    ]);
}

// Menampilkan halaman formulir edit pengguna
public function edit(string $id)
{
    // Mengambil data pengguna berdasarkan ID
    $user = UserModel::find($id);
    // Mengambil semua data level untuk dropdown di formulir
    $levels = LevelModel::all();

    // Jika pengguna tidak ditemukan, redirect dengan pesan error
    // if ($user) {
    //     return redirect('/user')->with('error', 'Pengguna tidak ditemukan');
    // }

    // Breadcrumb untuk navigasi
    $breadcrumb = (object) [
        'title' => 'Edit Pengguna',
        'list'  => ['Beranda', 'Pengguna', 'Edit']
    ];

    // Informasi halaman
    $page = (object) [
        'title' => 'Edit Pengguna'
    ];

    // Menentukan menu yang sedang aktif
    $activeMenu = 'user';

    // Mengembalikan tampilan edit dengan data pengguna
    return view('user.edit', [
        'breadcrumb' => $breadcrumb,
        'page'       => $page,
        'user'       => $user,
        'level'     => $levels,
        'activeMenu' => $activeMenu
    ]);
}

// Menyimpan perubahan data pengguna
public function update(Request $request, string $id)
{
    // Validasi data input pengguna
    $request->validate([
        'username' => 'required|string|min:3|unique:m_user,username,' . $id . ',user_id', // Username unik kecuali untuk pengguna yang sedang diedit
        'nama'     => 'required|string|max:100', // Maksimal 100 karakter
        'password' => 'nullable|min:5', // Bisa kosong, jika diisi minimal 5 karakter
        'level_id' => 'required|integer' // Harus berupa angka
    ]);

    // Mencari pengguna berdasarkan ID
    $user = UserModel::find($id);

    // Jika pengguna tidak ditemukan, redirect dengan pesan error
    // if ($user) {
    //     return redirect('/user')->with('error', 'Pengguna tidak ditemukan');
    // }

    // Perbarui data pengguna
    $user->update([
        'username' => $request->username,
        'nama'     => $request->nama,
        'password' => $request->password ? bcrypt($request->password) : $user->password, // Enkripsi password jika diisi
        'level_id' => $request->level_id
    ]);

    // Redirect ke daftar pengguna dengan pesan sukses
    return redirect('/user')->with('success', 'Data pengguna berhasil diperbarui');
}

// Menghapus data user
public function destroy(string $id)
{
    $check = UserModel::find($id);
    if (!$check) { // untuk mengecek apakah data user dengan id yang dimaksud ada atau tidak
        return redirect('/user')->with('error', 'Data user tidak ditemukan');
    }

    try {
        UserModel::destroy($id); // Hapus data level

        return redirect('/user')->with('success', 'Data user berhasil dihapus');
    } catch (\Illuminate\Database\QueryException $e) {

        // Jika terjadi error ketika menghapus data, redirect kembali ke halaman dengan membawa pesan error
        return redirect('/user')->with('error', 'Data user gagal dihapus karena masih terdapat tabel lain yang terkait dengan data ini');
    }
}






    }
// namespace App\Http\Controllers;

// use App\Models\LevelModel;
// use App\Models\UserModel;
// use Illuminate\Http\Request;
// use Yajra\DataTables\Facades\DataTables;

// class UserController extends Controller
// {
//     // Menampilkan halaman awal user
//     public function index()
//     {
//         $breadcrumb = (object) [
//             'title' => 'Daftar User',
//             'list' => ['Home', 'User']
//         ];

//         $page = (object) [
//             'title' => 'Daftar user yang terdaftar dalam sistem'
//         ];

//         $activeMenu = 'user'; // Menentukan menu aktif

//         return view('user.index', [
//             'breadcrumb' => $breadcrumb,
//             'page' => $page,
//             'activeMenu' => $activeMenu
//         ]);
//     }


//     public function list(Request $request)
// {
//     $users = UserModel::select('user_id', 'username', 'nama', 'level_id')
//         ->with('level');

//     return DataTables::of($users)
//         // menambahkan kolom index / no urut (default nama kolom: DT_RowIndex)
//         ->addIndexColumn()
//         ->addColumn('aksi', function ($user) { // menambahkan kolom aksi
//             $btn = '<a href="'.url('/user/' . $user->user_id).'" class="btn btn-info btn-sm">Detail</a> ';
//             $btn .= '<a href="'.url('/user/' . $user->user_id . '/edit').'" class="btn btn-warning btn-sm">Edit</a> ';
//             $btn .= '<form class="d-inline-block" method="POST" action="'.url('/user/' . $user->user_id).'">'
//                 . csrf_field() . method_field('DELETE')
//                 . '<button type="submit" class="btn btn-danger btn-sm" onclick="return confirm(\'Apakah Anda yakin menghapus data ini?\')">Hapus</button></form>';
//             return $btn;
//         })
//         ->rawColumns(['aksi']) // memberitahu bahwa kolom aksi adalah html
//         ->make(true);
// }

// }




    //     public function index(){

    //         $user = UserModel::with('level')->get();
    //         return view('user', ['data' => $user]); 

    //         // $user = UserModel::with('level')->get();
    //         // dd($user);

    //         // $user = UserModel::all(); 
    //         // return view('user', ['data' => $user]);
    //     }

    //     public function tambah() {
    //         return view ('tambah_user') ;

    //     }

    //     public function tambah_simpan(Request $request) {
    //     UserModel::create([
    //         'username' => $request->username,
    //         'nama' => $request->nama,
    //         'password' => Hash::make($request->password),
    //         'level_id' => $request->level_id
    //     ]);

    //     return redirect('/user');
    //     }

    //         public function ubah($id){
    //         $user = UserModel::find($id);
    //         return view('user_ubah', ['data' => $user]);
    //     }

    //         public function ubah_simpan($id, Request $request){
    //         $user = UserModel::find($id);

    //         $user->username = $request->username;
    //         $user->nama = $request->nama;
    //         $user->password = Hash::make($request->password);
    //         $user->level_id = $request->level_id;

    //         $user->save();

    //         return redirect('/user');
    //     }

    //         public function hapus($id){
    //             $user = UserModel::find($id);
    //             $user->delete();

    //             return redirect('/user');


        






            
        

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
