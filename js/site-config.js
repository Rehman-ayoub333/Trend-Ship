/**
 * TrendShip site config — base URL, admin links, shared assets.
 */
(function () {
  var FALLBACK = 'http://localhost/TrendShip';

  function assetRoot() {
    var scripts = document.getElementsByTagName('script');
    for (var i = scripts.length - 1; i >= 0; i--) {
      var src = scripts[i].getAttribute('src') || '';
      if (src.indexOf('site-config.js') !== -1) {
        return src.replace(/js\/site-config\.js.*$/, '');
      }
    }
    return '';
  }

  function detectBase() {
    if (location.protocol === 'file:') return FALLBACK;
    var path = location.pathname.replace(/\\/g, '/');
    var match = path.match(/\/(trendship)(?=\/|$)/i);
    if (match) {
      var start = path.indexOf(match[0]);
      var segment = path.substring(start + 1, start + 1 + match[1].length);
      return location.origin + '/' + segment;
    }
    if (path === '/' || path === '') return location.origin;
    return location.origin + path.replace(/\/[^/]*$/, '');
  }

  var root = assetRoot();

  window.TRENDSHIP_BASE = detectBase();
  window.TRENDSHIP_ADMIN_LOGIN = TRENDSHIP_BASE + '/admin/login.html';
  window.TRENDSHIP_ADMIN_LOGIN_POST = TRENDSHIP_BASE + '/admin/login.php';

  /* Load shared nav assets after all page styles are parsed */
  function loadNavAssets() {
    if (!document.querySelector('link[data-trendship-nav]')) {
      var navCss = document.createElement('link');
      navCss.rel = 'stylesheet';
      navCss.href = root + 'css/nav.css';
      navCss.setAttribute('data-trendship-nav', '1');
      document.head.appendChild(navCss);
    }
    if (!document.querySelector('script[data-trendship-nav]')) {
      var s = document.createElement('script');
      s.src = root + 'js/nav.js';
      s.defer = true;
      s.setAttribute('data-trendship-nav', '1');
      document.head.appendChild(s);
    }
  }

  function onReady() {
    loadNavAssets();
  }

  if (document.readyState === 'loading') {
    document.addEventListener('DOMContentLoaded', onReady);
  } else {
    onReady();
  }
})();
