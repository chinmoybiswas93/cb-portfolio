export function parseDuration(durationString) {
  if (!durationString) return 0
  const trimmed = durationString.trim()
  if (trimmed.endsWith('ms')) {
    return parseFloat(trimmed) || 0
  }
  if (trimmed.endsWith('s')) {
    return (parseFloat(trimmed) || 0) * 1000
  }
  const numeric = parseFloat(trimmed)
  return Number.isNaN(numeric) ? 0 : numeric * 1000
}

export function getLoaderCycleDuration(element) {
  if (!element) return 2400

  const styles = window.getComputedStyle(element)
  const customDuration = styles.getPropertyValue('--cb-loader-duration')
  if (customDuration) {
    return parseDuration(customDuration) || 2400
  }

  const animationDuration = styles.animationDuration || ''
  return parseDuration(animationDuration.split(',')[0]) || 2400
}

export function revealAppShell(rootId) {
  const root = document.getElementById(rootId)
  const loader = document.getElementById('cb-portfolio-loader')
  if (!root) return

  root.classList.remove('cb-portfolio-hidden')
  root.classList.add('cb-portfolio-ready')

  if (loader) {
    loader.classList.add('cb-portfolio-loader--hidden')
    setTimeout(() => loader.parentNode?.removeChild(loader), 400)
  }
}
