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
const formFields = import.meta.glob('./fields/Form/*.vue', { eager: true })
const detailFields = import.meta.glob('./fields/Detail/*.vue', { eager: true })
const indexFields = import.meta.glob('./fields/Index/*.vue', { eager: true })

function componentNameFromPath(path) {
  return path.split('/').at(-1).split('.')[0]
}

function registerFormFields(app) {
  for (const path in formFields) {
    app.component(
      `Form${componentNameFromPath(path)}`,
      formFields[path].default
    )
  }
}

function registerDetailFields(app) {
  for (const path in detailFields) {
    app.component(
      `Detail${componentNameFromPath(path)}`,
      detailFields[path].default
    )
  }
}

function registerIndexFields(app) {
  for (const path in indexFields) {
    app.component(
      `Index${componentNameFromPath(path)}`,
      indexFields[path].default
    )
  }
}

export default function (app) {
  registerFormFields(app)
  registerDetailFields(app)
  registerIndexFields(app)
}
