<?php

namespace App\Helpers;

use App\Models\User;
use Carbon\Carbon;

class Authentication
{
    public $user = null;

    public function __construct()
    {
        $this->setup();
    }

    private function setup()
    {
        $user_id = session('user_id', null);
        if ($user_id !== null) {
            $this->user = User::find($user_id);
            $this->user->last_action = Carbon::now();
            $this->user->save();
        }
    }

    public function loggedIn()
    {
        return $this->user != null;
    }

    public function user($id = null, $set_last_login = true)
    {
        if ($id !== null) {
            session(['user_id' => $id]);
            $this->user = User::find($id);
            if ($set_last_login) {
                $this->user->last_login = Carbon::now();
                $this->user->last_action = Carbon::now();
                $this->user->save();
            }
        }
        return $this->user;
    }

    public function logout()
    {
        session(['user_id' => null]);
    }
}