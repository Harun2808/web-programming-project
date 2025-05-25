export function initCarsPage() {
  const user = JSON.parse(localStorage.getItem('user'))
  if (user?.role === 'sales') {
    const addBtn = document.getElementById('addCarBtn')
    if (addBtn) addBtn.style.display = 'none'
  }
  loadCars()

  const carForm = document.getElementById('carForm')
  const modalElement = document.getElementById('modalCenter')
  const bootstrapModal = new bootstrap.Modal(modalElement)

  async function fetchCars() {
    const apiBase = 'http://localhost:8000/cars'
    const carTableBody = document.getElementById('carTableBody')

    try {
      const res = await fetch(apiBase)
      if (!res.ok) throw new Error('Failed to load cars')
      const cars = await res.json()

      carTableBody.innerHTML = ''

      cars.forEach(car => {
        carTableBody.innerHTML += `
          <tr>
            <td><strong>${car.make}</strong></td>
            <td>${car.model}</td>
            <td>${car.year}</td>
             <td>${car.price}</td>
      <td>${car.status}</td>
            <td>
              <div class="dropdown">
                <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                  <i class="bx bx-dots-vertical-rounded"></i>
                </button>
                <div class="dropdown-menu">
                  <a href="#" class="dropdown-item edit-car" data-id="${car.id}"><i class="bx bx-edit-alt me-1"></i> Edit</a>
                  <a href="#" class="dropdown-item delete-car" data-id="${car.id}"><i class="bx bx-trash me-1"></i> Delete</a>
                </div>
              </div>
            </td>
          </tr>
        `
      })

      attachCarActionListeners()
    } catch (error) {
      console.error(error)
    }
  }

  function attachCarActionListeners() {
    document.querySelectorAll('.delete-car').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.preventDefault()
        const id = btn.dataset.id
        if (confirm('Delete this car?')) {
          try {
            const res = await fetch(`http://localhost:8000/cars/${id}`, { method: 'DELETE' })
            if (!res.ok) throw new Error('Delete failed')
            loadCars()
          } catch (err) {
            alert('Error deleting car')
          }
        }
      })
    })

    document.querySelectorAll('.edit-car').forEach(btn => {
      btn.addEventListener('click', async e => {
        e.preventDefault()
        const id = btn.dataset.id
        try {
          const res = await fetch(`http://localhost:8000/cars/${id}`)
          if (!res.ok) throw new Error('Fetch failed')
          const car = await res.json()

          carForm.carMake.value = car.make
          carForm.carModel.value = car.model
          carForm.carYear.value = car.year
          carForm.carPrice.value = car.price
          carForm.carStatus.value = car.status
          carForm.dataset.editingId = id
          bootstrapModal.show()
        } catch (err) {
          alert('Error loading car data')
        }
      })
    })
  }

  carForm.addEventListener('submit', async e => {
    e.preventDefault()

    const id = carForm.dataset.editingId
    const payload = {
      make: carForm.carMake.value.trim(),
      model: carForm.carModel.value.trim(),
      year: carForm.carYear.value.trim(),
      price: carForm.carPrice.value.trim(),
      status: carForm.carStatus.value
    }

    try {
      let res
      if (id) {
        res = await fetch(`http://localhost:8000/cars/${id}`, {
          method: 'PUT',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
      } else {
        res = await fetch(`http://localhost:8000/cars`, {
          method: 'POST',
          headers: { 'Content-Type': 'application/json' },
          body: JSON.stringify(payload)
        })
      }

      if (!res.ok) throw new Error('Save failed')
      bootstrapModal.hide()
      loadCars()
    } catch (err) {
      alert('Error saving car')
    }
  })

  function clearCarForm() {
    carForm.reset()
    delete carForm.dataset.editingId
  }

  document.querySelector('[data-bs-target="#modalCenter"]').addEventListener('click', () => {
    clearCarForm()
  })

  function loadCars() {
    fetchCars()
  }
}
