<?php

namespace App\Http\Controllers;

use App\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DataTables;

class RoomController extends Controller
{

    public function index(Request $request)
    {

        $rooms = Room::latest()->get();

        if ($request->ajax()) {
            $data = Room::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', $data)
                ->make(true);
        }

        return view('room', compact('rooms'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        Room::updateOrCreate(['id' => $request->room_id],
            ['number' => $request->number, 'capacity' => $request->capacity,
                'comfortable' => $request->comfortable, 'price' => $request->price]);

        return response()->json(['success' => 'Room saved successfully.']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $room = Room::find($id);
        return response()->json($room);
    }


    public function destroy($id)
    {
        Room::find($id)->delete();

        return response()->json(['success' => 'Room deleted successfully.']);
    }
}
