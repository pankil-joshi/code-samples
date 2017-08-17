<?php


namespace App\Http\Controllers\Admin;

use App\Models\User;

class UserController {
    
    public function listAll() {

        $users = User::all();
        
        return view('admin.user.list')->with(
            [
                'meta' => ['title' => 'User\'s List'],
                'users' => $users
            ]);        
    }
    
    public function add() {
        
        return view('admin.user.save')->with(
            [
                'meta' => ['title' => 'Add a User']
            ]);        
    } 
    
    public function edit($userId) {
        
        $user = User::find($userId);
        
        return view('admin.user.save')->with(
            [
                'meta' => ['title' => 'Edit'],
                'user' => $user
            ]);        
    }     
}
