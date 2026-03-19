export function sortByOrderIndex(items) {
  return [...items].sort((a, b) => (a.order_index || 999) - (b.order_index || 999))
}
