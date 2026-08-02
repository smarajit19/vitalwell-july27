# Vital Peptides — WooCommerce Theme

A pixel-accurate WordPress/WooCommerce port of the Vital Peptides React storefront. Fully responsive (same breakpoints as the original: 640 / 768 / 1024 / 1280 / 1536px), with a custom AJAX cart drawer, typing hero headline, autoplay product carousel, quality tabs, FAQ accordions, testimonial ticker, and all secondary pages.

## Requirements

WordPress 6.0+, WooCommerce 7.0+, PHP 7.4+.

## Installation

1. Zip the `vitalpeptides` folder (or use the provided `vitalpeptides-theme.zip`) and install via **Appearance → Themes → Add New → Upload Theme**, then activate.
2. Install and activate **WooCommerce** (the theme's shop, product, cart drawer, and checkout styling depend on it).

## Import products

1. Open `vitalpeptides-products.csv` and replace `https://YOURSITE.com` in the **Images** column with your actual site URL (the product images ship inside the theme at `wp-content/themes/vitalpeptides/assets/images/products/`). Alternatively, upload the images to the Media Library and use those URLs.
2. Go to **Products → All Products → Import**, choose the CSV, tick **"Update existing products"** off, and run the import. Column mapping is automatic, including the custom meta columns (`_vp_dosage`, `_vp_purity`, `_vp_badge`, `_vp_rating`, `_vp_review_count`, `_vp_member_price`).
3. Product categories (Recovery, GLP, Growth, Metabolic, Longevity) are created automatically during import.

## Pages

Create these pages (title → slug). Each automatically uses its matching template via WordPress slug-based template resolution:

- Research → `research`
- Certificates → `certificates`
- Contact → `contact`
- FAQ → `faq`
- Shipping → `shipping`
- Returns → `returns`
- Privacy Policy → `privacy-policy`
- Terms of Service → `terms-of-service`
- Disclaimer → `disclaimer`
- Research Use Only → `research-use-only`
- Partner Program → `partner-program`

Then set **Settings → Reading → Your homepage displays: A static page** and pick any page as Homepage (the theme's `front-page.php` renders the full landing page regardless of page content).

WooCommerce creates Shop, Cart, Checkout, and My Account pages automatically at setup.

## Notes

- **Cart drawer**: any "Add to Cart" button opens the slide-in drawer (AJAX, no reload). Quantity/remove controls in the drawer update via WooCommerce cart fragments.
- **Members-only pricing**: member prices are stored in `_vp_member_price` meta and displayed on product pages. Hook into it for role-based pricing logic if needed.
- **Newsletter**: signups are stored in the `vp_newsletter_subscribers` option (Tools → Site Health → Info, or read programmatically). Swap the `vp_ajax_newsletter` handler in `functions.php` to integrate Mailchimp/Klaviyo etc.
- **Logo**: uses `assets/images/LogoMain.svg` / `LogoWhite.svg` by default; a custom logo set in the Customizer takes precedence in the header.
