/* ================================================================
   HTML-шаблоны сайдбара и топбара — вставляются напрямую в DOM,
   никакого сервера не нужно (чисто статика)
   ================================================================ */

var SIDEBAR_HTML = `
<nav class="admin-sidebar" id="sidebar">
  <div class="sidebar-brand">
    <a href="dashboard.html">Ortobase</a>
  </div>
  <div class="sidebar-nav">
    <div class="sidebar-section-label">Главное</div>
    <ul class="nav flex-column mb-2">
      <li class="nav-item">
        <a href="dashboard.html" class="sidebar-link" data-page="dashboard">Дэшборд</a>
      </li>
      <li class="nav-item">
        <a href="catalog.html" class="sidebar-link" data-page="catalog">
          Каталог <span class="sidebar-badge">9</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="categories.html" class="sidebar-link" data-page="categories">
          Категории <span class="sidebar-badge">3</span>
        </a>
      </li>
    </ul>
    <div class="sidebar-section-label">Доступ</div>
    <ul class="nav flex-column">
      <li class="nav-item">
        <a href="users.html" class="sidebar-link" data-page="users">
          Пользователи <span class="sidebar-badge">6</span>
        </a>
      </li>
      <li class="nav-item">
        <a href="settings.html" class="sidebar-link" data-page="settings">Настройки</a>
      </li>
    </ul>
  </div>
  <div class="sidebar-footer">v0.1 · Курсовой Ortobase<br>© 2025</div>
</nav>`;

var TOPBAR_HTML = `
<header class="admin-topbar" id="topbar">
  <button class="btn-sidebar-toggle me-2" id="btn-sidebar-toggle" aria-label="Меню">
    <i class="bi bi-list"></i>
  </button>
  <nav class="topbar-nav">
    <a href="#" class="topbar-nav-link">Админ</a>
    <a href="dashboard.html" class="topbar-nav-link" data-page="dashboard">Дэшборд</a>
    <a href="users.html" class="topbar-nav-link" data-page="users">Пользователи</a>
  </nav>
  <div class="topbar-search">
    <div class="input-group">
      <span class="input-group-text"><i class="bi bi-search"></i></span>
      <input type="text" class="form-control" placeholder="Поиск по adminке...">
    </div>
  </div>
  <div class="topbar-actions">
    <button class="btn-topbar-icon" title="Уведомления"><i class="bi bi-bell"></i></button>
    <button class="btn-topbar-icon" id="btn-theme-toggle" title="Сменить тему"><i class="bi bi-moon"></i></button>
    <div class="d-flex align-items-center gap-2 ms-1">
      <div class="topbar-avatar">АС</div>
      <div class="d-none d-md-block">
        <div class="topbar-user-name">Анна С.</div>
        <div class="topbar-user-role">Администратор</div>
      </div>
    </div>
  </div>
</header>`;

/* ================================================================
   Инициализация
   ================================================================ */
$(function () {

  /* Текущая страница по имени файла */
  var currentPage = window.location.pathname.split('/').pop().replace('.html', '') || 'dashboard';

  /* Вставляем сайдбар и топбар */
  $('#sidebar-placeholder').html(SIDEBAR_HTML);
  $('#topbar-placeholder').html(TOPBAR_HTML);

  /* Подсвечиваем активный пункт */
  $('.sidebar-link[data-page="' + currentPage + '"]').addClass('active');
  $('.topbar-nav-link[data-page="' + currentPage + '"]').addClass('active');

  /* Тема */
  updateThemeIcon($('html').attr('data-theme'));

  $('#btn-theme-toggle').on('click', function () {
    var next = $('html').attr('data-theme') === 'dark' ? 'light' : 'dark';
    $('html').attr('data-theme', next);
    localStorage.setItem('admin-theme', next);
    updateThemeIcon(next);
  });

  function updateThemeIcon(theme) {
    $('#btn-theme-toggle i').attr('class', 'bi ' + (theme === 'dark' ? 'bi-sun' : 'bi-moon'));
  }

  /* Мобильный сайдбар */
  $(document).on('click', '#btn-sidebar-toggle', function () {
    $('.admin-sidebar').addClass('open');
    $('.sidebar-overlay').addClass('show');
  });

  $(document).on('click', '.sidebar-overlay', function () {
    $('.admin-sidebar').removeClass('open');
    $('.sidebar-overlay').removeClass('show');
  });

  /* ================================================================
     БАР-ЧАРТ (только на дэшборде)
     ================================================================ */
  if ($('#chart-bars').length) {
    var chartData = {
      '14': [12, 14,  9, 22, 15, 11, 17, 25, 19, 14, 23, 28, 31, 45],
      '7':  [19, 14, 23, 28, 31, 45, 52],
      '30': [8, 11, 7, 14, 10, 9, 12, 14, 9, 22, 15, 11, 17, 25, 19, 14, 23, 28, 31, 45, 38, 29, 33, 41, 36, 27, 44, 51, 48, 57]
    };

    var chartLabels = {
      '14': { 0: '30 апр', 13: '6 мая' },
      '7':  { 0: '30 апр', 6:  '6 мая' },
      '30': { 0: '7 апр',  29: '6 мая' }
    };

    var activePeriod = '14';

    function renderChart(period) {
      var data   = chartData[period];
      var labels = chartLabels[period] || {};
      var max    = Math.max.apply(null, data);
      var $bars  = $('#chart-bars').empty();

      $.each(data, function (i, val) {
        var pct     = max > 0 ? Math.round((val / max) * 100) : 0;
        var active  = i === data.length - 1 ? ' active' : '';
        var label   = labels[i] ? '<span class="chart-bar-label">' + labels[i] + '</span>' : '';
        $bars.append(
          '<div class="chart-bar-col">' +
            '<div class="chart-bar' + active + '" style="height:' + pct + '%"></div>' +
            label +
          '</div>'
        );
      });

      $('#chart-period-label').text(period + ' дней');
    }

    renderChart(activePeriod);

    $(document).on('click', '.chart-tab', function () {
      activePeriod = $(this).data('period');
      $('.chart-tab').removeClass('active');
      $(this).addClass('active');
      renderChart(activePeriod);
    });
  }

});
