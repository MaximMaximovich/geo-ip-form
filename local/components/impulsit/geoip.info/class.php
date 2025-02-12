<?php

use Bitrix\Main\Loader;
use Bitrix\Main\Component\ParameterSigner;
use Bitrix\Main\Engine\Contract\Controllerable;
use Bitrix\Main\Engine\ActionFilter;
use \Bitrix\Main\Web\HttpClient;
use Bitrix\Highloadblock as HL;
use Bitrix\Main\Mail\Event;


/**
 * GeoIpInfo - Класс компонента формы GEOIP
 */
class GeoIpInfo extends \CBitrixComponent implements Controllerable
{
    /**
     *  Переменная в которой храниться название классса таблицы HL блока
     *
     * @var string
     */
    private string $entityDataClass = '';

    /**
     *  Метод для подготовки параметров
     *
     * @param $arParams
     * @return array
     */
    public function onPrepareComponentParams($arParams): array
    {
        if (!isset($arParams['HLBLOCK_ID'])) {
            $arParams['HLBLOCK_ID'] = 0;
        } else {
            $arParams['HLBLOCK_ID'] = (int)$arParams['HLBLOCK_ID'];
        }

        if (empty($arParams['IPSTACK_ACCESS_KEY'])) {
            $arParams['IPSTACK_ACCESS_KEY'] = '';
        }

        $arParams['MAIL_EVENT_NAME'] = trim($arParams['MAIL_EVENT_NAME']);
        $arParams['MAIL_TEMPLATE_ID'] = (int)$arParams['MAIL_MESSAGE_ID'];

        return $arParams;
    }

    /**
     *  Метод для выполнения действий компонета на хите
     *
     * @return void
     */
    public function executeComponent(): void
    {
        $this->signAdditionalsParams();
        $this->IncludeComponentTemplate();
    }

    /**
     *  Подписка дополнительных параметров
     *
     * @return void
     * @throws \Bitrix\Main\ArgumentTypeException
     */
    protected function signAdditionalsParams(): void
    {
        $this->initComponentTemplate();

        // поле 'HIDDEN' для идентификации компонента
        $this->arResult['HIDDEN'] = md5(implode('', $this->arParams) . __CLASS__);

        $additionalParams = [
            'templateFile' => 'form_result',
            'templateFolder' => $this->getTemplate()->GetFolder(),
            'hidden' => $this->arResult['HIDDEN']
        ];

        $this->arResult['additionalSignedParams'] = \Bitrix\Main\Component\ParameterSigner::signParameters(
            $this->getName() . '_additional',
            $additionalParams
        );
    }

    /**
     *  Возвращает список основных параметров компонента
     *
     * @return string[]
     */
    protected function listKeysSignedParameters(): array
    {
        return ['HLBLOCK_ID', 'IPSTACK_ACCESS_KEY', 'MAIL_MESSAGE_ID', 'MAIL_EVENT_NAME'];
    }

    /**
     *   Конфигурация префильтров для обработчиков Action
     *
     * @return array[]
     */
    public function configureActions(): array
    {
        return [
            'ajaxGetData' => [
                '-prefilters' => [
                    ActionFilter\Authentication::class,
                ],
                '+prefilters' => [
                    new ActionFilter\Httpmethod([ActionFilter\Httpmethod::METHOD_POST]),
                ],
            ]
        ];
    }

    /**
     *   Обработчик Ajax запроса формы
     *
     * @param $arFormData
     * @param $addiSigned
     * @return false|string
     * @throws \Bitrix\Main\ArgumentTypeException
     * @throws \Bitrix\Main\Security\Sign\BadSignatureException
     */
    public function ajaxGetDataAction($arFormData, $addiSigned): false|string
    {

        $addiUnsigned = ParameterSigner::unsignParameters(
            $this->getName() . '_additional',
            $addiSigned
        );

        $arPostData = [];
        if (!empty($arFormData)) {
            foreach ($arFormData as $item) {
                $arPostData[$item['name']] = $item['value'];
            }
        }

        // Антиспам
        if ($addiUnsigned['hidden'] !== $arPostData['mode']) {
            return false;
        }

        // Валидация IP на сервере
        if (empty($arPostData['ip_address']) || (filter_var($arPostData['ip_address'], FILTER_VALIDATE_IP) === false)) {
            return false;
        }

        if (empty($arPostData['geo_service'])) {
            return false;
        }

        if (empty($this->arParams['HLBLOCK_ID'])) {
            return false;
        }

        $this->arResult['LABELS'] = $this->getHlBlockLables($this->arParams['HLBLOCK_ID']);
        $this->arResult['DATA'] = $this->getInfo(
            $arPostData['ip_address'],
            $arPostData['geo_service'],
            $this->arParams['HLBLOCK_ID'],

        );

        ob_start();

        $this->includeComponentTemplate(
            $addiUnsigned['templateFile'],
            $addiUnsigned['templateFolder']
        );

        return ob_get_clean();
    }


