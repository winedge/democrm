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
import { useNotification } from '@/Core/composables/useBroadcast'
import { emitGlobal } from '@/Core/composables/useGlobalEventListener'
import { GatePlugin } from '@/Core/gate'
import { translate } from '@/Core/i18n'

import UserInvitationAcceptForm from './components/UserInvitationAcceptForm.vue'
import UsersStore from './store/Users'
import UsersPersonalAccessTokens from './views/UsersPersonalAccessTokens.vue'
import UsersProfile from './views/UsersProfile.vue'
import settingsRoutes from './settingsRoutes'

if (window.Innoclapps) {
  Innoclapps.booting(function (app, router, store) {
    const users = this.scriptConfig('users') || []
    const currentUserId = this.scriptConfig('user_id')

    app.component('UserInvitationAcceptForm', UserInvitationAcceptForm)

    store.registerModule('users', UsersStore)

    store.commit('users/SET', users)

    app.use(GatePlugin, store.getters['users/current'])

    router.beforeEach((to, from, next) => {
      const onlySuperAdminRoute = to.matched.find(
        match => match.meta.superAdmin
      )

      if (
        onlySuperAdminRoute &&
        onlySuperAdminRoute.meta.superAdmin === true &&
        !store.getters['users/current'].super_admin
      ) {
        next({ path: '/403' })
      } else {
        next()
      }
    })

    if (currentUserId) {
      useNotification(currentUserId, notification => {
        this.request(`/notifications/${notification.id}`).then(({ data }) =>
          emitGlobal('new-notification', data)
        )
      })
    }

    // Preload and unlock notification audio on first user interaction.
    // Many browsers block autoplay of audio; playing a short audio on the
    // first user gesture unlocks subsequent programmatic audio.play() calls.
    try {
      const audio = new Audio('/audio/notification.mp3')
      audio.preload = 'auto'
      // store a reference so other parts can reuse or check if preloaded
      window.Innoclapps = window.Innoclapps || {}
      window.Innoclapps.preloadedNotificationAudio = audio

      const unlockAudio = async () => {
        try {
          // try to play/pause to unlock the audio output
          await audio.play()
          audio.pause()
          audio.currentTime = 0
        } catch (e) {
          // If play() is rejected, try to resume AudioContext (some browsers)
          try {
            const Ctx = window.AudioContext || window.webkitAudioContext
            if (Ctx) {
              const ctx = new Ctx()
              await ctx.resume()
            }
          } catch (err) {
            // ignore
          }
        }
      }

      // Unlock on first user gesture
      document.addEventListener('click', unlockAudio, { once: true })
      document.addEventListener('keydown', unlockAudio, { once: true })
    } catch (e) {
      // ignore any errors during preloading
      // eslint-disable-next-line no-console
      console.debug('Notification audio preload failed', e)
    }

    settingsRoutes.forEach(route => router.addRoute('settings', route))

    router.addRoute({
      path: '/profile',
      name: 'profile',
      component: UsersProfile,
      meta: {
        title: translate('users::profile.profile'),
      },
    })

    router.addRoute({
      path: '/personal-access-tokens',
      name: 'personal-access-tokens',
      component: UsersPersonalAccessTokens,
      meta: {
        title: translate('core::api.personal_access_tokens'),
      },
    })
  })
}
