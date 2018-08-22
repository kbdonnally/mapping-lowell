if (document.querySelector('.exhibit-wrapper')) {

	/* 1. Add IDs to narrative sub-headings */

	var narrative 	 = document.querySelector('.exhibit__narrative');
	var headings 	 = narrative.querySelectorAll('h3');
	// remove special characters
	var headingTexts = Array.from(headings, h => h.innerText.replace(/[^\w\s]/gi, ''));
	// slugify
	var headingIds 	 = headingTexts.map(h => h.toLowerCase().replace(/ /g, '-'));
	var i 			 = 0;

	// assign ids to all <h3>
	for (const h of Array.from(headings)) {
		h.setAttribute('id', headingIds[i]);
		i++;
	}

	/* 2. Highlight SVGs on click: */
	var map = document.querySelector('#neatline-map');

	map.addEventListener('click', toggleColors)

	function toggleColors(e) {
		console.log(e.target.fill);
	}
}