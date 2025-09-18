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
    'forms' => 'Formulários web',
    'form' => 'Formulário da Web',
    'created' => 'Formulário da Web adicionado com sucesso.',
    'updated' => 'Formulário da Web atualizado com sucesso.',
    'deleted' => 'Formulário da Web excluído com sucesso.',
    'submission' => 'Submissão do Formulário da Web',
    'total_submissions' => 'Submissões: :total',
    'editor' => 'Editor',
    'submit_options' => 'Opções de envio',
    'info' => 'Crie formulários da web personalizáveis que podem ser incorporados em seu site existente ou compartilhe os formulários como link para criar automaticamente negócios, contatos e empresas.',
    'inactive_info' => 'Este formulário está inativo. Você pode visualizar o formulário porque está conectado. Se quiser que o formulário seja publicamente disponibilizado, certifique-se de defini-lo como ativo.',
    'create' => 'Criar Formulário da Web',
    'active' => 'Ativo',
    'title' => 'Título',
    'title_visibility_info' => 'O título não é visível para os visitantes que preencherão o formulário.',
    'fields_action_required' => 'Ação adicional necessária',
    'required_fields_needed' => 'Para salvar novos negócios, é necessário adicionar pelo menos o campo de e-mail ou telefone de contato.',
    'must_requires_fields' => 'Para salvar novos negócios, seu formulário da web deve exigir pelo menos o campo de e-mail ou telefone de contato.',
    'non_optional_fields_required' => 'Campos não opcionais necessários',
    'notifications' => 'Notificações',
    'notification_email_placeholder' => 'Digite o endereço de e-mail',
    'new_notification' => '+ Adicionar Email',
    'no_sections' => 'Este formulário da web não tem seções definidas.',
    'style' => [
        'style' => 'Estilo',
        'primary_color' => 'Cor Primária',
        'background_color' => 'Cor de Fundo',
        'logo' => 'Exibir um logotipo no topo do formulário.',
    ],
    'success_page' => [
        'success_page' => 'Página de Sucesso',
        'success_page_info' => 'O que deve acontecer depois que um visitante enviar este formulário?',
        'thank_you_message' => 'Exibir mensagem de agradecimento',
        'redirect' => 'Redirecionar para outro site',
        'title' => 'Título',
        'title_placeholder' => 'Digite o texto para a mensagem de sucesso.',
        'message' => 'Mensagem',
        'redirect_url' => 'URL do site',
        'redirect_url_placeholder' => 'Digite a URL para redirecionar após o envio do formulário.',
    ],
    'saving_preferences' => [
        'saving_preferences' => 'Salvando preferências',
        'deal_title_prefix' => 'Prefixo do título do negócio',
        'deal_title_prefix_info' => 'Para cada novo negócio criado por meio do formulário, o nome do negócio será prefixado com o texto adicionado no campo para facilitar o reconhecimento.',
    ],
    'sections' => [
        'new' => 'Adicionar nova seção',
        'type' => 'Tipo de Seção',
        'types' => [
            'input_field' => 'Campo de Entrada',
            'message' => 'Mensagem',
            'file' => 'Arquivo',
        ],
        'field' => [
            'resourceName' => 'Campo para',
        ],
        'introduction' => [
            'introduction' => 'Introdução',
            'title' => 'Título',
            'message' => 'Mensagem',
        ],
        'message' => [
            'message' => 'Mensagem',
        ],
        'file' => [
            'file' => 'Arquivo',
            'files' => 'Arquivos',
            'multiple' => 'Permitir upload de múltiplos arquivos?',
        ],
        'submit' => [
            'button' => 'Botão de envio',
            'default_text' => 'Enviar',
            'button_text' => 'Texto do botão',
            'spam_protected' => 'Protegido contra spam?',
            'require_privacy_policy' => 'Requer consentimento de política de privacidade',
            'privacy_policy_url' => 'URL da política de privacidade',
        ],
        'embed' => [
            'embed' => 'Incorporar',
            'share_via_link' => 'Compartilhar via link',
            'embed_form_Website' => 'Incorporar o formulário no seu site',
            'copy_code_snippet' => 'Copie o trecho de código abaixo',
            'paste_code_form_location' => 'Cole o código exatamente onde deseja que o formulário apareça em seu modelo ou editor CMS',
            'cms_snippet_editing_mode' => 'Ao inserir o trecho no seu CMS, certifique-se de que está no modo :editing_mode.',
            'editing_mode' => 'edição',
            'iframe_protocol_requirement' => 'Você deve colocar o trecho iframe em um site que use o mesmo protocolo que sua instalação. Por exemplo, se a instalação atual usa o protocolo URL :uri_protocol, você precisará adicionar o iframe em um site que use o protocolo :uri_protocol. Adicionar um iframe com URL https em uma URL não https impedirá o carregamento do formulário.',
            'snippet_code' => 'Código do trecho',
        ],
    ],
];
