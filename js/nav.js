/**
 * Shared navigation — mobile overlay + visibility safeguards.
 */
(function () {
  function ensureNavVisible() {
    document.querySelectorAll('#nav .n-a, #nav .n-maison').forEach(function (el) {
      el.style.removeProperty('opacity');
      el.style.removeProperty('visibility');
      el.style.removeProperty('transform');
    });
  }

  function initOverlay() {
    var btn = document.getElementById('nav-menu');
    var overlay = document.getElementById('nav-overlay');
    var closeBtn = document.getElementById('nav-close');
    if (!btn || !overlay) return;

    var open = false;

    function setOpen(state) {
      open = state;
      btn.setAttribute('aria-expanded', state ? 'true' : 'false');
      overlay.classList.toggle('open', state);
      overlay.style.transform = state ? 'scale(1)' : 'scale(0)';
      overlay.style.opacity = state ? '1' : '0';
      document.body.style.overflow = state ? 'hidden' : '';
      if (state) {
        overlay.querySelectorAll('.no-item, .no-footer').forEach(function (el) {
          el.style.opacity = '1';
          el.style.transform = 'none';
        });
      }
    }

    btn.addEventListener('click', function () { setOpen(!open); });
    if (closeBtn) closeBtn.addEventListener('click', function () { setOpen(false); });
    document.addEventListener('keydown', function (e) {
      if (e.key === 'Escape' && open) setOpen(false);
    });
    overlay.querySelectorAll('.no-item').forEach(function (a) {
      a.addEventListener('click', function () { setOpen(false); });
    });
  }

  ensureNavVisible();
  initOverlay();
  document.addEventListener('DOMContentLoaded', function () {
    ensureNavVisible();
    initOverlay();
  });
  window.addEventListener('load', ensureNavVisible);
})();
