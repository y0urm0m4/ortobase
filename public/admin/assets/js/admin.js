$(function () {

  /* ---- Каталог: табы + поиск ---- */
  if ($('#catalog-table').length) {

    var activeCategory = 'all';

    function filterTable() {
      var query = $('#catalog-search').val().toLowerCase();
      var visibleCount = 0;

      $('#catalog-tbody tr').each(function () {
        var matchCat   = activeCategory === 'all' || $(this).data('category') === activeCategory;
        var matchQuery = $(this).find('.fw-medium.small').first().text().toLowerCase().includes(query);
        var visible    = matchCat && matchQuery;
        $(this).toggle(visible);
        if (visible) visibleCount++;
      });

      $('#catalog-empty').toggleClass('d-none', visibleCount > 0);
    }

    $(document).on('click', '.catalog-tab', function () {
      activeCategory = $(this).data('category');
      $('.catalog-tab').removeClass('active');
      $(this).addClass('active');
      filterTable();
    });

    $('#catalog-search').on('input', filterTable);
  }

  /* ---- Пользователи: табы + поиск ---- */
  if ($('#users-table').length) {

    var activeStatus = 'all';

    function filterUsers() {
      var query = $('#users-search').val().toLowerCase();
      var visibleCount = 0;

      $('#users-tbody tr').each(function () {
        var matchStatus = activeStatus === 'all' || $(this).data('status') === activeStatus;
        var name  = $(this).find('td:eq(0) .fw-medium').text().toLowerCase();
        var email = $(this).find('td:eq(1)').text().toLowerCase();
        var matchQuery = !query || name.includes(query) || email.includes(query);
        var visible = matchStatus && matchQuery;
        $(this).toggle(visible);
        if (visible) visibleCount++;
      });

      $('#users-count').text(visibleCount);
    }

    $(document).on('click', '.users-tab', function () {
      activeStatus = $(this).data('status');
      $('.users-tab').removeClass('active');
      $(this).addClass('active');
      filterUsers();
    });

    $('#users-search').on('input', filterUsers);
  }

  /* ---- Дэшборд: бар-чарт ---- */
  if (!$('#catalogChart').length) return;

  var chartData = {
    '7':  [19, 14, 23, 28, 31, 45, 52],
    '14': [12, 14,  9, 22, 15, 11, 17, 25, 19, 14, 23, 28, 31, 45],
    '30': [8, 11, 7, 14, 10, 9, 12, 14, 9, 22, 15, 11, 17, 25, 19, 14, 23, 28, 31, 45, 38, 29, 33, 41, 36, 27, 44, 51, 48, 57]
  };

  var chartLabels = {
    '7':  ['30 апр', '', '', '', '', '', '6 мая'],
    '14': ['23 апр', '', '', '', '', '', '', '', '', '', '', '', '', '6 мая'],
    '30': ['7 апр',  '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '', '6 мая']
  };

  var activePeriod = '7';

  function cssVar(name) {
    return getComputedStyle(document.documentElement).getPropertyValue(name).trim();
  }

  var ctx = document.getElementById('catalogChart').getContext('2d');

  var chart = new Chart(ctx, {
    type: 'bar',
    data: {
      labels: chartLabels[activePeriod],
      datasets: [{
        data: chartData[activePeriod],
        backgroundColor: cssVar('--accent'),
        borderRadius: 4,
        borderSkipped: false
      }]
    },
    options: {
      responsive: true,
      maintainAspectRatio: false,
      plugins: {
        legend: { display: false },
        tooltip: {
          callbacks: {
            label: function (ctx) { return ctx.parsed.y + ' просмотров'; }
          }
        }
      },
      scales: {
        x: {
          grid: { display: false },
          border: { display: false },
          ticks: { color: cssVar('--text-muted'), font: { size: 11 } }
        },
        y: {
          grid: { color: cssVar('--border') },
          border: { display: false },
          ticks: { color: cssVar('--text-muted'), font: { size: 11 } }
        }
      }
    }
  });

  function updatePeriod(period) {
    chart.data.labels = chartLabels[period];
    chart.data.datasets[0].data = chartData[period];
    chart.update();
    $('#chart-period-label').text(period + ' дней');
  }

  function updateColors() {
    chart.data.datasets[0].backgroundColor = cssVar('--accent');
    chart.options.scales.x.ticks.color = cssVar('--text-muted');
    chart.options.scales.y.ticks.color = cssVar('--text-muted');
    chart.options.scales.y.grid.color = cssVar('--border');
    chart.update();
  }

  $(document).on('click', '.chart-tab', function () {
    var p = $(this).data('period');
    activePeriod = p;
    $('.chart-tab').removeClass('active');
    $(this).addClass('active');
    updatePeriod(p);
  });

  new MutationObserver(updateColors).observe(
    document.documentElement,
    { attributes: true, attributeFilter: ['data-bs-theme'] }
  );

});
