document.addEventListener('DOMContentLoaded', function () {
  // Initialize the SPA
  initSPA();

  // Load initial page
  const initialPage = window.location.hash.substring(1) || 'dashboard';
  loadPage(initialPage);
});

function initSPA() {
  // Handle menu clicks
  document.addEventListener('click', function (e) {
    const link = e.target.closest('[data-spa-link]');
    if (link) {
      e.preventDefault();
      const page = link.getAttribute('href').substring(1);
      loadPage(page);
      updateActiveMenu(link);
    }
  });

  // Handle browser back/forward
  window.addEventListener('popstate', function () {
    const page = window.location.hash.substring(1) || 'dashboard';
    loadPage(page);
  });
}

function loadPage(page) {
  // Show loading state
  const contentDiv = document.getElementById('app-content');
  contentDiv.innerHTML = `
        <div class="text-center py-5">
            <div class="spinner-border text-primary" role="status">
                <span class="visually-hidden">Loading...</span>
            </div>
        </div>
    `;

  // Update URL
  if (page !== 'dashboard') {
    history.pushState(null, null, `#${page}`);
  } else {
    history.pushState(null, null, window.location.pathname);
  }

  // Load content from html folder
  fetch(`../html/${page}.html`)
    .then(response => {
      if (!response.ok) throw new Error('Page not found');
      return response.text();
    })
    .then(html => {
      contentDiv.innerHTML = html;
      document.title = `${page.charAt(0).toUpperCase() + page.slice(1)} | Your App`;
      initPageScripts();
    })
    .catch(error => {
      console.error('Error:', error);
      contentDiv.innerHTML = `
                <div class="card">
                    <div class="card-body text-center py-5">
                        <h4>Error loading page</h4>
                        <p>${error.message}</p>
                        <a href="#dashboard" class="btn btn-primary" data-spa-link>
                            Return to Dashboard
                        </a>
                    </div>
                </div>
            `;
    });
}

function updateActiveMenu(activeLink) {
  // Remove active class from all menu items
  document.querySelectorAll('.menu-item').forEach(item => {
    item.classList.remove('active');
  });

  // Add active class to current menu item
  const menuItem = activeLink.closest('.menu-item');
  if (menuItem) {
    menuItem.classList.add('active');
  }
}

function initPageScripts() {
  // Initialize Bootstrap components if needed
  if (typeof bootstrap !== 'undefined') {
    // Tooltips
    const tooltips = [].slice.call(document.querySelectorAll('[data-bs-toggle="tooltip"]'));
    tooltips.forEach(tooltip => new bootstrap.Tooltip(tooltip));

    // Dropdowns
    const dropdowns = [].slice.call(document.querySelectorAll('[data-bs-toggle="dropdown"]'));
    dropdowns.forEach(dropdown => new bootstrap.Dropdown(dropdown));
  }
}
