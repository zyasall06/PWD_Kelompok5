document.addEventListener('DOMContentLoaded', function () {
  const buttons = document.querySelectorAll('.btn');
  const loginForm = document.getElementById('login-form');
  const registerForm = document.getElementById('register-form');
  const purchaseForm = document.getElementById('purchase-form');
  const dashboardSection = document.getElementById('dashboard');
  const profileName = document.getElementById('profile-name');
  const profileEmail = document.getElementById('profile-email');
  const profileStatus = document.getElementById('profile-status');
  const ticketStatusText = document.getElementById('ticket-status-text');
  const ticketDetails = document.getElementById('ticket-details');
  const dashboardTicketType = document.getElementById('dashboard-ticket-type');
  const dashboardTicketSeat = document.getElementById('dashboard-ticket-seat');
  const seatPreviewText = document.getElementById('seat-preview-text');
  const downloadButton = document.getElementById('download-ticket');

  let state = {
    user: null,
    ticket: null,
  };

  function saveState() {
    localStorage.setItem('yf-state', JSON.stringify(state));
  }

  function loadState() {
    const saved = localStorage.getItem('yf-state');
    if (saved) {
      try {
        state = JSON.parse(saved);
      } catch (error) {
        console.error('Failed to parse saved state', error);
      }
    }
  }

  function updateDashboard() {
    if (!state.user) {
      if (profileName) profileName.textContent = 'Guest';
      if (profileEmail) profileEmail.textContent = '-';
      if (profileStatus) profileStatus.textContent = 'Not signed in';
      if (ticketStatusText) ticketStatusText.textContent = 'No ticket purchased yet.';
      if (ticketDetails) ticketDetails.classList.add('hidden');
      if (seatPreviewText) seatPreviewText.textContent = 'No seat selected';
      if (dashboardSection) dashboardSection.classList.add('hidden');
      return;
    }

    if (profileName) profileName.textContent = state.user.name || state.user.email.split('@')[0];
    if (profileEmail) profileEmail.textContent = state.user.email;
    if (profileStatus) profileStatus.textContent = 'Signed in';
    if (dashboardSection) dashboardSection.classList.remove('hidden');

    if (state.ticket) {
      if (ticketStatusText) ticketStatusText.textContent = 'Ticket purchased successfully.';
      if (ticketDetails) ticketDetails.classList.remove('hidden');
      if (dashboardTicketType) dashboardTicketType.textContent = state.ticket.type;
      if (dashboardTicketSeat) dashboardTicketSeat.textContent = state.ticket.seat;
      if (seatPreviewText) seatPreviewText.textContent = `Seat ${state.ticket.seat} in zone ${state.ticket.seat.charAt(0)}`;
    } else {
      if (ticketStatusText) ticketStatusText.textContent = 'No ticket purchased yet.';
      if (ticketDetails) ticketDetails.classList.add('hidden');
      if (seatPreviewText) seatPreviewText.textContent = 'No seat selected';
    }
  }

  function createTicketFile() {
    if (!state.user || !state.ticket) return;

    const ticketContent = `YOUTHREVER FEST 2026 Ticket\n` +
      `Name: ${state.user.name}\n` +
      `Email: ${state.user.email}\n` +
      `Ticket Type: ${state.ticket.type}\n` +
      `Seat: ${state.ticket.seat}\n` +
      `Gate: A5\n` +
      `Date: 20–21 September 2026\n` +
      `Enjoy the festival!`;

    const blob = new Blob([ticketContent], { type: 'text/plain' });
    const url = URL.createObjectURL(blob);
    const link = document.createElement('a');
    link.href = url;
    link.download = `YOUTHREVER-FEST-${state.ticket.type}-TICKET.txt`;
    document.body.appendChild(link);
    link.click();
    document.body.removeChild(link);
    URL.revokeObjectURL(url);
  }

  if (loginForm) {
    loginForm.addEventListener('submit', function (event) {
      event.preventDefault();
      const email = document.getElementById('login-email').value.trim();
      const password = document.getElementById('login-password').value.trim();
      if (!email || !password) {
        alert('Masukkan email dan password untuk masuk.');
        return;
      }
      state.user = {
        email,
        name: email.split('@')[0],
      };
      saveState();
      updateDashboard();
      alert('Berhasil masuk. Dashboard profile aktif.');
    });
  }

  if (registerForm) {
    registerForm.addEventListener('submit', function (event) {
      event.preventDefault();
      const name = document.getElementById('register-name').value.trim();
      const email = document.getElementById('register-email').value.trim();
      const password = document.getElementById('register-password').value.trim();
      if (!name || !email || !password) {
        alert('Lengkapi semua data untuk membuat akun baru.');
        return;
      }
      state.user = {
        name,
        email,
      };
      saveState();
      updateDashboard();
      alert('Akun berhasil dibuat. Silakan cek dashboard.');
    });
  }

  if (purchaseForm) {
    purchaseForm.addEventListener('submit', function (event) {
      event.preventDefault();
      const name = document.getElementById('buyer-name').value.trim();
      const email = document.getElementById('buyer-email').value.trim();
      const type = document.getElementById('ticket-type').value;
      const seat = document.getElementById('seat-select').value;
      if (!name || !email || !type || !seat) {
        alert('Lengkapi semua data tiket sebelum melakukan pembelian.');
        return;
      }
      state.user = state.user || { name, email };
      state.ticket = {
        type,
        seat,
      };
      saveState();
      updateDashboard();
      alert('Pembelian tiket berhasil. Silakan cek dashboard untuk detail dan unduh tiket.');
    });
  }

  if (downloadButton) {
    downloadButton.addEventListener('click', function () {
      createTicketFile();
    });
  }

  buttons.forEach((button) => {
    button.addEventListener('mouseover', function () {
      button.style.boxShadow = '0 16px 32px rgba(203, 161, 53, 0.25)';
    });
    button.addEventListener('mouseout', function () {
      button.style.boxShadow = 'none';
    });
  });

  document.documentElement.style.scrollBehavior = 'smooth';
  loadState();
  updateDashboard();
});
