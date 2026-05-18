$(function () {

  /* ---- Определяем текущую страницу ---- */
  var currentPage = window.location.pathname.split('/').pop().replace('.php', '') || 'dashboard';

  /* ---- Загрузка сайдбара и топбара через jQuery ---- */
  $('#sidebar-placeholder').load('layout/sidebar.html', function () {
    /* Подсветить активный пункт */
    $('.sidebar-link[data-page="' + currentPage + '"]').addClass('active');
  });

  $('#topbar-placeholder').load('layout/topbar.html', function () {
    /* Подсветить активный пункт топбара */
    $('.topbar-nav-link[data-page="' + currentPage + '"]').addClass('active');

    /* Кнопка переключения темы */
    $(document).on('click', '#btn-theme-toggle', function () {
      var isDark = $('html').attr('data-theme') === 'dark';
      var next = isDark ? 'light' : 'dark';
      $('html').attr('data-theme', next);
      localStorage.setItem('admin-theme', next);
      updateThemeIcon(next);
    });

    updateThemeIcon($('html').attr('data-theme'));
  });

  function updateThemeIcon(theme) {
    var icon = theme === 'dark' ? 'bi-sun' : 'bi-moon';
    $('#btn-theme-toggle').find('i').attr('class', 'bi ' + icon);
  }

  /* ---- Мобильный сайдбар ---- */
  $(document).on('click', '#btn-sidebar-toggle', function () {
    $('.admin-sidebar').addClass('open');
    $('.sidebar-overlay').addClass('show');
  });

  $(document).on('click', '.sidebar-overlay, .sidebar-close', function () {
    $('.admin-sidebar').removeClass('open');
    $('.sidebar-overlay').removeClass('show');
  });

  /* ================================================================
     БАР-ЧАРТ (только на дэшборде)
     ================================================================ */
  if ($('#chart-bars').length) {
    var chartData = {
      '14':  [12, 14,  9, 22, 15, 11, 17, 25, 19, 14, 23, 28, 31, 45],
      '7':   [19, 14, 23, 28, 31, 45, 52],
      '30':  [8, 11, 7, 14, 10, 9, 12, 14, 9, 22, 15, 11, 17, 25, 19, 14, 23, 28, 31, 45, 38, 29, 33, 41, 36, 27, 44, 51, 48, 57]
    };

    var chartLabels = {
      '14': { 0: '30 апр', 13: '6 мая' },
      '7':  { 0: '30 апр', 6: '6 мая' },
      '30': { 0: '7 апр', 29: '6 мая' }
    };

    var activePeriod = '14';

    function renderChart(period) {
      var data = chartData[period];
      var labels = chartLabels[period] || {};
      var max = Math.max.apply(null, data);
      var $bars = $('#chart-bars');
      $bars.empty();

      $.each(data, function (i, val) {
        var pct = max > 0 ? Math.round((val / max) * 100) : 0;
        var isActive = (i === data.length - 1) ? ' active' : '';
        var label = labels[i] ? '<span class="chart-bar-label">' + labels[i] + '</span>' : '';
        $bars.append(
          '<div class="chart-bar-col">' +
            '<div class="chart-bar' + isActive + '" style="height:' + pct + '%"></div>' +
            label +
          '</div>'
        );
      });

      /* Обновить заголовок */
      $('#chart-period-label').text(period + ' дней');
    }

    renderChart(activePeriod);

    $(document).on('click', '.chart-tab', function () {
      var p = $(this).data('period');
      activePeriod = p;
      $('.chart-tab').removeClass('active');
      $(this).addClass('active');
      renderChart(p);
    });
  }

});
