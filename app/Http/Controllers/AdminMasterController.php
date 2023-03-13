<?php

namespace App\Http\Controllers;

use App\Http\Resources\admin\MasterResource;
use App\Models\User;
use Illuminate\Http\Request;

class AdminMasterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $req)
    {
        // ! probably elequent query is not optimized, because we get the employee relation and withing the employee, we get the faculty relation
        return User::role('master')->cpagination($req,MasterResource::class);
        
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
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $master = User::findorfail($id);
        if ($master->cRole() != 'master') {
            return response()->json([
                'message' => 'استاد یافت نشد'
            ], 400);
        }   
        return MasterResource::make($master);
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
        $master = User::findorfail($id)->first();
        if ($master->cRole() != 'master') {
            return response()->json([
                'message' => 'استاد یافت نشد'
            ], 400);
        }
        $master->destroy();
        return response()->json([
            'message' => 'استاد حذف شد'
        ]);
    }
}
