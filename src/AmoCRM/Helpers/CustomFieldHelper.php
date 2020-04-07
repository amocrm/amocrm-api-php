<?php

namespace AmoCRM\Helpers;

class CustomFieldHelper
{
    const FIELD_TYPE_CODE_TEXT = 'TEXT';
    const FIELD_TYPE_CODE_NUMERIC = 'NUMERIC';
    const FIELD_TYPE_CODE_CHECKBOX = 'CHECKBOX';
    const FIELD_TYPE_CODE_SELECT = 'SELECT';
    const FIELD_TYPE_CODE_MULTISELECT = 'MULTISELECT';
    const FIELD_TYPE_CODE_DATE = 'DATE';
    const FIELD_TYPE_CODE_DATE_TIME = 'DATE_TIME';
    const FIELD_TYPE_CODE_URL = 'URL';
    const FIELD_TYPE_CODE_MULTITEXT = 'MULTITEXT';
    const FIELD_TYPE_CODE_TEXTAREA = 'TEXTAREA';
    const FIELD_TYPE_CODE_RADIOBUTTON = 'RADIOBUTTON';
    const FIELD_TYPE_CODE_STREETADDRESS = 'STREETADDRESS';
    const FIELD_TYPE_CODE_SMART_ADDRESS = 'SMART_ADDRESS';
    const FIELD_TYPE_CODE_BIRTHDAY = 'BIRTHDAY';
    const FIELD_TYPE_CODE_ITEMS = 'ITEMS';
    const FIELD_TYPE_CODE_CATEGORY = 'CATEGORY';
    const FIELD_TYPE_CODE_LEGAL_ENTITY = 'legal_entity';
    const FIELD_TYPE_CODE_ORG_LEGAL_NAME = 'org_legal_name';


    const FIELD_TYPE_TEXT = 1;
    const FIELD_TYPE_NUMERIC = 2;
    const FIELD_TYPE_CHECKBOX = 3;
    const FIELD_TYPE_SELECT = 4;
    const FIELD_TYPE_MULTISELECT = 5;
    const FIELD_TYPE_DATE = 6;
    const FIELD_TYPE_URL = 7;
    const FIELD_TYPE_MULTITEXT = 8;
    const FIELD_TYPE_TEXTAREA = 9;
    const FIELD_TYPE_RADIOBUTTON = 10;
    const FIELD_TYPE_STREETADDRESS = 11;
    const FIELD_TYPE_SMART_ADDRESS = 13;
    const FIELD_TYPE_BIRTHDAY = 14;
    const FIELD_TYPE_LEGAL_ENTITY = 15;
    const FIELD_TYPE_ITEMS = 16;
    const FIELD_TYPE_ORG_LEGAL_NAME = 17;
    const FIELD_TYPE_CATEGORY = 18;
    const FIELD_TYPE_DATE_TIME = 19;


    const FIELD_TYPE_MATCHING = [
        self::FIELD_TYPE_TEXT           => self::FIELD_TYPE_CODE_TEXT,
        self::FIELD_TYPE_NUMERIC        => self::FIELD_TYPE_CODE_NUMERIC,
        self::FIELD_TYPE_CHECKBOX       => self::FIELD_TYPE_CODE_CHECKBOX,
        self::FIELD_TYPE_SELECT         => self::FIELD_TYPE_CODE_SELECT,
        self::FIELD_TYPE_MULTISELECT    => self::FIELD_TYPE_CODE_MULTISELECT,
        self::FIELD_TYPE_DATE           => self::FIELD_TYPE_CODE_DATE,
        self::FIELD_TYPE_URL            => self::FIELD_TYPE_CODE_URL,
        self::FIELD_TYPE_MULTITEXT      => self::FIELD_TYPE_CODE_MULTITEXT,
        self::FIELD_TYPE_TEXTAREA       => self::FIELD_TYPE_CODE_TEXTAREA,
        self::FIELD_TYPE_RADIOBUTTON    => self::FIELD_TYPE_CODE_RADIOBUTTON,
        self::FIELD_TYPE_STREETADDRESS  => self::FIELD_TYPE_CODE_STREETADDRESS,
        self::FIELD_TYPE_SMART_ADDRESS  => self::FIELD_TYPE_CODE_SMART_ADDRESS,
        self::FIELD_TYPE_BIRTHDAY       => self::FIELD_TYPE_CODE_BIRTHDAY,
        self::FIELD_TYPE_LEGAL_ENTITY   => self::FIELD_TYPE_CODE_LEGAL_ENTITY,
        self::FIELD_TYPE_ITEMS          => self::FIELD_TYPE_CODE_ITEMS,
        self::FIELD_TYPE_ORG_LEGAL_NAME => self::FIELD_TYPE_CODE_ORG_LEGAL_NAME,
        self::FIELD_TYPE_CATEGORY       => self::FIELD_TYPE_CODE_CATEGORY,
        self::FIELD_TYPE_DATE_TIME      => self::FIELD_TYPE_CODE_DATE_TIME,
    ];

    /**
     * @param int $typeId
     *
     * @return string
     */
    public static function convertTypeIdToCode(int $typeId): string
    {
        return strtolower(self::FIELD_TYPE_MATCHING[$typeId] ?? '');
    }

    /**
     * @param string $type
     *
     * @return int
     */
    public static function convertCodeToTypeId(string $type): int
    {
        return array_change_key_case(array_flip(self::FIELD_TYPE_MATCHING))[strtolower($type)] ?? 0;
    }
}
