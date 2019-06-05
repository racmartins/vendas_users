<?php

namespace App;

use App\Product;

use App\Scopes\SellerScope;

use Illuminate\Database\Eloquent\Concerns\addGlobalScope;
use Illuminate\Database\Eloquent\Model;

class Seller extends User
{
	protected static function boot(){//utilizado normalmente para construír, inicializar
		parent::boot();
		static::addGlobalScope(new SellerScope);
	}
    public function products(){
        return $this->hasMany(Product::class);
    }
}
