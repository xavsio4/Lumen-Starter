<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\Bookmark;
use Auth;
use Illuminate\Support\Facades\Response;

class BookmarkController extends Controller
{
    /**
    * Create a new controller instance.
    *
    * @return void
    */
    public function __construct()
    {
        $this->middleware('auth');
    }
    
    /**
    * Display a listing of the resource.
    *
    * @return \Illuminate\Http\Response
    */
    public function index(Request $request)
    {
        //$bookmark = Auth::user()->bookmark()->paginate(); //or
        $bookmark = Auth::user()->bookmark()->get();
        return response()->json(['status' => 'success','result' => $bookmark]);
    }
    
    /**
    * Store a newly created resource in storage.
    *
    * @param  \Illuminate\Http\Request  $request
    * @return \Illuminate\Http\Response
    */
    public function store(Request $request)
    {
        $this->validate($request, [
        'url' => 'required',
        'title' => 'required'
        ]);
        if(Auth::user()->bookmark()->Create($request->all())){
            return response()->json(['status' => 'success']);
        }else{
            return response()->json(['status' => 'fail']);
        }
        
    }
    
    /**
    * Display the specified resource.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function show($id)
    {
        $bookmark = Bookmark::where('id', $id)->get();
        return response()->json($bookmark);
        
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
        $this->validate($request, [
        'url' => 'filled',
        'title' => 'filled'
        ]);
        $bookmark = Bookmark::find($id);
        if($bookmark->fill($request->all())->save()){
            return response()->json(['status' => 'success']);
        }
        return response()->json(['status' => 'failed']);
    }
    
    /**
    * Remove the specified resource from storage.
    *
    * @param  int  $id
    * @return \Illuminate\Http\Response
    */
    public function destroy($id)
    {
        if(Bookmark::destroy($id)){
            return response()->json(['status' => 'success']);
        }
    }
}