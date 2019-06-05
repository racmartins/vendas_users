<?php

namespace App;

use App\Scopes\BuyerScope;
use App\Transaction;
use Illuminate\Database\Eloquent\Concerns\addGlobalScope;
use Illuminate\Database\Eloquent\Model;

class Buyer extends User
{
	protected static function boot(){//utilizado normalmente para construír, inicializar
		parent::boot();
		static::addGlobalScope(new BuyerScope);
	}
    public function transactions(){
        return $this->hasMany(Transaction::Class);
    }
}
