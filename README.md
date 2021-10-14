# Mage2 Module Shadab Customproduct

    ``shadab/module-customproduct``

 - [Main Functionalities](#markdown-header-main-functionalities)
 - [Installation](#markdown-header-installation)
 - [Configuration](#markdown-header-configuration)
 - [Specifications](#markdown-header-specifications)
 - [Web Services (API)](#markdown-header-webservices)

## Main Functionalities
Custom products module

## Installation
\* = in production please use the `--keep-generated` option

### Type 1: Zip file

 - Unzip the zip file in root folder
 - Enable the module by running `php bin/magento module:enable Shadab_Customproduct`
 - Apply database updates by running `php bin/magento setup:upgrade`\*
 - Flush the cache by running `php bin/magento cache:flush`


## Configuration

 - Product Disclaimer (settings/general/disclaimer)


## Specifications

 - Model
	- customproducts

## Web Services (API)

	/V1/customproduct/products/:entityId/:storeId

	Eg. http://localmagento.com/rest/V1/customproduct/products/1/0
	 GET Method
	 -------------------------------------------------------
	/V1/customproduct/products/:entityId

	Eg. http://localmagento.com/rest/V1/customproduct/products/1

	PUT Method

	 -------------------------------------------------------
	 /V1/customproduct/products/:entityId
	 
	 Eg. http://localmagento.com/rest/V1/customproduct/products/1

	DELETE Method
	 
	 -------------------------------------------------------
	 /V1/customproduct/products/search
	 
	 Eg. http://localmagento.com/rest/V1/customproduct/search?searchCriteria[filter_groups][0][filters][0][field]=vendor_number&searchCriteria[filter_groups][0][filters][0][value]=bbbb& searchCriteria[filter_groups][0][filters][0][condition_type]=like

	GET Method
	
	Note: Before accessing API please generate token to use API
	http://localmagento.com/rest/V1/integration/admin/token
	Payload:
	{
		"username":"username",
		"password":"password"
	}
	POST Method

	- 




