<?php

namespace App\Http\Controllers\Api\Data;

use App\Http\Controllers\Controller;
use App\Models\Data\Opd;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Validator;

class ApiUsersController extends Controller
{
    public function get_data_user(Request $request)
    {
        $users = [];

        if ($request->has('id') && $request->id) {
            $users = User::where('id', $request->id)->get();
        } elseif ($request->has('username') && $request->username) {
            $users = User::where('username', $request->username)->get();
        } elseif ($request->has('email') && $request->email) {
            $users = User::where('email', $request->email)->get();
        } else {
            $users = User::get();
        }

        $data = [];

        foreach ($users as $user) {
            $data[] = [
                'id' => $user->id,
                'name' => $user->name,
                'username' => $user->username,
                'email' => $user->email,
                'phone' => $user->phone,
                'roles' => $user->getRoleNames()
            ];
        }

        if (!empty($data)) {
            return response()->json([
                'success' => true,
                'alert' => 'success',
                'data' => $data,
            ], 200);
        }

        return response()->json([
            'success' => false,
            'alert' => 'danger',
            'message' => 'Data Not Found!',
        ], 404);
    }

    public function create_data_user(Request $request)
    {
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'username' => 'required|min:4|max:12|unique:users,username',
                'email' => [
                    'nullable',
                    'email',
                    'unique:users,email'
                ],
                'phone' => [
                    'nullable',
                    'unique:users,phone',
                    'regex:/^(\+62|62|0)8[0-9]*$/',
                    'min:9',
                    'max:15'
                ],
                'role' => 'required|in:admin,user'
            ],
            [
                'name.required' => 'Nama tidak boleh kosong!',
                'name.max' => 'Panjang maksimal nama adalah 255 karakter!',
                'username.required' => 'Username tidak boleh kosong!',
                'username.min' => 'Panjang minimal username adalah 4 karakter!',
                'username.max' => 'Panjang maksimal username adalah 12 karakter!',
                'username.unique' => 'Username sudah digunakan!',
                'email.email' => 'Format email tidak sesuai! Contoh yang benar: example@email.com',
                'email.unique' => 'Email sudah digunakan!',
                'phone.unique' => 'Nomor telepon sudah digunakan!',
                'phone.regex' => 'Format salah! Format yang benar diawali dengan 0, 62, atau +62 diikuti angka.',
                'phone.min' => 'Panjang nomor minimal 9 angka!',
                'phone.max' => 'Panjang nomor maksimal 15 angka!',
                'role.required' => 'Role user tidak boleh kosong!',
                'role.in' => 'Role user hanya boleh admin atau user!'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            DB::beginTransaction();
            $user = User::create([
                'name' => $request->name,
                'username' => $request->username,
                'email' => $request->email,
                'phone' => $request->phone,
                'password' => Hash::make($request->username . '123')
            ]);

            if ($user) {
                $user->assignRole($request->role);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil tersimpan!',
                    'alert' => 'success',
                    'data' => [
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'roles' => $user->getRoleNames()
                    ]
                ], 200);
            }
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
            ], 500);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function update_data_user(Request $request)
    {
        // return $request->all();
        $validator = Validator::make(
            $request->all(),
            [
                'name' => 'required|max:255',
                'username' => 'required|min:4|max:12|unique:users,username,' . $request->id,
                'email' => [
                    'nullable',
                    'email',
                    'unique:users,email,' . $request->id,
                ],
                'phone' => [
                    'nullable',
                    'unique:users,phone,' . $request->id,
                    'regex:/^(\+62|62|0)8[0-9]*$/',
                    'min:9',
                    'max:15'
                ],
                'role' => 'required|in:admin,user'
            ],
            [
                'name.required' => 'Nama tidak boleh kosong!',
                'name.max' => 'Panjang maksimal nama adalah 255 karakter!',
                'username.required' => 'Username tidak boleh kosong!',
                'username.min' => 'Panjang minimal username adalah 4 karakter!',
                'username.max' => 'Panjang maksimal username adalah 12 karakter!',
                'username.unique' => 'Username sudah digunakan!',
                'email.email' => 'Format email tidak sesuai! Contoh yang benar: example@email.com',
                'email.unique' => 'Email sudah digunakan!',
                'phone.unique' => 'Nomor telepon sudah digunakan!',
                'phone.regex' => 'Format salah! Format yang benar diawali dengan 0, 62, atau +62 diikuti angka.',
                'phone.min' => 'Panjang nomor minimal 9 angka!',
                'phone.max' => 'Panjang nomor maksimal 15 angka!',
                'role.required' => 'Role user tidak boleh kosong!',
                'role.in' => 'Role user hanya boleh admin atau user!'
            ]
        );

        if ($validator->fails()) {
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $validator->errors()
            ], 400);
        }

        try {
            DB::beginTransaction();
            $user = User::find($request->id);

            if ($user) {
                $user->name = $request->name;
                $user->username = $request->username;
                $user->email = $request->email;
                $user->phone = $request->phone;
                $user->save();
                $user->syncRoles($request->role);
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Data berhasil tersimpan!',
                    'alert' => 'success',
                ], 200);
            }
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
            ], 500);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. data gagal di simpan!',
                'alert' => 'danger',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function reset_password_user(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return response()->json([
                'success' => false,
                'message' => 'Some paramter\'s id missing!',
                'alert' => 'danger',
            ], 400);
        }
        try {
            DB::beginTransaction();
            $user = User::find($request->id);
            if ($user) {
                $user->password = Hash::make('password');
                $user->save();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'Password berhasil di reset!',
                    'alert' => 'success',
                    'data' => [
                        'name' => $user->name,
                        'username' => $user->username,
                        'email' => $user->email,
                        'phone' => $user->phone,
                        'roles' => $user->getRoleNames()
                    ]
                ], 200);
            }
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. password gagal di reset!',
                'alert' => 'danger',
            ], 500);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. password gagal di reset!',
                'alert' => 'danger',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function lock_user(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return response()->json([
                'success' => false,
                'message' => 'Some paramter\'s id missing!',
                'alert' => 'danger',
            ], 400);
        }
        try {
            DB::beginTransaction();
            $user = User::find($request->id);
            if ($user) {
                $user->delete();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'User berhasil di di kunci!',
                    'alert' => 'success',
                ], 200);
            }
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. gagal mengunci user!',
                'alert' => 'danger',
            ], 500);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. gagal mengunci user!',
                'alert' => 'danger',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function unlock_user(Request $request)
    {
        if (!$request->has('id') || !$request->id) {
            return response()->json([
                'success' => false,
                'message' => 'Some paramter\'s id missing!',
                'alert' => 'danger',
            ], 400);
        }
        try {
            DB::beginTransaction();
            $user = User::onlyTrashed()->find($request->id);
            if ($user) {
                $user->restore();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'message' => 'User telah diaktifkan kembali!',
                    'alert' => 'success',
                ], 200);
            }
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. gagal mengaktifkan user!',
                'alert' => 'danger',
            ], 500);
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'message' => 'Terjadi kesalahan. gagal mengaktifkan user!',
                'alert' => 'danger',
                'errors' => $th->getMessage()
            ], 500);
        }
    }

    public function skpd_user(Request $request)
    {
        // Validasi awal untuk memastikan ID pengguna ada
        if (!$request->has('id') || !$request->id) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Some parameter(s) is missing!',
            ], 400);
        }

        // Cari pengguna berdasarkan ID
        $user = User::find($request->id);
        if (!$user) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'User not found!',
            ], 400);
        }

        // Jika hanya data pengguna yang diminta
        if ($request->has('only_user') && $request->only_user) {
            $opds = $user->opds->map(function ($itemOpd) {
                return [
                    'id' => $itemOpd->id,
                    'kode_unik_opd' => $itemOpd->kode_unik_opd,
                    'kode_opd' => $itemOpd->kode_opd,
                    'nama_opd' => $itemOpd->nama_opd,
                    'tahun' => $itemOpd->tahun,
                ];
            });

            return response()->json([
                'success' => true,
                'data' => [
                    'id' => $user->id,
                    'name' => $user->name,
                    'username' => $user->username,
                    'opds' => $opds,
                ],
            ], 200);
        }

        // Data OPD berdasarkan tahun yang tidak terkait dengan pengguna
        if (!$request->has('tahun') || !$request->tahun) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Parameter "tahun" is required!',
            ], 400);
        }

        $tagging = DB::table('opd_user')->where('user_id', $user->id)->get();
        $opds = Opd::where('tahun', $request->tahun)->whereNotIn('id', $tagging->pluck('opd_id')->toArray())->get();

        return response()->json([
            'success' => true,
            'data' => $opds,
        ], 200);
    }


    public function tagging_skpd_user(Request $request)
    {
        if (!$request->has('user_id') || !$request->has('opd_id') || !$request->user_id || !$request->opd_id) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Some parameter\'s is missing!',
            ], 400);
        }

        $exists = DB::table('opd_user')->where([
            'user_id' => $request->user_id,
            'opd_id' => $request->opd_id,
        ])->exists();

        if ($exists) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'SKPD User sudah ada!',
            ], 422);
        }

        try {
            DB::beginTransaction();
            // Melakukan insert data ke tabel opd_user
            $inserted = DB::table('opd_user')->insert([
                'user_id' => $request->user_id,
                'opd_id' => $request->opd_id,
            ]);

            // Jika insert berhasil, commit transaksi
            if ($inserted) {
                DB::commit();
                return response()->json([
                    'success' => true,
                    'alert' => 'success',
                    'message' => 'Data berhasil disimpan',
                ], 200);
            } else {
                // Jika insert gagal, rollback transaksi
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'alert' => 'danger',
                    'message' => 'Data gagal disimpan',
                ], 422);
            }
        } catch (\Throwable $th) {
            DB::rollback();
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage(),
            ], 500);  // Status code 500 untuk server error
        }
    }

    public function remove_skpd_user(Request $request)
    {
        // return $request->all();
        if (!$request->has('user_id') || !$request->has('opd_id') || !$request->user_id || !$request->opd_id) {
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Some parameter\'s is missing!',
            ], 400);
        }

        try {
            DB::beginTransaction();

            // Mengecek apakah data dengan kondisi user_id dan opd_id ada
            $data = DB::table('opd_user')->where([
                'user_id' => $request->user_id,
                'opd_id' => $request->opd_id,
            ]);

            // Jika data ada, lakukan penghapusan
            if ($data->exists()) {
                $data->delete();
                DB::commit();
                return response()->json([
                    'success' => true,
                    'alert' => 'success',
                    'message' => 'Data berhasil dihapus!',
                ], 200);
            } else {
                // Jika data tidak ditemukan, rollback transaksi
                DB::rollback();
                return response()->json([
                    'success' => false,
                    'alert' => 'danger',
                    'message' => 'Data tidak ditemukan untuk dihapus!',
                ], 422);
            }
        } catch (\Throwable $th) {
            // Rollback transaksi jika terjadi error
            DB::rollback();

            // Menyertakan pesan error untuk debugging
            return response()->json([
                'success' => false,
                'alert' => 'danger',
                'message' => 'Terjadi kesalahan: ' . $th->getMessage(),
            ], 500);
        }
    }
}
