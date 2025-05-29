function capitalize(str) {
  return str.charAt(0).toUpperCase() + str.slice(1)
}
document.addEventListener('DOMContentLoaded', function () {
  // Initialize the SPA
  initSPA()

  // Load initial page
  const initialPage = window.location.hash.substring(1) || 'dashboard'
  loadPage(initialPage)
})

function initSPA() {
  // Handle menu clicks
  document.addEventListener('click', function (e) {
    const link = e.target.closest('[data-spa-link]')
    if (link) {
      e.preventDefault()
      const page = link.getAttribute('href').substring(1)
      loadPage(page)
      updateActiveMenu(link)
    }
  })

  // Handle browser back/forward
  window.addEventListener('popstate', function () {
    const page = window.location.hash.substring(1) || 'dashboard'
    loadPage(page)
  })
}

function loadPage(page) {
  const contentDiv = document.getElementById('app-content')
  contentDiv.innerHTML = `<div class="text-center py-5">
    <div class="spinner-border text-primary" role="status">
      <span class="visually-hidden">Loading...</span>
    </div>
  </div>`

  if (page !== 'dashboard') {
    history.pushState(null, null, `#${page}`)
  } else {
    history.pushState(null, null, window.location.pathname)
  }

  fetch(`../html/${page}.html`)
    .then(response => {
      if (!response.ok) throw new Error('Page not found')
      return response.text()
    })
    .then(html => {
      contentDiv.innerHTML = html
      document.title = `${page.charAt(0).toUpperCase() + page.slice(1)} | Your App`

      if (page !== 'dashboard') {
        // 🧠 Dynamically import the page-specific module and initialize it
        import(`../js/${page}.js`)
          .then(module => {
            const functionName = `init${capitalize(page)}Page`
            if (typeof module[functionName] === 'function') {
              module[functionName]()
            } else {
              console.warn(`Function ${functionName} not found in ${page}.js`)
            }
          })
          .catch(error => {
            console.error(`Failed to load ${page}.js:`, error)
          })
      }
    })
    .catch(error => {
      console.error('Error loading page:', error)
      contentDiv.innerHTML = `<div class="card">
        <div class="card-body text-center py-5">
          <h4>Error loading page</h4>
          <p>${error.message}</p>
          <a href="#dashboard" class="btn btn-primary" data-spa-link>
              Return to Dashboard
          </a>
        </div>
      </div>`
    })
}

function updateActiveMenu(activeLink) {
  // Remove active class from all menu items
  document.querySelectorAll('.menu-item').forEach(item => {
    item.classList.remove('active')
  })

  // Add active class to current menu item
  const menuItem = activeLink.closest('.menu-item')
  if (menuItem) {
    menuItem.classList.add('active')
  }
}

// function initPageScripts() {
//   if (typeof bootstrap !== 'undefined') {
//     const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
//     tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

//     const dropdowns = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
//     dropdowns.forEach(dropdown => new bootstrap.Dropdown(dropdown));
//   }

//   const currentPage = window.location.hash.substring(1) || 'dashboard';

//   if (currentPage === 'employees') {
//     initEmployeesPage();
//   }
//   // else if (currentPage === 'dashboard') { ... }
// }

// // Your employee page JS moved here:
// function initEmployeesPage() {
//   // example
//   loadEmployees();
//   console.log('Employees page initialized');
//   // attach listeners, modal logic etc.
// }
// function loadEmployees() {
//   const apiBase = 'http://localhost:8000/employees';
//   const employeesTableBody = document.getElementById('employeesTableBody');
//   const employeeForm = document.getElementById('employeeForm');
//   const modalElement = document.getElementById('modalCenter');
//   const bootstrapModal = new bootstrap.Modal(modalElement);

//   if (!employeesTableBody) {
//     console.error('Element #employeesTableBody not found. Make sure the HTML is loaded.');
//     return;
//   }

//   // Load all employees and populate the table
//   async function fetchEmployees() {
//     try {
//       const res = await fetch(apiBase);
//       if (!res.ok) throw new Error('Network error');
//       const employees = await res.json();

//       employeesTableBody.innerHTML = ''; // clear table

//       employees.forEach(emp => {
//         const statusClass =
//           {
//             Active: 'bg-label-success',
//             'On Leave': 'bg-label-warning',
//             Inactive: 'bg-label-danger'
//           }[emp.status] || 'bg-label-secondary';

