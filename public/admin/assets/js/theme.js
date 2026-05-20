(function () {
  var t = localStorage.getItem('ortobase-theme') === 'dark' ? 'dark' : 'light';
  document.documentElement.setAttribute('data-bs-theme', t);
  var link = document.getElementById('theme-css');
  if (link) link.href = link.href.replace(/(?:light|dark)\.css/, t + '.css');
})();

$(document).on('click', '#themeToggle', function () {
  var current = document.documentElement.getAttribute('data-bs-theme') === 'dark' ? 'dark' : 'light';
  var next = current === 'dark' ? 'light' : 'dark';

  document.documentElement.setAttribute('data-bs-theme', next);
  localStorage.setItem('ortobase-theme', next);

  var link = document.getElementById('theme-css');
  if (link) link.href = link.href.replace(/(?:light|dark)\.css/, next + '.css');

  $(this).find('i').toggleClass('bi-moon bi-sun');
});

$(function () {
  var t = document.documentElement.getAttribute('data-bs-theme');
  var icon = t === 'dark' ? 'bi-sun' : 'bi-moon';
  $('#themeToggle i').removeClass('bi-moon bi-sun').addClass(icon);
});
