<?php

use App\Models\Account\Language as LanguageModel;
use App\Models\Geo\City as CityModel;
use App\Models\Geo\Country as CountryModel;
use App\Models\Geo\State as StateModel;
use App\Services\Geo\CityService;
use App\Services\Geo\CountryService;
use App\Services\Geo\StateService;
use Illuminate\Database\Seeder;

class GeoDataSeeder extends Seeder
{
    protected CountryService $countryService;

    protected StateService $stateService;

    protected CityService $cityService;

    /**
     * GeoDataSeeder constructor.
     *
     * @param CountryService $countryService
     * @param StateService   $stateService
     * @param CityService    $cityService
     */
    public function __construct(
        CountryService $countryService,
        StateService $stateService,
        CityService $cityService
    ) {
        $this->countryService = $countryService;

        $this->stateService = $stateService;

        $this->cityService = $cityService;
    }

    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var CountryModel $country */
        /** @var StateModel $state */
        /** @var CityModel $city */

        $country = $this->countryService->createCountry([
            'alpha2'     => 'UA',
            'alpha3'     => 'UKR',
            'name'       => [
                LanguageModel::LANGUAGE_EN => 'Ukraine',
                LanguageModel::LANGUAGE_RU => 'Украина',
                LanguageModel::LANGUAGE_UK => 'Україна',
            ],
            'phone_mask' => '380 (##) ##-##-###',
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Autonomous Republic of Crimea',
                LanguageModel::LANGUAGE_RU => 'Автономная Республика Крым',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Алушта',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Балаклава',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Евпатория',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Инкерман',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Керчь',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Симферополь',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Судак',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Феодосия',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Ялта',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Vinnytsia region',
                LanguageModel::LANGUAGE_RU => 'Винницкая область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Винница',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Volyn region',
                LanguageModel::LANGUAGE_RU => 'Волынская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Луцк',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Нововолынск',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Dnipropetrovsk region',
                LanguageModel::LANGUAGE_RU => 'Днепропетровская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Вольногорск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Днепр / Днепропетровск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Желтые Воды',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Каменское / Днепродзержинск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Кривой Рог',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Марганец',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Першотравенск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Покров / Орджоникидзе',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Терновка',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Donetsk region',
                LanguageModel::LANGUAGE_RU => 'Донецкая область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Авдеевка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Бахмут / Артемовск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Горловка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Дебальцево',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Доброполье',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Докучаевск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Донецк',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Дружковка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Енакиево',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Ждановка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Кировск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Кировское',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Константиновка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Краматорск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Лиман / Красный Лиман',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Макеевка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Мариуполь',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Мирноград / Димитров',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Новогродовка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Покровск / Красноармейск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Селидово',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Славянск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Снежное',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Торез',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Торецк / Дзержинск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Угледар',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Харцызск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Шахтерск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Ясиноватая',
            ],
        ]);


        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Zhytomyr Oblast',
                LanguageModel::LANGUAGE_RU => 'Житомирская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Житомир',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Zakarpattia Oblast',
                LanguageModel::LANGUAGE_RU => 'Закарпатская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Ужгород',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Чоп',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Zaporizhia Oblast',
                LanguageModel::LANGUAGE_RU => 'Запорожская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Запорожье',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Энергодар',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Ivano-Frankivsk region',
                LanguageModel::LANGUAGE_RU => 'Ивано-Франковская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Ивано-Франковск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Яремче',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Kiev region',
                LanguageModel::LANGUAGE_RU => 'Киевская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Ирпень',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Киев',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Славутич',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Kirovograd region',
                LanguageModel::LANGUAGE_RU => 'Кировоградская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Кропивницкий / Кировоград',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Lugansk region',
                LanguageModel::LANGUAGE_RU => 'Луганская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Алчевск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Брянка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Кировск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Лисичанск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Луганск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Первомайск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Ровеньки',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Рубежное',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Северодонецк',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Стаханов',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Хрустальный / Красный Луч',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Lviv region',
                LanguageModel::LANGUAGE_RU => 'Львовская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Борислав',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Львов',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Новый Роздол',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Трускавец',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Червоноград',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Mykolaiv Oblast',
                LanguageModel::LANGUAGE_RU => 'Николаевская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Николаев',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Южноукраинск',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Odessa Oblast',
                LanguageModel::LANGUAGE_RU => 'Одесская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Одесса',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Черноморск / Ильичевск',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Poltava Oblast',
                LanguageModel::LANGUAGE_RU => 'Полтавская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Горишние Плавни / Комсомольск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Кременчуг',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Полтава',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Rivne Oblast',
                LanguageModel::LANGUAGE_RU => 'Ровненская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Вараш / Кузнецовск',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Ровно',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Sumy Oblast',
                LanguageModel::LANGUAGE_RU => 'Сумская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Сумы',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Ternopil Oblast',
                LanguageModel::LANGUAGE_RU => 'Тернопольская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Тернополь',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Kharkiv Oblast',
                LanguageModel::LANGUAGE_RU => 'Харьковская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Изюм',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Люботин',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Харьков',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Kherson Oblast',
                LanguageModel::LANGUAGE_RU => 'Херсонская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Новая Каховка',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Херсон',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Khmelnytskyi Oblast',
                LanguageModel::LANGUAGE_RU => 'Хмельницкая область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Нетешин',
            ],
        ]);


        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Хмельницкий',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Cherkasy Oblast',
                LanguageModel::LANGUAGE_RU => 'Черкасская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Черкассы',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Chernihiv Oblast',
                LanguageModel::LANGUAGE_RU => 'Черниговская область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Чернигов',
            ],
        ]);

        $state = $this->stateService->createState($country, [
            'name' => [
                LanguageModel::LANGUAGE_EN => 'Chernivtsi Oblast',
                LanguageModel::LANGUAGE_RU => 'Черновицкая область',
            ],
        ]);

        $this->cityService->createCity($state, [
            'name' => [
                LanguageModel::LANGUAGE_RU => 'Черновцы',
            ],
        ]);
    }
}
