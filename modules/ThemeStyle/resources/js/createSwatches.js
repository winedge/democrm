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
import { DEFAULT_PALETTE_CONFIG } from './constants'
import {
  hexToHSL,
  HSLToHex,
  lightnessFromHSLum,
  luminanceFromHex,
} from './helpers'
import {
  createDistributionValues,
  createHueScale,
  createSaturationScale,
} from './scales'

export function createSwatches(palette) {
  const { value, valueStop } = palette

  // Tweaks may be passed in, otherwise use defaults
  const useLightness =
    palette.useLightness ?? DEFAULT_PALETTE_CONFIG.useLightness

  const h = palette.h ?? DEFAULT_PALETTE_CONFIG.h
  const s = palette.s ?? DEFAULT_PALETTE_CONFIG.s
  const lMin = palette.lMin ?? DEFAULT_PALETTE_CONFIG.lMin
  const lMax = palette.lMax ?? DEFAULT_PALETTE_CONFIG.lMax

  // Create hue and saturation scales based on tweaks
  const hueScale = createHueScale(h, valueStop)
  const saturationScale = createSaturationScale(s, valueStop)

  // Get the base hex's H/S/L values
  const { h: valueH, s: valueS, l: valueL } = hexToHSL(value)

  // Create lightness scales based on tweak + lightness/luminance of current value
  const lightnessValue = useLightness ? valueL : luminanceFromHex(value)

  const distributionScale = createDistributionValues(
    lMin,
    lMax,
    lightnessValue,
    valueStop
  )

  const swatches = hueScale.map(({ stop }, stopIndex) => {
    const newH = valueH + hueScale[stopIndex].tweak
    const newS = valueS + saturationScale[stopIndex].tweak

    const newL = useLightness
      ? distributionScale[stopIndex].tweak
      : lightnessFromHSLum(newH, newS, distributionScale[stopIndex].tweak)

    const newHex = HSLToHex(newH, newS, newL)

    return {
      stop,
      // Sometimes the initial value is changed slightly during conversion,
      // overriding that with the original value
      hex:
        stop === valueStop ? `#${value.toUpperCase()}` : newHex.toUpperCase(),
      // Used in graphs
      h: newH,
      hScale: hueScale[stopIndex].tweak,
      s: newS,
      sScale: saturationScale[stopIndex].tweak,
      l: newL,
    }
  })

  return swatches
}
