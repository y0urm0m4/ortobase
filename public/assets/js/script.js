/* Подсветка активного пункта навбара по текущему URL */
(function () {
  const path = window.location.pathname.split('/').pop() || 'index.html';
  document.querySelectorAll('.navbar .nav-link').forEach(function (link) {
    const href = link.getAttribute('href');
    if (href && href === path) {
      link.classList.add('active-page');
    }
  });
})();
