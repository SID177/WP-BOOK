# WP-BOOK

A WordPress plugin.

## Getting Started

It adds a Custom Post Type **wp-book**, Custom Taxonomy **wp-book-category** and **wp-book-tag**.
It supports metadata for wp-book. Eg. Author, Publisher, Year, Edition, Price and URL.
You can set currency for price.

The meta data are stored in **wp_bookmeta** table, this is a custom table.

It has a settings page from which you can set currency and number of posts to show.

The structure of this plugin is created from https://wppb.me/

This plugin is internationalized and follows PHP coding standards.

### Installing

* Download zip of this repository.
* Goto wp-admin of your site.
* Click on **Plugins** located in menu.
* Click on **Add New** and then **Upload Plugin**.
* Upload the downloaded repository here.

After uploading, activate the plugin from plugins page.

## Usage Guidelines

It will add **Books** menu on wp-admin. From here you can manage book posts.

On edit post screen, you can set metadata from below the text editor.

You can set categories and tags of this book from Book Categories and Book Tags metaboxes.

### WP Book Widget

This plugin adds **WP Book Widget** which can be used from **Appearance -> Widgets** page.

You can specify title, category and number of items in the widget settings.

All the categories you created from Book Categories metabox will be shown under category dropdown.

### Shortcode

It supports a shortcode **[wp-book]**. Supported attributes are id, author_name, year, category, tag, and publisher. The slug of the category and tag needs to be specified here.

```
[wp-book category="novel" publisher="John Newman"]
```

