<?php

namespace App\Livewire\Content;

use App\Models\User;
use Livewire\Component;
use Illuminate\Support\Facades\Auth;
use Illuminate\Validation\Rule;

class UserManagement extends Component
{
    public $submit_func;

    public $user;

    public $total_users;

    public $user_id, $name, $phone_number, $email, $status, $password, $password_confirmation;

    public function getUser($userId)
    {
        $this->user = User::where('id', $userId)->first();

        if ($this->user) {
            $this->user_id = $this->user->id;
            $this->name = $this->user->name;
            $this->phone_number = $this->user->phone_number;
            $this->email = $this->user->email;
            $this->status = $this->user->status;

            $this->password = null;
            $this->password_confirmation = null;
        } else {
            session()->flash('error', 'User not found.');
        }
    }

    protected function rules()
    {
        $passwordRules = $this->user_id
            ? 'nullable|string|min:8|confirmed'
            : 'required|string|min:8|confirmed';

        return [
            'name' => 'required|string|max:255',
            'phone_number' => [
                'required',
                'string',
                'max:20',
                Rule::unique('users', 'phone_number')->ignore($this->user_id, 'id'),
            ],
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('users', 'email')->ignore($this->user_id, 'id'),
            ],
            'status' => 'nullable|string|max:255',
            'password' => $passwordRules,
        ];
    }

    public function render()
    {

        return view('livewire.content.user-management');
    }

    public function resetFields()
    {
        $this->reset([
            'name', 'phone_number', 'email', 'status', 'password'
        ]);
    }

    public function submit_user()
    {
        $this->validate();

        if ($this->submit_func == "add-user") {
            $user = User::create([
                'name' => $this->name,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            session()->flash('message', 'User successfully created.');

        } else if ($this->submit_func == "edit-user") {
            $this->user->name = $this->name;
            $this->user->phone_number = $this->phone_number;
            $this->user->email = $this->email;
            $this->user->status = $this->status;

            if (isset($this->password)) {
                $this->user->password = bcrypt($this->password);
            }

            $this->user->save();

            session()->flash('message', 'User successfully updated.');
        }

        return redirect()->route('users');
    }
}
