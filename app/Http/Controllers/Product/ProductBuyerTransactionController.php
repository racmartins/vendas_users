<?php

namespace App\Http\Controllers\Product;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Transaction;
use App\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;

class ProductBuyerTransactionController extends ApiController
{
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Product $product, User $buyer)
    {
             $regras = [
            'quantity' => 'required|integer|min:1',
        ];
        $this->validate($request, $regras);
        if ($buyer->id == $product->seller_id) {
            return $this->errorResponse('O comprador deve ser diferente do vendedor', 409);
        }
        if (!$buyer->utilizador_Verificado()) {
            return $this->errorResponse('O comprador deve ser um utilizador verificado', 409);
        }
        if (!$product->seller->utilizador_Verificado()) {
            return $this->errorResponse('O vendedor deve ser um utilizador verificado', 409);
        }
        if (!$product->esta_Disponivel()) {
            return $this->errorResponse('O produto para esta transação não está disponível', 409);
        }
        if ($product->quantity < $request->quantity) {
            return $this->errorResponse('O produto não tem a quantidade disponível requirida para esta transação', 409);
        }
        return DB::transaction(function () use ($request, $product, $buyer) {
            $product->quantity -= $request->quantity;
            $product->save();
            $transaction = Transaction::create([
                'quantity' => $request->quantity,
                'buyer_id' => $buyer->id,
                'product_id' => $product->id,
            ]);
            return $this->showOne($transaction, 201);
        });
    }


}
