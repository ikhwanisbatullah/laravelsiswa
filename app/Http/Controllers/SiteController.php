<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Mail\NotifPendaftaranSiswa;
use App\Post;

class SiteController extends Controller
{
    public function home()
    {
        $post =Post::all();
        return view('sites.home',compact(['post']));
    }
    public function about()
    {
        return view('sites.about');
    }
    public function register()
    {
        return view('sites.register');
    }
    public function postregister(Request $request)
    {
        dd($request->all());
        //insert ke tabeel user
        $user = new \App\User;
        $user->role = 'siswa';
        $user->name = $request->nama_depan;
        $user->email = $request->email;
        $user->password = bcrypt('$request->password');
        $user->remember_token = str_random(60);
        $user->save();

         //insert ke table  siswa
        $request->request->add(['user_id' =>$user->id ]);
        $siswa = \App\Siswa::create($request->all());

        // \Mail::raw('cek'.$user->name, function ($message) use($user){
        //     $message->sender('john@johndoe.com', 'John Doe');
        //     $message->to('$user->email', $user->nama_depan);
        //     $message->subject('Subject ok');
        // });
        \Mail::to('$user->email')->send(new NotifPendaftaranSiswa);
        return redirect('/')->with('sukses', 'Data Berhasi Dikirim');
    }
    
    public function singlepost($slug)
    {
	$post =Post::where('slug','=',$slug)->first();
	return view('sites.singlepost',compact(['post']));
    }
}
