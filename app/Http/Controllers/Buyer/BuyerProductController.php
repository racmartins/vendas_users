<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use App\Http\Controllers\ApiController;
use Illuminate\Database\Query\dd;
use Illuminate\Http\Request;

class BuyerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $products = $buyer->transactions()->with('product') //query que inclui products em transactions
            ->get()
            ->pluck('product'); //permite obter só uma parte da coleção através do indice product
        //dd($products);
        return $this->showAll($products);
    }
}
