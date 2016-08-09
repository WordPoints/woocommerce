#!/bin/bash

set -e
shopt -s expand_aliases

# Install WooCommerce when running tests.
install-woocommerce() {

    mkdir /tmp/woocommerce

	curl -L "https://github.com/woothemes/woocommerce/archive/${WC_VERSION}.tar.gz" \
		| tar xvz --strip-components=1 -C /tmp/woocommerce

	mv /tmp/woocommerce /tmp/wordpress/src/wp-content/plugins/woocommerce
}

# Sets up custom configuration.
function wordpoints-dev-lib-config() {

	alias setup-phpunit="\setup-phpunit; install-woocommerce"
}

set +e

# EOF
