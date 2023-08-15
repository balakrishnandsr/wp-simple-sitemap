# The problem to be solved in your own words
Sitemap serves as a roadmap for search engines to understand the organization of user site's content and helps them crawl and index pages more effectively.
sitemap make it easier for search engine bots to discover and navigate through all the content.

# A technical spec of how you will solve it
This issue can be resolved through the creation of a sitemap. This will involve leveraging core WordPress functions such as `wp_remote_post` to effectively crawl URLs. The HTML files obtained will be stored using `WP_Filesystem_Direct`. To enhance user experience, I've incorporated AJAX calls for seamless sitemap viewing and execution.

# The technical decisions you made and why
* Using `WP-AJAX` due to `Dynamic Content Loading`, `Interactive User Interfaces`, `Reduced Bandwidth Usage` and AJAX allows you to perform tasks in the background without disrupting the main user experience.
* Using `wp_remote_post()` over file_get_content(). Its is a wordpress function and file_get_content may get failed in some secured servers.
* Using Shortcode `[wp-simple-sitemap]`. Users have the ability to edit the content according to their preferences.


# How the code itself works and why
* On clicking the button `Run`, The plugin loads the content of home page and stored into homepage.html and filter only the `URLs` to display at `sitemap.html`.
* Subsequently, it eliminates duplicate URLs as they lack meaningful purpose.
* Create the `sitemap.html` to display the results.
* Then it schedule a cron for every 1 hours to generate a sitemap to stay updated.
* Finally the `sitemap.html` will display below the `Run` button with some UI.


# How your solution achieves the admin’s desired outcome per the user story
* Once we enable the plugin, we will get a side menu `WP Simple Sitemap` and `settings` link below the plugin name at the plugin page.
* Click on the side menu `WP Simple Sitemap` or `settings` which take to a page where it displays a instructions and two buttons `Run` and `View`.
* On clicking the button `Run` it search for hyperlinks from the home page and displays the result.
* On clicking the button `View` it will displays the stored result.
* User can display the sitemap.html to front-end by using `[wp-simple-sitemap]` shortcode




