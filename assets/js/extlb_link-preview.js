jQuery(document).ready(function ($) {

	$('.entry-content a').hover(function () {
		// console.log('Mouse entered!');

    // ON MOUSE ENTER
    var hovered_link = $(this)
    var link_text = hovered_link.html();
    var link_url = hovered_link.attr('href');

    var link_width = hovered_link.width();
    var offset_x = Math.round(link_width / 2);
    var offset_y = 40;

    var data = {
      'action': 'show_link_preview',
      'link': link_url,
      // 'link': ajax_object.special_value
    };

    // ajax_object.ajax_url is defined in post-preview.php in pp_scripts_styles() in wp_localize_script()
    var ajaxurl = ajax_object.ajax_url;

    $.post(ajaxurl, data, function (response) {
      // console.log(JSON.stringify(response));

      if (response.status == 'success') {

				// Stops if mobile is disabled and window size is smaller than 768px
				if (response.options['disable_mobile'] && $(window).width() < 768)
					return false;

				if (response.link_type == 'intern') {
					hovered_link.css('position', 'relative');
					var append_popup = '';

					// Create the popup
					if (response.options['darkmode']) {
						// Show darkmode
						append_popup = '<div class="extlb-popup extlb-popup--dark" style="top: ' + offset_y + 'px; left: ' + offset_x + 'px;">';
						if (response.thumbnail != null && response.options['hide_thumbnails'] != true ) {
							// Show preview with thumbnail if it has one and it's not disabled
							append_popup += '<div class="extlb-popup__image-holder extlb-popup__image-holder--top">';
							append_popup += '<img src="' + response.thumbnail + '" class="extlb-popup__image">';
							append_popup += '</div>';
						}
						append_popup += '<span class="extlb-popup__title extlb-popup__title--dark">' + response.title + '</span>';
						append_popup += '<span class="extlb-popup__content extlb-popup__content--dark">' + response.excerpt + '</span>';
						append_popup += '<span class="extlb-popup__readmore extlb-popup__readmore--dark">weiterlesen ...</span>';
						append_popup += '</div>';
					} else {
						append_popup = '<div class="extlb-popup" style="top: ' + offset_y + 'px; left: ' + offset_x + 'px;">';
						if (response.thumbnail != null && response.options['hide_thumbnails'] != true ) {
							// Show preview with thumbnail
							append_popup += '<div class="extlb-popup__image-holder extlb-popup__image-holder--top">';
							append_popup += '<img src="' + response.thumbnail + '" class="extlb-popup__image">';
							append_popup += '</div>';
						}
						append_popup += '<span class="extlb-popup__title">' + response.title + '</span>';
						append_popup += '<span class="extlb-popup__content">' + response.excerpt + '</span>';
						append_popup += '<span class="extlb-popup__readmore">weiterlesen ...</span>';
						append_popup += '</div>';
					}

					// Append the created popup to the hovered link
					hovered_link.append( append_popup );
				}

      } else if (response.status == 'error') {
        console.log('Extendlab Link Preview: ' + response.status_message);
      }

    });

  }, function () {
    // console.log('Mouse leaved!');
    $(".extlb-popup").remove();
  });
});