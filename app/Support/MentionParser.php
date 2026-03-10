<?php

namespace App\Support;

use App\Models\User;

class MentionParser
{
    /**
     * Parse @Name patterns from text and return matching User models (excluding excludeUserId).
     *
     * @return \Illuminate\Support\Collection<int, User>
     */
    public static function parse(string $body, ?int $excludeUserId = null): \Illuminate\Support\Collection
    {
        if (empty(trim($body))) {
            return collect();
        }

        $names = [];
        if (preg_match_all('/@([A-Za-z0-9._\'-]+(?:\s+[A-Za-z0-9._\'-]+){0,1})(?=\s|$|[.,!?;:])/u', $body, $matches)) {
            $names = array_unique(array_map('trim', $matches[1]));
        }

        if (empty($names)) {
            return collect();
        }

        $query = User::query()->where(function ($q) use ($names) {
            foreach ($names as $name) {
                $q->orWhereRaw('LOWER(name) = LOWER(?)', [trim($name)]);
            }
        });
        if ($excludeUserId) {
            $query->where('id', '!=', $excludeUserId);
        }

        return $query->get();
    }
}
