<?php

namespace App\Support;

use Illuminate\Support\Facades\File;
use Illuminate\Support\Str;
use Throwable;

class Helpers
{
    /**
     * Get current Git commit hash if possible.
     */
    public static function getCurrentGitCommitHash(bool $shortHash = true): ?string
    {
        $hash = null;
        $gitPath = base_path('/.git');

        try {
            $head = Str::after(trim(File::get($gitPath . '/HEAD')), 'ref: ');
            $hash = trim(File::get($gitPath . '/' . $head));
        } catch (Throwable) {
            return null;
        }

        return $shortHash ? Str::take($hash, 7) : $hash;
    }
}
