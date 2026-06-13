/* Nav toggle */
(function() {
  var btn = document.querySelector('.nav-toggle');
  var nav = document.querySelector('nav');
  if (!btn || !nav) return;
  btn.addEventListener('click', function() {
    nav.classList.toggle('nav-open');
  });
})();

/* Rundown day switcher */
function rdShowDay(day) {
  var d1 = document.getElementById('rdDay1');
  var d2 = document.getElementById('rdDay2');
  var t1 = document.getElementById('rdTabDay1');
  var t2 = document.getElementById('rdTabDay2');
  if (!d1 || !d2 || !t1 || !t2) return;

  if (day === 1) {
    d1.style.display = 'block';
    d2.style.display = 'none';
    t1.style.background = '#5d3f5d'; t1.style.color = '#fff';
    t2.style.background = 'transparent'; t2.style.color = '#555';
  } else {
    d1.style.display = 'none';
    d2.style.display = 'block';
    t2.style.background = '#5d3f5d'; t2.style.color = '#fff';
    t1.style.background = 'transparent'; t1.style.color = '#555';
  }
}
