<?php namespace App\Http\Controllers;

use App\Polygon;
use Illuminate\Http\Request;

class PolygonController extends Controller
{

    public function store(Request $request)
    {
        $polygon = Polygon::create([
           'field_id' => $request->input('field_id'),
           'polygon_data' => json_encode($request->input('polygon'))
       ]);

        return $polygon;
    }

    public function index(Request $request)
    {
        return Marker::all();
    } 
    public function getPolygon($id, Request $request)
    {
        return Polygon::where('field_id',$id)->get();
    }
    public function deletePolygon($id, Request $request)
    {
        return Polygon::where('id',$id)->delete(); 
    }
}
