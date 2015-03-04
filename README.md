# Set Table

Set Table is a Craft CMS field type to allow you to create static tables. Set Table is essentially identical to a regular Table field, apart from a few small differences. The aim of this plugin is to provide end-users with a set collection of rows for their table - defined in the field settings.

**Label Type**
In addition to the regular column options for a table (text, multi-line text, select, checkbox), you can set a column's type to be a Label. This will display the provided value as a read-only label.

<img src="https://raw.githubusercontent.com/engram-design/SetTable/master/screenshots/types.png" />

###Use Case

By now you might be a bit confused as to why Set Table even exists, being that you can simply use a regular table to do the same thing. However there are potential cases where read-only portions of a table might be helpful to your end user. Likewise for the number of rows in the table.

A good use case would be a list of social media fields, containing links to the website's various pages.

Firstly, you could simply use a collection of text fields like so:

<img src="https://raw.githubusercontent.com/engram-design/SetTable/master/screenshots/before.png" />

This is a little messy visually, and unnecessary amount of fields. You'd be better off with a regular table:

<img src="https://raw.githubusercontent.com/engram-design/SetTable/master/screenshots/table.png" />

Great! Nice and neat. We can even use it to define the icon class for templating.

But what if you don't want your users to be able to edit the Name and Icon Class fields? If you're using an icon font, this will certainly break things on the site. And maybe you also don't want users to add any more social links? What if they try to add a service without an icon?

Obviously there are ways around the above (provide a placeholder when no icon, and educate your editors on table fields), but here's a Set Table.

<img src="https://raw.githubusercontent.com/engram-design/SetTable/master/screenshots/after.png" />

There's also some small visual tweaks to the table compared to a regular one.

###Field Settings

If you can edit a Table, you can edit a Set Table.

<img src="https://raw.githubusercontent.com/engram-design/SetTable/master/screenshots/settings.png" />
