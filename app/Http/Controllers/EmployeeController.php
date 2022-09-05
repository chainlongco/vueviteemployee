<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Employee;
use Image;

class EmployeeController extends Controller
{
    public function index() {
        $employees = Employee::all();
        return $employees;
    }

    public function store(Request $request) {
        //$dd($request->all());
        /*return Employee::create([
            'first_name' => "$request("firstName")",
            'middle_name' => $request("middleName"),
            'last_name' => $request("lastName"),
        ]);*/
        /*$data = $request->all();
        $response = Employee::create($data);
        return response()->json([
            'status' => 'success',
            'data' => $response
        ], 200);*/

        /*$response = Employee::create([
            'first_name' => "Jacky",
            'middle_name' => "JS",
            'last_name' => "Shyu",
            'email' => "shyujacky@yahoo.com",
            'phone' => "1234567897",
            'birthday' => "1967-01-01",
            'ssn' => "123456789",
            'gender' => "M",
            'position' => "Manager",
            'salary' => "1000",
            'address' => "5003 Laurel Springs",
            'address2' => "Suite 100",
            'city' => "Dallas",
            'state' => "TX",
            'zip' => "76033",
            'img' => "123456",
            'start_date' => "2002-01-01",
            'edate_date' => "2080-01-01"
        ]);*/

        // Method 1 (Passing image as an object):
        $image = null;
        $image = time().'.'.$request->image->extension();  
        //$request->image->move(public_path('images'), $image);

        // Method 2 (Passing img as a string):
        /*if($request->img) {
            $image = time() . '.' . explode('/', explode(':', substr($request->img, 0, strpos($request->img, ';')))[1])[1];
            //Image::make($request->img)->save(public_path('images/' . $image));
        }else{
            $image = null;
        }*/

    
        /*$image = $request->file('img');
        $imageName = $image->getClientOriginalName();
        $imageName = time().'_'.$imageName;
        $image->move(public_path('/images'), $imageName);

        if ($request->img) {
            $image = $this.handleImage($request);
        }*/
        
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
            'img' => $image,
            'start_date' => $request->start_date,
            'end_date' => $request->end_date
        ]);
        if ($response && $image) {
            $this->handleImage($request, $image);
        };
        return response()->json([
            'status' => 'success',
            'data' => $response
        ], 200);
    }

    private function handleImage(Request $request, String $imageName) {
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
        $image = $request->file('image');
        $image->move(public_path('/images'), $imageName);

        // Method 2 (Passing img as a string):
        /*//$image = time() . '.' . explode('/', explode(':', substr($request->img, 0, strpos($request->img, ';')))[1])[1];
        Image::make($request->img)->save(public_path('images/' . $imageName));*/
    }
}
