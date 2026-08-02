<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('posts')
            ->where('titulo', 'like', 'Galería Devstagram %')
            ->update(['descripcion' => '']);
    }

    public function down()
    {
        // Las descripciones de relleno eliminadas no deben restaurarse.
    }
};
