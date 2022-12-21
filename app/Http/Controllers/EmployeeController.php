<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Session;
use Image;
use Exception;
use Validator;

class EmployeeController extends Controller
{
    public function __invoke() {
        $user = null;
        if (Session::has('user')) {
            $user = Session::get('user');   
        }
        return view('employees.index', compact('user'));
    }

    public function index() {
        $employees = Employee::all();
        return $employees;
    }

    public function store(Request $request) {
        $validator = Validator::make($request->all(), [
            'first_name' => 'required|min:2',
            'last_name' => 'required',
            'email' => 'required|email|min:15',
            'phone' => 'required',
            'birthday' => 'required',
            'ssn' => 'required',
            'gender' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'start_date' => 'required',
        ],
        [
            'start_date.required'=> 'The hire date field is required.', // custom message
        ]
        );

        // I do not know how to test this way. So, I user the way above.
        /*$this->validate($request, [
            'first_name' => 'required|min:2',
            'last_name' => 'required',
            'email' => 'required|email|min:15',
            'phone' => 'required',
            'birthday' => 'required',
            'ssn' => 'required',
            'gender' => 'required',
            'position' => 'required',
            'salary' => 'required',
            'address' => 'required',
            'city' => 'required',
            'state' => 'required',
            'zip' => 'required',
            'start_date' => 'required',
        ]);*/

        if ($validator->passes()) {
            // Method 1 (Passing img as an object):
            $img = null;
            if ((!is_string($request->img)) && ($request->img != null)) {
                //$img = time().'.'.$request->img->extension();
                $img = $this->retrieveRandomImageName($request);
            }
            //$request->img->move(public_path('images'), $img);

            // Method 2 (Passing img as a string):
            /*if($request->img) {
                $img = time() . '.' . explode('/', explode(':', substr($request->img, 0, strpos($request->img, ';')))[1])[1];
                //Image::make($request->img)->save(public_path('images/' . $img));
            }else{
                $img = null;
            }*/

        
            /*$img = $request->file('img');
            $imageName = $img->getClientOriginalName();
            $imageName = time().'_'.$imageName;
            $img->move(public_path('/images'), $imageName);

            if ($request->img) {
                $img = $this.handleImage($request);
            }*/
            $oldImageName = '';
            if ($request->id != '') {
                //$employee = DB::table('employees')->where('id', $request->id);
                $employee = Employee::find($request->id);
                if ($employee->first()->img) {
                    $oldImageName = $employee->first()->img;
                }
                $employee->first_name = $request->first_name;
                $employee->middle_name = $request->middle_name;
                $employee->last_name = $request->last_name;
                $employee->email = $request->email;
                $employee->phone = $request->phone;
                $employee->birthday = $request->birthday;
                $employee->ssn = $request->ssn;
                $employee->gender = $request->gender;
                $employee->position = $request->position;
                $employee->salary = $request->salary;
                $employee->address = $request->address;
                $employee->address2 = $request->address2;
                $employee->city = $request->city;
                $employee->state = $request->state;
                $employee->zip = $request->zip;
                if ($img) {
                    $employee->img = $img;
                }
                $employee->start_date = $request->start_date;
                $employee->end_date = $request->end_date;
                $response = $employee->save();
            } else {
                $response = Employee::create([
                    'first_name' => $request->first_name,
                    'middle_name' => $request->middle_name,
                    'last_name' => $request->last_name,
                    'email' => $request->email,
                    'phone' => $request->phone,
                    'birthday' => $request->birthday,
                    'ssn' => $request->ssn,
                    'gender' => $request->gender,
                    'position' => $request->position,
                    'salary' => $request->salary,
                    'address' => $request->address,
                    'address2' => $request->address2,
                    'city' => $request->city,
                    'state' => $request->state,
                    'zip' => $request->zip,
                    'img' => $img,
                    'start_date' => $request->start_date,
                    'end_date' => $request->end_date
                ]);
            }
            if ($response && $img) {
                $this->handleImage($request, $img, $oldImageName);
            };
            return response()->json([
                'status' => 1,
                'data' => $response
            ], 200);
        }
        return response()->json(['status'=>0, 'error'=>$validator->errors()->toArray()]);
    }

