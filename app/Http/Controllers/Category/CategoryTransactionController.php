<?php

namespace App\Http\Controllers\Category;

use App\Category;
use App\Http\Controllers\ApiController;
//use Illuminate\Database\Eloquent\Concerns\whereHas;
//use Illuminate\Database\Eloquent\pluck;
use Illuminate\Http\Request;

class CategoryTransactionController extends ApiController
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Category $category)
    {
        $transactions = $category->products()
            ->whereHas('transactions') //queremos apaenas produtos que tenham pelo menos uma transação
            ->with('transactions') // havendo a certeza do método anterior fazemos a carga dos valores na coleção
            ->get()
            ->pluck('transactions') //obtém uma série de coleções
            ->collapse(); //unimos a série numa coleção única
        return $this->showAll($transactions);
    }

}
