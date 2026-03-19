const baseHeaders = () => ({
  'X-WP-Nonce': cbPortfolioData.nonce
})

const jsonHeaders = () => ({
  ...baseHeaders(),
  'Content-Type': 'application/json'
})

const handleResponse = async (response) => {
  if (!response.ok) {
    let message = `Server responded with ${response.status}`
    try {
      const data = await response.json()
      if (data.message) message = data.message
    } catch (_) {}
    throw new Error(message)
  }
  const text = await response.text()
  return text ? JSON.parse(text) : null
}

const api = {
  async get(endpoint) {
    const response = await fetch(`${cbPortfolioData.restUrl}${endpoint}`, {
      headers: baseHeaders()
    })
    return handleResponse(response)
  },

  async post(endpoint, data) {
    const response = await fetch(`${cbPortfolioData.restUrl}${endpoint}`, {
      method: 'POST',
      headers: jsonHeaders(),
      body: JSON.stringify(data)
    })
    return handleResponse(response)
  },

  async del(endpoint) {
    const response = await fetch(`${cbPortfolioData.restUrl}${endpoint}`, {
      method: 'DELETE',
      headers: baseHeaders()
    })
    return handleResponse(response)
  }
}

export default api
