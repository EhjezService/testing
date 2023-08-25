<?php

namespace App\Http\Controllers\Booking;

use App\Http\Controllers\Controller;
use App\Models\BookHall;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Session;

class BookHallController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        //
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



    //////////////////////////////////

    private function getValidationRules()
    {
        return [
            'bookDate' => 'required|date',
            'beneficial' => 'required|string',
            'bookType' => 'required|string',
            'accountType' => 'required|string',
            'payer' => 'required|string',
            'payPhoto' => 'nullable|image|mimes:jpeg,png,jpg,gif|max:2048',
        ];
    }

    //////////////////////////////////////////upload photo
    public function uploadPayPhoto(Request $request)
    {
        $file = $request->file('payPhoto');
        $timestamp = round(microtime(true) * 1000); // Generate a timestamp in milliseconds
        $extension = $file->getClientOriginalExtension(); // Get the original file extension
        $filename = "pay_" . "{$timestamp}.{$extension}"; // Combine the timestamp and extension to create a unique filename
        $file->move(public_path('images/pay_photos'), $filename);
        $pay_url = 'images/halls/' . $filename;
        return $pay_url;
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */

    public function store(Request $request)
    {



        $request->validate($this->getValidationRules());

        $book = new BookHall();

        $book->hall_id = $request->hall_id;
        $book->user_id = Session::get('userID');
        $book->date = $request->bookDate;
        $book->beneficial = $request->beneficial;
        $book->payer = $request->payer;
        $book->account_type = $request->accountType;
        $book->type = $request->bookType;

        // Upload the payment photo if it exists
        if ($request->hasFile('payPhoto')) {
            $pay_url = $this->uploadPayPhoto($request);
            $book->pay_photo = $pay_url;
        }

        if ($book->save()) {
            return redirect()->back()->with('success', 'تم الحجز بنجاح!');
        } else {
            return redirect()->back()->with('success', 'تم الحجز!');
        }

        ////////-------------------

    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
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
        //
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