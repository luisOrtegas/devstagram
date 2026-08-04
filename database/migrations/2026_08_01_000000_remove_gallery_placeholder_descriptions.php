<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        DB::table('posts')
            ->where('descripcion', 'Imagen disponible en la galería local de Devstagram.')
            ->update(['descripcion' => '']);
    }

    public function down()
    {
        // La descripción era texto de relleno y no debe restaurarse.
    }
};
