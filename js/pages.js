
(function () {
  // ── Common: Nav toggle (shared selector: .nav-toggle + nav) ──
  function initNavToggle() {
    var btn = document.querySelector(".nav-toggle");
    var nav = document.querySelector("nav");
    if (!btn || !nav) return;

    // Pastikan tidak double-bind saat script re-evaluated
    if (btn.dataset.navBound === "1") return;
    btn.dataset.navBound = "1";

    btn.addEventListener("click", function () {
      nav.classList.toggle("nav-open");
      btn.setAttribute(
        "aria-expanded",
        nav.classList.contains("nav-open") ? "true" : "false",
      );
    });
  }

  // ── About: Scroll reveal ──
  function initAbout() {
    var blocks = document.querySelectorAll(".about-block");
    if (!blocks || !blocks.length) return;

    var io = new IntersectionObserver(
      function (entries) {
        entries.forEach(function (e) {
          if (e.isIntersecting) {
            e.target.classList.add("visible");
            io.unobserve(e.target);
          }
        });
      },
      { threshold: 0.15 },
    );

    blocks.forEach(function (b) {
      io.observe(b);
    });
  }

  document.addEventListener("DOMContentLoaded", function () {
    initNavToggle();
    initAbout();
  });
})();
