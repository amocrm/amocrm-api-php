<?php

namespace AmoCRM\Helpers;

interface EntityTypesInterface
{
    public const LEADS = 'leads';
    public const LEAD = 'lead';
    public const LEADS_PIPELINES = 'pipelines';
    public const LEADS_LOSS_REASONS = 'loss_reasons';
    public const LEADS_STATUSES = 'statuses';
    public const SOURCES = 'sources';
    public const CONTACTS = 'contacts';
    public const CONTACT = 'contact';
    public const CATALOGS = 'catalogs';
    public const COMPANIES = 'companies';
    public const CONTACTS_AND_COMPANIES = 'contacts_and_companies';
    public const COMPANY = 'company';
    public const CUSTOMERS = 'customers';
    public const CUSTOMERS_TRANSACTIONS = 'transactions';
    public const EVENTS = 'events';
    public const NOTES = 'notes';
    public const TAGS = 'tags';
    public const TASKS = 'tasks';
    public const WEBHOOKS = 'webhooks';
    public const UNSORTED = 'unsorted';
    public const CATALOG_ELEMENTS = 'elements';
    public const CATALOG_ELEMENTS_FULL = 'catalog_elements';
    public const USER_ROLES = 'roles';
    public const USERS = 'users';
    public const CUSTOMERS_SEGMENTS = 'segments';
    public const CUSTOMERS_STATUSES = 'statuses';
    public const WIDGETS = 'widgets';
    public const STATUS_RIGHTS = 'status_rights';
    public const CATALOG_RIGHTS = 'catalog_rights';
    public const CALLS = 'calls';
    public const PRODUCTS = 'products';
    public const SETTINGS = 'settings';
    public const SHORT_LINKS = 'short_links';
    public const LINKS = 'links';
    public const TALKS = 'talks';
    public const SUBSCRIPTIONS = 'subscriptions';
    public const CURRENCIES = 'currencies';

    public const CUSTOM_FIELDS = 'custom_fields';
    public const CUSTOM_FIELD_GROUPS = 'custom_field_groups';

    public const DEFAULT_CATALOG_TYPE_STRING = 'regular';
    public const INVOICES_CATALOG_TYPE_STRING = 'invoices';
    public const PRODUCTS_CATALOG_TYPE_STRING = 'products';
    public const SUPPLIERS_CATALOG_TYPE_STRING = 'suppliers';

    public const CHAT_TEMPLATES = 'chat_templates';

    public const MIN_CATALOG_ID = 1000;
}
