<?php

namespace App\Livewire\Content;

use App\Models\School;
use Livewire\Component;
use Illuminate\Validation\Rule;

class SchoolManagement extends Component
{
    public $submit_func;

    public $school;

    public $total_schools;

    public $school_id, $name, $address, $phone_number, $email, $status, $password, $password_confirmation;

    public function getSchool($schoolId)
    {
        $this->school = School::where('school_id', $schoolId)->first();

        if ($this->school) {
            $this->school_id = $this->school->school_id;
            $this->name = $this->school->name;
            $this->address = $this->school->address;
            $this->phone_number = $this->school->phone_number;
            $this->email = $this->school->email;
            $this->status = $this->school->status;

            $this->password = null;
            $this->password_confirmation = null;
        } else {
            session()->flash('error', 'School not found.');
        }
    }

    protected function rules()
    {
        $passwordRules = $this->school_id
            ? 'nullable|string|min:8|confirmed'
            : 'required|string|min:8|confirmed';

        return [
            'name' => 'required|string|max:255',
            'address' => 'required|string|max:255',
            'phone_number' => 'required|string|max:20',
            'email' => [
                'required',
                'email',
                'max:255',
                Rule::unique('schools', 'email')->ignore($this->school_id, 'school_id'),
                Rule::unique('users', 'email'),
            ],
            'status' => 'nullable|string|max:255',
            'password' => $passwordRules,
        ];
    }

    public function render()
    {
        return view('livewire.content.school-management');
    }

    public function resetFields()
    {
        $this->reset([
            'name', 'address', 'phone_number', 'email', 'status', 'password'
        ]);
    }

    public function submit_school()
    {
        $this->validate();

        if ($this->submit_func == "add-school") {
            $school = School::create([
                'name' => $this->name,
                'address' => $this->address,
                'phone_number' => $this->phone_number,
                'email' => $this->email,
                'password' => bcrypt($this->password),
            ]);

            session()->flash('message', 'School successfully created.');

        } else if ($this->submit_func == "edit-school") {
            $this->school->name = $this->name;
            $this->school->address = $this->address;
            $this->school->phone_number = $this->phone_number;
            $this->school->email = $this->email;
            $this->school->status = $this->status;

            if (isset($this->password)) {
                $this->school->password = bcrypt($this->password);
            }

            $this->school->save();

            session()->flash('message', 'School successfully updated.');
        }

        return redirect()->route('schools');
    }
}
