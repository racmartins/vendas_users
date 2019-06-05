<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerSellerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $sellers = $buyer->transactions()->with('product.seller') //higher loading
                ->get()
                ->pluck('product.seller') //obtemos unicamente os vendedores
                ->unique('id') //para que os vendedores não se repitam
                ->values();
        //dd($sellers);
        return $this->showAll($sellers);
    }
}
