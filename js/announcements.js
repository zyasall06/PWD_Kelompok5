/* ── Filter ──────────────────────────────────────── */
function filterAnn(type, btn) {
  document.querySelectorAll('.filter-btn').forEach(b => b.classList.remove('active'));
  btn.classList.add('active');

  const cards = document.querySelectorAll('.ann-card');
  let visible = 0;
  cards.forEach(card => {
    const match = type === 'all' || card.dataset.type === type;
    card.style.display = match ? '' : 'none';
    if (match) visible++;
  });
  document.getElementById('annEmpty').classList.toggle('show', visible === 0);
}

/* ── Scroll reveal ───────────────────────────────── */
const io = new IntersectionObserver(entries => {
  entries.forEach(e => { 
    if (e.isIntersecting) { 
      e.target.classList.add('visible'); 
      io.unobserve(e.target); 
    } 
  });
}, { threshold: 0.1 });
document.querySelectorAll('.ann-card').forEach((c, i) => {
  c.style.transitionDelay = (i * 0.08) + 's';
  io.observe(c);
});

/* ── Nav toggle ──────────────────────────────────── */
(function(){
  var btn = document.querySelector('.nav-toggle');
  var nav = document.querySelector('nav');
  if (!btn||!nav) return;
  btn.addEventListener('click', function(){ nav.classList.toggle('nav-open'); });
})();
