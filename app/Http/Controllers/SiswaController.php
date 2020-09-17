<?php

namespace App\Http\Controllers;
use App\Siswa;
use App\Imports\Siswaimport;
use Illuminate\Http\Request;
use App\Exports\SiswaExport;
use Maatwebsite\Excel\Facades\Excel;
use PDF;

class SiswaController extends Controller
{
    public function index(Request $request)
    {
        $data_siswa = \App\Siswa::all();
        if($request->has('cari')){
            $data_siswa = \App\Siswa::where('nama_depan','LIKE','%'.$request->cari.'%')->paginate(20);
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
        $this->validate($request,[
            'nama_depan' =>'required|min:5',
            'nama_belakang' =>'required',
            'email' =>'required|email|unique:users',
            'jenis_kelamin' =>'required',
            'agama' => 'required',
            'avatar' => 'mimes:jpg,png',
        ]);
        //insert ke tabeel user
        $user = new \App\User;
        $user->role = 'siswa';
        $user->name = $request->nama_depan;
        $user->email = $request->email;
        $user->password = bcrypt('rahasia');
        $user->remember_token = str_random(60);
        $user->save();
        
        //insert ke table  siswa
        $request->request->add(['user_id' =>$user->id ]);
        $siswa = \App\Siswa::create($request->all());
        if($request->hasFile('avatar')){
            $request->file('avatar')->move('images/',$request->file('avatar')->getClientOriginalName());
            $siswa->avatar = $request->file('avatar')->getClientOriginalName();
	        $siswa->save();
         }
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
         if($request->hasFile('avatar')){
            $request->file('avatar')->move('images/',$request->file('avatar')->getClientOriginalName());
            $siswa->avatar = $request->file('avatar')->getClientOriginalName();
	        $siswa->save();
         }
         return redirect('/siswa')->with('sukses','Data Berhasil diupdate');
    }
    public function delete($id)
    {
        $siswa = \App\Siswa::find($id);
        $siswa->delete($siswa);
        return redirect('/siswa')->with('sukses','Data Berhasil dihapus');
    }
    public function profile($id)
    {
        $siswa = \App\Siswa::find($id);
        $matapelajaran = \App\Mapel::all();
        // Menyiapkan data untuk chart
        $categories = [];
        $data = [];
        foreach($matapelajaran as $mp){
            if($siswa->mapel()->wherePivot('mapel_id',$mp->id)->first()){
            $categories[] =$mp->nama;
            $data[] =$siswa->mapel()->wherePivot('mapel_id',$mp->id)->first()->pivot->nilai;
            }
        }
        return view('/siswa.profile',['siswa' =>$siswa, 'matapelajaran' =>$matapelajaran, 'categories' =>$categories, 'data' =>$data]);
    }
    public function addnilai(Request $request, $idsiswa)
    {
        //dd($request->all());
        $siswa = \App\Siswa::find($idsiswa);
        if($siswa->mapel()->where('mapel_id', $request->mapel)->exists()){
            return redirect('siswa/'.$idsiswa.'/profile')->with('error','Data nilai Sudah ada');
        }
        $siswa->mapel()->attach($request->mapel,['nilai' => $request->nilai]);
        
        return redirect('siswa/'.$idsiswa.'/profile')->with('sukses','Data nilai berhasil diinputkan');
    }
    public function deletenillai($idsiswa,$idmapel)
    {
        //dd($request->all());
        $siswa = \App\Siswa::find($idsiswa);
        $siswa->mapel()->detach($idmapel);
        return redirect()->back()->with('sukses','Data nilai telah dihapus');
    }
    public function exportExcel() 
    {
        return Excel::download(new SiswaExport, 'siswa.xlsx');
    }
    public function exportPdf() 
    {
        //$pdf = PDF::loadHTML('<h1>Data Siswa</h1>');
        $siswa =Siswa::all();
        $pdf = PDF::loadView('export.siswapdf', ['siswa' =>$siswa]);
        return $pdf->download('siswa.pdf');
    }

    public function getdatasiswa() 
    {
        $siswa = Siswa::select('siswa.*');
        return \DataTables::eloquent($siswa)
        ->addColumn('nama_lengkap',function($s){
            return $s->nama_depan.' '.$s->nama_belakang;
        })
        ->addColumn('rata2_nilai',function($s){
            return $s->rataRataNilai();
        })
        ->addColumn('aksi',function($s){
            return '<a href="/siswa/'.$s->id.'/profile/" class="btn btn-warning">Profil</a>';
        })
        ->rawColumns(['rata2_nilai','aksi','nama_lengkap'])
        ->toJson();
    }
    public function profilsaya()
    {
    //$siswa = auth()->user()->siswa;
    return view('siswa.profilsaya');
    
    //$siswa = auth()->user()->siswa;
	//return view('siswa.profilsaya',compact(['siswa']));
    }
    
    public function importexcel(Request $request)
    {
        Excel::import(new \App\Imports\Siswaimport,$request->file('data_siswa'));
    }

}
