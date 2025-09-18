<?php
/**
 * Concord CRM - https://www.concordcrm.com
 *
 * @version   1.6.0
 *
 * @link      Releases - https://www.concordcrm.com/releases
 * @link      Terms Of Service - https://www.concordcrm.com/terms
 *
 * @copyright Copyright (c) 2022-2025 KONKORD DIGITAL
 */

return [
    'contact' => 'Контакт',
    'contacts' => 'Контакты',
    'convert' => 'Преобразовать в контакт',
    'create' => 'Создать контакт',
    'add' => 'Добавить контакт',
    'total' => 'Всего контактов',
    'import' => 'Импорт контактов',
    'export' => 'Экспорт контактов',
    'no_companies_associated' => 'Контакт не имеет связанных компаний.',
    'no_deals_associated' => 'Контакт не имеет связанных сделок.',
    'works_at' => ':job_title в :company',
    'create_with' => 'Создать контакт с :name',
    'associate_with' => 'Связать контакт с :name',
    'associated_company' => 'Связанные с контактом компании',
    'dissociate' => 'Отвязать контакт',
    'exists_in_trash_by_email' => 'Контакт с этим адресом электронной почты уже существует в корзине, вы не сможете создать новый контакт с таким же адресом электронной почты, вы хотите восстановить удаленный контакт?',
    'exists_in_trash_by_phone' => 'Контакт (:contact) со следующими номерами: :phone_numbers, уже есть в корзине, восстановить удаленный контакт?',
    'possible_duplicate' => 'Возможен дубликат контакта :display_name.',
    'associate_field_info' => 'Используйте это поле, чтобы найти и связать существующий контакт вместо создания нового.',
    'cards' => [
        'recently_created' => 'Недавно созданные контакты',
        'recently_created_info' => 'Отображение последних :total созданных контактов за последние :days дней, отсортированных сверху по самым новым.',
        'by_day' => 'Контакты по дням',
        'by_source' => 'Контакты по источнику',
    ],
    'count' => [
        'all' => '1 контакт | :count контакта',
    ],
    'notifications' => [
        'assigned' => 'Вы были назначены контакту :name пользователем :user',
    ],
    'filters' => [
        'my' => 'Мои контакты',
        'my_recently_assigned' => 'Мои недавно назначенные контакты',
    ],
    'mail_placeholders' => [
        'assigneer' => 'Имя пользователя, назначившего контакт',
    ],
    'workflows' => [
        'triggers' => [
            'created' => 'Контакт создан',
        ],
        'actions' => [
            'fields' => [
                'email_to_contact' => 'Контакт email',
                'email_to_owner_email' => 'Контакт email ответственного',
                'email_to_creator_email' => 'Контакт email создателя',
                'email_to_company' => 'Контакт основная компания',
            ],
        ],
    ],
    'validation' => [
        'email' => [
            'unique' => 'Контакт или член команды с таким адресом электронной почты уже существует.',
        ],
        'phone' => [
            'unique' => 'Контакт с таким номером телефона уже существует.',
        ],
    ],
    'empty_state' => [
        'title' => 'Вы не создали ни одного контакта.',
        'description' => 'Начните организовывать людей сейчас.',
    ],
];
