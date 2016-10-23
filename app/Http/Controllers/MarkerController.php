<?php namespace App\Http\Controllers;

use App\Marker;
use Illuminate\Http\Request;

class MarkerController extends Controller
{

    public function store(Request $request)
    {

       $marker = Marker::create([
           'field_id' => $request->input('field_id'),
           'title' => $request->input('title'),
           'description' => $request->input('description'),
           'lat' => $request->input('lat'),
           'lng' => $request->input('lng')
       ]);

        return $marker;
    }

    public function index(Request $request)
    {
        return Marker::all();
    } 
    public function getMarker($id, Request $request)
    {
        return Marker::where('field_id',$id)->get();
    }
    public function deleteMarker($id, Request $request)
    {
        return Marker::where('id',$id)->delete(); 
    }
}
