<?php

namespace Database\Seeders;

use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $password = Hash::make('Devstagram2026!');

        $profiles = [
            ['name' => 'Ana Torres', 'username' => 'ana.tech', 'email' => 'ana@devstagram.local'],
            ['name' => 'Carlos Mendoza', 'username' => 'carlos.dev', 'email' => 'carlos@devstagram.local'],
            ['name' => 'Sofía Ramírez', 'username' => 'sofia.design', 'email' => 'sofia@devstagram.local'],
            ['name' => 'Miguel Hernández', 'username' => 'miguel.foto', 'email' => 'miguel@devstagram.local'],
            ['name' => 'Laura Castillo', 'username' => 'laura.code', 'email' => 'laura@devstagram.local'],
        ];

        $users = collect($profiles)->map(function ($profile) use ($password) {
            return User::updateOrCreate(
                ['email' => $profile['email']],
                array_merge($profile, ['password' => $password])
            );
        });

        $images = [
            '1c36db64-3f3a-4d91-be16-ff9701a7255c.jpg',
            'f3284728-79d2-44a2-863f-70812e47f1e6.jpg',
            'fc52748d-ca10-4d46-8eca-2fe414493a58.jpg',
            '58ec458d-19f1-4da9-a14b-827d4e8cc091.jpg',
            '92bd641a-1513-4ea1-997c-fa2eaaada94b.jpg',
            '5db3851c-9533-4186-afd8-06e638d4c557.jpg',
            '0c130619-d317-469f-bc3b-321aa097ac3e.jpg',
            'eaf53bca-9872-46e9-b3e9-6a178e4c590a.jpg',
            '16517a92-28fc-43a8-a87f-6ae620152744.jpg',
            '7c3ba457-4640-43be-b454-213e3180faa4.jpg',
        ];

        $titles = [
            'Mi espacio de trabajo',
            'Aprendiendo algo nuevo',
            'Diseño para el próximo proyecto',
            'Una tarde de fotografía',
            'Código, café y buenas ideas',
            'Explorando la ciudad',
            'Prototipo terminado',
            'Detalles que inspiran',
            'Preparando una nueva publicación',
            'Resultados de esta semana',
        ];

        foreach ($users as $userIndex => $user) {
            for ($postIndex = 0; $postIndex < 2; $postIndex++) {
                $index = ($userIndex * 2) + $postIndex;

                Post::updateOrCreate(
                    [
                        'user_id' => $user->id,
                        'titulo' => $titles[$index],
                    ],
                    [
                        'descripcion' => 'Publicación de prueba para verificar perfiles, búsqueda, seguimiento y feed.',
                        'imagen' => $images[$index],
                    ]
                );
            }
        }

        foreach ($users as $index => $user) {
            $nextUser = $users[($index + 1) % $users->count()];
            $secondUser = $users[($index + 2) % $users->count()];

            $user->followers()->syncWithoutDetaching([
                $nextUser->id,
                $secondUser->id,
            ]);
        }
    }
}
