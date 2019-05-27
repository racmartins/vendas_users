<?php

namespace App;

use App\Seller;
use App\Transaction;
use App\Category;

use Illuminate\Database\Eloquent\Model;

class Product extends Model
{
    const PRODUTO_DISPONIVEL='disponivel';
    const PRODUTO_NAO_DISPONIVEL = 'não disponível';

    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status', //produto disponível ou não disponível
        'image',
        'seller_id',
    ];

    public function esta_Disponivel(){
        return $this->status == Product::PRODUTO_DISPONIVEL;
    }

    public function seller(){
        return $this->belongsTo(Seller::class);
    }

    public function transactions(){
        return $this->hasMany(Transaction::class);
    }

    public function categories(){
        return $this->belongsToMany(Category::class);
    }
}
