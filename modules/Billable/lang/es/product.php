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
    'products' => 'Productos',
    'product' => 'Producto',
    'create' => 'Crear producto',
    'edit' => 'Editar producto',
    'export' => 'Exportar producto',
    'import' => 'Importar producto',
    'created' => 'Producto creado correctamente',
    'updated' => 'Producto actualizado correctamente',
    'deleted' => 'Producto eliminado correctamente',
    'manage' => 'Administrar productos',
    'name' => 'Nombre',
    'description' => 'Descripción',
    'table_heading' => 'Producto',
    'tax' => 'Impuesto',
    'quantity' => 'Cantidad',
    'qty' => 'CANTIDAD',
    'unit_price' => 'Precio unitario',
    'direct_cost' => 'Costo directo',
    'unit' => 'Unidad (kg, lotes)',
    'sku' => 'CÓDIGO',
    'is_active' => 'Activo',
    'tax_rate' => 'Tasa de impuesto',
    'tax_label' => 'Etiqueta de impuestos',
    'tax_percent' => 'Porcentaje de impuestos',
    'discount' => 'Descuento',
    'amount' => 'Cantidad',
    'discount_percent' => 'Porcentaje de descuento',
    'discount_amount' => 'Cantidad de descuento',
    'will_be_added_as_new' => ':name se agregará como nuevo producto',
    'total_products' => 'Total de productos',
    'total_sold' => 'Total de ventas',
    'sold_amount_exc_tax' => 'Importe vendido (sin impuestos)',
    'interest_in_product' => 'Interés en el producto',
    'resource_has_no_products' => 'No hay productos creados, empiece por añadir productos',
    'exists_in_trash_by_name' => 'Ya existe un producto con el mismo nombre en la papelera, ¿desea restaurar el producto de la papelera?',
    'choose_or_enter' => 'Seleccione o introduzca un producto',
    'cards' => [
        'performance' => 'Rendimiento del producto',
        'performance_info' => 'La columna "Interés en el producto" refleja todos los productos que se agregan a las ofertas, pero la columna "Total vendido" refleja los productos que se agregan a las ofertas y las ofertas se marcan como ganadas',
    ],
    'count' => '0 productos | 1 producto | :count productos',
    'settings' => [
        'default_tax_type' => '¿Venden sus productos con impuestos incluidos?',
        'default_discount_type' => 'Tipo de descuento predeterminado',
    ],
    'actions' => [
        'mark_as_active' => 'Marcar como activo',
        'mark_as_inactive' => 'Marcar como inactivo',
        'update_unit_price' => 'Actualizar precio',
        'update_tax_rate' => 'Actualizar tasa de impuestos',
    ],
    'validation' => [
        'sku' => [
            'unique' => 'Ya existe un producto con este SKU.',
        ],
    ],
];
