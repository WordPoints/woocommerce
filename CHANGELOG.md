# Change Log for WooCommerce Module

All notable changes to this project will be documented in this file.

This project adheres to [Semantic Versioning](http://semver.org/) and [Keep a CHANGELOG](http://keepachangelog.com/).

## [Unreleased]

Nothing documented right now.

## [1.1.0] - 2017-03-25

### Requirements

- WordPoints 2.3+
- WooCommerce 3.0+

### Added

- Developer changelog.
- `Namespace` and `Module URI` headers.
- Order Complete hook event that fires when an order is completed and reverses when it is refunded.
- Order entity.
 - Base order attribute class.
 - Attributes: Created Via, Custom Note, Date Completed, Date Created, Date Paid, Cart Tax, Shipping Tax, Tax Discount, Discount Total, Grand Total, and Shipping Total.
 - Relationships: Customer.
 - Restriction: Nonpublic.
- Publish Product hook event class to provide custom strings for the event automatically registered by WordPoints core. #25
- Reviewing a Product hook event class to provide custom strings for the event automatically registered by WordPoints core. #31
- Product Review entity class.
 - Also deregister the `parent` entity relationship for product reviews.

### Changed

- Points to money conversion rate setting to be a number input accepting any number supplied by the user. #16
- Unit tests to use `get_sites()` instead of direct queries when retrieving sites.
- Code to use new getter methods for WooCommerce objects instead of accessing the properties directly. #21
- Gateway tests to not rely on magic properties when getter methods are available. #22
- Order factory used in the unit tests to use `wc_get_product()` instead of the soft-deprecated `get_product()`.
- Gateway tests to initialize the gateways in `setUp()` instead of overriding the default settings of our gateway.
- Gateway class to be autoloaded. #29

### Removed

- Lingering references to the never-shipped points hook in the uninstaller. #24

### Fixed

- `WordPoints_WooCommerce_Points_UnitTestCase` to extend `WordPoints_PHPUnit_TestCase_Points` instead of the deprecated `WordPoints_Points_UnitTestCase`.
- The gateway's `process_payment()` method not returning an array on failure. #22
- Typo in the use of the `custom_fields` property of the product factory used in the PHPUnit tests.

## [1.0.2] - 2016-09-08

### Requirements

- WordPoints 2.1+

### Fixed

- Deprecated notices when uninstalling on latest WordPoints versions. #7

## [1.0.1] - 2015-03-17

### Fixed

- Updates not being available via the WordPoints.org module. #15

## [1.0.0] - 2015-01-08

### Added

- Payment gateway that uses points as currency.
 - The admin sets which points type to use.
 - How many points to charge for the monetary amount can be set to 1 to 1 or 1 to 100.

[unreleased]: https://github.com/WordPoints/woocommerce/compare/master...HEAD
[1.1.0]: https://github.com/WordPoints/woocommerce/compare/1.0.2...1.1.0
[1.0.2]: https://github.com/WordPoints/woocommerce/compare/1.0.1...1.0.2
[1.0.1]: https://github.com/WordPoints/woocommerce/compare/1.0.0...1.0.1
[1.0.0]: https://github.com/WordPoints/woocommerce/compare/...1.0.0
