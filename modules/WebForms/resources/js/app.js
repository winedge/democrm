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
import { translate } from '@/Core/i18n'

import RecordTabTimelineWebFormSubmission from './components/RecordTabTimelineWebFormSubmission.vue'
import SettingsWebForms from './components/SettingsWebForms.vue'
import WebFormPublicView from './views/WebFormPublicView.vue'
import CreateWebForm from './views/WebFormsCreate.vue'
import EditWebForm from './views/WebFormsEdit.vue'

if (window.Innoclapps) {
  Innoclapps.booting(function (app, router) {
    app.component('WebFormPublicView', WebFormPublicView)

    app.component(
      'WebFormSubmissionChangelog',
      RecordTabTimelineWebFormSubmission
    )

    router.addRoute('settings', {
      path: 'forms/:id/edit',
      name: 'web-form-edit',
      component: EditWebForm,
    })

    router.addRoute('settings', {
      path: 'forms',
      name: 'web-forms-index',
      component: SettingsWebForms,
      meta: {
        title: translate('webforms::form.forms'),
      },
      children: [
        {
          path: 'create',
          name: 'web-form-create',
          component: CreateWebForm,
        },
      ],
    })
  })
}
