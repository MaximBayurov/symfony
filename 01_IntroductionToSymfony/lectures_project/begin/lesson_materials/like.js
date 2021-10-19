$(function () {

  $('[data-item=likes]').each(function () {
    const $container = $(this);

    $container.on('click', function (e) {
      e.preventDefault();

      $.ajax({
        url: '/articles/10/like/like',
        method: 'POST'
      }).then(function (data) {
        $container.find('.fa-heart').removeClass('far').addClass('fas');
        $container.find('[data-item=likesCount]').text(data.likes);
      });
    });
  });

});