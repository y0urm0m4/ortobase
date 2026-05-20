var PAGE_TITLES = {
  dashboard:  'Дэшборд',
  catalog:    'Каталог',
  categories: 'Категории',
  users:      'Пользователи',
  settings:   'Настройки'
};

$(function () {
  var page = document.body.dataset.page || '';

  $.when(
    $('#sidebar-mount').load('partials/sidebar.html'),
    $('#topbar-mount').load('partials/topbar.html')
  ).done(function () {
    if (page) {
      $('.nav-link[data-page="' + page + '"]').addClass('active');
      var title = PAGE_TITLES[page];
      if (title) $('#crumb').text(title);
    }
  });
});
