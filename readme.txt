=== Goldstar ===
Contributors: nhanlt, tuanphp, jef
Tags: goldstar, ticket, discount, artsopolis, apollo
Requires at least: 3.3
Tested up to: 3.9
Stable tag: 1.3
License: GPLv2 or later

Goldstar will provide a basic list of discount ticket offers from goldstar.com



== Description ==

Goldstar is a WordPress plugin that provides great value and selection on live entertainment tickets. goldstar.com is the first place people go to find something great to do and it's free and easy to use. You'll discover fun event ideas, half-price tickets and everything else you need for a great night out.

Goldstar event listings are attractive content for your blog, and you'll earn money through Goldstar free member signups or ticket sales.

This plugin requires a valid API key and Affiliate ID number as issued by Goldstar once you have registered and been accepted into the Goldstar Affiliate Program.



== Installation ==

    Install the Goldstar plugin either via the WordPress.org plugin directory, or by uploading the files to your server. Use the "Plugins" tab located in the left-side navbar of the Dashboard. Select "Add New" from the "Plugins" tab and enter Goldstar.com in the Search box. Then click on "Install now" link. After download is complete, click on "Activate Plugin".

    After installing the plugin contact Goldstar to obtain valid API key and Affiliate ID number, Goldstar Affiliate Program.

    Click on the "Settings" link for the Goldstar plugin to build a Half-Price Tickets Page. Enter the API key and Affiliate ID number. The API key is a long alphanumeric text string and the Affiliate ID is a 4-digit number.

    Under "Display Settings" you may enter a custom background color for your search bar, sort order for the offer list, and which territory you would like to list offers for. Select a territory ID from the list link and then enter that ID number into the Territory ID field. Territories correspond to the cities served by Goldstar.

    Click "Save Changes." Once your changes are saved add the following string to a page on your site - including brackets and insert: [goldstar-plugin]

    If you would like to add more than one territory you will need to set up a separate page or section and add the following snippet: [goldstar-plugin hour=1 territory_id=#]. Replace "#" with the appropriate territory ID number. You may create as many additional territories as you wish by following this format.

    Other requirements: *Write permission of goldstar/xml directory is required. *cURL extension is required. *simplexml extension is required. *json extension is required. *openssl extension is required.

== Screenshots ==

1. API region at admin page
2. Filter at admin page
3. Frontend of plugin



== Changelog ==


= 1.0 =
* Provides list of discount offers with affiliate ID tracking
* Provides date, location and price search filters
* Provides links directly to Goldstar's ticket purchasing page


= 1.1 =
* Readme file updates
= 1.2 =
* All category check box fields are now pre-selected.
* Additional error messages added
= 1.2.1 =
* Search bar icon wrapping fix.
* Search bar spacing fix.
* Constraining of offer image size fix
= 1.2.2 =
* Category filtering fix
= 1.3 =
* Update plugin's css to run compatible with most of themes
= 1.3.1 =
* Bug fixes and adds two new js files