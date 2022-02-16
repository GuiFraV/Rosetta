<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Manager;
use App\Models\User;
use App\Models\Agency;
use Auth;

class ManagerController extends Controller
{
    
    /**
    * Get all the managers / agencies and pass them to the index view.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function index()
    {
        $objects = Manager::with('user')->get();
        $agencies = Agency::get();
        return view('admin.managers.index')->with('objects',$objects)->with('agencies',$agencies);
    }

    /**
    * Activate or disable a manager.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function activatemanager(Request $request){
        $user = User::find($request->user_id);        
        $user->active = ($request->statusactive == 0) ? 1 : 0;
        $user->save();                    
        return $user;     
    }

    /**
    * Store the new manager into the database.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {

        // Set entries as variable for easier back-end validation
        $first_name = $request->first_name_add_manager;
        $last_name = $request->last_name_add_manager;
        $email = $request->email_add_manager;
        $password = $request->password_add_manager;
        $phone = $request->phone_add_manager;
        $skype = $request->skype_add_manager;
        $signature = $request->signature_add_manager;
        $agency = $request->agency_add_manager;
        $type = $request->manager_add_radios;
        
        // Validation        
        if ($first_name === null || strlen($first_name) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The first name is missing or incorrect.');
        } else if ($last_name === null || strlen($last_name) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The last name is missing or incorrect.');
        } else if ($email === null || strlen($email) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The email is missing or incorrect.');
        } else if ($password === null || strlen($password) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The password is missing or incorrect.');
        } else if ($phone === null || strlen($phone) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The password is missing or incorrect.');
        } else if ($skype === null || strlen($skype) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The password is missing or incorrect.');
        } else if ($signature === null || strlen($signature) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The signature is missing or incorrect.');
        } else if ($agency < 1 || $agency > 7) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The agency is incorrect.');
        } else if ($type != "LM" && $type != "TM") {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The type of the manager is incorrect.');
        }

        // Test if the email is already given to another user
        $testExists = User::where('email', $email)->get();
        if(count($testExists) > 0) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The email is already used by another manager.');
        }

        // Create the user
        $user = new User;
        $user->role_id = 3;
        $user->active = 1;
        $user->email = $email;
        $user->password = bcrypt($password);
        $user->save();

        // Create the Manager
        $manager = new Manager;
        $manager->first_name = $first_name;
        $manager->last_name = $last_name;
        $manager->phone = $phone;
        $manager->skype_id = $skype;
        $manager->type = $type;
        $manager->signature = $signature;
        $manager->agency_id = $agency;
        $manager->user_id = $user->id;
        $manager->save();

        $objects = Manager::with('user')->get();

        return redirect()->route('admin.managers.index')->with('creationSuccess', 'Manager has been added successfully')->with('objects', $objects);
    }
    
    /**
    * Update a manager in the database.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function update(Request $request)
    {
        
        // Set entries as variable for easier back-end validation
        $first_name = $request->first_name_edit_manager;
        $last_name = $request->last_name_edit_manager;
        $email = $request->email_edit_manager;
        $phone = $request->phone_edit_manager;
        $skype = $request->skype_edit_manager;
        $agency = $request->agency_edit_manager;
        $type = $request->manager_edit_radios;
        
        // Validation        
        if ($first_name === null || strlen($first_name) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The first name is missing or incorrect.');
        } else if ($last_name === null || strlen($last_name) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The last name is missing or incorrect.');
        } else if ($email === null || strlen($email) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The email is missing or incorrect.');
        } else if ($phone === null || strlen($phone) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The password is missing or incorrect.');
        } else if ($skype === null || strlen($skype) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The password is missing or incorrect.');
        } else if ($agency < 1 || $agency > 7) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The agency is incorrect.');
        } else if ($type != "LM" && $type != "TM") {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The type of the manager is incorrect.');
        }

        $manager = Manager::find($request->id_manager);
        $manager->first_name = $first_name;
        $manager->last_name = $last_name;
        $manager->phone = $phone;
        $manager->skype_id = $skype;
        $manager->type = $type;
        $manager->agency_id = $agency;
        $manager->user->email = $email;
        $manager->save();
        
        $objects = Manager::with('user')->get();

        return redirect()->route('admin.managers.index')->with('updateSuccess', 'Manager has been updated successfully')->with('objects', $objects);
    }

    /**
    * Update a manager's password.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function editpassword(Request $request)
    {

        // Set entry as variable for easier back-end validation
        $password = $request->password_edit_manager;
        
        // Validation        
        if ($password === null) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The password is missing.');
        } else if (strlen($password) > 191) {
            return redirect()->route('admin.managers.index')->with('validationError', 'Form error! The password cannot exceed 190 characters.');
        }      

        $manager = Manager::with('user')->find($request->pass_id_manager);
        $manager->user->password = bcrypt($password);
        $manager->push();
        
        $objects = Manager::with('user')->get();

        return redirect()->route('admin.managers.index')->with('updateSuccess','Password has been updated successfully')->with('objects',$objects);
    }
}
