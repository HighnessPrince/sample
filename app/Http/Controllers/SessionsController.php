<?php

namespace App\Http\Controllers;


use App\Http\Requests;
use Auth;
use Illuminate\Http\Request;

class SessionsController extends Controller {
    
    public function __construct() {
        
        $this->middleware('guest', [
            'only' => ['create']
        ]);
    }
    
    //
    public function create() {
        
        return view('sessions.create');
    }
    
    public function store(Request $request) {
        
        $credentials = $this->validate($request, [
            'email'    => 'required|email|max:255',
            'password' => 'required'
        ]);
        
        $accredited = Auth::attempt($credentials, $request->has('remember'));
        if ( ! $accredited) {
            
            session()->flash('danger', '很抱歉，您的邮箱和密码不匹配');
            
            return redirect()->back();
        }
        
        if ( ! Auth::user()->activated) {
            Auth::logout();
            session()->flash('warning', '你的账号未激活，请检查邮箱中的注册邮件进行激活。');
            
            return redirect('/');
        }
        
        session()->flash('success', '欢迎回来！');
        
        return redirect()->intended(route('users.show', [Auth::user()]));
    }
    
    public function destroy() {
        
        Auth::logout();
        session()->flash('success', '您已成功退出！');
        
        return redirect()->route('login');
    }
}
