<?php

namespace App\Http\Controllers\Category;

use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class CategoryBuyerController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $buyers = $category->products() //obter produtos
            ->whereHas('transactions')  //assegurar que têm tansações
            ->with('transactions.buyer') //assegurar que são as transações com comprador
            ->get()
            ->pluck('transactions') //obtemos séries transações
            ->collapse() //colapsamos numa série única
            ->pluck('buyer') //obtemos séries compradores
            ->unique() // consideramos elementos distintos
            ->values(); // eliminamos os valores vazios
        return $this->showAll($buyers);
    }
}
