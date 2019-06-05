<?php
namespace App;

use App\Product;
use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Category extends Model
{
	use SoftDeletes;

	protected $dates=['deleted_at'];
    protected $fillable = [
        'name',
        'description',

    ];

    protected $hidden=['pivot']; //já que implicitamente gera este atributo

    public function products(){
        return $this->belongsToMany(Product::Class);
    }
}
