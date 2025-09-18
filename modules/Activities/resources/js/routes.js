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
import { useStorage } from '@vueuse/core'

import { translate } from '@/Core/i18n'

import ActivitiesCalendar from './views/ActivitiesCalendar.vue'
import ActivitiesCreate from './views/ActivitiesCreate.vue'
import ActivitiesEdit from './views/ActivitiesEdit.vue'
import ActivitiesIndex from './views/ActivitiesIndex.vue'
import CalendarSync from './views/CalendarSync.vue'

const isCalendarDefaultView = useStorage(
  'activity-calendar-view-default',
  false
)

export default [
  {
    path: '/calendar/sync',
    name: 'calendar-sync',
    component: CalendarSync,
    meta: {
      title: translate('activities::calendar.calendar_sync'),
    },
  },
  {
    path: '/activities/calendar',
    name: 'activity-calendar',
    component: ActivitiesCalendar,
    meta: { title: translate('activities::calendar.calendar') },
    beforeEnter: () => {
      isCalendarDefaultView.value = true
    },
  },
  {
    path: '/activities',
    name: 'activity-index',
    component: ActivitiesIndex,
    meta: {
      title: translate('activities::activity.activities'),
      subRoutes: ['create-activity', 'edit-activity', 'view-activity'],
      calendarRoute: 'activity-calendar',
    },
    beforeEnter: async (to, from) => {
      if (
        isCalendarDefaultView.value &&
        from.name != to.meta.calendarRoute &&
        to.meta.subRoutes.indexOf(to.name) === -1 &&
        // The calendar does not have filters, hence, it's not supported
        // for this reason, we will show the table view
        !to.query.view_id
      ) {
        return { name: to.meta.calendarRoute }
      }

      if (to.meta.subRoutes.indexOf(to.name) === -1 && !to.query.view_id) {
        isCalendarDefaultView.value = false
      }
    },
    children: [
      {
        path: 'create',
        name: 'create-activity',
        components: {
          create: ActivitiesCreate,
        },
        meta: { title: translate('activities::activity.create') },
      },
      {
        path: ':id',
        name: 'view-activity',
        meta: {
          scrollToTop: false,
        },
        components: {
          edit: ActivitiesEdit,
        },
      },
      {
        path: ':id/edit',
        name: 'edit-activity',
        meta: {
          scrollToTop: false,
        },
        components: {
          edit: ActivitiesEdit,
        },
      },
    ],
  },
]
