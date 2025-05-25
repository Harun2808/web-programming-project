export function initCustomersPage() {
  const user = JSON.parse(localStorage.getItem('user'))
  if (user?.role === 'sales') {
    const addBtn = document.getElementById('addCustomerBtn')
    if (addBtn) addBtn.style.display = 'none'
  }
  const apiBase = 'http://localhost:8000/customers'
  const customersTableBody = document.getElementById('customersTableBody')
  const customerForm = document.getElementById('customerForm')
  const modalElement = document.getElementById('customerModal')
  const bootstrapModal = new bootstrap.Modal(modalElement)

  // ✅ Get auth headers
  function getAuthHeaders() {
    const token = localStorage.getItem('token')
    return {
      'Content-Type': 'application/json',
      Authorization: `Bearer ${token}`
    }
  }

  // ✅ Wrapper to handle 401 responses globally
  async function fetchWithAuth(url, options = {}) {
    const res = await fetch(url, {
      ...options,
      headers: {
        ...getAuthHeaders(),
        ...(options.headers || {})
      }
    })

    if (res.status === 401) {
      alert('Session expired. Please login again.')
      localStorage.removeItem('token')
      window.location.href = '/login.html'
      return null
    }

    return res
  }

  async function fetchCustomers() {
    try {
      const res = await fetchWithAuth(apiBase)
      if (!res) return

      const customers = await res.json()
      customersTableBody.innerHTML = ''

      customers.forEach(customer => {
        customersTableBody.innerHTML += `
          <tr>
            <td>${customer.name}</td>
            <td>${customer.email}</td>
            <td>${customer.phone}</td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item edit-customer" data-id="${customer.id}">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <a href="#" class="dropdown-item delete-customer" data-id="${customer.id}">
                    <i class="bx bx-trash me-1"></i> Delete
                  </a>
                </div>
              </div>
            </td>
          </tr>`
      })

      attachCustomerActionListeners()
    } catch (error) {
      console.error('Failed to load customers:', error)
    }
  }

  function attachCustomerActionListeners() {
    document.querySelectorAll('.delete-customer').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.preventDefault()
        const id = btn.dataset.id
        if (confirm('Are you sure you want to delete this customer?')) {
          try {
            const res = await fetchWithAuth(`${apiBase}/${id}`, { method: 'DELETE' })
            if (!res) return
            if (!res.ok) throw new Error('Delete failed')
            fetchCustomers()
          } catch (error) {
            alert('Delete failed')
            console.error(error)
          }
        }
      })
    })

    document.querySelectorAll('.edit-customer').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.preventDefault()
        const id = btn.dataset.id
        try {
          const res = await fetchWithAuth(`${apiBase}/${id}`)
          if (!res) return
          if (!res.ok) throw new Error('Fetch failed')
          const customer = await res.json()

          customerForm.customerName.value = customer.name
          customerForm.customerEmail.value = customer.email
          customerForm.customerPhone.value = customer.phone

          customerForm.dataset.editingId = id
          bootstrapModal.show()
        } catch (error) {
          alert('Failed to fetch customer data')
          console.error(error)
        }
      })
    })
  }

  customerForm.addEventListener('submit', async e => {
    e.preventDefault()

    const id = customerForm.dataset.editingId
    const payload = {
      name: customerForm.customerName.value.trim(),
      email: customerForm.customerEmail.value.trim(),
      phone: customerForm.customerPhone.value.trim()
    }

    try {
      let res
      if (id) {
        res = await fetchWithAuth(`${apiBase}/${id}`, {
          method: 'PUT',
          body: JSON.stringify(payload)
        })
      } else {
        res = await fetchWithAuth(apiBase, {
          method: 'POST',
          body: JSON.stringify(payload)
        })
      }

      if (!res) return
      if (!res.ok) throw new Error('Failed to save customer')

      bootstrapModal.hide()
      fetchCustomers()
    } catch (error) {
      alert('Error saving customer')
      console.error(error)
    }
  })

  document.querySelector('[data-bs-target="#customerModal"]').addEventListener('click', () => {
    customerForm.reset()
    delete customerForm.dataset.editingId
  })

  fetchCustomers()
}
