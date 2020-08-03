<?php

namespace App\Models\Account;

use App\Models\Traits\UuidTrait;
use Illuminate\Database\Eloquent\Model;

class Language extends Model
{
    const LANGUAGE_EN = 'en';
    const LANGUAGE_ES = 'es';
    const LANGUAGE_RU = 'ru';
    const LANGUAGE_PT = 'pt';
    const LANGUAGE_FR = 'fr';
    const LANGUAGE_DE = 'de';
    const LANGUAGE_IT = 'it';
    const LANGUAGE_UK = 'uk';

    const LANGUAGES = [
        self::LANGUAGE_EN,
        self::LANGUAGE_ES,
        self::LANGUAGE_RU,
        self::LANGUAGE_PT,
        self::LANGUAGE_FR,
        self::LANGUAGE_DE,
        self::LANGUAGE_IT,
        self::LANGUAGE_UK,
    ];

    use UuidTrait;

    protected $table = 'account_languages';
}
