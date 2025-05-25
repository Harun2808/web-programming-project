export function initUsersPage() {
  const apiBase = 'http://localhost:8000/users'
  const usersTableBody = document.getElementById('usersTableBody')
  const usersForm = document.getElementById('usersForm')
  const usersModalElement = document.getElementById('usersModal')
  const bootstrapModal = new bootstrap.Modal(usersModalElement)
  const addUserBtn = document.getElementById('addUserBtn')

  async function fetchUsers() {
    try {
      const res = await fetch(apiBase)
      if (!res.ok) throw new Error('Failed to fetch users')
      const users = await res.json()

      usersTableBody.innerHTML = ''
      users.forEach(user => {
        usersTableBody.innerHTML += `
          <tr>
            <td>${user.name}</td>
            <td>${user.email}</td>
            <td>${user.role || ''}</td>
       
           
                 <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item edit-user" data-id="${user.id}">
                    <i class="bx bx-edit-alt me-1"></i> Edit
                  </a>
                  <a href="#" class="dropdown-item delete-user" data-id="${user.id}">
                    <i class="bx bx-trash me-1"></i> Delete
                  </a>
                </div>
              </div>
        
            </td>
          </tr>
        `
      })

      attachActionListeners()
    } catch (error) {
      console.error('Error loading users:', error)
    }
  }

  function attachActionListeners() {
    document.querySelectorAll('.edit-user').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.preventDefault()
        const id = btn.dataset.id
        try {
          const res = await fetch(`${apiBase}/${id}`)
          if (!res.ok) throw new Error('Failed to fetch user')
          const user = await res.json()

          document.getElementById('email').value = user.email
          document.getElementById('role').value = user.role || ''
          document.getElementById('hiddenName').value = user.name || ''
          document.getElementById('hiddenPassword').value = user.password || ''

          bootstrapModal.show()
          usersForm.dataset.editingId = id
        } catch (error) {
          alert('Failed to load user data')
          console.error(error)
        }
      })
    })

    document.querySelectorAll('.delete-user').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.preventDefault()
        const id = btn.dataset.id
        if (confirm('Are you sure you want to delete this user?')) {
          try {
            const res = await fetch(`${apiBase}/${id}`, { method: 'DELETE' })
            if (!res.ok) throw new Error('Delete failed')
            fetchUsers()
          } catch (error) {
            alert('Failed to delete user')
            console.error(error)
          }
        }
      })
    })
  }

  usersForm.addEventListener('submit', async e => {
    e.preventDefault()

    const id = usersForm.dataset.editingId
    const payload = {
      email: document.getElementById('email').value.trim(),
      role: document.getElementById('role').value.trim(),
      name: document.getElementById('hiddenName').value.trim(),
      password: document.getElementById('hiddenPassword').value.trim()
    }

    // Only send password if filled (for create or password change)

    try {
      let res
      if (id) {
        res = await fetch(`${apiBase}/${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
      } else {
        // For new user, password is required, validate here
        if (!payload.password) {
          alert('Password is required for new users')
          return
        }
        res = await fetch(apiBase, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
      }

      if (!res.ok) throw new Error('Failed to save user')

      bootstrapModal.hide()
      usersForm.reset()
      fetchUsers()
    } catch (error) {
      alert('Error saving user')
      console.error(error)
    }
  })

  //   document.querySelector('[data-bs-target="#usersModal"]').addEventListener('click', () => {
  //     usersForm.reset()
  //     delete usersForm.dataset.editingId
  //   })

  // Initial fetch
  fetchUsers()
}
