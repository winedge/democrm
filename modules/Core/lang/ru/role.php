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
    'permissions' => 'Разрешения',
    'role' => 'Роль',
    'roles' => 'Роли',
    'name' => 'Название',
    'create' => 'Создать роль',
    'edit' => 'Изменить роль',
    'created' => 'Роль успешно создана',
    'updated' => 'Роль успешно изменена',
    'deleted' => 'Роль успешно удалена',
    'granted' => 'Разрешено',
    'revoked' => 'Запрещено',
    'capabilities' => [
        'access' => 'Доступ',
        'view' => 'Просмотр',
        'delete' => 'Удаление',
        'bulk_delete' => 'Массовое удаление',
        'edit' => 'Редактировать',
        'all' => 'Все :resourceName',
        'owning_only' => 'Только ответственный',
    ],
    'view_non_authorized_after_record_create' => 'Ваша учетная запись не авторизована для просмотра этой записи, так как вы не являетесь владельцем записи, после перенаправления с этой страницы вы не сможете получить доступ к записи.',
    'empty_state' => [
        'title' => 'Нет ролей',
        'description' => 'Начните с создания новой роли.',
    ],
];
