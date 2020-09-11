<?php

use App\Models\Account\Language as LanguageModel;
use App\Models\Geo\Location as LocationModel;
use App\Services\Geo\LocationService;
use Illuminate\Database\Seeder;

class GeoDataSeeder extends Seeder
{
    protected LocationService $locationService;

    /**
     * GeoDataSeeder constructor.
     *
     * @param LocationService $locationService
     */
    public function __construct(LocationService $locationService)
    {
        $this->locationService = $locationService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $locations = [
            [
                'type'       => LocationModel::TYPE_COUNTRY,
                'name'       => [
                    LanguageModel::LANGUAGE_EN => 'Ukraine',
                    LanguageModel::LANGUAGE_RU => 'Украина',
                    LanguageModel::LANGUAGE_UK => 'Україна',
                ],
                'parameters' => [
                    'alpha2'     => 'UA',
                    'alpha3'     => 'UKR',
                    'phone_mask' => '380 (##) ##-##-###',
                ],
                'children'   => [
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Autonomous Republic of Crimea',
                            LanguageModel::LANGUAGE_RU => 'Автономная Республика Крым',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Алушта',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Балаклава',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Евпатория',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Инкерман',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Керчь',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Симферополь',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Судак',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Феодосия',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Ялта',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Vinnytsia region',
                            LanguageModel::LANGUAGE_RU => 'Винницкая область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Винница',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Volyn region',
                            LanguageModel::LANGUAGE_RU => 'Волынская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Луцк',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Нововолынск',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Dnipropetrovsk region',
                            LanguageModel::LANGUAGE_RU => 'Днепропетровская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Вольногорск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Днепр / Днепропетровск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Желтые Воды',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Каменское / Днепродзержинск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Кривой Рог',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Марганец',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Першотравенск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Покров / Орджоникидзе',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Терновка',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Donetsk region',
                            LanguageModel::LANGUAGE_RU => 'Донецкая область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Авдеевка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Бахмут / Артемовск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Горловка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Дебальцево',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Доброполье',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Докучаевск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Донецк',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Дружковка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Енакиево',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Ждановка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Кировск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Кировское',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Константиновка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Краматорск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Лиман / Красный Лиман',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Макеевка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Мариуполь',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Мирноград / Димитров',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Новогродовка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Покровск / Красноармейск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Селидово',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Славянск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Снежное',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Торез',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Торецк / Дзержинск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Угледар',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Харцызск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Шахтерск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Ясиноватая',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Zhytomyr Oblast',
                            LanguageModel::LANGUAGE_RU => 'Житомирская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Житомир',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Zakarpattia Oblast',
                            LanguageModel::LANGUAGE_RU => 'Закарпатская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Ужгород',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Чоп',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Zaporizhia Oblast',
                            LanguageModel::LANGUAGE_RU => 'Запорожская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Запорожье',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Энергодар',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Ivano-Frankivsk region',
                            LanguageModel::LANGUAGE_RU => 'Ивано-Франковская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Ивано-Франковск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Яремче',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Kiev region',
                            LanguageModel::LANGUAGE_RU => 'Киевская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    'name' => [
                                        LanguageModel::LANGUAGE_RU => 'Ирпень',
                                    ],
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    'name' => [
                                        LanguageModel::LANGUAGE_RU => 'Киев',
                                    ],
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    'name' => [
                                        LanguageModel::LANGUAGE_RU => 'Славутич',
                                    ],
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Kirovograd region',
                            LanguageModel::LANGUAGE_RU => 'Кировоградская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Кропивницкий / Кировоград',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Lugansk region',
                            LanguageModel::LANGUAGE_RU => 'Луганская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Алчевск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Брянка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Кировск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Лисичанск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Луганск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Первомайск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Ровеньки',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Рубежное',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Северодонецк',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Стаханов',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Хрустальный / Красный Луч',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Lviv region',
                            LanguageModel::LANGUAGE_RU => 'Львовская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Борислав',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Львов',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Новый Роздол',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Трускавец',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Червоноград',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Mykolaiv Oblast',
                            LanguageModel::LANGUAGE_RU => 'Николаевская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Николаев',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Южноукраинск',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Odessa Oblast',
                            LanguageModel::LANGUAGE_RU => 'Одесская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Одесса',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Черноморск / Ильичевск',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Poltava Oblast',
                            LanguageModel::LANGUAGE_RU => 'Полтавская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Горишние Плавни / Комсомольск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Кременчуг',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Полтава',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Rivne Oblast',
                            LanguageModel::LANGUAGE_RU => 'Ровненская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Вараш / Кузнецовск',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Ровно',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Sumy Oblast',
                            LanguageModel::LANGUAGE_RU => 'Сумская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Сумы',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Ternopil Oblast',
                            LanguageModel::LANGUAGE_RU => 'Тернопольская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Тернополь',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Kharkiv Oblast',
                            LanguageModel::LANGUAGE_RU => 'Харьковская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Изюм',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Люботин',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Харьков',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Kherson Oblast',
                            LanguageModel::LANGUAGE_RU => 'Херсонская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Новая Каховка',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Херсон',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Khmelnytskyi Oblast',
                            LanguageModel::LANGUAGE_RU => 'Хмельницкая область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Нетешин',
                                ],
                            ],
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Хмельницкий',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Cherkasy Oblast',
                            LanguageModel::LANGUAGE_RU => 'Черкасская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Черкассы',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Chernihiv Oblast',
                            LanguageModel::LANGUAGE_RU => 'Черниговская область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Чернигов',
                                ],
                            ],
                        ],
                    ],
                    [
                        'type'     => LocationModel::TYPE_STATE,
                        'name'     => [
                            LanguageModel::LANGUAGE_EN => 'Chernivtsi Oblast',
                            LanguageModel::LANGUAGE_RU => 'Черновицкая область',
                        ],
                        'children' => [
                            [
                                'type' => LocationModel::TYPE_CITY,
                                'name' => [
                                    LanguageModel::LANGUAGE_RU => 'Черновцы',
                                ],
                            ],
                        ],
                    ],
                ],
            ],
        ];

        $this->locationService->createTreeLocations($locations);
    }
}
