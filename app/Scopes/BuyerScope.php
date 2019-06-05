<?php

namespace App\Scopes;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Scope;
use Illuminate\Database\Eloquent\Builder;



class BuyerScope implements Scope { //implementa uma interface chamada Scope
	// A FUNÇÃO APPLY MODIFICA A CONSULTA TÍPICA DO MODELO E AGREGA TRANSACTION
	public function apply(Builder $builder, Model $model){ //recebe o construtor da consulta e o modelo como tal
		$builder->has('transactions');
	}
}