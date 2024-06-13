<?php

namespace App\Http\Controllers;

use App\Models\Restorations;
use Illuminate\Http\Request;

class RestorationsController extends Controller
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
                'User_id' => 'reuqired',
                'lending_id' => 'reuqired',
                'date_time' => 'reuqired',
                'total_good_stuff' => 'reuqired',
                'total_defec_stuff' => 'reuqired',
            ]);

            $getLending = Lending::where('id', $request->lending_id)->first(); // get data peminjaman yang sesuai dnegan pengembaliannya

            $totalStuff = $request->total_good_stuff + $request->total_defec_stuff; // variabel penampug jumalah barang yang akan dikembalikan

            if ($getLending['total_stuff'] != $total_Stuff) { // pengecekan apakah jumlah barang yang dipinjam jumlahnya sama atau tidak 
                return APIFormatter::sendResponse(400, false, 'The amound of items returned does not match the amound borrowed');
            } else {
                $getStuffStock = stuffStock::where('stuff_id', $getLending['stuff_id'])->first(); // get data stuff yang barangnya sedang dipinjam

                $createRestoration = Restoration::create([
                    'user_id' => $request->User_id,
                    'lendinh_id' => $request->lendinh_id,
                    'date_time' => $request->date_time,
                    'total_good_stuff' => $request->total_good_stuff,
                    'total_defec_stuff' => $request->total_defec_stuff,
                ]);

                $updateStock = $getStuffStock->update([
                    'total_available' => $getStuffStock['total_available'] + $request->total_good_stuff, 
                    'total_defec' => $getStuffStock['total_defec'] + $request->total_defec_stuff,
                ]); // update jumlah barang yang tersedia yang ditambahkan dengan jumlah barang bagus yang dikembalikan dan update jumlah barang yang rusak ditambah dengan jumlah barang rusak yang dikembalikan 

                if ($createRestoration && $updateStock) {
                    return APIFormatter::sendResponse(200, 'Successfully Creat A Restoration Data', $createRestoration);
                }
            } 
        } catch (Exception $e) {
            return APIFormatter::sendResponse(400, false, $e->getMessage());
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Models\Restorations  $restorations
     * @return \Illuminate\Http\Response
     */
    public function show(Restorations $restorations)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  \App\Models\Restorations  $restorations
     * @return \Illuminate\Http\Response
     */
    public function edit(Restorations $restorations)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Models\Restorations  $restorations
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Restorations $restorations)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Models\Restorations  $restorations
     * @return \Illuminate\Http\Response
     */
    public function destroy(Restorations $restorations)
    {
        //
    }
}
