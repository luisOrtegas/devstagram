<?php

namespace App\Http\Controllers;

use App\Models\Post;
use App\Models\User;
use Illuminate\Http\RedirectResponse;
use Illuminate\Notifications\DatabaseNotification;

class NotificationController extends Controller
{
    public function show(DatabaseNotification $notification): RedirectResponse
    {
        abort_unless(
            $notification->notifiable_type === User::class
            && (int) $notification->notifiable_id === auth()->id(),
            403
        );

        $notification->markAsRead();

        $post = Post::with('user')->find($notification->data['post_id'] ?? null);

        if (! $post) {
            return redirect()
                ->route('home')
                ->with('mensaje', 'La publicación de esta notificación ya no está disponible.');
        }

        return redirect()->route('posts.show', [
            'user' => $post->user,
            'post' => $post,
        ]);
    }

    public function markAllAsRead(): RedirectResponse
    {
        auth()->user()->unreadNotifications->markAsRead();

        return back()->with('mensaje', 'Todas las notificaciones fueron marcadas como leídas.');
    }
}
