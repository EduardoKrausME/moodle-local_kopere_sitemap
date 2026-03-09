# local_kopere_sitemap Plugin

**local_kopere_sitemap** is a Moodle plugin that automatically generates a **public XML sitemap** for the site and adds a reference to the sitemap in the `<head>` of all Moodle pages.

The plugin’s purpose is to make it easier for **search engines to index your Moodle site** (Google, Yahoo, DuckDuckGo, etc.), allowing crawlers to find relevant public pages on the platform, such as open courses, categories, forums, and homepage content.

The sitemap is generated dynamically from the existing data in the Moodle database and respects content visibility settings.

# How the plugin works

The plugin has two main components:

1. **XML Sitemap Generator**
2. **Automatic injection of the sitemap link into the site’s `<head>`**

These two mechanisms work together to allow search engines to discover and index the platform’s public content.

# Public XML sitemap

The sitemap is available at the following URL:

```text
https://yourmoodle.com/local/kopere_sitemap/sitemap.php
```

This page generates a **valid XML file following the Sitemap protocol standard**:

```text
https://www.sitemaps.org/protocol.html
```

Simplified example of the generated XML:

```xml
<urlset xmlns="http://www.sitemaps.org/schemas/sitemap/0.9">
    <url>
        <loc>https://yourmoodle.com</loc>
        <lastmod>2026-03-09T10:30:00+00:00</lastmod>
    </url>

    <url>
        <loc>https://yourmoodle.com/course/view.php?id=5</loc>
        <lastmod>2026-03-08T19:10:00+00:00</lastmod>
    </url>
</urlset>
```

Each item contains:

* **loc** → public URL
* **lastmod** → last modification date

# Content that may appear in the sitemap

The administrator can choose which types of content should be included in the sitemap.

## Site front page

Includes the Moodle homepage:

```text
https://yourmoodle.com/
```

This is usually the first page indexed by search engines.

## Course categories

Includes visible categories in the course tree.

Example:

```text
https://yourmoodle.com/course/index.php?categoryid=3
```

Only categories **marked as visible** are included.

## Courses open for enrollment

Courses are included when they meet the following conditions:

* course is visible
* category is visible
* has an active enrollment method
* usually with **self-enrollment**

Example URL:

```text
https://yourmoodle.com/course/view.php?id=10
```

This allows public courses to be found by search engines.

## Moodle blog

If enabled, the sitemap includes public entries from the Moodle blog.

Example:

```text
https://yourmoodle.com/blog/index.php?entryid=54
```

Only posts published with public visibility are listed.

## Public forums

Visible forums may appear in the sitemap when:

* they are on the **front page**
* or they belong to courses accessible to guests

Example:

```text
https://yourmoodle.com/mod/forum/view.php?id=21
```

## Front page modules

Activities added to the **front page course (course id = 1)** may also appear in the sitemap.

Example:

```text
https://yourmoodle.com/mod/page/view.php?id=12
https://yourmoodle.com/mod/url/view.php?id=8
https://yourmoodle.com/mod/resource/view.php?id=3
```

Only **visible** modules are included.

# Automatic sitemap inclusion on the site

The plugin uses Moodle’s modern **Hooks** system to automatically insert the sitemap link into the `<head>` of pages.

Example of generated HTML:

```html
<link rel="sitemap" type="application/xml" title="Sitemap" href="https://yourmoodle.com/local/kopere_sitemap/sitemap.php">
```

This link allows:

* search engines to discover the sitemap automatically
* crawlers to interpret the site structure
* SEO tools to detect the LMS sitemap

# Plugin settings

The administrator can configure the plugin’s behavior through the administrative settings.

## Enable or disable the plugin

Allows the sitemap generation to be completely enabled or disabled.

When disabled:

* the sitemap is not generated
* the link in the `<head>` is not inserted

## Sitemap URL

Allows you to define the public URL of the sitemap.

Normally:

```text
https://yourmoodle.com/local/kopere_sitemap/sitemap.php
```

It is also possible to define an absolute URL.

## Include front page

Includes the Moodle homepage in the sitemap.

## Include courses

Includes public and visible courses that allow enrollment.

## Include categories

Includes visible course categories.

## Include blog

Includes public posts from the Moodle blog.

## Include forums

Includes visible forums from accessible courses.

## Include front page modules

Includes activities from the front page course.

# SEO compatibility

The plugin was designed to work with search engines and SEO tools.

The sitemap follows:

* the official sitemap protocol standard
* valid XML format
* absolute URLs
* dates in ISO-8601 format

This ensures compatibility with:

* Google Search Console
* Yahoo Webmaster Tools
* SEO crawlers
* automatic indexers
