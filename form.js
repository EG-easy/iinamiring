$('#js-selectFile').on('click', 'button', function () {
    $('#js-upload').click();
    return false;
});

$('#js-upload').on('change', function() {

    var file = $(this).prop('files')[0];

    $('#js-selectFile').find('.icon').addClass('select').html('選択中');

    if(!($('.filename').length)){
        $('#js-selectFile').append('<div class="filename"></div>');
    };

    $('.filename').html('ファイル名：' + file.name);
});

$(function() {
  // 画像がクリックされた時の処理です。
  $('img.thumbnail').click(function() {
    var $imageList = $('.image_list');

    // 現在の選択を解除します。
    $imageList.find('img.thumbnail.checked').removeClass('checked');

    // チェックを入れた状態にします。
    $(this).addClass('checked');
  });
});
