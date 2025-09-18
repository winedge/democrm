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
    'products' => 'Товары',
    'product' => 'Товар',
    'create' => 'Создать товар',
    'edit' => 'Изменить товар',
    'export' => 'Экспорт товаров',
    'import' => 'Импорт товаров',
    'created' => 'Товар успешно создан',
    'updated' => 'Товар успешно изменен',
    'deleted' => 'Товар успешно удален',
    'related_products' => 'Связанные товары',
    'manage' => 'Управление товарами',
    'name' => 'Название',
    'description' => 'Описание',
    'table_heading' => 'Товар',
    'tax' => 'Налог',
    'quantity' => 'Количество',
    'qty' => 'КОЛ-ВО',
    'unit_price' => 'Цена за 1 единицу',
    'direct_cost' => 'Стоимость от поставщика',
    'unit' => 'Единица (м2, п.м.)',
    'sku' => 'Артикул',
    'is_active' => 'Активный',
    'tax_rate' => 'Ставка налога',
    'tax_label' => 'Налоговая этикетка',
    'tax_percent' => 'Налоговый процент',
    'discount' => 'Скидка',
    'amount' => 'Сумма',
    'discount_percent' => 'Процент скидки',
    'discount_amount' => 'Сумма скидки',
    'will_be_added_as_new' => ':name будет добавлено как новый товар',
    'total_products' => 'Всего товаров',
    'total_sold' => 'Всего продано',
    'sold_amount_exc_tax' => 'Сумма продажи (без учета налогов)',
    'interest_in_product' => 'Интерес к товару',
    'resource_has_no_products' => 'Товары не созданы, начните с добавления товаров',
    'exists_in_trash_by_name' => 'Товар с таким названием уже находится в корзине. Восстановить удаленный товар?',
    'choose_or_enter' => 'Выберите или введите товар',
    'cards' => [
        'performance' => 'Характеристика товара',
        'performance_info' => 'Столбец «Интерес к товару» отражает все товары, добавленные к сделкам, а столбец «Всего продано» отражает товары, добавленные к сделкам, и сделки отмечены как завершенные.',
    ],
    'count' => '0 товаров | 1 товар | :count товара',
    'settings' => [
        'default_tax_type' => 'Вы продаете свою продукцию по ставкам, включающим налог?',
        'default_discount_type' => 'Тип скидки по умолчанию',
    ],
    'actions' => [
        'mark_as_active' => 'Отметить как активный',
        'mark_as_inactive' => 'Отметить как неактивный',
        'update_unit_price' => 'Обновить цену',
        'update_tax_rate' => 'Обновить налоговую ставку',
        'update_tax_label' => 'Обновить налоговую метку',
    ],
    'validation' => [
        'sku' => [
            'unique' => 'Товар с таким артикулом уже существует.',
        ],
    ],
    'empty_state' => [
        'title' => 'Вы не создали ни одного товара.',
        'description' => 'Экономьте время, используя предустановленные товары.',
    ],
];
