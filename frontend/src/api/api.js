const API_URL = 'http://localhost:8000/api'

async function handleResponse(response) {
    if (!response.ok) {
        const error = await response.json().catch(() => ({}))
        throw new Error(error.message || 'Błąd API')
    }
    return response.json()
}

export async function fetchAnnouncements() {
    const res = await fetch(`${API_URL}/announcements`, {
        headers: {
            Accept: 'application/json',
        },
    })
    return handleResponse(res)
}
export async function createAnnouncement(data) {
    const res = await fetch(`${API_URL}/announcements`, {
        headers: {
            'Accept': 'application/json'
        },
        method: 'POST',
        body: data
    })
    return handleResponse(res)
}

export async function updateAnnouncement(id, data) {
    data.append('_method', 'PUT')
    const res = await fetch(`${API_URL}/announcements/${id}`, {
        headers: {
            'Accept': 'application/json',
        },
        method: 'POST',
        body: data,
    })
    return handleResponse(res)
}

export async function deleteAnnouncement(id) {
    const res = await fetch(`${API_URL}/announcements/${id}`, {
        headers: {
            'Accept': 'application/json'
        },
        method: 'DELETE',
    })

    return handleResponse(res)
}

