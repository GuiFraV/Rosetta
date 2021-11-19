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
    public function index()
    {
        $objects = Manager::with('user')->get();
        // foreach ($objects as $object){
        //     $object->email = $object->user->email;
        //     $object->password = $object->user->password;
        //     $object->role = $object->user->role_id;
            
        // }
        // $manager = new Manager;
        // $manager->save();
        
        // Update a manager


        // $manager = Manager::find(1);

        // $manager->first_name = 'F test 2';
        // $manager->user->name = 'Test username 2';
        // $manager->push();
        

        // Delete a manager


        // $user = User::with('manager')->find(6);
        // $user->delete();

        // $manager = Manager::find($user->manager->id);
        // $manager->delete();


        // Add a manager


        // $user = new User;
        // $user->role_id = 3;
        // $user->email = 'manager2@gmail.com';
        // $user->password = bcrypt('pass@manager2');
        // $user->save();

        // $manager = new Manager;
        // $manager->first_name = 'F test 3';
        // $manager->last_name = 'L test 3';
        // $manager->signature = 'aaaa';
        // $manager->user_id = $user->id;

        // $manager->save();

        

        

        $agencies = Agency::get();
        return view('admin.managers.index')->with('objects',$objects)->with('agencies',$agencies);
        // return json_decode(Auth::user());
    }
    public function activatemanager(Request $request){
        $user = User::find($request->user_id);
        if ($request->statusactive == 0){
            $user->active = 1;
        }else if($request->statusactive == 1){
            $user->active = 0;
        }
        $user->push();
        
            
        return $user;
           
            
        
    }
    public function store(Request $request)
    {
        $user = new User;
        $user->role_id = 3;
        $user->active = 1;
        $user->email = $request->email_add_manager;
        $user->password = bcrypt($request->password_add_manager);
        $user->save();

        $manager = new Manager;
        $manager->first_name = $request->first_name_add_manager;
        $manager->last_name = $request->last_name_add_manager;
        $manager->type = $request->manager_add_radios;
        $manager->signature = $request->signature_add_manager;
        $manager->agency_id = $request->agency_add_manager;
        $manager->user_id = $user->id;

        $manager->save();
        $objects = Manager::with('user')->get();

        return redirect()->route('admin.managers.index')->with('succesadd','Manager has been added successfully')->with('objects',$objects);

    }

    public function savemanager(){
        $data = '[
            {
              "first_name": "Alexandra",
              "last_name": "Duverger",
              "type": "TM",
              "agnecy": 1,
              "email": "transport@intergate-logistic.com",
              "password": "W96&7lmpD13FT5$"
            },
            {
              "first_name": "Diana",
              "last_name": "Viazovskaia",
              "type": "LM",
              "agnecy": 2,
              "email": "baltic1@intergate-logistic.com",
              "password": "9e5ga3p&wR0AEF$"
            },
            {
              "first_name": "Djovany",
              "last_name": "Hollande",
              "type": "TM",
              "agnecy": 1,
              "email": "goods1@intergate-logistic.com",
              "password": "GW4&4xzA9eGW03$"
            },
            {
              "first_name": "Elena",
              "last_name": "Raileanu",
              "type": "LM",
              "agnecy": 1,
              "email": "transport2@intergate-logistic.com",
              "password": "XT67x&C$g8eHG54$"
            },
            {
              "first_name": "Evgeniia",
              "last_name": "Petrunina I",
              "type": "LM",
              "agnecy": 2,
              "email": "logistic1@intergate-logistic.com",
              "password": "N90z$&f3Pd45qoP$"
            },
            {
              "first_name": "Fouad",
              "last_name": "Ait El Madani",
              "type": "TM",
              "agnecy": 1,
              "email": "fouad.madani@intergate-logistic.com",
              "password": "Sanoha@100%"
            },
            {
              "first_name": "Jalila",
              "last_name": "Lahoucen",
              "type": "TM",
              "agnecy": 4,
              "email": "shipping@intergate-logistic.com",
              "password": "985g&a3xw80AEF$"
            },
            {
              "first_name": "Joanna",
              "last_name": "Michalska I",
              "type": "LM",
              "agnecy": 2,
              "email": "logistic7@intergate-logistic.com",
              "password": "Y2&0fK9Aa81wfDH$"
            },
            {
              "first_name": "Joanna",
              "last_name": "Michalska II",
              "type": "LM",
              "agnecy": 2,
              "email": "logistic2@intergate-logistic.com",
              "password": "Y2&0fK9Aa81wfDH$"
            },
            {
              "first_name": "Lahcen",
              "last_name": "Asenkl",
              "type": "TM",
              "agnecy": 4,
              "email": "transport6@intergate-logistic.com",
              "password": "Y90fT9C&a84wfD$"
            },
            {
              "first_name": "Malgorzata",
              "last_name": "Ledwojcik",
              "type": "TM",
              "agnecy": 2,
              "email": "transport4@intergate-logistic.com",
              "password": "df90&Rv280dasS$"
            },
            {
              "first_name": "Malika",
              "last_name": "Bouljihal",
              "type": "TM",
              "agnecy": 4,
              "email": "shipping1@intergate-logistic.com",
              "password": "GQ78xa&A9rWQ0Z$"
            },
            {
              "first_name": "Michal",
              "last_name": "Uciechowski",
              "type": "TM",
              "agnecy": 2,
              "email": "transport5@intergate-logistic.com",
              "password": "Y05F&Z3el45qoA$"
            },
            {
              "first_name": "Muriel",
              "last_name": "Nourry",
              "type": "TM",
              "agnecy": 1,
              "email": "transport1@intergate-logistic.com",
              "password": "Y75F&Z3mZ46qoA$"
            },
            {
              "first_name": "Roksana",
              "last_name": "Koceluch",
              "type": "TM",
              "agnecy": 1,
              "email": "roksana.koceluch@intergate-logistic.com",
              "password": "X8&5zf3Pd45qoP$"
            },
            {
              "first_name": "Sandra",
              "last_name": "Silva",
              "type": "LM",
              "agnecy": 1,
              "email": "logistic5@intergate-logistic.com",
              "password": "2Addz&qM3N90Rv$"
            },
            {
              "first_name": "Sara",
              "last_name": "Mastari",
              "type": "TM",
              "agnecy": 3,
              "email": "commercial1@intergate-logistic.com",
              "password": "3N90Rv2Ad&d7zqM$"
            },
            {
              "first_name": "Sarah",
              "last_name": "Benryab",
              "type": "LM",
              "agnecy": 2,
              "email": "shipping3@intergate-logistic.com",
              "password": "Y85FZ&3P$d45qoA$"
            },
            {
              "first_name": "Test",
              "last_name": "Test",
              "type": "",
              "agnecy": 4,
              "email": "test@intergate-logistc.com",
              "password": "Test202012345test"
            },
            {
              "first_name": "Support &",
              "last_name": "Helpdesk",
              "type": "Admin",
              "agnecy": 4,
              "email": "support@intergate-logistic.com",
              "password": "S71&5jk$3Pd$45qS$"
            },
            {
              "first_name": "Zakari",
              "last_name": "Hessas",
              "type": "TM",
              "agnecy": 1,
              "email": "commercial@intergate-logistic.com",
              "password": "ML65xzA&8eHG03$"
            }
          ]';
        $persons = json_decode($data);
        foreach($persons as $request){
            $user = new User;
            $user->role_id = 3;
            $user->active = 1;
            $user->email = $request->email;
            $user->password = bcrypt($request->password);
            $user->save();

            $manager = new Manager;
            $manager->first_name = $request->first_name;
            $manager->last_name = $request->last_name;
            $manager->type = $request->type;
            $manager->agency_id = $request->agnecy;
            $manager->signature = "";
            $manager->user_id = $user->id;

            $manager->save();
        }
        return "Done";
    }
    
    public function update(Request $request)
    {
        // $request->validate([
        //     'city_name' => 'required'
        // ]);
    
        // $city->update($request->all());

        $manager = Manager::find($request->id_manager);

        $manager->first_name = $request->first_name_edit_manager;
        $manager->last_name = $request->last_name_edit_manager;
        $manager->type = $request->manager_edit_radios;
        $manager->agency_id = $request->agency_edit_manager;
        $manager->user->email = $request->email_edit_manager;
        $manager->push();
        
        $objects = Manager::with('user')->get();

        return redirect()->route('admin.managers.index')->with('succesupdate','Manager has been updated successfully')->with('objects',$objects);
    }
    public function editpassword(Request $request)
    {
        // $request->validate([
        //     'city_name' => 'required'
        // ]);
    
        // $city->update($request->all());

        $manager = Manager::with('user')->find($request->pass_id_manager);
        $manager->user->password = bcrypt($request->password_edit_manager);
        $manager->push();
        
        $objects = Manager::with('user')->get();

        return redirect()->route('admin.managers.index')->with('succesupdate','Password has been updated successfully')->with('objects',$objects);
    }
}
