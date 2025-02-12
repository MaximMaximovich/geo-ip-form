<?php

namespace Sprint\Migration;


class Version20250212045320 extends Version
{
    protected $author = "admin";

    protected $description = "Миграция для GeoIpBase";

    protected $moduleVersion = "4.18.0";

    /**
     * @throws Exceptions\HelperException
     * @return bool|void
     */
    public function up()
    {
        $helper = $this->getHelperManager();
    $hlblockId = $helper->Hlblock()->saveHlblock(array (
  'NAME' => 'GeoIpBase',
  'TABLE_NAME' => 'b_hlbd_geo_ip_base',
  'LANG' => 
  array (
    'ru' => 
    array (
      'NAME' => 'База данных GeoIP',
    ),
    'en' => 
    array (
      'NAME' => 'GeoIP database',
    ),
  ),
));
        $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_GEO_IP',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => '',
  'SORT' => '10',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'IP address',
    'ru' => 'IP адрес',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'IP address',
    'ru' => 'IP адрес',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'IP address',
    'ru' => 'IP адрес',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
            $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_GEO_COORDINATES',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => '',
  'SORT' => '20',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Coordinates',
    'ru' => 'Координаты',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Coordinates',
    'ru' => 'Координаты',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Coordinates',
    'ru' => 'Координаты',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
            $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_GEO_COUNTRY',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => '',
  'SORT' => '30',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Country',
    'ru' => 'Страна',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Country',
    'ru' => 'Страна',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Country',
    'ru' => 'Страна',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
            $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_GEO_REGION',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => '',
  'SORT' => '40',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'Region',
    'ru' => 'Регион',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'Region',
    'ru' => 'Регион',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'Region',
    'ru' => 'Регион',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
            $helper->Hlblock()->saveField($hlblockId, array (
  'FIELD_NAME' => 'UF_GEO_CITY',
  'USER_TYPE_ID' => 'string',
  'XML_ID' => '',
  'SORT' => '50',
  'MULTIPLE' => 'N',
  'MANDATORY' => 'N',
  'SHOW_FILTER' => 'N',
  'SHOW_IN_LIST' => 'Y',
  'EDIT_IN_LIST' => 'Y',
  'IS_SEARCHABLE' => 'N',
  'SETTINGS' => 
  array (
    'SIZE' => 20,
    'ROWS' => 1,
    'REGEXP' => '',
    'MIN_LENGTH' => 0,
    'MAX_LENGTH' => 0,
    'DEFAULT_VALUE' => '',
  ),
  'EDIT_FORM_LABEL' => 
  array (
    'en' => 'City',
    'ru' => 'Город',
  ),
  'LIST_COLUMN_LABEL' => 
  array (
    'en' => 'City',
    'ru' => 'Город',
  ),
  'LIST_FILTER_LABEL' => 
  array (
    'en' => 'City',
    'ru' => 'Город',
  ),
  'ERROR_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
  'HELP_MESSAGE' => 
  array (
    'en' => '',
    'ru' => '',
  ),
));
        }
}
