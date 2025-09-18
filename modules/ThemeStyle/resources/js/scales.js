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
import { DEFAULT_STOP, DEFAULT_STOPS } from './constants'

export function createSaturationScale(tweak = 0, stop = DEFAULT_STOP) {
  const stops = DEFAULT_STOPS
  const index = stops.indexOf(stop)

  if (index === -1) {
    throw new Error(`Invalid key value: ${stop}`)
  }

  return stops.map(stop => {
    const diff = Math.abs(stops.indexOf(stop) - index)

    const tweakValue = tweak
      ? Math.round((diff + 1) * tweak * (1 + diff / 10))
      : 0

    if (tweakValue > 100) {
      return { stop, tweak: 100 }
    }

    return { stop, tweak: tweakValue }
  })
}

export function createHueScale(tweak = 0, stop = DEFAULT_STOP) {
  const stops = DEFAULT_STOPS
  const index = stops.indexOf(stop)

  if (index === -1) {
    throw new Error(`Invalid parameter value: ${stop}`)
  }

  return stops.map(stop => {
    const diff = Math.abs(stops.indexOf(stop) - index)
    const tweakValue = tweak ? (diff + 1) * tweak - tweak : 0

    // If tweak value is below 0 or above 360, wrap it around
    if (tweakValue < 0) {
      return { stop, tweak: 360 + tweakValue }
    } else if (tweakValue > 360) {
      return { stop, tweak: tweakValue - 360 }
    }

    return { stop, tweak: tweakValue }
  })
}

export function createDistributionValues(
  min = 0,
  max = 100,
  lightness,
  stop = DEFAULT_STOP
) {
  const stops = DEFAULT_STOPS

  // Create known stops
  const newValues = [
    { stop: 0, tweak: max },
    { stop, tweak: lightness },
    { stop: 1000, tweak: min },
  ]

  // Create missing stops
  for (let i = 0; i < stops.length; i++) {
    const stopValue = stops[i]

    if (stopValue === 0 || stopValue === 1000 || stopValue === stop) {
      continue
    }

    const diff = Math.abs((stopValue - stop) / 100)

    const totalDiff =
      stopValue < stop
        ? Math.abs(stops.indexOf(stop) - stops.indexOf(DEFAULT_STOPS[0])) - 1
        : Math.abs(
            stops.indexOf(stop) -
              stops.indexOf(DEFAULT_STOPS[DEFAULT_STOPS.length - 1])
          ) - 1

    const increment = stopValue < stop ? max - lightness : lightness - min

    const tweak =
      stopValue < stop
        ? (increment / totalDiff) * diff + lightness
        : lightness - (increment / totalDiff) * diff

    newValues.push({ stop: stopValue, tweak: Math.round(tweak) })
  }

  newValues.sort((a, b) => a.stop - b.stop)

  return newValues
}
