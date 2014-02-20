=== Goldstar ===
Contributors: NhanLT, TuanNQ, jef
Tags: goldstar, ticket, discount
Requires at least: 3.3
Tested up to: 3.8
Stable tag: 1.0

License: GPLv2 or later

Tap into Goldstar's 10,000+ live event deals and promotions with this simple, easy-to-configure plugin. 

== Description ==

Goldstar is a WordPress plugin that provides great value and selection on live entertainment tickets. [Goldstar.com](http://goldstar.com) is the first place people go to find something great to do and it's free and easy to use. You'll discover fun event ideas, half-price tickets and everything else you need for a great night out.

Goldstar event listings are attractive content for your blog and you can earn money from new Goldstar free member signups or ticket sales.

This plugin requires a valid API key and Affiliate ID number as issued by Goldstar once you have registered and been accepted into the [Goldstar Affiliate Program](http://www.goldstar.com/company/affiliate_program).

== Installation ==

1. Install the Goldstar plugin either via the WordPress.org plugin directory, or by uploading the files to your server.  Use the "Plugins" tab located in the left-side navbar of the Dashboard.  Select "Add New" from the "Plugins" tab and enter Goldstar.com in the Search box.  Then click on "Install now" link.  After download is complete, click on "Activate Plugin".

2. After installing the plugin contact Goldstar to obtain valid API key and Affiliate ID number, [Goldstar Affiliate Program](http://www.goldstar.com/company/affiliate_program).

3. Click on the "Settings" link for the Goldstar plugin to build a Half-Price Tickets Page.  Enter the API key and Affiliate ID number. The API key is a long alphanumeric text string and the Affiliate ID is a 4-digit number.

4. Under "Display Settings" you may enter a custom background color for your search bar, sort order for the offer list, and which territory you would like to list offers for. Select a territory ID from the list link and then enter that ID number into the Territory ID field.  Territories correspond to the cities served by Goldstar.

5. Click "Save Changes." Once your changes are saved add the following string to a page on your site - including brackets and insert: [goldstar-plugin]

6. If you would like to add more than one territory you will need to set up a separate page or section and add the following snippet: [goldstar-plugin hour=1 territory_id=#]. Replace "#" with the appropriate territory ID number. You may create as many additional territories as you wish by following this format.

7. Other requirements: 
*Write permission of goldstar/xml directory is required.
*cURL extension is required.
*simplexml extension is required.
*json extension is required.
*openssl extension is required.

== Screenshots ==

1. Admin affiliate ID, API key validation, display filters

2. Front end search bar and discount offer

== Changelog ==

= 1.0 =

* Provides list of discount offers with affiliate ID tracking
* Provides date, location and price search filters
* Provides links directly to Goldstar's ticket purchasing page