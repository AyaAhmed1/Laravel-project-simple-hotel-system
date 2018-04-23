<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Yajra\Datatables\Facades\Datatables;
use App\Http\Requests\StoreRoomRequest;
use App\Http\Requests\UpdateRoomRequest;
use App\Room;
use App\Floor;
use App\User;
class RoomsController extends Controller
{
public function index(){
    //$rooms =Room::with('floor','user')->get();
    return view('rooms.index');
    //return $rooms; 
}

public function getdatatable(){
    header("Access-Control-Allow-Origin: *");
    $rooms =Room::with('floor','user')->get();
    return datatables()->of($rooms)
    ->addColumn('action', function ($data) {
    return "<a class='btn btn-xs btn-primary' href='/rooms/$data->id/edit'>Edit</a> 
    <a class='btn btn-xs btn-danger delete ' csrf_token() id='delete' post='$data->id' href='/rooms/$data->id'>Delete </a>
    ";
   })
    ->make(true);
  } 
  
public function create(){
  $users=User::all();
  $floors=Floor::all();
    return view('rooms.create',[
       'users'=>$users,
       'floors'=>$floors,
   ]);
  }
  
public function store(StoreRoomRequest $request){
  $room = Room::create([
      'number' => $request->number,
      'capacity' =>$request->capacity,
      'price'=>$request->price,
      'floor_id'=> $request->floor,
      'user_id'=> $request->user,
      'isavailable'=> 'yes',
    ]);
    return redirect('rooms');
  }  

public function edit($id){
  $room=Room::find($id);
  $users=User::all();
  $floors=Floor::all();
    return view('rooms.edit',[
      'users'=> $users,
      'room'=> $room,
      'floors'=>$floors,
    ]);
}
public function update(UpdateRoomRequest $request){
Room::where('id',$request->id)->update([
  'number' => $request->number,
  'capacity' =>$request->capacity,
  'price'=>$request->price,
  'floor_id'=> $request->floor,
  'user_id'=> $request->user,
  'isavailable'=> 'yes',    
]);
return redirect('rooms');

  }
  public function delete($id){
    Room::destroy($id);
    return redirect('rooms');
    }     

}