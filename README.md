## N2N CRM — Controllers, Views, Routes, and Request Flow

This document maps the Laravel modules' controllers to their routes and views, and explains the high-level request flow for this repository.

### Overview

- This app is a modular Laravel application. Each module under `modules/` may define its own `routes/`, `app/Http/Controllers/`, and `resources/views/`.
- Blade views in modules are typically referenced via namespaced view hints like `module::path.name` (e.g., `core::app`).
- Routes are split across `routes/web.php`, `routes/api.php`, and module-specific route files in `modules/*/routes/`.

---

## Route Files

Core route files:

- `routes/web.php`
- `routes/api.php`
- `routes/console.php`

Module route files (selection):

- `modules/Core/routes/web.php`
- `modules/Core/routes/api.php`
- `modules/Auth/routes/web.php`
- `modules/Billable/routes/api.php`
- `modules/Brands/routes/api.php`
- `modules/Calls/routes/web.php`
- `modules/Calls/routes/api.php`
- `modules/Comments/routes/api.php`
- `modules/Contacts/routes/api.php`
- `modules/Contacts/routes/channels.php`
- `modules/Deals/routes/api.php`
- `modules/Deals/routes/channels.php`
- `modules/Documents/routes/web.php`
- `modules/Documents/routes/api.php`
- `modules/Installer/routes/web.php`
- `modules/MailClient/routes/web.php`
- `modules/MailClient/routes/api.php`
- `modules/MailClient/routes/channels.php`
- `modules/Notes/routes/api.php`
- `modules/ThemeStyle/routes/web.php`
- `modules/Translator/routes/api.php`
- `modules/Updater/routes/web.php`
- `modules/Updater/routes/api.php`
- `modules/Users/routes/web.php`
- `modules/Users/routes/api.php`
- `modules/Users/routes/channels.php`
- `modules/WebForms/routes/web.php`
- `modules/WebForms/routes/api.php`

---

## Controllers

Controllers discovered across the app (selection):

- `app/Http/Controllers/Controller.php` (base controller)

Core controllers:

- `modules/Core/app/Http/Controllers/ApiController.php`
- `modules/Core/app/Http/Controllers/MediaViewController.php`
- `modules/Core/app/Http/Controllers/OAuthController.php`
- `modules/Core/app/Http/Controllers/PrivacyPolicy.php`
- `modules/Core/app/Http/Controllers/ScriptController.php`
- `modules/Core/app/Http/Controllers/ServeApplication.php`
- `modules/Core/app/Http/Controllers/StyleController.php`
- `modules/Core/app/Http/Controllers/SynchronizationGoogleWebhookController.php`

Other modules:

- `modules/Activities/app/Http/Controllers/OAuthCalendarController.php`
- `modules/Activities/app/Http/Controllers/OutlookCalendarWebhookController.php`
- `modules/Auth/app/Http/Controllers/Auth/ForgotPasswordController.php`
- `modules/Auth/app/Http/Controllers/Auth/LoginController.php`
- `modules/Auth/app/Http/Controllers/Auth/ResetPasswordController.php`
- `modules/Documents/app/Http/Controllers/DocumentController.php`
- `modules/Installer/app/Http/Controllers/InstallController.php`
- `modules/Installer/app/Http/Controllers/RequirementsController.php`
- `modules/MailClient/app/Http/Controllers/MailTrackerController.php`
- `modules/MailClient/app/Http/Controllers/OAuthEmailAccountController.php`
- `modules/ThemeStyle/app/Http/Controllers/ThemeStyle.php`
- `modules/Updater/app/Http/Controllers/FinalizeUpdateController.php`
- `modules/Updater/app/Http/Controllers/MigrateController.php`
- `modules/Updater/app/Http/Controllers/UpdateDownloadController.php`
- `modules/Updater/app/Http/Controllers/FilePermissionsError.php`
- `modules/Users/app/Http/Controllers/UserInvitationAcceptController.php`
- `modules/WebForms/app/Http/Controllers/WebFormController.php`

Note: Many modules also include action controllers (invokables) in `app/Http/Controllers` referenced directly in routes; only key ones are listed above.

---

## Views

App-level views:

- `resources/views/favicon.blade.php`
- `resources/views/errors/resetting.blade.php`
- `resources/views/components/mail/{layout,button,footer,header,layout,panel,subcopy}.blade.php`

