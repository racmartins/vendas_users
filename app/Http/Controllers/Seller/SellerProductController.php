<?php

namespace App\Http\Controllers\Seller;

use App\Http\Controllers\ApiController;
use App\Product;
use App\Seller;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Storage;

class SellerProductController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Seller $seller)
    {
            $products = $seller->products;
            return $this->showAll($products);
    }
    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request, Seller $seller, Product $product)
    {
       $regras = [
            'name' => 'required',
            'description' => 'required',
            'quantity' => 'required|integer|min:1',
            'image' => 'required|image',
        ];
        $this->validate($request, $regras);
        $data = $request->all();
        $data['status'] = Product::PRODUTO_NAO_DISPONIVEL;
        $data['image'] = $request->image->store('');
        $data['seller_id'] = $seller->id;
        $product = Product::create($data);
        return $this->showOne($product, 201);
    }

    /**
     * Display the specified resource.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */


    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Seller $seller, Product $product)
    {
        $regras = [
            'quantity' => 'integer|min:1',
            'status' => 'in: ' . Product::PRODUTO_DISPONIVEL . ',' . Product::PRODUTO_NAO_DISPONIVEL,
            'image' => 'image',
        ];
        $this->validate($request, $regras);
        $this->verificarVendedor($seller, $product);
        $product->fill($request->only([
            'name',
            'description',
            'quantity',
        ]));
        if ($request->has('status')) {
            $product->status = $request->status;
            if ($product->esta_Disponivel() && $product->categories()->count() == 0) {
                return $this->errorResponse('Um produto ativo deve ter pelo menos uma categoria', 409);
            }
        }
        if ($request->hasFile('image')) {
            Storage::delete($product->image);
            $product->image = $request->image->store('');
        }
        if ($product->isClean()) {
            return $this->errorResponse('Deve especificar-se pelo menos um valor diferenet para atualizar', 422);
        }
        $product->save();
        return $this->showOne($product);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Seller  $seller
     * @return \Illuminate\Http\Response
     */
    public function destroy(Seller $seller, Product $product)
    {
        $this->verificarVendedor($seller, $product);
        Storage::delete($product->image);
        $product->delete();
        return $this->showOne($product);
    }
    protected function verificarVendedor(Seller $seller, Product $product)
    {
        if ($seller->id != $product->seller_id) {
            throw new HttpException(422,"O vendedor especificado não é o vendedor real do produto");
        }
    }
}
