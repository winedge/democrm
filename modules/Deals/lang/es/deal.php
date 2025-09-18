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
    'deal' => 'Oferta',
    'deals' => 'Ofertas',
    'create' => 'Crear oferta',
    'add' => 'Agregar oferta',
    'sort_by' => 'Ordenar ofertas por',
    'name' => 'Nombre de la operación',
    'choose_or_create' => 'Escoger o crear oferta',
    'associate_with' => 'Asociar oferta con :name',
    'add_products' => 'Agregar productos',
    'dont_add_products' => 'No agregar productos',
    'reopen' => 'Reabrir',
    'status' => [
        'status' => 'Estado',
        'won' => 'Ganado',
        'lost' => 'Perdido',
        'open' => 'Abierto',
    ],
    'been_in_stage_time' => 'Ha estado aquí por :time',
    'hasnt_been_in_stage' => 'Este acuerdo aún no ha llegado a esta etapa',
    'total_created' => 'Total creados',
    'total_assigned' => 'Total asignados',
    'import' => 'Importar ofertas',
    'export' => 'Exportar ofertas',
    'import_in' => 'Importar ofertas en :pipeline',
    'total' => 'Ofertas totales',
    'closed_deals' => 'Ofertas cerradas',
    'won_deals' => 'Ofertas ganadas',
    'open_deals' => 'Ofertas abiertas',
    'lost_deals' => 'Ofertas perdidas',
    'forecast_amount' => 'Importe previsto',
    'closed_amount' => 'Importe cerrado',
    'dissociate' => 'Disociar trato',
    'no_companies_associated' => 'La oferta no tiene empresas asociadas',
    'no_contacts_associated' => 'La oferta no tiene contactos asociados',
    'associate_field_info' => 'Utilice este campo para asociar una operación existente en lugar de crear una nueva.',
    'create_with' => 'Crear oferta con :name',
    'already_associated' => 'Esta oferta ya está asociada con :with.',
    'lost_reasons' => [
        'lost_reason' => 'Razón perdida',
        'lost_reasons' => 'Razones perdidas',
        'name' => 'Nombre',
        'choose_lost_reason' => 'Elija un motivo perdido',
        'choose_lost_reason_or_enter' => 'Elija un motivo perdido o ingrese manualmente',
    ],
    'settings' => [
        'lost_reason_is_required' => 'Se requiere razón de pérdida',
        'lost_reason_is_required_info' => 'Cuando se active esta opción, los agentes de ventas deberán elegir o introducir el motivo de la pérdida al marcar la operación como perdida.',
        'allow_lost_reason_enter' => 'Permitir a los agentes de ventas introducir un motivo de pérdida personalizado',
        'allow_lost_reason_enter_info' => 'Cuando se desactive, los agentes de ventas sólo podrán elegir de la lista predefinida de motivos de pérdida cuando marquen la operación como perdida.',
    ],
    'cards' => [
        'by_stage' => 'Ofertas por etapa',
        'lost_in_stage' => 'Etapa de ofertas perdidas',
        'lost_in_stage_info' => 'Visualiza en qué etapa se pierden más negocios. Las etapas que se muestran en los informes son las etapas a las que pertenecía la oferta en el momento en que se marcó como perdida.',
        'won_in_stage' => 'Etapa de acuerdos ganados',
        'won_in_stage_info' => 'Vea en qué etapa se ganan más los negocios. Las etapas que se muestran en los informes son las etapas a las que pertenecía el negocio en el momento en que se marcó como ganado.',
        'closing' => 'Cerrar acuerdos',
        'closing_info' => 'Vea los tratos que se prevé que se cerrarán en función del período seleccionado y la fecha de cierre prevista; los tratos marcados como "Ganados" o "Perdidos" se excluyen de la lista.',
        'recently_created' => 'Ofertas creadas recientemente',
        'recently_modified' => 'Ofertas modificadas recientemente',
        'won_by_revenue_by_month' => 'Ingresos de negocios ganados por mes',
        'won_by_date' => 'Acuerdos ganados por día',
        'assigned_by_sale_agent' => 'Acuerdos asignados por el agente de ventas',
        'assigned_by_sale_agent_info' => 'Vea el número total de acuerdos asignados para cada representante de ventas. Vea cuántos ingresos es probable que estas ofertas generen para su negocio. Y cuántos ingresos ya tiene de acuerdos cerrados.',
        'created_by_sale_agent' => 'Operaciones creadas por el agente de ventas',
        'created_by_sale_agent_info' => 'Vea qué representantes de ventas están creando la mayor cantidad de tratos. Vea cuántos ingresos es probable que estas ofertas generen para su negocio. Y cuántos ingresos ya tiene de acuerdos cerrados.',
        'recently_created_info' => 'Mostrando el último :total de tratos creados en los últimos :days días, ordenados por los más nuevos en la parte superior',
        'recently_modified_info' => 'Mostrando el último :total de ofertas modificadas en los últimos :days días.',
        'won_by_month' => 'Contratos ganados por mes',
    ],
    'notifications' => [
        'assigned' => 'Ha sido asignado a la operación :name por :user',
    ],
    'stage' => [
        'weighted_value' => ':weighted_total - :win_probability de :total',
        'changed_date' => 'Etapa de fecha de modificación',
        'add' => 'Añadir nueva etapa',
        'name' => 'Nombre de la etapa',
        'win_probability' => 'Probabilidad de ganar',
        'delete_usage_warning' => 'La etapa ya está asociada a las operaciones, por lo que no puede eliminarse.',
    ],
    'deal_amount' => 'Importe de la operación',
    'deal_expected_close_date' => 'Fecha prevista de cierre de la operación',
    'count' => [
        'all' => '1 oferta| :count ofertas',
        'open' => 'recuento de operaciones abiertas :resource',
        'won' => 'recuento de operaciones ganadas :resource',
        'lost' => 'recuento de operaciones perdidas :resource',
        'closed' => 'recuento de operaciones cerradas :resource',
    ],
    'pipeline' => [
        'name' => 'Nombre del proyecto',
        'pipeline' => 'Proyecto',
        'pipelines' => 'Proyectos',
        'create' => 'Crear proyecto',
        'edit' => 'Editar proyecto',
        'updated' => 'Proyecto actualizado correctamente',
        'deleted' => 'Proyecto eliminado correctamente',
        'delete_primary_warning' => 'No se puede eliminar el proyecto principal',
        'delete_usage_warning_deals' => 'El proyecto ya está asociado a las operaciones, por lo que no se puede eliminar.',
        'visibility_group' => [
            'primary_restrictions' => 'Este es el proyecto principal, por lo tanto, la visibilidad no se puede cambiar.',
        ],
        'reorder' => 'Reordenar proyectos',
    ],
    'actions' => [
        'change_stage' => 'Cambiar etapa',
        'mark_as_open' => 'Marcar como abierto',
        'mark_as_won' => 'Marcar como ganado',
        'mark_as_lost' => 'Marcar como perdido',
    ],
    'views' => [
        'my' => 'Mis ofertas',
        'my_recently_assigned' => 'Mis últimas ofertas asignadas',
        'created_this_month' => 'Ofertas creadas este mes',
        'won' => 'Acuerdos ganados',
        'lost' => 'Acuerdos perdidos',
        'open' => 'Ofertas abiertas',
    ],
    'mail_placeholders' => [
        'assigneer' => 'El nombre de usuario que asignó la operación',
    ],
    'workflows' => [
        'triggers' => [
            'status_changed' => 'Modificación del estado de la operación',
            'stage_changed' => 'Etapa de la operación modificada',
            'created' => 'Oferta creada',
        ],
        'actions' => [
            'mark_associated_activities_as_complete' => 'Marcar las actividades asociadas como finalizadas',
            'mark_associated_deals_as_won' => 'Marcar acuerdos asociados como ganados',
            'mark_associated_deals_as_lost' => 'Marcar acuerdos asociados como perdidos',
            'delete_associated_activities' => 'Eliminar actividades asociadas',
            'fields' => [
                'email_to_contact' => 'Contacto principal de la operación',
                'email_to_company' => 'Empresa principal de la operación',
                'email_to_owner_email' => 'Correo electrónico del propietario de la operación',
                'email_to_creator_email' => 'Correo electrónico del creador de la oferta',
                'lost_reason' => 'Con el siguiente motivo de pérdida',
            ],
        ],
    ],
    'timeline' => [
        'stage' => [
            'moved' => ':user movió trato de :previous a :stage etapa',
        ],

        'marked_as_lost' => ':user marcó trato como perdido por el siguiente motivo: :reason',
        'marked_as_won' => ':user marcó la oferta como ganada',
        'marked_as_open' => ':user marcó la oferta como abierta',
    ],
    'metrics' => [
        'open' => 'Ofertas abiertas',
    ],
];
