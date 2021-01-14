<?php

namespace App\Http\Controllers;

use App\Client;
use Illuminate\Http\JsonResponse;
use Illuminate\Http\Request;
use DataTables;

class ClientController extends Controller
{

    public function index(Request $request)
    {

        $clients = Client::latest()->get();

        if ($request->ajax()) {
            $data = Client::latest()->get();

            return Datatables::of($data)
                ->addIndexColumn()
                ->addColumn('action', $data)
                ->make(true);
        }

        return view('client', compact('clients'));
    }

    /**
     * @param Request $request
     * @return JsonResponse
     */
    public function store(Request $request)
    {
        Client::updateOrCreate(['id' => $request->client_id],
            ['surname' => $request->surname, 'name' => $request->name,
                'lastname' => $request->lastname, 'mail' => $request->mail,
                'comment' => $request->comment]);

        return response()->json(['success' => 'Client saved successfully.']);
    }

    /**
     * @param $id
     * @return JsonResponse
     */
    public function edit($id)
    {
        $client = Client::find($id);
        return response()->json($client);
    }


    public function destroy($id)
    {
        Client::find($id)->delete();

        return response()->json(['success' => 'Product deleted successfully.']);
    }
}
