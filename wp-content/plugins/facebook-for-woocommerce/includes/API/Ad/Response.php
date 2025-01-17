<?php
declare( strict_types=1 );

namespace WooCommerce\Facebook\API\Ad;

defined( 'ABSPATH' ) || exit;

use WooCommerce\Facebook\API\Response as ApiResponse;

/**
 * Generic response object for flows corresponding to Facebook Ad requests.
 *
 * @since 3.1.0
 */
class Response extends ApiResponse {

	public function get_data() {
		return $this->response_data['data'];
	}
}
