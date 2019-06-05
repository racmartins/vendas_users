<?php

namespace App\Http\Controllers\Buyer;

use App\Buyer;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class BuyerCategoryController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Buyer $buyer)
    {
        $categories = $buyer->transactions()->with('product.categories') //higher loading
                ->get()
                ->pluck('product.categories') //obtemos unicamente os vendedores
                ->collapse() //junta uma série de listas numa lista única
                ->unique('id') //para que os vendedores não se repitam
                ->values();
        //dd($categories);
        return $this->showAll($categories);
    }
}
