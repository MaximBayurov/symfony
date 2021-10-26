import $ from 'jquery';

$(function () {

  $('[data-item=likes]').each(function () {
    const $container = $(this);

    $container.on('click', function (e) {
      e.preventDefault();
      
      const type = $container.data('type');
      const slug = $container.data('slug');

      $.ajax({
        url: `/articles/${slug}/like/${type}`,
        method: 'POST'
      }).then(function (data) {
        let needToggle = +$container.find('[data-item=likesCount]').text() === 0 || data.likes === 0;
        if (needToggle) {
          $container.find('[data-item=likesCount]').toggleClass('text-danger text-success');
        }
        $container.find('[data-item=likesCount]').text(data.likes);
        $container.find('.fa-heart').toggleClass('far fas');
        $container.data('type', type === 'like' ? 'dislike' : 'like');
      });
    });
  });

});