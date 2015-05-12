=== Goldstar ===

Contributors: nhanlt, tuanphp, jef

Tags: goldstar, ticket, discount
Requires at least: 3.3

Tested up to: 4.0

Stable tag: 1.4
License: GPLv2 or later


Tap into Goldstar's 10,000+ live entertainment offers and promotions with this simple, easy-to-configure plugin.



== Description ==

Goldstar is a WordPress plugin that provides great value and selection on live entertainment tickets. [goldstar.com](http://www.goldstar.com) is the first place people go to find something great to do and it's free and easy to use. You'll discover fun event ideas, half-price tickets and everything else you need for a great night out.

Goldstar event listings are attractive content for your blog, and you'll earn money through Goldstar free member signups or ticket sales.

This plugin requires a valid API key and Affiliate ID number as issued by Goldstar once you have registered and been accepted into the [Goldstar Affiliate Program](http://www.goldstar.com/company/affiliate_program).



== Installation ==


1. Install the Goldstar plugin either via the WordPress.org plugin directory, or by uploading the files to your server. Use the "Plugins" tab located in the left-side navbar of the Dashboard. Select "Add New" from the "Plugins" tab and enter Goldstar.com in the Search box. Then click on "Install now" link. After download is complete, click on "Activate Plugin".

2. After installing the plugin contact Goldstar to obtain valid API key and Affiliate ID number, [Goldstar Affiliate Program](http://www.goldstar.com/company/affiliate_program).

3. Click on the "Settings" link for the Goldstar plugin to build a Half-Price Tickets Page. Enter the API key and Affiliate ID number. The API key is a long alphanumeric text string and the Affiliate ID is a 4-digit number. Leave the Title and Body Text areas blank on this page.

4. Under "Display Settings" you can select the sort order for the offer list and which territory you would like to list offers for. Select a Territory ID from the list link and then enter that ID number into the Territory ID field. Territories correspond to the cities served by Goldstar.

5. Click "Save Changes." Once your changes are saved go to “Pages” on your dashboard and create a “New Page”. Add a title like “Half Price Tickets” or “Discount Tickets”. In the body text area, add the following snippet: [goldstar-plugin hour=1 territory_id=#]. Replace "#" with the appropriate territory ID number and make sure to include the brackets.

6. If you would like to add more than one territory to your web site you will need to set up a separate page and add the same code snippet: [goldstar-plugin hour=1 territory_id=#]. Replace "#" with the appropriate territory ID number. You may create as many additional territories as you wish by following this format. You have access to all Goldstar territories.

7. Other requirements: *Write permission of goldstar/xml directory is required. *cURL extension is required. *simplexml extension is required. *json extension is required. *openssl extension is required.

8. To add the featured offer teaser widget, on the main Goldstar plugin admin form enter the slug of the page you are adding the main Goldstar plugin to. IMPORTANT: If the slug you enter for the widget does not match the slug of the page you have added the main Goldstar plugin to the widget will NOT display. For more information about slugs, you may wish to visit this page: http://codex.wordpress.org/Glossary#Slug

9. From the main Goldstar plugin Settings admin form select the "Featured Events" tab to select the specific event offers you would like to feature in the widget.  This tab is located at the top of form next to the "Configuration" tab just above the "Add New Post" field.

10. Next, select Appearance > Widget from the left column and then drag the "Goldstar Teaser" widget to main sidebar position you prefer. Once positioned click to open the widget's display settings. You should enter a maximum number of offers to be featured (between 1 and 15). You may also select various font size and color attributes to customize the widget's appearance.

11. Once you have completed the widget's display settings click "Save". Please note that the widget will only display those offers that have been manually selected and will not auto fill offers. Once all currently selected offers have expired, if no other offers have since been selected, the widget will automatically be suppressed from view.

== Screenshots ==


1. Admin affiliate ID and API key validation configuration settings
2. Admin plugin display settings
3. Admin featured offer widget configuration settings
4. Admin featured offer selection list
5. Admin featured offer widget display settings
6. Front end search bar and discount offer example
7. Front end featured offer widget example

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
= 1.3.2 =
* Readme file updates, bug fixes
= 1.3.3 =
* CSS compatibility fixes


= 1.3.4 =
* Integrate Goldstar API's image
* Move xml file to wp-content/uploads/goldstar-xml folder

= 1.3.5 =
* Default image size modification
= 1.3.6 =
* Consolidated js files, modified default image size, modified container size
= 1.4 =* Design modifications, new featured offer "teaser" widget
= 1.4.1 =* Readme file updates, modified screenshot