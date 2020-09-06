<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $data_siswa = \App\Siswa::all();
        if($request->has('cari')){
            $data_siswa = \App\Siswa::where('nama_depan','LIKE','%'.$request->cari.'%')->get();
        }else{
           $data_siswa = \App\Siswa::all();
        }
        //dd($request->all());
        
        //return view('siswa.index', ['data_siswa'=> $data_siswa]);
        //return 'ini siswa';
        return view('siswa.index', ['data_siswa'=> $data_siswa]);
    }
    public function create(Request $request)
    {
        \App\Siswa::create($request->all());
        return redirect('/siswa')->with('sukses', 'Data Berhasi Diinput');
    }
    public function edit($id)
    {
        $siswa = \App\Siswa::find($id);
        //dd($siswa);
        return view('siswa/edit',['siswa'=>$siswa]);
    }

    public function update(Request $request,$id)
    {
        //dd($request->all());
         $siswa = \App\Siswa::find($id);
         $siswa->update($request->all());
         return redirect('/siswa')->with('sukses','Data Berhasil diupdate');
    }
    public function delete($id)
    {
        $siswa = \App\Siswa::find($id);
        $siswa->delete($siswa);
        return redirect('/siswa')->with('sukses','Data Berhasil dihapus');
    }
}