Core views:

- `modules/Core/resources/views/app.blade.php`
- `modules/Core/resources/views/boot.blade.php`
- `modules/Core/resources/views/brand.blade.php`
- `modules/Core/resources/views/privacy-policy.blade.php`
- `modules/Core/resources/views/theme-change.blade.php`
- `modules/Core/resources/views/components/layouts/guest.blade.php`
- `modules/Core/resources/views/mail/action.blade.php`
- `modules/Core/resources/views/media/preview.blade.php`
- `modules/Core/resources/views/warnings/*.blade.php`

Other modules (selection):

- Auth: `modules/Auth/resources/views/{login, passwords/email, passwords/reset, components/layouts/auth}.blade.php`
- Billable: `modules/Billable/resources/views/products/table.blade.php`
- Documents: `modules/Documents/resources/views/{view, pdf, signatures}.blade.php`, `modules/Documents/resources/views/mail/{send, thankyou}.blade.php`
- Installer: `modules/Installer/resources/views/{requirements, permissions, setup, user, database, finalize}.blade.php`, plus components/includes
- MailClient: none under `resources/views` except route-driven operations
- ThemeStyle: none (served by controller)
- Updater: `modules/Updater/resources/views/{migrate, finalize}.blade.php`, `modules/Updater/resources/views/errors/{updating, patching}.blade.php`
- Users: `modules/Users/resources/views/invitations/show.blade.php`
- WebForms: `modules/WebForms/resources/views/view.blade.php`

---

## Route → Controller → View Mappings (examples)

Public/document flows:

- Documents
  - Web: `modules/Documents/routes/web.php`
    - `GET /d/{uuid}` → `DocumentController@show` → view `documents::view`
    - `GET /d/{uuid}/pdf` → `DocumentController@pdf` → view render as PDF (`documents::pdf`)
  - API: `modules/Documents/routes/api.php`
    - `POST /d/{uuid}/accept|sign|validate` → `DocumentAcceptController`

- WebForms
  - Web: `modules/WebForms/routes/web.php`
    - `GET /forms/f/{uuid}` → `WebFormController@show` → view `webforms::view`
    - `POST /forms/f/{uuid}` → `WebFormController@store` (process submission)

- Users (Invitations)
  - Web: `modules/Users/routes/web.php`
    - `GET /invitation/{token}` → `UserInvitationAcceptController@show` → view `users::invitations.show`
    - `POST /invitation/{token}` → `UserInvitationAcceptController@accept`

Core app serving and assets:

- Core
  - Web: `modules/Core/routes/web.php`
    - `GET /scripts/{script}` → `ScriptController@show`
    - `GET /styles/{style}` → `StyleController@show`
    - `GET /privacy-policy` → `PrivacyPolicy` → view `core::privacy-policy`
    - `GET /media/{token}` → `MediaViewController@show` → view `core::media.preview`
    - `GET /media/{token}/download|preview` → `MediaViewController`
    - OAuth: `GET /{provider}/connect|callback` → `OAuthController`
  - App entry:
    - `ServeApplication` returns `core::app` which boots the SPA/shell

- Mail tracking and OAuth (MailClient)
  - Web: `modules/MailClient/routes/web.php`
    - `GET /mt/o/{hash}` → `MailTrackerController@opens`
    - `GET /mt/l` → `MailTrackerController@link`
    - `GET /mail/accounts/{type}/{provider}/connect` → `OAuthEmailAccountController@connect`

- Installer/Updater
  - Installer web: `modules/Installer/routes/web.php`
    - `GET /requirements` → `RequirementsController@show` → view `installer::requirements-checker`
    - `GET /setup` → `InstallController@setup` → view `installer::setup`
    - `GET /user` → `InstallController@user` → view `installer::user`
    - `GET /finalize` → `InstallController@finalize` → view `installer::finish`
  - Updater web: `modules/Updater/routes/web.php`
    - `GET /migrate` → `MigrateController@show` → view `updater::migrate`
    - `GET /update/finalize` → `FinalizeUpdateController@show` → view `updater::finalize`

- Auth (web)
  - `modules/Auth/routes/web.php`
    - Login: `Auth\LoginController@showLoginForm` → view `auth::login`
    - Forgot password: `Auth\ForgotPasswordController@showLinkRequestForm` → view `auth::passwords.email`
    - Reset password: `Auth\ResetPasswordController@showResetForm` → view `auth::passwords.reset`

