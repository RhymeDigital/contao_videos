/**
 * Video management for Contao Open Source CMS
 *
 * Copyright (C) 2014-2015 HB Agency
 *
 * @package    Videos
 * @link       http://www.hbagency.com
 * @license    http://opensource.org/licenses/lgpl-3.0.html
 */

/**
 * Class VideoRequest
 *
 * Provide methods to handle Ajax requests.
 * @copyright  HB Agency 2015
 * @author     Blair Winans <bwinans@hbagency.com>
 * @author     Adam Fisher <afisher@hbagency.com>
 */
var VideoRequest =
{

    /**
	 * Feature/unfeature an element
	 *
	 * @param {object} el The DOM element
	 * @param {string} id The ID of the target element
	 *
	 * @returns {boolean}
	 */
	toggleFeatured: function(el, id) {
		el.blur();

		var image = $(el).getFirst('img'),
			featured = (image.src.indexOf('featured_') == -1);

		// Send the request
		if (!featured) {
			image.src = image.src.replace('featured_.gif', 'featured.gif');
			new Request.Contao().post({'action':'toggleFeaturedVideo', 'id':id, 'state':1, 'REQUEST_TOKEN':Contao.request_token});
		} else {
			image.src = image.src.replace('featured.gif', 'featured_.gif');
			new Request.Contao().post({'action':'toggleFeaturedVideo', 'id':id, 'state':0, 'REQUEST_TOKEN':Contao.request_token});
		}

		return false;
	}
	
}