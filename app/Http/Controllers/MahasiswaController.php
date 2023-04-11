<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Mahasiswa;
use Illuminate\Support\Facades\Auth;
use App\Models\Kelas;

class MahasiswaController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $user = Auth::user();
        $mahasiswas = Mahasiswa::paginate(5);
        // $posts = Mahasiswa::orderBy('nim', 'desc')->paginate(6);
        return view('mahasiswas.index', compact('mahasiswas','user'))
        ->with('i', (request()->input('page', 1) - 1) *5);
    }

    // public function index(Request $request){
    //     $mahasiswas = Mahasiswa::where([
    //         ['Nama', '!=', null],
    //         [function ($query) use ($request) {
    //             if (($search = $request->search)) {
    //                 $query->orWhere('Nama', 'LIKE', '%' . $search . '%')
    //                 ->get();
    //             }
    //         }]
    //     ])->paginate(5);
    //     $posts = Mahasiswa::orderBy('Nim', 'desc');
    //     return view('mahasiswas.index', compact('mahasiswas'));
    // }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $kelas = Kelas::all();
        return view('mahasiswas.create',['kelas' => $kelas]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'tgl_lahir' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'email' => 'required',
            'no_hp' => 'required',
        ]);

        // Mahasiswa::create($request->all());

        $mahasiswas = new Mahasiswa;
        $mahasiswas ->nim = $request->get('nim');
        $mahasiswas ->nama = $request->get('nama');
        $mahasiswas ->tgl_lahir = $request->get('tgl_lahir');
        $mahasiswas ->jurusan = $request->get('jurusan');
        $mahasiswas ->email = $request->get('email');
        $mahasiswas ->no_hp = $request->get('no_hp');

        $kelas = new Kelas;
        $kelas->id = $request->get('kelas');

        $mahasiswas->kelas()->associate($kelas);
        $mahasiswas->save();

        return redirect()->route('mahasiswas.index')
        ->with('success', 'Mahasiswa Berhasil Ditambahkan');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($nim)
    {
        $Mahasiswa = Mahasiswa::find($nim);
        return view('mahasiswas.detail', compact('Mahasiswa'));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($nim)
    {
        $kelas = Kelas::all();
        $Mahasiswa = Mahasiswa::find($nim);
        return view('mahasiswas.edit', compact('Mahasiswa','kelas'));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $nim)
    {
        $request->validate([
            'nim' => 'required',
            'nama' => 'required',
            'tgl_lahir' => 'required',
            'kelas' => 'required',
            'jurusan' => 'required',
            'email' => 'required',
            'no_hp' => 'required',
        ]);

        // Mahasiswa::find($nim)->update($request->all());
        $mahasiswas = Mahasiswa::with('kelas')->where('nim', $nim)->first();
        $mahasiswas->nim = $request->get('nim');
        $mahasiswas->nama = $request->get('nama');
        $mahasiswas->tgl_lahir = $request->get('tgl_lahir');
        $mahasiswas->jurusan = $request->get('jurusan');
        $mahasiswas->email = $request->get('email');
        $mahasiswas->no_hp = $request->get('no_hp');

        $kelas = new Kelas;
        $kelas->id = $request->get('kelas');

        $mahasiswas->kelas()->associate($kelas);
        $mahasiswas->save();

        return redirect()->route('mahasiswas.index')
        ->with('success', 'Mahasiswa Berhasil Diupdate');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($nim)
    {
        Mahasiswa::find($nim)->delete();

        return redirect()->route('mahasiswas.index')
        ->with('success', 'Mahasiswa Berhasil Dihapus');
    }
}