    /**
     *  Метод получения данных результата запроса
     *
     * @param string $ip
     * @param string $service
     * @param int $hlBlockId
     * @return array
     */
    public function getInfo(string $ip, string $service, int $hlBlockId): array
    {
        $ipInfo = [];

        $this->initEntityDataClass($hlBlockId);

        $ipInfo = $this->getGeoIpRecord($ip);

        if ($ipInfo) {
            return $ipInfo;
        }

        if ($service == 'sypexgeo.net') {
            $ipInfo = $this->getInfoFromSypexgeo($ip);
        } else if ($service == 'ipstack.com') {
            $ipInfo = $this->getInfoFromIpstack($ip);
        }

        if (!empty($ipInfo)) {

            $this->addGeoIpRecord($ipInfo);
        }

        return $ipInfo;
    }


    /**
     *  Инициализация переменной класса HL блока
     * @param int $hlBlockId
     * @return string
     */
    private function initEntityDataClass(int $hlBlockId): string
    {
        if (!empty($this->entityDataClass)) {
            return $this->entityDataClass;
        }

        try {
            Loader::includeModule('highloadblock');

            $hlblock = HL\HighloadBlockTable::getById($hlBlockId)->fetch();
            $entity = HL\HighloadBlockTable::compileEntity($hlblock);
            $this->entityDataClass = (string)$entity->getDataClass();

        } catch (Exception $exception) {

            $arCFields = [
                'ERROR_TYPE' => 'GEOIP_COMPONENT::initEntityDataClass',
                'ERROR_INFO' => $exception->getMessage()
            ];

            $this->eventSend($arCFields);

        }

        return $this->entityDataClass;
    }

    /**
     *  Получение массива названий полей HL блока
     *
     * @param int $hlBlockId
     * @return array
     * @throws \Bitrix\Main\ArgumentException
     * @throws \Bitrix\Main\ObjectPropertyException
     * @throws \Bitrix\Main\SystemException
     */
    private function getHlBlockLables(int $hlBlockId): array
    {
        if (empty($hlBlockId)) {
            return [];
        }

        $arLabels = [];
        $rsUserFields = \Bitrix\Main\UserFieldTable::getList([
            'select' => [
                '*',
                'UFL_' => 'USER_FIELD_LANG.*',
            ],
            'filter' => [
                'ENTITY_ID' => 'HLBLOCK_' . $hlBlockId, 'UFL_LANGUAGE_ID' => 'ru'
            ],
            'runtime' => [
                'USER_FIELD_LANG' => [
                    'data_type' => \Bitrix\Main\UserFieldLang::class,
                    'reference' => [
                        '=this.ID' => 'ref.USER_FIELD_ID',
                    ],
                ],
            ],
            'cache' => [
                'ttl' => 36000,
            ]
        ]);
        while ($arUserField = $rsUserFields->fetch()) {
            $arLabels[$arUserField['FIELD_NAME']] = $arUserField['UFL_LIST_COLUMN_LABEL'];
        }

        return $arLabels;
    }

    /**
     *  Получение записи HL блока по IP адресу
     * @param string $ip
     * @return array
     */
    private function getGeoIpRecord(string $ip): array
    {
        try {

            $arRes = $this->entityDataClass::getList(
                [
                    'select' => ['*'],
                    'filter' => ['UF_GEO_IP' => $ip],
                    'cache' => [
                        'ttl' => 36000,
                    ]
                ]
            )->fetch();

        } catch (Exception $exception) {

            $arCFields = [
                'ERROR_TYPE' => 'GEOIP_COMPONENT::getGeoIpRecord',
                'ERROR_INFO' => $exception->getMessage()
            ];

            $this->eventSend($arCFields);

        }

        if (!empty($arRes)) {
            $arInfo = [
                'UF_GEO_IP' => $arRes['UF_GEO_IP'],
                'UF_GEO_COORDINATES' => $arRes['UF_GEO_COORDINATES'],
                'UF_GEO_COUNTRY' => $arRes['UF_GEO_COUNTRY'],
                'UF_GEO_REGION' => $arRes['UF_GEO_REGION'],
                'UF_GEO_CITY' => $arRes['UF_GEO_CITY']
            ];
        }

        return (!empty($arInfo)) ? $arInfo : [];
    }

