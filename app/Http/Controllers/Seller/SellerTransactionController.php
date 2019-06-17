<?php

namespace App\Http\Controllers\Seller;

use App\Seller;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;

class SellerTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
         $transactions = $seller->products() //lista de produtos do vendedor
            ->whereHas('transactions') //há apenas interesse nos que estejam em transações
            ->with('transactions')
            ->get()
            ->pluck('transactions')
            ->collapse();
        return $this->showAll($transactions);
    }
}
