<?php

namespace App\Http\Controllers\Admins;

use App\Http\Controllers\Controller;
use App\Models\BookHall;
use App\Models\Hall;
use App\Models\HallImage;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;
use Illuminate\Support\Facades\Validator;
use Illuminate\Validation\ValidationException;
use Symfony\Component\HttpFoundation\File\Exception\UploadFileException;


class HallAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //redirect to dashbord (hadmin) of hall for specific admin
        return view('pages.admins.hadmin')->with('hall', $this->getHall());
    }
    public function getHall()
    {
        $hall = Hall::where('user_id', '=', Session::get('userID'))->first();
        return $hall;
    }
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    // Define the validation rules as a separate function
    private function getValidationRules()
    {
        return [
            'name' => ['required', 'string', 'max:255'],
            'profile' => ['required', 'file'],
            'location' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'amplitude' => ['required', 'string', 'max:255'],
            'social_media' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'services' => ['required', 'string', 'max:255'],
            'events' => ['required', 'string', 'max:255'],
            'conditions' => ['required', 'string', 'max:255'],
        ];
    }

    public function validation(Request $request)
    {
        $request->validate($this->getValidationRules());
    }

    public function store(Request $request)
    {
        $this->validation($request);

        $profile_url = $this->uploadProfile($request);
        $hall = Hall::create([
            'name' => $request->name,
            'profile' => $profile_url,
            'user_id' => Session::get('userID'),
            'location' => $request->location,
            'price' => $request->price,
            'amplitude' => $request->amplitude,
            'social_media' => $request->social_media,
            'description' => $request->description,
            'services' => $request->services,
            'events' => $request->events,
            'conditions' => $request->conditions,
        ]);

        return back();
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

    public function update(Request $request, $id)
    {

        // Retrieve the existing record
        $hall = Hall::find($id);

        if (!$hall) {
            // Handle the case when the record with $id doesn't exist
            return redirect('pages.index');
        }

        $request->validate([
            'name' => ['required', 'string', 'max:255'],
            'location' => ['required', 'string', 'max:255'],
            'price' => ['required', 'string', 'max:255'],
            'amplitude' => ['required', 'string', 'max:255'],
            'social_media' => ['required', 'string', 'max:255'],
            'description' => ['required', 'string', 'max:255'],
            'services' => ['required', 'string', 'max:255'],
            'events' => ['required', 'string', 'max:255'],
            'conditions' => ['required', 'string', 'max:255'],
        ]);

        // ... Rest of the update function ...

        // Update the fields with the new data from the $request
        $hall->name = $request->name;
        $hall->location = $request->location;
        $hall->price = $request->price;
        $hall->amplitude = $request->amplitude;
        $hall->social_media = $request->social_media;
        $hall->description = $request->description;
        $hall->services = $request->services;
        $hall->events = $request->events;
        $hall->conditions = $request->conditions;

        // Upload the new profile if provided
        if ($request->hasFile('profile')) {
            $profile_url = $this->uploadProfile($request);
            $hall->profile = $profile_url;
        }

        // Save the updated record
        $hall->save();
        return back()->with('success', 'Hall updated successfully.');
    }

    ////////upload hall image رئيسي

    public function uploadProfile(Request $request)
    {
        $file = $request->file('profile');
        $timestamp = round(microtime(true) * 1000); // Generate a timestamp in milliseconds
        $extension = $file->getClientOriginalExtension(); // Get the original file extension
        $filename = "profile_" . "{$timestamp}.{$extension}"; // Combine the timestamp and extension to create a unique filename
        $file->move(public_path('images/halls'), $filename);
        $profile_url = 'images/halls/' . $filename;
        return $profile_url;
    }
    ////////////
    public function uploadImage(Request $request)
    {

        try {
            //another way to validate input file
            $validator = Validator::make($request->all(), [
                'imagefile' => 'required|file'
            ]);

            if ($validator->fails()) {
                throw new ValidationException($validator);
            }


            $file = $request->file('imagefile');
            //get file name
            //$filename = $file->getClientOriginalName();
            $timestamp = round(microtime(true) * 1000); // Generate a timestamp in milliseconds
            $extension = $file->getClientOriginalExtension(); // Get the original file extension
            $filename = "hall_" . "{$timestamp}.{$extension}"; // Combine the timestamp and extension to create a unique filename

            $file->move(public_path('images/halls'), $filename);


            $hall = Hall::where('user_id', '=', Session::get('userID'))->first();
            ///////////////////////////// the same of  create funtion 
            $hallImage = new HallImage();
            $hallImage->hall_id = $hall->id;
            $hallImage->url = 'images/halls/' . $filename;
            if ($hallImage->save()) {
                return back()->with('uploaded', 'تم رفع الصوره بنجاح ');
            } else {
                throw new UploadFileException();
            }
            ///////////////////////////////////


        } catch (UploadFileException $e) {
            return back()->with('error', 'حدث خطا اثناء الرفع ');

        } catch (ValidationException $e) {
            return back()->withErrors($e->validator)->withInput();
        }


    }

    //recive  the hall  id form booking page 
    public function getBookInfo($hall_id)
    {

        $hall = Hall::select('name', 'price', 'id', 'user_id')
            ->where('id', '=', $hall_id)
            ->first();
        $bookedDays = BookHall::where('hall_id', $hall_id)
            ->pluck('date')
            ->toArray();



        $BookInfo = array(
            'hall' => $hall,
            'bookedDays' => $bookedDays,
        );
        return view('pages.hall')->with($BookInfo);
    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //  $hall=Hall::where('user_id','=',Session::get('userID'))->first();
        //  return view('pages.admins.hadmin')->with('hall',$hall);
    }


    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {


    }


    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}