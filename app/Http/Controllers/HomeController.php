<?php

namespace App\Http\Controllers;


use App\Client;
use Illuminate\Support\Facades\DB;

class HomeController extends Controller
{
    //
    public function index() {

        /* $query=DB::insert("insert into клиент(Фамилия,Имя,Отчество,Комментарий,Электр_почта ) values (?,?,?,?,?)",
            ['1','2','3','4','5']);
        var_dump($query); */

        $data = Client::query()->select('Электр_почта','Фамилия','Имя','Отчество',
            'Комментарий')->get();
        // dd($data);

        return view('home');

    }
}