    protected function retrieveRandomImageName($request) {
        return time().'.'.$request->img->extension();
    }

    protected function handleImage(Request $request, String $imageName, String $oldImageName) {
        /*$this->validate($request,[
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg',
        ]);*/
        /*if(Auth::user()->userprofile){
            $oldimage = Userprofile::where('user_id', Auth::user()->id)->firstOrFail();
            File::delete([
                public_path($oldimage->image),
                public_path($oldimage->thumbnail),
            ]);
            $oldimage->delete();
        }*/
        
        //$image = $request->file('img');
        //$imageName = $image->getClientOriginalName();
        //$imageName = time().'_'.$imageName;
        //thumbnail = $image->getClientOriginalName();
        //$thumbnail= time().'_thumbnail'.$thumbnail;

        //Image::make($image)
        //->fit(100, 100)
        //->save(public_path('/images/').$thumbnail);
        //$image->move(public_path('/images'), $imageName);

        //$img = new Userprofile;
        //$img->user_id = Auth::user()->id;
        //$img->image = 'images/'.$imageName;
        //$img->thumbnail = 'images/'.$thumbnail;
        //$img->save();
        //return 'images/' .$imageName;

        // Method 1 (Passing image as an object):
        $img = $request->file('img');
        //$img->move(public_path('/images'), $imageName); // I can use this directly, but if size is too big, this will cause error. So, I need to resize it first.
        $filePath = public_path('/images');
        $img = Image::make($img->path());
        $img->resize(100, 100, function ($const) {
            $const->aspectRatio();
        })->save($filePath.'/'.$imageName);

        // Method 2 (Passing img as a string):
        /*//$image = time() . '.' . explode('/', explode(':', substr($request->img, 0, strpos($request->img, ';')))[1])[1];
        Image::make($request->img)->save(public_path('images/' . $imageName));*/

        // if update, delete old image from images folder
        if ($oldImageName) {
            File::delete([
                public_path('/images/' .$oldImageName)
            ]);
        }
    }

    public function delete($id) {
        try {
            $employee = DB::table('employees')->where('id', $id);
            if ($employee->first()) {
                $imageName = $employee->first()->img;
                $fullName = $employee->first()->first_name .' ' .$employee->first()->last_name;
                $response = $this->deleteEmployee($employee);
                if ($response) {
                    if ($imageName) {
                        File::delete([
                            public_path('/images/' .$imageName)
                        ]);
                    }
                    $message = 'Employee: ' .$fullName .' has been deleted successfully!'; 
                    return response()->json(['status'=>2, 'msg'=>$message]);
                }
            } else {
                return response()->json(['status'=>0, 'msg'=>'Sorry, employee not found']);
            }
        } catch (Exception $e) {
            $message = 'Sorry, employee cannot be deleted: ' .$e->getMessage();
            return response()->json(['status'=>1, 'msg'=>$message]);
        } 
    }

    public function deleteEmployee($employee) {
        return $employee->delete();
    }

    public function edit($id) {
        $employee = DB::table('employees')->where('id', $id)->first();
        return response()->json([
            'id'=>$employee->id,
            'first_name'=>$employee->first_name,
            'middle_name'=>$employee->middle_name,
            'last_name'=>$employee->last_name,
            'email'=>$employee->email,
            'phone'=>$employee->phone,
            'birthday'=>$employee->birthday,
            'ssn'=>$employee->ssn,
            'gender'=>$employee->gender,
            'position'=>$employee->position,
            'salary'=>$employee->salary,
            'address'=>$employee->address,
            'address2'=>$employee->address2,
            'city'=>$employee->city,
            'state'=>$employee->state,
            'zip'=>$employee->zip,
            'img'=>$employee->img,
            'start_date'=>$employee->start_date,
            'end_date'=>$employee->end_date,
        ]);
    }
}
