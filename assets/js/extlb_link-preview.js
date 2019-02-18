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

				if (response.link_type == 'intern') {
					hovered_link.css('position', 'relative');

					if (response.thumbnail != null) {
						// Show preview with thumbnail
						hovered_link.append(`
							<div class="extlb-popup" style="top: ` + offset_y + `px; left: ` + offset_x + `px;">
								<div class="extlb-popup__image-holder extlb-popup__image-holder--top">
									<img src="` + response.thumbnail + `" class="extlb-popup__image">
								</div>
								<span class="extlb-popup__title">` + response.title + `</span>
								<span class="extlb-popup__content">` + response.excerpt + `</span>
								<span class="extlb-popup__readmore">weiterlesen ...</span>
							</div>
						`);
					}
					else {
						// Show preview without thumbnail
						hovered_link.append(`
							<div class="extlb-popup" style="top: ` + offset_y + `px; left: ` + offset_x + `px;">
								<span class="extlb-popup__title">` + response.title + `</span>
								<span class="extlb-popup__content">` + response.excerpt + `</span>
								<span class="extlb-popup__readmore">weiterlesen ...</span>
							</div>
						`);
					}
				}

			} else if (response.status == 'error') {
				console.log( 'Extendlab Link Preview: ' + response.status_message);
			}

		});


	}, function () {
		// console.log('Mouse leaved!');
		$(".extlb-popup").remove();
	});
});