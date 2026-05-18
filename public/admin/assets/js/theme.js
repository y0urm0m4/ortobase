/* Переключатель темы — запускается ДО рендера, чтобы не моргало */
(function () {
  var saved = localStorage.getItem('admin-theme');
  var theme = saved === 'dark' ? 'dark' : 'light';
  document.documentElement.setAttribute('data-theme', theme);
})();
