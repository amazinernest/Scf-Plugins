# Simple CPT & Fields

**The Ultimate Lightweight Content Management Toolkit for WordPress & Elementor.**

Simple CPT & Fields (SCF) is a powerful, no-nonsense plugin designed to transform your WordPress site into a fully-fledged Content Management System (CMS). Whether you are building a Church website, a Portfolio, a Real Estate listing site, or a Corporate directory, SCF gives you the tools to create Custom Post Types, Taxonomies, and Custom Fields effortlessly—without bloating your site.

## 🚀 Key Features

*   **Custom Post Types (CPT) Manager**: Create unlimited custom content types (e.g., Sermons, Events, Team, Portfolios) with a simple UI.
*   **Taxonomy Manager**: Organize your content with custom categories and tags (e.g., Sermon Series, Event Types).
*   **Advanced Custom Fields**: Add extra data to your posts. Supported field types include:
    *   📝 **Text & Text Area**
    *   🖊️ **WYSIWYG Editor** (Rich Text)
    *   🖼️ **Image** (with Media Library integration)
    *   📂 **File** (PDFs, Audio, Docs)
    *   🔢 **Number**
    *   📧 **Email**
    *   🔗 **URL**
    *   📅 **Date**
    *   🎨 **Color Picker**
    *   🔘 **Select, Radio, & Checkbox** (with custom options)
*   **Elementor Integration**: First-class support for Elementor Page Builder.
    *   **Dynamic Tags**: Inject field values directly into any Elementor widget (Headings, Text, Buttons, Images).
    *   **Custom Widget**: A dedicated "SCF Field Display" widget for quick output.
*   **Developer Friendly**: Includes Shortcodes and PHP helper functions for use in theme templates.

---

## 📖 Use Case Example: Church Website

SCF is perfect for structured data. Here is how you can power a Church Website:

1.  **Sermons**: Create a "Sermon" Post Type.
    *   Fields: *Preacher* (Text), *Bible Passage* (Text), *Audio Recording* (File), *Video Link* (URL), *Date Preached* (Date).
    *   Taxonomy: *Series* (Group sermons together).
2.  **Events**: Create an "Event" Post Type.
    *   Fields: *Event Date* (Date), *Location* (Text), *Registration Link* (URL), *Flyer* (Image).
3.  **Staff**: Create a "Team Member" Post Type.
    *   Fields: *Position* (Text), *Email* (Email), *Bio* (WYSIWYG), *Photo* (Image).

---

## 📦 Installation

1.  **Download** the latest release from GitHub (or click "Code" > "Download ZIP").
2.  **Upload** to your WordPress Dashboard:
    *   Go to `Plugins` > `Add New` > `Upload Plugin`.
    *   Select the `.zip` file and click "Install Now".
3.  **Activate** the plugin.
4.  **Navigate** to the new `Simple CPT & Fields` menu item in your dashboard to start configuring.

---

## ⚙️ Configuration & Usage

### 1. Creating Content Types
*   Go to **Simple CPT & Fields** > **Custom Post Types**.
*   Enter a **Slug** (e.g., `sermon`), **Plural Name** (e.g., `Sermons`), and **Singular Name** (e.g., `Sermon`).
*   Click **Add Post Type**. Your new menu item will appear instantly in the admin sidebar.

### 2. Organizing with Taxonomies
*   Go to the **Custom Taxonomies** section.
*   Enter details (e.g., `series` for Sermons) and select which Post Type it belongs to.
*   Click **Add Taxonomy**.

### 3. Adding Custom Fields
*   Go to the **Custom Fields** section.
*   **Field Key**: Unique identifier (e.g., `sermon_audio`).
*   **Field Type**: Choose from the dropdown (e.g., `File`).
*   **Label**: Human-readable name (e.g., `Sermon Audio MP3`).
*   **Post Type**: Select where this field should appear.
*   **Options** (for Select/Radio/Checkbox): Enter one per line in `value : Label` format.
    *   Example:
        ```text
        morning : Morning Service
        evening : Evening Service
        ```

---

## 🎨 Elementor Integration

SCF makes it incredibly easy to display your custom data in Elementor.

### Method A: Dynamic Tags (Recommended)
1.  Edit your Single Post Template or Page in Elementor.
2.  Drag in a widget (e.g., **Text Editor** or **Image**).
3.  Click the **Dynamic Tags** icon (stack of coins) next to the content field.
4.  Select **SCF Field** (for text/URL) or **SCF Image** (for images).
5.  Click the wrench icon 🔧 to select the specific **Field Key** you want to display.

### Method B: SCF Widget
1.  Search for the **SCF Field Display** widget in the Elementor panel.
2.  Drag it onto the page.
3.  Select the **Field Key** from the dropdown.

---

## 💻 Developer Guide

### Shortcodes
Display any field inside posts, pages, or text widgets:
```shortcode
[scf_field key="my_custom_field"]
```
For a specific post:
```shortcode
[scf_field key="my_custom_field" post_id="123"]
```

### PHP Helper Function
Retrieve values in your theme's `single.php` or `archive.php`:

```php
// Get field value
$value = scf_get_field( 'my_custom_field' );

// Get specific post field
$value = scf_get_field( 'my_custom_field', $post_id );

// Example: Display an image
$image_id = scf_get_field( 'staff_photo' );
echo wp_get_attachment_image( $image_id, 'medium' );
```

---

## ❤️ Contributing

Contributions are welcome! If you have suggestions or find bugs, please open an issue or submit a pull request on the [GitHub Repository](https://github.com/amazinernest/Scf-Plugins).

---

**Built with ❤️ for the WordPress Community.**
