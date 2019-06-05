<?php

use App\User;
use App\Product;
use App\Category;
use App\Transaction;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;


class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // $this->call(UsersTableSeeder::class);
    	DB::statement('SET FOREIGN_KEY_CHECKS=0');

        User::truncate();
        Category::truncate();
        Product::truncate();
        Transaction::truncate();
        DB::table('category_product')->truncate();

        $quantidade_Utilizadores = 1000;
        $quantidade_Categorias = 30;
        $quantidade_Produtos = 1000;
        $quantidade_Transacoes = 1000;

        factory(User::class,$quantidade_Utilizadores)->create();
        factory(Category::class,$quantidade_Categorias)->create();
        factory(Product::class,$quantidade_Transacoes)->create()->each(
        	function ($produto){
        		$categorias = Category::all()->random(mt_rand(1,5))->pluck('id');
        		$produto->categories()->attach($categorias);
        	}
        );
        factory(Transaction::class,$quantidade_Transacoes)->create();
    }
}
