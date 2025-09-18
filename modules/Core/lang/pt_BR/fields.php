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
    'add' => 'Adicionar Novo',
    'fields' => 'Campos',
    'field' => 'Campo',
    'updated' => 'Campos Atualizados',
    'reseted' => 'Os campos foram redefinidos com sucesso',
    'updated_field' => 'Campo Atualizado',
    'optional' => '(opcional)',
    'configured' => 'Campos configurados com sucesso',
    'no_longer_available' => 'Campo não está mais disponível',
    'new_value' => 'Novo Valor',
    'old_value' => 'Valor Antigo',
    'manage' => 'Gerenciar Campos',
    'hide_updated' => 'Esconder Campos Atualizados',
    'view_updated' => 'Ver Campos Atualizados',
    'primary' => 'Campo Primário',
    'visible' => 'Visível',
    'label' => 'Rótulo',
    'is_readonly' => 'Somente Leitura',
    'settings' => [
        'create' => 'Campos de Criação',
        'create_info' => 'Campos que serão exibidos quando algum usuário criar um registro.',
        'update' => 'Campos de Edição',
        'update_info' => 'Campos que estão disponíveis e serão exibidos na visualização de edição/visualização do registro.',
        'detail' => 'Detalhar Campos',
        'detail_info' => 'Campos que estão disponíveis e serão exibidos no detalhe dos registros.',
        'list' => 'Campos de Lista',
        'list_info' => 'Para ajustar os campos exibidos na exibição de lista, ao visualizar a lista :resourceName, clique no ícone :icon localizado na barra de navegação e escolha "Configurações da lista".',
    ],
    'collapsed_by_default' => 'Recolhido por padrão?',
    'is_required' => 'Obrigatório?',
    'option_enabled_will_propagate' => 'Quando ativado, propaga-se também para a visualização ":view_name".',
    'options' => 'Opções',
    'mark_as_unique' => 'Não permitir valores duplicados',
    'mark_as_unique_change_info' => 'O valor desta opção pode ser definido apenas na criação do campo.',
    'validation' => [
        'exist' => 'Parece que o campo com este ID já existe para o recurso.',
        'requires_options' => 'Adicione opções para este campo.',
        'field_type_invalid' => 'O tipo de campo não é suportado',
        'field_id_invalid' => 'Somente caracteres alfabéticos minúsculos (a-z) e sublinhado (_) são aceitos.',
        'refuses_options' => 'Este campo não suporta opções.',
    ],
    'custom' => [
        'create_option_icon' => 'Clique no ícone :icon para criar uma nova opção.',
        'field' => 'Campo Customizado',
        'create' => 'Criar Novo Campo Customizado',
        'update' => 'Atualizar Campo Customizado',
        'type' => 'Tipo do Campo',
        'id' => 'ID do Campo',
        'id_info' => 'Digite o ID do campo em letras minúsculas, apenas caracteres alfabéticos (a-z) e sublinhado (_) são aceitos.',
        'updated' => 'Campo personalizado atualizado com sucesso',
        'created' => 'Campo personalizado criado com sucesso',
        'deleted' => 'Campo personalizado excluído com sucesso',
    ],
    'email_copied' => 'Endereço de e-mail copiado para a área de transferência',
    'next_activity_date' => 'Data da Próxima Atividade',
    'next_activity_date_info' => 'Este campo é somente leitura e é atualizado automaticamente com base nas atividades futuras do registro, indica quando a próxima ação do representante de vendas deve ser tomada.',
    'phones' => [
        'add' => '+ Adicionar outro',
        'copied' => 'Número de telefone copiado para a área de transferência',
        'types' => [
            'type' => 'Tipo',
            'mobile' => 'Celular',
            'work' => 'Trabalho',
            'other' => 'Outro',
        ],
    ],
    'contacts' => [
        'first_name' => 'Primeiro Nome',
        'last_name' => 'Último Nome',
        'email' => 'Endereço de E-mail',
        'job_title' => 'Cargo',
        'phone' => 'Telefone',
        'street' => 'Endereço',
        'city' => 'Cidade',
        'state' => 'Estado',
        'postal_code' => 'CEP',
        'owner_assigned_date' => 'Data de Atribuição do Proprietário',
        'country' => [
            'name' => 'País',
        ],
        'source' => [
            'name' => 'Fonte',
        ],
        'user' => [
            'name' => 'Proprietário',
        ],
    ],
    'companies' => [
        'name' => 'Nome',
        'email' => 'Endereço de E-mail',
        'parent' => [
            'name' => 'Matriz',
        ],
        'phone' => 'Telefone',
        'street' => 'Endereço da Rua',
        'city' => 'Cidade',
        'state' => 'Estado',
        'postal_code' => 'CEP',
        'domain' => 'Nome de domínio da empresa',
        'owner_assigned_date' => 'Data de Atribuição do Proprietário',
        'country' => [
            'name' => 'País',
        ],
        'industry' => [
            'name' => 'Indústria',
        ],
        'user' => [
            'name' => 'Proprietário',
        ],
        'source' => [
            'name' => 'Fonte',
        ],
    ],
    'deals' => [
        'name' => 'Nome do Negócio',
        'expected_close_date' => 'Data Prevista de Fechamento',
        'amount' => 'Quantia',
        'owner_assigned_date' => 'Data de Atribuição do Proprietário',
        'user' => [
            'name' => 'Proprietário',
        ],
        'stage' => [
            'name' => 'Etapa',
        ],
        'pipeline' => [
            'name' => 'Pipeline',
        ],
    ],
    'documents' => [
        'title' => 'Título do Documento',
        'owner_assigned_date' => 'Data de Atribuição do Proprietário',
        'accepted_at' => 'Aceito em',
        'amount' => 'Quantia',
        'original_date_sent' => 'Data Original de Envio',
        'last_date_sent' => 'Última Data de Envio',
        'brand' => [
            'name' => 'Marca',
        ],
        'user' => [
            'name' => 'Proprietário',
        ],
        'type' => [
            'name' => 'Tipo',
        ],
    ],
];
