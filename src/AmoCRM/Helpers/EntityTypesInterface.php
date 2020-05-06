<?php

namespace AmoCRM\AmoCRM\Helpers;

interface EntityTypesInterface
{
    const LEADS = 'leads';
    const CONTACTS = 'contacts';
    const CATALOGS = 'catalogs';
    const COMPANIES = 'companies';
    const CUSTOMERS = 'customers';
    const EVENTS = 'events';
    const NOTES = 'notes';
    const TAGS = 'tags';
    const TASKS = 'tasks';
    const WEBHOOKS = 'webhooks';
    const UNSORTED = 'unsorted';
    const CATALOG_ELEMENTS = 'elements';
    const USER_ROLES = 'roles';
    const CUSTOMERS_SEGMENTS = 'segments';


    const CUSTOM_FIELDS = 'custom_fields';
    const CUSTOM_FIELD_GROUPS = 'custom_field_groups';

    const DEFAULT_CATALOG_TYPE_STRING = 'regular';
    const INVOICES_CATALOG_TYPE_STRING = 'invoices';
    const PRODUCTS_CATALOG_TYPE_STRING = 'products';

    const MIN_CATALOG_ID = 1000;
}
