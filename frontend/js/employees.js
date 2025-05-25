// public/js/employees.js
export function initEmployeesPage() {
  const user = JSON.parse(localStorage.getItem('user'))
  if (user?.role === 'sales') {
    const addBtn = document.getElementById('addEmployeeBtn')
    if (addBtn) addBtn.style.display = 'none'
  }
  const apiBase = 'http://localhost:8000/employees'
  const employeesTableBody = document.getElementById('employeesTableBody')
  const employeeForm = document.getElementById('employeeForm')
  const modalElement = document.getElementById('modalCenter')
  const bootstrapModal = new bootstrap.Modal(modalElement)

  async function fetchEmployees() {
    try {
      const res = await fetch(apiBase)
      if (!res.ok) throw new Error('Network error')
      const employees = await res.json()

      employeesTableBody.innerHTML = ''

      employees.forEach(emp => {
        const statusClass =
          {
            Active: 'bg-label-success',
            'On Leave': 'bg-label-warning',
            Inactive: 'bg-label-danger'
          }[emp.status] || 'bg-label-secondary'

        employeesTableBody.innerHTML += `
          <tr>
            <td>
              <div class="d-flex align-items-center">
                <div>
                  <strong>${emp.name}</strong>
                  <p class="text-muted mb-0">${emp.contact}</p>
                </div>
              </div>
            </td>
            <td>${emp.position}</td>
            <td>${emp.contact}</td>
            <td><span class="badge ${statusClass} me-1">${emp.status}</span></td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item edit-employee" data-id="${emp.id}">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <a href="#" class="dropdown-item delete-employee" data-id="${emp.id}">
                    <i class="bx bx-trash me-1"></i> Delete
                  </a>
                </div>
              </div>
            </td>
          </tr>`
      })

      attachEmployeeActionListeners()
    } catch (error) {
      console.error('Failed to load employees:', error)
    }
  }

  function attachEmployeeActionListeners() {
    document.querySelectorAll('.delete-employee').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.preventDefault()
        const id = btn.dataset.id
        if (confirm('Are you sure you want to delete this employee?')) {
          try {
            const res = await fetch(`${apiBase}/${id}`, { method: 'DELETE' })
            if (!res.ok) throw new Error('Delete failed')
            fetchEmployees()
          } catch (error) {
            alert('Delete failed')
            console.error(error)
          }
        }
      })
    })

    document.querySelectorAll('.edit-employee').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.preventDefault()
        const id = btn.dataset.id
        try {
          const res = await fetch(`${apiBase}/${id}`)
          if (!res.ok) throw new Error('Fetch failed')
          const emp = await res.json()

          employeeForm.employeeName.value = emp.name
          employeeForm.employeePosition.value = emp.position
          employeeForm.employeeContact.value = emp.contact
          employeeForm.employeeStatus.value = emp.status

          employeeForm.dataset.editingId = id
          bootstrapModal.show()
        } catch (error) {
          alert('Failed to fetch employee data')
          console.error(error)
        }
      })
    })
  }

  employeeForm.addEventListener('submit', async e => {
    e.preventDefault()

    const id = employeeForm.dataset.editingId
    const user = JSON.parse(localStorage.getItem('user'))
    const userId = user?.id

    const payload = {
      name: employeeForm.employeeName.value.trim(),
      position: employeeForm.employeePosition.value.trim(),
      contact: employeeForm.employeeContact.value.trim(),
      status: employeeForm.employeeStatus.value,
      user_id: userId
    }

    try {
      let res
      if (id) {
        res = await fetch(`${apiBase}/${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
      } else {
        res = await fetch(apiBase, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
      }

      if (!res.ok) throw new Error('Failed to save employee')

      bootstrapModal.hide()
      fetchEmployees()
    } catch (error) {
      alert('Error saving employee')
      console.error(error)
    }
  })

  document.querySelector('[data-bs-target="#modalCenter"]').addEventListener('click', () => {
    employeeForm.reset()
    delete employeeForm.dataset.editingId
  })

  fetchEmployees()
}
