<?php

namespace App\Notifications\Traits;

use Illuminate\Support\Facades\Lang;
use Illuminate\Support\Str;

/**
 * @property array $lang
 * @property array $placeholdersMap
 */
trait HasTranslations
{
    protected array $placeholdersMap = [];

    /**
     * Get translated text and replace placeholders with actual content.
     */
    public function lang(string $key): string
    {
        return Str::swap($this->placeholdersMap, $this->lang[Lang::getLocale()][$key]);
    }
}
