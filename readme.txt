=== Goldstar ===
Contributors: NhanLT, TuanNQ, jef
Tags: goldstar, ticket, discount
Requires at least: 3.3
Tested up to: 3.8
Stable tag: 1.0

License: GPLv2 or later

Tap into Goldstar's 10,000+ live event deals and promotions with this simple, easy-to-configure plugin. 

== Description ==

Goldstar is a WordPress plugin that provides discount ticket offers and promotions from [Goldstar.com](http://goldstar.com). 
Goldstar.com is free and easy to do that will bring you with many benefit like fun ideas, half-price tickets and everything else you need for a great night out.

This plugin requires a valid API key and customer ID number a issued by Goldstar once you have registered for the [Goldstar Affiliate Program](http://www.goldstar.com/company/affiliate_program).

== Installation ==

1. Install Goldstar either via the WordPress.org plugin directory, or by uploading the files to your server.

2. After installing the plugin contact Goldstar to obtain valid API key and customer ID number, [Goldstar Affiliate Program](http://www.goldstar.com/company/affiliate_program).

3. Enter the API key and customer ID number.

4. Under "Display Setting" you may enter a custom background color for your search bar, sort order for the offer list, and which territory you would like to list offers for. Select a territory ID from the list link and then enter that ID number into the Territory ID field.

5. Click "Save Changes." Once your changes are saved add the following string to a page on your site - including brackets and insert : [goldstar-plugin hour=1 territory_id=#]

6. Other requirements: 
*Write permission of goldstar/xml directory is required.
*cURL extension is required.
*simplexml extension is required.
*json extension is required.
*openssl extension is required.

== Screenshots ==

1. Admin affiliate ID and API key

2. Admin offer filters

3. Front end search bar and discount offer

== Changelog ==

= 1.0 =

* Provides list of discount offers with affiliate ID tracking
* Provides date, location and price search filters
* Provides links directly to Goldstar's ticket purchasing page