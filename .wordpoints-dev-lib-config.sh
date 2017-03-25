#!/bin/bash

set -e
shopt -s expand_aliases

# Install WooCommerce when running tests.
install-woocommerce() {

    mkdir /tmp/woocommerce

	# We have to clone with git because we need the tests directory, which isn't
	# included in the export.
	git clone --depth=1 --branch="$WC_VERSION" \
		https://github.com/woocommerce/woocommerce.git /tmp/woocommerce

	mv /tmp/woocommerce /tmp/wordpress/src/wp-content/plugins/woocommerce
}

# Sets up custom configuration.
function wordpoints-dev-lib-config() {

	alias setup-phpunit="\setup-phpunit; install-woocommerce"
}

set +e

# EOF
