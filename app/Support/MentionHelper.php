<?php

namespace App\Support;

class MentionHelper
{
    /**
     * Escape HTML and highlight @mentions in blue.
     */
    public static function highlight(string $text): string
    {
        $safe = e($text);
        // Match @Name (1-2 words) only - e.g. @Dr.Legon or @Test User, not @Dr. Legon Reply
        return preg_replace(
            '/@([A-Za-z0-9._\'-]+(?:\s+[A-Za-z0-9._\'-]+){0,1})(?=\s|$|[.,!?;:])/u',
            '<span class="mention">@$1</span>',
            $safe
        );
    }
}
