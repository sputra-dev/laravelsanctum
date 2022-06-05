<?php

namespace App\Http\Controllers\API;

use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use App\Models\Admin;
use App\Http\Resources\AdminResource;
use Validator;

class AdminController extends Controller
{
  /**
   * Display a listing of the resource.
   *
   * @return \Illuminate\Http\Response
   */
  public function index()
  {
      $data = Admin::latest()->get();
      return response()->json([AdminResource::collection($data), 'Admins fetched.']);
  }

  /**
   * Store a newly created resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @return \Illuminate\Http\Response
   */
  public function store(Request $request)
  {
      $validator = Validator::make($request->all(),[
          'name' => 'required|string|max:150',
          'email' => 'required|string|max:150',
          'password' => 'required|string|max:225',
      ]);

      if($validator->fails()){
          return response()->json($validator->errors());
      }

      $program = Admin::create([
          'name' => $request->name,
          'email' => $request->email,
          'password' => $request->password,
          'level' => $request->level,
          'status' => $request->status
       ]);

      return response()->json(['Admin created successfully.', new AdminResource($program)]);
  }

  /**
   * Display the specified resource.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function show($id)
  {
      $program = Admin::find($id);
      if (is_null($program)) {
          return response()->json('Data not found', 404);
      }
      return response()->json([new AdminResource($program)]);
  }

  /**
   * Update the specified resource in storage.
   *
   * @param  \Illuminate\Http\Request  $request
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function update(Request $request, Admin $program)
  {
      $validator = Validator::make($request->all(),[
          'name' => 'required|string|max:150',
          'email' => 'required|string|max:150',
          'password' => 'required|string|max:225'
      ]);

      if($validator->fails()){
          return response()->json($validator->errors());
      }

      $program->name = $request->name;
      $program->email = $request->email;
      $program->password = $request->password;
      $program->level = $request->level;
      $program->status = $request->status;
      $program->save();

      return response()->json(['Admin updated successfully.', new AdminResource($program)]);
  }

  /**
   * Remove the specified resource from storage.
   *
   * @param  int  $id
   * @return \Illuminate\Http\Response
   */
  public function destroy(Admin $program)
  {
      $program->delete();

      return response()->json('Admin deleted successfully');
  }
}