    /**
     *  Добавление записи в HL блок
     * @param array $arRecord
     * @return bool
     */
    private function addGeoIpRecord(array $arRecord): bool
    {
        if (empty($arRecord) || empty($arRecord['UF_GEO_IP'])) {
            return false;
        }
        $result = false;
        try {
            $result = $this->entityDataClass::add(
                [
                    'UF_GEO_IP' => $arRecord['UF_GEO_IP'],
                    'UF_GEO_COORDINATES' => $arRecord['UF_GEO_COORDINATES'] ?? '',
                    'UF_GEO_COUNTRY' => $arRecord['UF_GEO_COUNTRY'] ?? '',
                    'UF_GEO_REGION' => $arRecord['UF_GEO_REGION'] ?? '',
                    'UF_GEO_CITY' => $arRecord['UF_GEO_CITY'] ?? ''
                ]
            );
        } catch (Exception $exception) {

            $arCFields = [
                'ERROR_TYPE' => 'GEOIP_COMPONENT::addGeoIpRecord',
                'ERROR_INFO' => $exception->getMessage()
            ];

            $this->eventSend($arCFields);

        }

        return (bool)$result;
    }

    /**
     *  Получение данных из сервиса sypexgeo.net
     * @param string $ip
     * @return array
     */
    private function getInfoFromSypexgeo(string $ip): array
    {
        $arInfo = [];
        $queryUrl = sprintf('http://us3.sxgeo.city/json/%s/', $ip);

        try {
            $options = [
                'version' => HttpClient::HTTP_1_1,
            ];

            $httpClient = new HttpClient($options);

            $name = "User-Agent";
            $value = "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36";
            $httpClient->setHeader($name, $value, true);

            // get-запрос
            $response = $httpClient->get($queryUrl);

        } catch (Exception $exception) {

            $arCFields = [
                'ERROR_TYPE' => 'GEOIP_COMPONENT::getInfoFromSypexgeo',
                'ERROR_INFO' => $exception->getMessage()
            ];

            $this->eventSend($arCFields);

        }

        $result = json_decode($response, true);

        if (!empty($result['error'])) {

            $arCFields = [
                'ERROR_TYPE' => 'GEOIP_COMPONENT::getInfoFromSypexgeo',
                'ERROR_INFO' => $result['error']
            ];

            $this->eventSend($arCFields);

            return [];
        }

        if ($result['ip']) {
            $arInfo = [
                'UF_GEO_IP' => $result['ip'],
                'UF_GEO_COORDINATES' => sprintf('%s, %s', $result['city']['lat'], $result['city']['lon']),
                'UF_GEO_COUNTRY' => $result['country']['name_ru'],
                'UF_GEO_REGION' => $result['region']['name_ru'],
                'UF_GEO_CITY' => $result['city']['name_ru'],
            ];
        }
        return $arInfo;
    }

    /**
     *  Получение данных из сервиса ipstack.com
     *
     * @param string $ip
     * @return array
     */
    private function getInfoFromIpstack(string $ip): array
    {
        $arInfo = [];

        $queryUrl = sprintf('https://api.ipstack.com/%s?access_key=%s&format=1&language=ru', $ip, $this->arParams['IPSTACK_ACCESS_KEY']);

        try {

            $options = [
                'version' => HttpClient::HTTP_1_1,
            ];

            $httpClient = new HttpClient($options);

            $name = "User-Agent";
            $value = "Mozilla/5.0 (Linux; Android 6.0; Nexus 5 Build/MRA58N) AppleWebKit/537.36 (KHTML, like Gecko) Chrome/124.0.0.0 Mobile Safari/537.36";
            $httpClient->setHeader($name, $value, true);

            // get-запрос
            $response = $httpClient->get($queryUrl);

        } catch (Exception $exception) {

            $arCFields = [
                'ERROR_TYPE' => 'GEOIP_COMPONENT::getInfoFromIpstack',
                'ERROR_INFO' => $exception->getMessage()
            ];

            $this->eventSend($arCFields);
        }

        $result = json_decode($response, true);

        if (!empty($result['error'])) {

            $arCFields = [
                'ERROR_TYPE' => $result['error']['type'],
                'ERROR_INFO' => $result['error']['info']
            ];

            $this->eventSend($arCFields);

            return [];
        }

        if ($result['ip']) {
            $arInfo = [
                'UF_GEO_IP' => $result['ip'],
                'UF_GEO_COORDINATES' => sprintf('%s, %s', $result['latitude'], $result['longitude']),
                'UF_GEO_COUNTRY' => $result['country_name'],
                'UF_GEO_REGION' => $result['region_name'],
                'UF_GEO_CITY' => $result['city'],
            ];
        }
        return $arInfo;
    }

    /**
     *  Отправка письма
     *
     * @param array $arCFields
     * @return void
     */
    private function eventSend(array $arCFields): void
    {
        Event::send([
            "EVENT_NAME" => $this->arParams['MAIL_EVENT_NAME'],
            'MESSAGE_ID' => $this->arParams['MAIL_MESSAGE_ID'],
            "LID" => "s1",
            "C_FIELDS" => $arCFields
        ]);
    }

}