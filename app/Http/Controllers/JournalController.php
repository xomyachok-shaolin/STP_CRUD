<?php

namespace App\Http\Controllers;

use App\Journal;
use App\Client;
use App\Room;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use Yajra\DataTables\Facades\DataTables;

class JournalController extends Controller
{
    public function index(Request $request)
    {
        $clients = Client::latest()->get();

        $rooms = Room::latest()->get();

        $journals = Journal::join('clients', 'journals.client_id', '=', 'clients.id')->
        join('rooms', 'journals.room_id', '=', 'rooms.id')->select('journals.*', 'clients.mail', 'rooms.number')->get();

        if ($request->ajax()) {

            $data = Journal::join('clients', 'journals.client_id', '=', 'clients.id')->
            join('rooms', 'journals.room_id', '=', 'rooms.id')->select('journals.*', 'clients.mail', 'rooms.number')->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', $data)
                ->make(true);
        }

        return view('journal', compact('clients', 'rooms', 'journals'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        Journal::updateOrCreate(['id' => $request->record_id],
            ['date_income' => $request->date_income, 'client_id' => $request->client_id,
                'room_id' => $request->room_id, 'date_export' => $request->date_export]);

        return response()->json(['success' => 'Record saved successfully.']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $journal = Journal::find($id);
        return response()->json($journal);
    }


    public function destroy($id)
    {
        Journal::find($id)->delete();

        return response()->json(['success' => 'Record deleted successfully.']);
    }
}