API endpoints (selection):

- Core API: `modules/Core/routes/api.php` (OAuth accounts, tags, Zapier hooks, calendars, etc.)
- Users API: `modules/Users/routes/api.php` (tokens, notifications, teams, profile, avatars)
- MailClient API: `modules/MailClient/routes/api.php` (accounts, messages, templates)
- Deals API: `modules/Deals/routes/api.php` (boards, pipelines, stages, statuses)
- Translator API: `modules/Translator/routes/api.php` (translations CRUD)
- Billable API: `modules/Billable/routes/api.php` (products, etc.)

---

## How Controllers Render Views

Controllers that return Blade views use statements such as:

```php
return view('core::app');
return view('documents::view', compact('document', 'title'));
return view('installer::requirements-checker', [...]);
```

Examples from the codebase:

- `modules/Core/app/Http/Controllers/ServeApplication.php` → `core::app`
- `modules/Core/app/Http/Controllers/PrivacyPolicy.php` → `core::privacy-policy`
- `modules/Core/app/Http/Controllers/MediaViewController.php` → `core::media.preview`
- `modules/Documents/app/Http/Controllers/DocumentController.php` → `documents::view`
- `modules/WebForms/app/Http/Controllers/WebFormController.php` → `webforms::view`
- `modules/Installer/app/Http/Controllers/RequirementsController.php` → `installer::requirements-checker`
- `modules/Updater/app/Http/Controllers/MigrateController.php` → `updater::migrate`
- `modules/Auth/app/Http/Controllers/Auth/LoginController.php` → `auth::login`

---

## Request Flow (High Level)

1) HTTP request enters via `public/index.php` and Laravel bootstrap.
2) Route matching occurs in the consolidated route table (core `routes/*` + module route files under `modules/*/routes/*`).
3) Matched route executes its controller or invokable class. Typical flows:
   - Web page: Controller prepares data and returns a Blade view (namespaced by module).
   - API endpoint: Controller returns JSON resources/responses.
   - File/media: Controller streams/downloads a file (e.g., MediaViewController).
   - OAuth/webhooks: Controller handles third-party callbacks and redirects.
4) Middleware defined per route/group executes before the controller (auth, permission, guest, etc.).
5) Response is sent back to the client. For SPA parts, `core::app` renders the shell and then front-end assets load.

---

## Quick Index: Controller ↔ View (Direct View Returns)

- `modules/Core/app/Http/Controllers/ServeApplication.php` → `core::app`
- `modules/Core/app/Http/Controllers/PrivacyPolicy.php` → `core::privacy-policy`
- `modules/Core/app/Http/Controllers/MediaViewController.php` → `core::media.preview`
- `modules/Documents/app/Http/Controllers/DocumentController.php` → `documents::view`, `documents::pdf`
- `modules/WebForms/app/Http/Controllers/WebFormController.php` → `webforms::view`
- `modules/Installer/app/Http/Controllers/RequirementsController.php` → `installer::requirements-checker`
- `modules/Installer/app/Http/Controllers/InstallController.php` → `installer::{requirements, permissions, setup, user, finish}`
- `modules/Updater/app/Http/Controllers/FinalizeUpdateController.php` → `updater::finalize`
- `modules/Updater/app/Http/Controllers/MigrateController.php` → `updater::migrate`
- `modules/Auth/app/Http/Controllers/Auth/LoginController.php` → `auth::login`
- `modules/Auth/app/Http/Controllers/Auth/ForgotPasswordController.php` → `auth::passwords.email`
- `modules/Auth/app/Http/Controllers/Auth/ResetPasswordController.php` → `auth::passwords.reset`

Note: Some controllers are API-only or return non-view responses.

---

## Conventions and Tips

- Module view namespaces are registered by the module service provider (e.g., `loadViewsFrom`), allowing `module::view` references.
- To trace a page:
  1. Find its URL in a module `routes/web.php`.
  2. Identify the controller and method.
  3. Open the controller to locate a `return view(...)` call.
  4. Open the resolved Blade file in `modules/<Module>/resources/views/...`.
- To trace an API endpoint, follow `routes/api.php` in the relevant module and open the mapped controller.

---

If you want this README to include every single controller and view path exhaustively, we can expand the lists automatically. The above includes the key mappings and patterns used across modules.


