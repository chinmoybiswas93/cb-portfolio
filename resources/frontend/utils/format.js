export function splitCommaList(str) {
  if (!str) return []
  return str.split(',').map(s => s.trim()).filter(Boolean)
}

export function getDomainFromUrl(url) {
  if (!url) return ''
  try {
    return new URL(url).hostname.replace('www.', '')
  } catch {
    return url
  }
}
