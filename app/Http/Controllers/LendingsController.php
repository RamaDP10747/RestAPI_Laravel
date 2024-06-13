<?php

namespace App\Http\Controllers;

use App\Models\Lendings;
use Illuminate\Http\Request;

class LendingsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        try {
            $getLending = Lending::with('stuff', 'user')->get();

            return ResponseFormatter::sendResponse(200, 'Successfully Get All Lending Data', $getLending);
        } catch (\Exception $e) {
            return ResponseFormatter::sendResponse(400, $e->getMessage());
        }
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
        try {
            $this->validate($request, [
            'stuff_id' => 'required',
            'date_time' => 'required',
            'name' => 'required',
            'user_id' => 'required',
            'notes' => 'required',
            'total_stuff' => 'required',
            ]);

            $getLending = Lending::create([
            'stuff_id' => $request->stuff_id,
            'date_time' => $request->date_time,
            'name' => $request->name,
            'user_id' => $request->user_id,
            'notes' => $request->notes,
            'total_stuff' => $request->total_stuff,
            ]);

            return ApiFormatter::sendResponse(200, 'success', $getLending);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse (400, 'bad request', $err->getMessage());
            }

    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Lendings  $lendings
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        try{
            $getLending = Lending::where('id', $id)->first();

            if (is_null($getLending)) {
                return ApiFormatter::sendResponse(400, 'bad request', 'Data not found!');
            } else {
                return ApiFormatter::sendResponse(200, 'success', $getLending);
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse (400, 'bad request', $err->getMessage());
            }

    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Lendings  $lendings
     * @return \Illuminate\Http\Response
     */
    public function edit(Lendings $lendings)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Lendings  $lendings
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        try {
            $this->validate($request, [
                'stuff_id' => 'required',
                'date_time' => 'required',
                'name' => 'required',
                'user_id' => 'required',
                'notes' => 'required',
                'total_stuff' => 'required',
            ]);

            $checkProses = Lending::where('id', $id)->update([
                'stuff_id' => $request->stuff_id,
                'date_time' => $request->date_time,
                'name' => $request->name,
                'user_id' => $request->user_id,
                'notes' => $request->notes,
                'total_stuff' => $request->total_stuff,
            ]);

            if($checkProses) {
                $getLending = Lending::find($id);
                return ApiFormatter::sendResponse(200, 'success', $getLending);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal mengubah data! ');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse (400, 'bad request', $err->getMessage());
            }

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Lendings  $lendings
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        try {
            $checkProses = Lending::where('id', $id)->delete();

                return ApiFormatter::sendResponse(200, 'success', 'Data berhasil dihapus!');
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse (400, 'bad request', $err->getMessage());
        }

    }

    public function trash()
    {
        try {
            $getLending = Lending::onlyTrashed()->get();

                return ApiFormatter::sendResponse(200, 'success', $getLending);
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse (400, 'bad request', $err->getMessage());
        }
    }

    public function restore($id)
    {
        try {
            $checkProses = Lending::onlyTrashed()->where('id', $id)->restore();

            if($checkProses) {
                $getLending = Lending::find($id);
                return ApiFormatter::sendResponse(200, 'success', $getLending);
            } else {
                return ApiFormatter::sendResponse(400, 'bad request', 'Gagal mengembalikan data! ');
            }
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse (400, 'bad request', $err->getMessage());
            }
    }

    public function deletePermanent($id)
    {
        try {
            $checkProses = Lending::onlyTrashed()->where('id', $id)->forceDelete();

                return ApiFormatter::sendResponse(200, 'success', 'Berhasil menghapus data secara permanen!');
        } catch (\Exception $err) {
            return ApiFormatter::sendResponse (400, 'bad request', $err->getMessage());
        }
    }
}


