<?php

namespace App\Support;

use App\Models\User;
use Illuminate\Support\HtmlString;

class MentionFormatter
{
    private const PATTERN = '/(?<![A-Za-z0-9._%+\-])@([A-Za-z0-9._\-]{3,20})/u';

    public static function usernames(?string $text): array
    {
        if (! $text || ! preg_match_all(self::PATTERN, $text, $matches)) {
            return [];
        }

        return collect($matches[1])
            ->map(fn ($username) => strtolower($username))
            ->unique()
            ->values()
            ->all();
    }

    public static function toHtml(?string $text): HtmlString
    {
        if (! $text) {
            return new HtmlString('');
        }

        $usernames = self::usernames($text);
        $users = User::query()
            ->whereIn('username', $usernames)
            ->get(['username'])
            ->keyBy(fn ($user) => strtolower($user->username));

        $escaped = e($text);
        $html = preg_replace_callback(self::PATTERN, function ($match) use ($users) {
            $user = $users->get(strtolower($match[1]));

            if (! $user) {
                return $match[0];
            }

            $url = e(route('posts.index', $user->username));
            $username = e($user->username);

            return '<a href="' . $url . '" class="font-bold text-sky-700 hover:underline">@' . $username . '</a>';
        }, $escaped);

        return new HtmlString($html ?? $escaped);
    }
}
