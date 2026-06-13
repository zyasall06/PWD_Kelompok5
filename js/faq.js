/* ── Accordion toggle ────────────────────────────── */
function toggleFaq(id) {
  const item = document.getElementById('faqItem' + id);
  const isOpen = item.classList.contains('open');
  // close all
  document.querySelectorAll('.faq-item').forEach(el => {
    el.classList.remove('open');
    el.querySelector('.q-icon').textContent = '+';
  });
  // open clicked if it was closed
  if (!isOpen) {
    item.classList.add('open');
    item.querySelector('.q-icon').textContent = '+';
  }
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
document.querySelectorAll('.faq-item').forEach((el, i) => {
  el.style.transitionDelay = (i * 0.07) + 's';
  io.observe(el);
});

/* ── Nav toggle ──────────────────────────────────── */
(function(){
  var btn = document.querySelector('.nav-toggle');
  var nav = document.querySelector('nav');
  if (!btn||!nav) return;
  btn.addEventListener('click', function(){ nav.classList.toggle('nav-open'); });
})();
