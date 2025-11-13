<?php

namespace App\Http\Controllers\Auth;
use App\Providers\RouteServiceProvider;
use App\Http\Controllers\Controller;
use App\Http\Requests\CheckEmployee;
use App\Http\Requests\CheckStudent;

use Illuminate\Support\Facades\Auth;
use Illuminate\Http\Request;
use App\Models\Employee;
use App\Models\MySchool;
// use App\Http\Requests\CheckEmployee;
 use App\Http\Requests\CheckSchool;

use Illuminate\Validation\ValidationException;
use Illuminate\Support\Facades\DB;
use App\Models\DepartmentEmployee;

class RegistrationController extends Controller
{
    protected $redirectTo = RouteServiceProvider::HOME;
    public function __construct()
    {
        // $this->middleware('auth:employee,student,admin', ['only'=>['index','logout',]]);   
        // $this->middleware('guest:employee,student,admin', ['only'=>['login','showLoginForm']]); 
    }

    public function school_register_form(){return view('auth.register_school');}
    public function employee_register_form(){return view('auth.register_employee');}
    public function student_register_form(){return view('auth.register_student');}
    public function regsubs(){return view('auth.regsub');}

    // public function assign_subject(Request $request){
    //     return my_class_employee()->assign_subject($request);
    // }
    public function regsub(Request $request){
        return  grader()->enter_grades($request);
    }

    public function register_school( CheckSchool $request)
    {
        if($request->isMethod('post'))
        {
            if($request->validated() && array_values($request['dept_id']) ==
            array_filter(array_values(array_intersect(range(1,11),$request['dept_id'])),'is_int')) // trying to make sure request is validated, and dept_id array is double validated
            {   
                $dept_array = array_filter($request['dept_id'], 'ctype_digit');
                if(count(array_intersect($dept_array, array(1,2,3)))<=0 )//check at least one of the departselection is top official before a school is registered, and returned array must all be digits
                { 
                  throw_error('login','you must either be an ICT admin, principal or Director to register a school'); 
                }
                elseif(count($request['dept_id'])>3) //prevent assigning employee to more than 4 department
                {
                    //throw ValidationException::withMessages([]);
                    throw_error('register_school','too many department allocation to a single staff');
                }           
                 else{
                    return my_school()->register_school($request);
                 }   
                
            }else{
                throw_error('login','validation error'); 
                
            }
              
        }else
        {
            
        }
    }
        
    
    public function register_employee(CheckEmployee $request)
    {  
        if($request->validated()/*&& in_array(Auth::guard()->user()->department,
        array("ict admin","principal","director"))*/)
        {
            return employee()->register_employee($request);
        }
        else 
        {
            return "you cant register an employee";
        } 
    }
    public function register_student(CheckStudent $request)
    { 
        if($request->validated() /*&& in_array(Auth::guard()->user()->department,
        array("ict admin","principal","director"))*/)
        {
            return  student()->register_student($request); 
        }
        else
        {
            return "you cant register a student";
        }
    }

    public function register_subject(Request $request){
      $validated = $request->validate(['name'=>'required|max:50|alpha', ]);
        
    }
}
