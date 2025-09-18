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

import SettingsTranslator from './components/SettingsTranslator.vue'

if (window.Innoclapps) {
  Innoclapps.booting(function (app, router) {
    router.addRoute('settings', {
      path: '/settings/translator',
      component: SettingsTranslator,
      meta: { title: translate('translator::translator.translator') },
    })
  })
}
