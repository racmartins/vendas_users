<?php
namespace App\Http\Controllers\Product;
use App\Product;
use App\Category;
use Illuminate\Http\Request;
use App\Http\Controllers\ApiController;
class ProductCategoryController extends ApiController
{

    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Product $product)
    {
        $categories = $product->categories;
        return $this->showAll($categories);
    }
    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, Product $product, Category $category)
    {
        //sync, attach, syncWithoutDetaching
        $product->categories()->syncWithoutDetaching([$category->id]); //adiciona a nova categoria sem eliminar as anteriores
        return $this->showAll($product->categories);
    }
    /**
     * Remove the specified resource from storage.
     *
     * @param  \App\Product  $product
     * @return \Illuminate\Http\Response
     */
    public function destroy(Product $product, Category $category)
    {
        if (!$product->categories()->find($category->id)) {
            return $this->errorResponse('A categoria especificada não é una categoria para este produto', 404);
        }
        $product->categories()->detach([$category->id]); //para eliminar a categoria
        return $this->showAll($product->categories);
    }
}