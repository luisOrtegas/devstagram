<?php

namespace Database\Seeders;

use App\Models\Comentario;
use App\Models\Post;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\File;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;

class DemoDataSeeder extends Seeder
{
    public function run()
    {
        $configuredPassword = config('devstagram.demo_password');
        $plainPassword = $configuredPassword ?: Str::random(20);
        $password = Hash::make($plainPassword);

        if (! $configuredPassword && $this->command) {
            $this->command->warn("Contraseña temporal de los perfiles demo: {$plainPassword}");
            $this->command->warn('Guárdala ahora: no se almacena en texto plano.');
        }

        $profiles = [
            ['name' => 'Ana Torres', 'username' => 'ana.tech', 'email' => 'ana@devstagram.local'],
            ['name' => 'Carlos Mendoza', 'username' => 'carlos.dev', 'email' => 'carlos@devstagram.local'],
            ['name' => 'Sofía Ramírez', 'username' => 'sofia.design', 'email' => 'sofia@devstagram.local'],
            ['name' => 'Miguel Hernández', 'username' => 'miguel.foto', 'email' => 'miguel@devstagram.local'],
            ['name' => 'Laura Castillo', 'username' => 'laura.code', 'email' => 'laura@devstagram.local'],
            ['name' => 'Diego Navarro', 'username' => 'diego.web', 'email' => 'diego@devstagram.local'],
            ['name' => 'Valeria Cruz', 'username' => 'valeria.art', 'email' => 'valeria@devstagram.local'],
            ['name' => 'Fernando Ruiz', 'username' => 'fernando.dev', 'email' => 'fernando@devstagram.local'],
            ['name' => 'Camila Vargas', 'username' => 'camila.media', 'email' => 'camila@devstagram.local'],
            ['name' => 'Ricardo Flores', 'username' => 'ricardo.tech', 'email' => 'ricardo@devstagram.local'],
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

        foreach ($users->take(5) as $userIndex => $user) {
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

        $availableImages = collect(File::files(public_path('uploads')))
            ->filter(function ($file) {
                return in_array(strtolower($file->getExtension()), ['jpg', 'jpeg', 'png', 'gif', 'webp']);
            })
            ->sortBy(function ($file) {
                return $file->getFilename();
            })
            ->values();

        Post::where('titulo', 'like', 'Galería Devstagram %')
            ->update(['descripcion' => '']);

        foreach ($availableImages as $imageIndex => $file) {
            $user = $users[$imageIndex % $users->count()];
            $filename = $file->getFilename();

            Post::firstOrCreate(
                ['imagen' => $filename],
                [
                    'user_id' => $user->id,
                    'titulo' => 'Galería Devstagram ' . ($imageIndex + 1),
                    'descripcion' => '',
                ]
            );
        }

        foreach ($users as $index => $user) {
            $nextUser = $users[($index + 1) % $users->count()];
            $secondUser = $users[($index + 2) % $users->count()];

            $user->followers()->syncWithoutDetaching([
                $nextUser->id,
                $secondUser->id,
            ]);
        }

        $comments = [
            '¡Excelente publicación! Gracias por compartirla.',
            'Me gustó mucho esta idea, sigue así.',
            'El resultado quedó increíble.',
            'Muy buen trabajo, espero ver más publicaciones.',
            'Interesante proyecto, gracias por mostrar el proceso.',
        ];

        $availablePosts = Post::whereIn('user_id', $users->pluck('id'))
            ->orderBy('id')
            ->get()
            ->values();

        foreach ($users as $userIndex => $user) {
            foreach ($comments as $commentIndex => $comment) {
                $postIndex = ($userIndex * count($comments) + $commentIndex) % $availablePosts->count();
                $post = $availablePosts[$postIndex];

                Comentario::updateOrCreate([
                    'user_id' => $user->id,
                    'post_id' => $post->id,
                    'comentario' => $comment,
                ]);
            }
        }
    }
}
