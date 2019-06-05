<?php

namespace App;

use App\Category;
use App\Seller;
use App\Transaction;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Product extends Model
{
    use SoftDeletes;

    const PRODUTO_DISPONIVEL='disponivel';
    const PRODUTO_NAO_DISPONIVEL = 'não disponível';

    protected $dates=['deleted_at'];
    protected $fillable = [
        'name',
        'description',
        'quantity',
        'status', //produto disponível ou não disponível
        'image',
        'seller_id',
    ];
    protected $hidden=['pivot']; //já que implicitamente gera este atributo

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
