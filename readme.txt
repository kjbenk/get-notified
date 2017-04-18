=== Get Notified ===
Contributors: kjbenk, wpmonty
Tags: notification, notify, email, message, ping, alert, slack, post, post status, status
Requires at least: 4.5
Tested up to: 4.7.3
Stable tag: 1.0.8
License: GPLv2 or later

Get Notified is a simple to use notification plugin that sends you messages of certain WordPress events via Email or Slack.

== Description ==
Get Notified sends you notifications when certain events happen on your site. For now, the plugin simply sends a notification via Email or Slack when a post changes status (i.e. publish, pending, draft, trash) or a new comment is created on your site.

*Examples*

1. Get an email when a post is published.
1. Post a message in Slack when a post is trashed.

We will continue to add new integrations and WordPress events, so please feel free to request new features.

Get Notified is 100% extendable for developers as well, so visit the [Github repo](https://github.com/kjbenk/get-notified) to contribute.

== Installation ==
1. Upload the `get-notified` folder to the `/wp-content/plugins/` directory
1. Activate the Get Notified plugin through the Plugins menu in WordPress
1. Configure the plugin by going to the `Get Notified` menu that appears in your admin menu

== Frequently Asked Questions ==
= Does Get Notified send to multiple email addresses? =

Yep! You can add multiple email addresses by using commas.

= How can I request new features? =

Create a new issue in the Github repo and include as much detail as you can think of.

[Click here to request a feature](https://github.com/kjbenk/get-notified/issues)

= How do I generate a Slack WebHook? =

Visit the link below, login to your Slack account, select the channel or user you'd like to send the message to, and click the `Add Incoming WebHook Integration` button.

Slack will generate the WebHook and show it to you on the next page. Simply copy the `Webhook URL` and paste it into the Get Notified > Integrations > Slack > Webhook URL field and click `Save Changes`.

If you don't see the field, select the checkbox and press `Save Changes`. It should show up once the page refreshes.

[Click here to create your WebHook](https://my.slack.com/services/new/incoming-webhook/)

= How can I help contribute? =

This is an open source plugin for WordPress and I welcome all contributions to the Github repo :)

[Click here to see the Github repo](https://github.com/kjbenk/get-notified)

= Where should I submit bugs? =

You can submit all bugs via issues at the Github repo.

[Click here to submit a bug](https://github.com/kjbenk/get-notified/issues)

== Screenshots ==

1. You can enable or disable what events you want to be notified about.
2. Integrations can be enabled or disabled at any time.

== Changelog ==

= 1.0.8 = 2017-4-5
* ADDED: Comment Created hook
* UPDATED: Readme text to include new FAQs
* UPDATED: Tested with 4.7.3

= 1.0.7 = 2016-4-6
* FIXED: Translation setup...again :)

= 1.0.6 = 2016-4-6
* ADDED: Language .pot file

= 1.0.5 = 2016-4-6
* REMOVED: Language .pot file

= 1.0.4 = 2016-4-6
* FIXED: Text Domain for translations

= 1.0.3 = 2016-4-4
* FIXED: Translation Process

= 1.0.2 = 2016-2-25
* ADDED: Screenshots

= 1.0.1 = 2016-2-25
* UPDATED: readme text to include support for email integration

= 1.0.0 = 2016-2-24
* Initial Release :)