//         employeesTableBody.innerHTML += `
//           <tr>
//             <td>
//               <div class="d-flex align-items-center">
//                 <div>
//                   <strong>${emp.name}</strong>
//                   <p class="text-muted mb-0">${emp.contact}</p>
//                 </div>
//               </div>
//             </td>
//             <td>${emp.position}</td>
//             <td>${emp.contact}</td>
//             <td><span class="badge ${statusClass} me-1">${emp.status}</span></td>
//             <td>
//               <div class="dropdown">
//                 <button type="button" class="btn p-0 dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
//                   <i class="bx bx-dots-vertical-rounded"></i>
//                 </button>
//                 <div class="dropdown-menu">
//                   <a href="#" class="dropdown-item edit-employee" data-id="${emp.id}">
//                     <i class="bx bx-edit-alt me-1"></i> Edit
//                   </a>
//                   <a href="#" class="dropdown-item delete-employee" data-id="${emp.id}">
//                     <i class="bx bx-trash me-1"></i> Delete
//                   </a>
//                 </div>
//               </div>
//             </td>
//           </tr>`;
//       });

//       attachEmployeeActionListeners();
//     } catch (error) {
//       console.error('Failed to load employees:', error);
//     }
//   }

//   // ✅ Add listeners for edit/delete buttons
//   function attachEmployeeActionListeners() {
//     document.querySelectorAll('.delete-employee').forEach(btn => {
//       btn.addEventListener('click', async e => {
//         e.preventDefault();
//         const id = btn.dataset.id;
//         if (confirm('Are you sure you want to delete this employee?')) {
//           try {
//             const res = await fetch(`${apiBase}/${id}`, { method: 'DELETE' });
//             if (!res.ok) throw new Error('Delete failed');
//             loadEmployees(); // reload after delete
//           } catch (error) {
//             alert('Delete failed');
//             console.error(error);
//           }
//         }
//       });
//     });

//     document.querySelectorAll('.edit-employee').forEach(btn => {
//       btn.addEventListener('click', async e => {
//         e.preventDefault();
//         const id = btn.dataset.id;
//         try {
//           const res = await fetch(`${apiBase}/${id}`);
//           if (!res.ok) throw new Error('Fetch failed');
//           const emp = await res.json();

//           // populate form for editing
//           employeeForm.employeeName.value = emp.name;
//           employeeForm.employeePosition.value = emp.position;
//           employeeForm.employeeContact.value = emp.contact;
//           employeeForm.employeeStatus.value = emp.status;

//           employeeForm.dataset.editingId = id;
//           bootstrapModal.show();
//         } catch (error) {
//           alert('Failed to fetch employee data');
//           console.error(error);
//         }
//       });
//     });
//   }
//   // ✅ Form submit for add/edit
//   employeeForm.addEventListener('submit', async e => {
//     e.preventDefault();

//     const id = employeeForm.dataset.editingId;
//     const user = JSON.parse(localStorage.getItem('user'));
//     const userId = user?.id;
//     const payload = {
//       name: employeeForm.employeeName.value.trim(),
//       position: employeeForm.employeePosition.value.trim(),
//       contact: employeeForm.employeeContact.value.trim(),
//       status: employeeForm.employeeStatus.value,
//       user_id: userId
//     };

//     try {
//       let res;
//       if (id) {
//         // Edit
//         res = await fetch(`${apiBase}/${id}`, {
//           method: 'PUT',
//           headers: { 'Content-Type': 'application/json' },
//           body: JSON.stringify(payload)
//         });
//       } else {
//         // Add
//         res = await fetch(apiBase, {
//           method: 'POST',
//           headers: { 'Content-Type': 'application/json' },
//           body: JSON.stringify(payload)
//         });
//       }

//       if (!res.ok) throw new Error('Failed to save employee');

//       bootstrapModal.hide();
//       loadEmployees(); // reload after save
//     } catch (error) {
//       alert('Error saving employee');
//       console.error(error);
//     }
//   });
//   function clearForm() {
//     employeeForm.reset();
//     delete employeeForm.dataset.editingId;
//   }

//   document.querySelector('[data-bs-target="#modalCenter"]').addEventListener('click', () => {
//     clearForm();
//   });
//   // Initial load
//   fetchEmployees();

//   // Clear form for add

//   // Form submission etc...
// }
