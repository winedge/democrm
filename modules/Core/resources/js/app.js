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
import { useI18n } from 'vue-i18n'
import {
  onBeforeRouteLeave,
  onBeforeRouteUpdate,
  useRoute,
  useRouter,
} from 'vue-router'
import { useStore } from 'vuex'

import './element-prototypes'

import { usePageTitle } from './composables/usePageTitle'
import routes from './router/routes'
import dataViewsStore from './store/DataViews'
import fieldsStore from './store/Fields'
import queryBuilderStore from './store/QueryBuilder'
import tableStore from './store/Table'
import registerComponents from './components'
import registerFields from './fields'
import { t, translate } from './i18n'

createGlobalAppObject()

if (window.Innoclapps) {
  Innoclapps.booting(function (app, router, store) {
    const pageTitle = usePageTitle()

    router.beforeEach((to, from, next) => {
      if (to.meta.title) pageTitle.value = to.meta.title
      if (store.state.sidebarOpen) store.commit('SET_SIDEBAR_OPEN', false)
      next()
    })

    registerComponents(app)
    registerFields(app)

    store.registerModule('fields', fieldsStore)
    store.registerModule('table', tableStore)
    store.registerModule('dataViews', dataViewsStore)
    store.registerModule('queryBuilder', queryBuilderStore)

    routes.forEach(route => router.addRoute(route))

    const commonDraggableOptions = {
      delay: 15,
      delayOnTouchOnly: true,
      animation: 0,
      disabled: false,
      ghostClass: 'drag-ghost-rounded',
    }

    const scrollableDraggableOptions = {
      scroll: true,
      scrollSpeed: 50,
      forceFallback: true,
      ...commonDraggableOptions,
      // Fixes text selection when dragging.
      onStart: function () {
        document.body.style.userSelect = 'none'
      },
      onEnd: function () {
        document.body.style.userSelect = ''
      },
    }

    app.config.globalProperties.$draggable = {
      common: commonDraggableOptions,
      scrollable: scrollableDraggableOptions,
    }

    // Configure global events
    this.$on('conflict', message => {
      if (message) {
        this.info(message)
      }
    })

    this.$on('error-404', () => {
      router.replace({
        name: '404',
      })
    })

    this.$on('error-403', error => {
      if (error.response.config.url !== '/broadcasting/auth') {
        router.replace({
          name: '403',
          query: { message: error.response.data.message },
        })
      }
    })

    this.$on('error', message => {
      if (message) {
        this.error(message)
      }
    })

    this.$on('too-many-requests', () => {
      this.error(translate('core::app.throttle_error'))
    })

    this.$on('token-expired', () => {
      this.error(translate('core::app.token_expired'), 30000, {
        action: {
          onClick: () => window.location.reload(),
          text: translate('core::app.reload'),
        },
      })
    })

    this.$on('maintenance-mode', message => {
      this.info(message || 'Down for maintenance', 30000, {
        action: {
          onClick: () => window.location.reload(),
          text: translate('core::app.reload'),
        },
      })
    })
  })
}

function createGlobalAppObject() {
  window._app_ = {
    router: {
      useRoute,
      useRouter,
      // Works only in "setup".
      onBeforeRouteUpdate,
      onBeforeRouteLeave,
    },
    store: {
      useStore,
    },
    i18n: {
      // Works only in "setup".
      useI18n,
      // Works outside of "setup".
      t,
      translate, // alias of "t"
    },
  }
}
