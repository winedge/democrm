<template>
  <span class="relative">
    <span
      v-if="hasUnreadNotifications"
      v-i-tooltip.bottom="totalUnreadNotifications"
      class="absolute -right-1 -top-3 z-10"
    >
      <span class="relative flex size-3">
        <span
          class="absolute inline-flex h-full w-full animate-ping rounded-full bg-primary-400 opacity-75"
        />

        <span class="relative inline-flex size-3 rounded-full bg-primary-500" />
      </span>
    </span>

    <IDropdown
      v-slot="{ hide }"
      placement="bottom-end"
      @show="loadNotifications"
    >
      <IDropdownButton variant="secondary" pill no-caret>
        <Icon icon="Bell" />
      </IDropdownButton>

      <IDropdownMenu class="max-w-xs sm:max-w-sm">
        <div class="-m-1">
          <IOverlay :show="notificationsBeingLoaded">
            <div
              :class="[
                'flex items-center px-4 py-3 sm:p-4',
                {
                  'border-b border-neutral-200 dark:border-neutral-500/30':
                    total > 0,
                },
              ]"
            >
              <div class="grow">
                <ITextDisplay
                  v-if="total > 0"
                  :text="$t('core::notifications.notifications')"
                />

                <IText
                  v-else
                  class="font-medium"
                  :text="$t('core::notifications.no_notifications')"
                />
              </div>

              <span
                v-i-tooltip="$t('core::settings.settings')"
                class="ml-2 inline-block"
              >
                <ILink
                  :to="{ name: 'profile', hash: '#notifications' }"
                  @click="hide"
                >
                  <Icon icon="Cog" class="size-5" />
                </ILink>
              </span>
            </div>

            <div class="max-h-96 overflow-y-auto">
              <template
                v-for="(notification, nidx) in notifications"
                :key="notification.id"
              >
                <IDropdownItem
                  class="rounded-none"
                  :title="localizeNotification(notification)"
                  :to="notification.data.path"
                  @click="hide"
                >
                  <IDropdownItemLabel class="truncate">
                    {{ localizeNotification(notification) }}
                  </IDropdownItemLabel>

                  <IDropdownItemDescription
                    :text="localizedDateTime(notification.created_at)"
                  />
                </IDropdownItem>

                <IDropdownSeparator v-if="nidx !== notifications.length - 1" />
              </template>
            </div>

            <div
              v-show="total > 0"
              class="flex items-center justify-end border-t border-neutral-200 px-4 py-2 dark:border-neutral-500/30"
            >
              <ILink
                :to="{ name: 'notifications' }"
                :text="$t('core::app.see_all')"
                @click="hide"
              />
            </div>
          </IOverlay>
        </div>
      </IDropdownMenu>
    </IDropdown>
  </span>
</template>

<script setup>
import { computed, shallowRef } from 'vue'
import { useI18n } from 'vue-i18n'
import { useRouter } from 'vue-router'

import { useDates } from '@/Core/composables/useDates'
import { useGlobalEventListener } from '@/Core/composables/useGlobalEventListener'

import { useNotifications } from '../composables/useNotifications'

const {
  localizeNotification,
  incrementTotalUnreadNotifications,
  markNotificationAsRead,
  markAllNotificationsRead,
  totalUnreadNotifications,
  hasUnreadNotifications,
  notificationsBeingLoaded,
  fetchNotifications,
} = useNotifications()

const router = useRouter()
const { localizedDateTime } = useDates()
const { t } = useI18n()

const notifications = shallowRef([])

const total = computed(() => notifications.value.length)

async function loadNotifications() {
  markAllNotificationsRead()
  const { data: pagination } = await fetchNotifications()
  notifications.value = pagination.data // paginated results, we are taking the latest 15 notifications
}

function sendStaticFloatingNotification(notification) {
  const options = {
    closed: () => markNotificationAsRead(notification.id),
    action: {
      text: t('core::app.view_record'),
      onClick: () => {
        markNotificationAsRead(notification.id)
        router.push(notification.data.path)

        return true
      },
    },
  }
  
  // Check if notification should be persistent (default to 8 seconds)
  let duration = 8000
  if (notification.data.persistent) {
    duration = -1 // -1 means no auto-dismiss
  }
  
  // ALWAYS play sound for notifications - make it more prominent
  playNotificationSound()
  
  Innoclapps.notify(
    localizeNotification(notification),
    null,
    duration,
    options,
    'static'
  )
}

// Separate function to handle notification sounds
function playNotificationSound() {
  console.log('Attempting to play notification sound...')
  
  // Try multiple approaches to ensure sound plays
  const soundMethods = [
    // Method 1: Try to play MP3 file
    () => {
      const audio = new Audio('/audio/notification.mp3')
      audio.volume = 0.7
      audio.preload = 'auto'
      return audio.play()
    },
    
    // Method 2: Try alternative MP3 path
    () => {
      const audio = new Audio('./audio/notification.mp3')
      audio.volume = 0.7
      audio.preload = 'auto'
      return audio.play()
    },
    
    // Method 3: Generate beep sound with Web Audio API
    () => {
      return new Promise((resolve, reject) => {
        try {
          const audioContext = new (window.AudioContext || window.webkitAudioContext)()
          
          // Resume audio context if suspended
          if (audioContext.state === 'suspended') {
            audioContext.resume()
          }
          
          const oscillator = audioContext.createOscillator()
          const gainNode = audioContext.createGain()
          
          oscillator.connect(gainNode)
          gainNode.connect(audioContext.destination)
          
          // Create a pleasant two-tone notification sound
          oscillator.frequency.setValueAtTime(800, audioContext.currentTime)
          oscillator.frequency.setValueAtTime(1000, audioContext.currentTime + 0.15)
          oscillator.type = 'sine'
          
          gainNode.gain.setValueAtTime(0.4, audioContext.currentTime)
          gainNode.gain.exponentialRampToValueAtTime(0.01, audioContext.currentTime + 0.4)
          
          oscillator.start(audioContext.currentTime)
          oscillator.stop(audioContext.currentTime + 0.4)
          
          oscillator.onended = () => resolve()
          
          setTimeout(() => resolve(), 500) // Fallback timeout
        } catch (error) {
          reject(error)
        }
      })
    },
    
    // Method 4: Try system notification sound (if available)
    () => {
      if ('Notification' in window && Notification.permission === 'granted') {
        // Create a silent notification just to trigger system sound
        const systemNotification = new Notification('', {
          silent: false,
          tag: 'crm-sound-trigger'
        })
        setTimeout(() => systemNotification.close(), 100)
        return Promise.resolve()
      }
      return Promise.reject('No system notification support')
    }
  ]
  
  // Try each method until one succeeds
  async function tryNextMethod(index = 0) {
    if (index >= soundMethods.length) {
      console.error('All notification sound methods failed')
      return
    }
    
    try {
      await soundMethods[index]()
      console.log(`Notification sound played successfully using method ${index + 1}`)
    } catch (error) {
      console.warn(`Sound method ${index + 1} failed:`, error)
      tryNextMethod(index + 1)
    }
  }
  
  tryNextMethod()
}

useGlobalEventListener('new-notification', notification => {
  incrementTotalUnreadNotifications()
  sendStaticFloatingNotification(notification)

  // In case the dropdown is already visible
  notifications.value = [notification, ...notifications.value]
})
</script>
