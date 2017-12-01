import $ from 'jquery';

class Application {
	constructor() {
		this.anchors();
	}

	anchors() {
		if (location.hash && $(window).scrollTop() > 0) {
			setTimeout(() => {
				window.scrollTo(0, 0);
			}, 1);
		}

		$(() => {
			if (location.hash) {
				this.scrollTo(location.hash);
			}
		});

		$('body').delegate('a[href^="#"]', 'click', (event) => {
			event.preventDefault();
			this.scrollTo($(event.target).attr('href'))
		});
	}

	scrollTo(href) {
		let $target = $(href);
		if ($target.length) {
			$('html, body').animate({
				scrollTop: $target.offset().top
			}, 1000, () => {
				location.hash = href.substring(1);
				$target.focus();
				if (!$target.is(':focus')) {
					$target.attr('tabindex', '-1');
					$target.focus();
				}
			});
		}
	}
}


let app = new Application();

export default Application;