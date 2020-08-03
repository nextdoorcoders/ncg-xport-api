<?php

use App\Models\Account\Language as LanguageModel;
use App\Models\Geo\City as CityModel;
use App\Models\Geo\Country as CountryModel;
use App\Models\Geo\State as StateModel;
use Illuminate\Database\Seeder;

class GeoDataSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        /** @var CountryModel $country */
        /** @var StateModel $state */

        $country = CountryModel::query()
            ->create([
                'alpha2'     => 'UA',
                'alpha3'     => 'UKR',
                'name'       => [
                    LanguageModel::LANGUAGE_EN => 'Ukraine',
                    LanguageModel::LANGUAGE_ES => 'Ucrania',
                    LanguageModel::LANGUAGE_RU => 'Украина',
                    LanguageModel::LANGUAGE_PT => 'Ucrânia',
                    LanguageModel::LANGUAGE_FR => 'Ukraine',
                    LanguageModel::LANGUAGE_DE => 'Ukraine',
                    LanguageModel::LANGUAGE_IT => 'Ucraina',
                    LanguageModel::LANGUAGE_UK => 'Україна',
                ],
                'phone_mask' => '380 (##) ##-##-###',
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Autonomous Republic of Crimea',
                    LanguageModel::LANGUAGE_RU => 'Автономная Республика Крым',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Алушта',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Балаклава',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Евпатория',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Инкерман',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Керчь',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Симферополь',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Судак',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Феодосия',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Ялта',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Vinnytsia region',
                    LanguageModel::LANGUAGE_RU => 'Винницкая область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Винница',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Volyn region',
                    LanguageModel::LANGUAGE_RU => 'Волынская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Луцк',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Нововолынск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Dnipropetrovsk region',
                    LanguageModel::LANGUAGE_RU => 'Днепропетровская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Вольногорск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Днепр / Днепропетровск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Желтые Воды',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Каменское / Днепродзержинск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Кривой Рог',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Марганец',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Першотравенск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Покров / Орджоникидзе',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Терновка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Donetsk region',
                    LanguageModel::LANGUAGE_RU => 'Донецкая область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Авдеевка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Бахмут / Артемовск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Горловка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Дебальцево',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Доброполье',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Докучаевск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Донецк',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Дружковка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Енакиево',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Ждановка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Кировск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Кировское',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Константиновка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Краматорск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Лиман / Красный Лиман',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Макеевка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Мариуполь',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Мирноград / Димитров',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Новогродовка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Покровск / Красноармейск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Селидово',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Славянск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Снежное',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Торез',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Торецк / Дзержинск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Угледар',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Харцызск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Шахтерск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Ясиноватая',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);


        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Zhytomyr Oblast',
                    LanguageModel::LANGUAGE_RU => 'Житомирская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Житомир',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Zakarpattia Oblast',
                    LanguageModel::LANGUAGE_RU => 'Закарпатская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Ужгород',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Чоп',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Zaporizhia Oblast',
                    LanguageModel::LANGUAGE_RU => 'Запорожская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Запорожье',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Энергодар',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Ivano-Frankivsk region',
                    LanguageModel::LANGUAGE_RU => 'Ивано-Франковская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Ивано-Франковск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Яремче',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Kiev region',
                    LanguageModel::LANGUAGE_RU => 'Киевская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Ирпень',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Славутич',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Kirovograd region',
                    LanguageModel::LANGUAGE_RU => 'Кировоградская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Кропивницкий / Кировоград',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Lugansk region',
                    LanguageModel::LANGUAGE_RU => 'Луганская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Алчевск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Брянка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Кировск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Лисичанск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Луганск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Первомайск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Ровеньки',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Рубежное',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Северодонецк',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Стаханов',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Хрустальный / Красный Луч',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Lviv region',
                    LanguageModel::LANGUAGE_RU => 'Львовская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Борислав',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Львов',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Новый Роздол',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Трускавец',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Червоноград',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Mykolaiv Oblast',
                    LanguageModel::LANGUAGE_RU => 'Николаевская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Николаев',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Южноукраинск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Odessa Oblast',
                    LanguageModel::LANGUAGE_RU => 'Одесская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Одесса',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Черноморск / Ильичевск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Poltava Oblast',
                    LanguageModel::LANGUAGE_RU => 'Полтавская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Горишние Плавни / Комсомольск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Кременчуг',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Полтава',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Rivne Oblast',
                    LanguageModel::LANGUAGE_RU => 'Ровненская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Вараш / Кузнецовск',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Ровно',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Sumy Oblast',
                    LanguageModel::LANGUAGE_RU => 'Сумская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Сумы',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Ternopil Oblast',
                    LanguageModel::LANGUAGE_RU => 'Тернопольская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Тернополь',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Kharkiv Oblast',
                    LanguageModel::LANGUAGE_RU => 'Харьковская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Изюм',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Люботин',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Харьков',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Kherson Oblast',
                    LanguageModel::LANGUAGE_RU => 'Херсонская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Новая Каховка',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Херсон',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Khmelnytskyi Oblast',
                    LanguageModel::LANGUAGE_RU => 'Хмельницкая область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Нетешин',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);


        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Хмельницкий',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Cherkasy Oblast',
                    LanguageModel::LANGUAGE_RU => 'Черкасская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Черкассы',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Chernihiv Oblast',
                    LanguageModel::LANGUAGE_RU => 'Черниговская область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Чернигов',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);

        $state = $country->states()
            ->create([
                'name' => [
                    LanguageModel::LANGUAGE_EN => 'Chernivtsi Oblast',
                    LanguageModel::LANGUAGE_RU => 'Черновицкая область',
                ],
            ]);

        $state->cities()
            ->create([
                'country_id' => $country->id,
                'name'       => [
                    LanguageModel::LANGUAGE_RU => 'Черновцы',
                ],
                'type'       => CityModel::TYPE_CITY,
            ]);
    }
}
