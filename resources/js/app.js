// ===========================
// Cyber Café 2099 — Main JS
// ===========================

// ---------- Rain Effect ----------
const rainContainer = document.getElementById('rain');

if (rainContainer) {
  for (let i = 0; i < 120; i++) {
    const drop = document.createElement('div');
    drop.classList.add('drop');
    drop.style.left            = Math.random() * 100 + 'vw';
    drop.style.animationDuration = (Math.random() * 1 + 0.5) + 's';
    drop.style.opacity         = Math.random();
    rainContainer.appendChild(drop);
  }
}

// ---------- Flash Message Auto-Dismiss ----------
const alerts = document.querySelectorAll('.alert');
alerts.forEach(alert => {
  setTimeout(() => {
    alert.style.transition = 'opacity 0.5s';
    alert.style.opacity = '0';
    setTimeout(() => alert.remove(), 500);
  }, 4000);
});