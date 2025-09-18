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
    'activities' => 'Atividades',
    'activity' => 'Atividade',
    'add' => 'Adicionar Atividade',
    'description' => 'Descrição',
    'description_info' => 'A descrição é visível para todos os convidados',
    'note' => 'Nota',
    'note_info' => 'As notas são privadas e visíveis apenas para os representantes de vendas.',
    'title' => 'Título',
    'due_date' => 'Data de Vencimento',
    'end_date' => 'Data Final',
    'create' => 'Criar Atividade',
    'download_ics' => 'Baixar Arquivo .ics',
    'created' => 'Atividade criada com sucesso',
    'updated' => 'Atividade atualizada com sucesso',
    'deleted' => 'Atividade excluída com sucesso',
    'export' => 'Exportar Atividades',
    'import' => 'Importar Atividades',
    'guests' => 'Convidados',
    'guest' => 'Convidado',
    'count_guests' => '1 convidado | :count convidados',
    'create_follow_up_task' => 'Criar tarefa de follow up',
    'follow_up_with_title' => 'Follow up com :with',
    'title_via_create_message' => 'Sobre um e-mail: :subject',
    'reminder_update_info' => 'Como o lembrete para esta atividade já foi enviado, você precisará atualizar a data de vencimento para um novo lembrete ser enviado para a fila.',
    'owner_assigned_date' => 'Data de Atribuição ao Proprietário',
    'reminder_sent_date' => 'Data de Envio do Lembrete',
    'reminder' => 'Lembrete',
    'owner' => 'Proprietário',
    'mark_as_completed' => 'Marcar como concluído',
    'mark_as_incomplete' => 'Marcar como incompleto',
    'is_completed' => 'Está Concluído',
    'completed_at' => 'Concluído em',
    'overdue' => 'Atrasado',
    'doesnt_have_activities' => 'Nenhuma Atividade',
    'count' => 'Nenhuma Atividade | 1 Atividade | :count Atividades',
    'incomplete_activities' => 'Atividades Incompletas',
    'activity_was_due' => 'Essa atividade venceu em :date',
    'next_activity_date' => 'Data da Próxima Atividade',
    'next_activity_date_info' => 'Este campo é somente leitura e é atualizado automaticamente com base nas atividades futuras do registro, indica quando a próxima ação do representante de vendas deve ser tomada.',
    'cards' => [
        'my_activities' => 'Minhas atividades',
        'my_activities_info' => 'Este card reflete as atividades que você é adicionado como proprietário',
        'created_by_agent' => 'Atividades criadas pelo vendedor',
        'created_by_agent_info' => 'Veja o número de atividades que cada vendedor criou. Veja quem está criando mais atividades e quem está criando menos.',
        'upcoming' => 'Próximas atividades',
        'upcoming_info' => 'Este card reflete as atividades que estão por vir e aquela que você está participando.',
    ],
    'type' => [
        'default_type' => 'Tipo de Atividade Padrão',
        'delete_primary_warning' => 'Você não pode excluir o tipo de atividade principal.',
        'delete_usage_warning' => 'O tipo já está associado a atividades, portanto, não pode ser excluído.',
        'delete_usage_calendars_warning' => 'Este tipo é usado como tipo padrão ao criar atividades por meio de calendários conectados e, portanto, não pode ser excluído.',
        'delete_is_default' => 'Este é um tipo de atividade padrão, portanto, não pode ser excluído.',
        'type' => 'Tipo de Atividade',
        'types' => 'Tipos de Atividade',
        'name' => 'Nome',
        'icon' => 'Ícone',
    ],
    'views' => [
        'open' => 'Atividades Abertas',
        'due_today' => 'Actividades Vence Hoje',
        'due_this_week' => 'Actividades Vence Esta Semana',
    ],
    'filters' => [
        'display' => [
            'has' => 'tem atividades :value:',
            'overdue' => 'tem :value: atividades',
            'doesnt_have_activities' => 'não possui qualquer atividade',
        ],
        'all' => 'Todos',
        'today' => 'Hoje',
        'tomorrow' => 'Amanhã',
        'this_week' => 'Esta Semana',
        'next_week' => 'Próxima Semana',
        'done' => 'Concluído',
        'done_empty_state' => 'As atividades concluídas serão mostradas aqui.',
    ],
    'settings' => [
        'send_contact_email' => 'Enviar modelo de e-mail "Contato atende a atividade" para contatos',
        'send_contact_email_info' => 'Se ativado, quando o contato é adicionado como convidado na atividade, um modelo de e-mail será enviado com arquivo .ics anexado e informações da atividade.',
    ],
    'manage_activities' => 'Gerenciar Atividades',
    'info' => 'Agende e gerencie atividades com contatos e vendedores.',
    'timeline' => [
        'heading' => 'Uma atividade foi criada',
    ],
    'permissions' => [
        'attends_and_owned' => 'Participantes e proprietários apenas',
    ],
    'actions' => [
        'update_type' => 'Atualizar tipo',
    ],
    'notifications' => [
        'due' => 'Sua atividade :activity vence em :date',
        'assigned' => 'Você foi atribuído à atividade :name por :user',
        'added_as_guest' => 'Você foi adicionado como convidado à atividade',
    ],
    'mail_placeholders' => [
        'assigneer' => 'O nome de usuário que atribuiu a atividade',
    ],
    'validation' => [
        'end_date' => [
            'less_than_due' => 'A data final não deve ser inferior à data de vencimento.',
        ],
        'end_time' => [
            'required_when_end_date_is_in_future' => 'Você deve especificar a hora de término quando a data de término for em um dia diferente.',
        ],
    ],
    'workflows' => [
        'actions' => [
            'create' => 'Criar Atividade',
        ],
        'fields' => [
            'create' => [
                'title' => 'Com título da atividade',
                'note' => 'Adicionar nota (opcional)',
            ],
        ],
    ],
    'metrics' => [
        'todays' => 'Atividades de Hoje',
    ],
    'empty_state' => [
        'title' => 'Você não criou nenhuma atividade.',
        'description' => 'Comece criando uma nova atividade.',
    ],
];
