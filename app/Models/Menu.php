<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Menu extends Model
{
    protected $table = 'menu';
    protected $primaryKey = 'id_menu';
    public $timestamps = false;

    protected $fillable = [
        'menu_name',
        'menu_link',
        'icon',
        'parent_id',
        'is_active'
    ];

    public function children()
    {
        return $this->hasMany(Menu::class, 'parent_id', 'id_menu');
    }
}
