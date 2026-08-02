# Vital Peptide Science — Compliance Changes & Production Deployment Guide

This document lists **every change made** for the marketing/compliance overhaul and the
**exact manual steps** to reproduce them in production. It is split into:

1. Files changed (theme code) — deploy by copying files.
2. Database / WooCommerce settings — reproduce via admin UI or SQL.
3. Content you MUST review/replace before go-live (placeholders).
4. Operational (non-code) compliance items.

Table prefix in this install is **`vp_`**. Adjust the SQL if production differs.
Always take a **full database + files backup** before deploying.

---

## 1. Files changed (copy these to production)

All paths are under `wp-content/themes/vitalpeptides/`.

| File | What changed |
|------|--------------|
| **`inc/compliance.php`** | **NEW FILE.** Central compliance module (age gate, gated checkout, researcher-type + agreement checkout fields, global footer disclaimer text, product scientific-spec fields + spec table, review/testimonial removal, prohibited-product safety net). |
| `functions.php` | `require inc/compliance.php`; product card no longer shows star rating / review count / "Research Grade" (now "Research Chemical"). |
| `header.php` | Removed **Partner Program** link from desktop + mobile nav. |
| `footer.php` | Added **global research-only disclaimer** bar (every page), business/DBA address + phone block; replaced therapeutic category shop links (Recovery/GLP/Growth) with neutral links. |
| `front-page.php` | Removed the **testimonial ticker** section. |
| `woocommerce/single-product.php` | Removed star-rating block & customer reviews; **replaced the therapeutic product description with the scientific specification table** (`vp_render_spec_table`); category label neutralised to "Research Chemical". |
| `woocommerce/archive-product.php` | Removed the therapeutic **category chip navigation** (Recovery/Growth/etc.). |
| `template-parts/home/faq.php` | Rewrote all FAQ entries — removed "supplements", "save up to 30%", "special deals", "the club"; now research-only framing. |
| `template-parts/home/why-choose.php` | Removed "Volume Discounts" and "Join 5,000+ ... Discord / dosing guides"; replaced with COA / research-grade cards. |
| `template-parts/home/cta.php` | Newsletter copy no longer promises "exclusive deals". |
| `page-privacy-policy.php` | Added a **Research Use Only** clause (no human/animal consumption, 21+, qualified researchers). |
| `page-returns.php` | Added a **Research Use Only** clause; opened/reconstituted items non-returnable. |
| `page-contact.php` | "Location: United States" replaced with the **business/DBA address block**. |
| **`inc/auth.php`** | **NEW FILE.** Custom signup + login processing (creates WooCommerce customers, auto-login, redirects), enables registration (safety-net filters), `vp_auth_url()` helper, and a "Create an account" hint on the My Account login form. |
| **`page-auth.php`** | **NEW FILE.** Themed `/auth` page — signup ("Join Vital Peptide Science") and login views, `?view=`, `?email=` prefill, `?redirect_to=`. |
| **`page-checkout.php`** | **NEW FILE.** Full-width checkout page template (replaces the narrow default `page.php` layout for the checkout page). |
| **`woocommerce/checkout/form-checkout.php`** | **NEW FILE (template override).** Two-column checkout (customer details + sticky order summary), "Order Summary" heading, trust row, and a styled Sign-In / Create-Account gate for non-registered visitors linking to `/auth`. |
| **`woocommerce/checkout/review-order.php`** | **NEW FILE (template override).** Order-summary rows with product thumbnails + "Qty:" + price (reference style). |
| **`woocommerce/checkout/form-billing.php`** | **NEW FILE (template override).** Splits the billing form into two sections — "Customer Information" (type, name, email, phone) and "Shipping Address" (address block) — to match the reference. |
| `inc/compliance.php` (checkout) | Reorders/relabels checkout fields, removes the company field, relabels "Billing details"/"Apply coupon", moves the coupon form + free-shipping progress bar into the order-summary card. `vp_free_shipping_threshold()` is filterable (return 0 to hide the bar). |
| `assets/css/main.css` (checkout) | Left title, section headings, City/State/ZIP 3-column row, light-fill inputs, unified order-summary card, navy pill Place-order button, ghost coupon "Apply", free-shipping progress bar. |
| `functions.php` | Also `require inc/auth.php`; bumped `VP_THEME_VERSION` to `1.1.0` (cache-busts CSS). |
| `header.php` | Account icon → `/auth?view=login` for guests, My Account for logged-in; mobile menu shows Sign In + Create Account. |
| `template-parts/home/unlock-access.php`, `woocommerce/archive-product.php` | "Create Account" / "Sign In" CTAs now point to `/auth`. |
| `assets/css/main.css` | Appended the **checkout redesign** (two-column, order-summary card, gradient Place-order button, guest gate) and **auth page** styles. |

> The Partner Program template (`page-partner-program.php`) is left in place but its page is
> unpublished (see §2). You may delete the file after confirming nothing links to it.

**Deploy method:** upload the whole `vitalpeptides` theme folder (or just the files above plus
the new `inc/` folder). No build step. Then hard-refresh / clear any page cache.

---

## 2. Database / WooCommerce settings

You can do all of these in the **admin UI** (recommended) or via **SQL**. Both are given.

### 2.1 Checkout must use the CLASSIC (shortcode) checkout
The custom researcher-type field, research-use agreement checkbox, and required Terms
checkbox rely on classic WooCommerce checkout hooks. The site shipped with the **block**
checkout, which those hooks do not touch — so the Checkout page content must be switched.

- **Admin:** Pages → **Checkout** → delete the "Checkout" block → add a Shortcode block
  containing `[woocommerce_checkout]` → Update.
- **SQL:**
  ```sql
  UPDATE vp_posts SET post_content = '[woocommerce_checkout]' WHERE ID = 11;
  ```
  (ID 11 is the Checkout page in this DB — confirm on production: it's the page set at
  WooCommerce → Advanced → Page setup → Checkout page.)

### 2.2 Disable guest checkout + require login
WooCommerce → Settings → **Accounts & Privacy**:
- Untick **"Allow customers to place orders without an account"** (guest checkout OFF).
- Tick **"Allow customers to log into an existing account during checkout"**.

- **SQL:**
  ```sql
  UPDATE vp_options SET option_value = 'no'  WHERE option_name = 'woocommerce_enable_guest_checkout';
  UPDATE vp_options SET option_value = 'yes' WHERE option_name = 'woocommerce_enable_checkout_login_reminder';
  ```
> The theme also force-disables guest checkout in code (`pre_option_*` filters in
> `compliance.php`) as a safety net. With guest checkout off, WooCommerce **natively** shows
> a login form on the checkout page and blocks order placement for anyone not signed in —
> the checkout page stays reachable (it is NOT redirected away); a guest simply sees the
> login form and no "Place order" button. This is the standard, robust gate.
>
> **Note:** the checkout page redirecting to the Cart page happens only when the cart is
> **empty** — that is normal WooCommerce behaviour, not the compliance gate.

### 2.3 Terms & Conditions page (required checkbox at checkout)
WooCommerce → Settings → Advanced → **Page setup** → set **Terms and conditions page**
to **Terms of Service**.

- **SQL:**
  ```sql
  INSERT INTO vp_options (option_name, option_value, autoload)
  VALUES ('woocommerce_terms_page_id', '83', 'yes')
  ON DUPLICATE KEY UPDATE option_value = '83';
  ```
  (83 = Terms of Service page in this DB.) With classic checkout + this set, WooCommerce
  renders a **required** "I have read and agree to the terms and conditions" checkbox.

### 2.4 Enable self-registration (signup feature)
Researchers can now create their own account via the themed `/auth` page. Enable
registration:
- **Admin:** Settings → General → tick **"Anyone can register"**; WooCommerce → Settings →
  Accounts & Privacy → tick **"Allow customers to create an account on the My account page"**.
- **SQL:**
  ```sql
  UPDATE vp_options SET option_value = '1'   WHERE option_name = 'users_can_register';
  UPDATE vp_options SET option_value = 'yes' WHERE option_name = 'woocommerce_enable_myaccount_registration';
  ```
> Checkout stays gated: guest checkout is OFF, and `woocommerce_enable_signup_and_login_from_checkout`
> is forced OFF in code, so a visitor must have an account and be signed in to place an order —
> they just create that account themselves via `/auth` (or the checkout gate links them there).
> `inc/auth.php` also force-enables registration via `pre_option_*` filters as a safety net.

### 2.4b Create the `/auth` page
- **Admin:** Pages → Add New → title "Account" → **set the permalink/slug to `auth`** →
  Publish. (Content can be empty — `page-auth.php` renders the login/signup card.)
- **SQL:**
  ```sql
  INSERT INTO vp_posts (post_author, post_date, post_date_gmt, post_content, post_title,
    post_status, comment_status, ping_status, post_name, post_type, post_modified,
    post_modified_gmt, to_ping, pinged, post_content_filtered, post_excerpt)
  VALUES (1, NOW(), UTC_TIMESTAMP(), '', 'Account', 'publish', 'closed', 'closed', 'auth',
    'page', NOW(), UTC_TIMESTAMP(), '', '', '', '');
  ```
- Then **Settings → Permalinks → Save** (flush rewrite rules) so `/auth/` resolves.

The page supports `?view=login` / `?view=signup`, `?email=` (prefill), and `?redirect_to=`.
Header "Sign In / Create Account", the homepage/shop unlock CTAs, and the checkout guest gate
all link here automatically.

### 2.5 Remove prohibited products
Prohibited list: Alcohol Wipes, Bacteriostatic Water, HCG, HGH, Injection supplies, Nasal
Spray, Tablets. In this catalog only **Bacteriostatic Water** exists (IDs 109, 110) — set to
Draft.

- **Admin:** Products → set each prohibited product to **Draft** (or Trash).
- **SQL:**
  ```sql
  UPDATE vp_posts SET post_status = 'draft' WHERE ID IN (109, 110);
  ```
> Code safety net: `compliance.php` also hides any product whose title matches a prohibited
> pattern (bac water, hcg, hgh, injection, syringe, needle, nasal spray, tablet, alcohol
> wipe) from the shop/search, even if accidentally re-published. If you add these back
> legitimately, edit `vp_prohibited_name_patterns()`.

### 2.6 Unpublish the Partner Program (affiliate) page
- **Admin:** Pages → **Partner Program** → move to Draft/Trash.
- **SQL:**
  ```sql
  UPDATE vp_posts SET post_status = 'draft' WHERE ID = 89;
  ```

### 2.7 Business/store address (WooCommerce)
WooCommerce → Settings → General → **Store Address** — enter the real MPA DBA address.
- **SQL (placeholders shown — replace values):**
  ```sql
  UPDATE vp_options SET option_value = '<street>' WHERE option_name = 'woocommerce_store_address';
  UPDATE vp_options SET option_value = '<city>'   WHERE option_name = 'woocommerce_store_city';
  UPDATE vp_options SET option_value = '<zip>'    WHERE option_name = 'woocommerce_store_postcode';
  ```

### 2.8 Product reviews
No setting change needed — the theme force-disables reviews site-wide
(`pre_option_woocommerce_enable_reviews = no`). If you prefer to also set it in the DB:
```sql
UPDATE vp_options SET option_value = 'no' WHERE option_name = 'woocommerce_enable_reviews';
```

### 2.9 After any SQL change: clear caches
```sql
DELETE FROM vp_options WHERE option_name LIKE '_transient_%';
```
Then flush permalinks (Settings → Permalinks → Save) and clear any object/page cache.

---

## 3. MUST review / replace before go-live (placeholders)

These are in the code as clearly-marked placeholders — the client/MPA must supply real values:

1. **Business address** — `footer.php` and `page-contact.php` currently show
   `MPA Wellness LLC (DBA Vital Peptide Science)` / `[Business street address], [City],
   [State] [ZIP], USA`. Replace with the **exact DBA name + address that matches MPA's
   records**, and confirm the phone `+1 (877) 625-7857` is correct. Also update
   WooCommerce Store Address (§2.7).

2. **Exact global footer disclaimer** — the required "exact" wording lives in ONE place:
   `vp_footer_disclaimer_text()` in `inc/compliance.php`. Paste MPA's approved disclaimer
   text there; it then appears on every page footer AND in the age-gate dialog.

3. **Research-use agreement text** at checkout — `vp_research_use_agreement_text()` in
   `inc/compliance.php`. Adjust to the approved wording.

4. **Product scientific data** — the spec table shows CAS number, molecular formula,
   molecular weight, purity, storage, batch/lot, and analysis details. Purity, storage and
   analysis have safe generic defaults; **CAS / formula / molecular weight / batch are blank
   until you fill them** (blank rows are hidden so no inaccurate data is ever shown). Fill
   per product at: Products → edit product → **Product data → General → Scientific
   Specifications**. Source every value from your supplier/CoA — do **not** guess CAS
   numbers. A starter reference of commonly-cited values is in §5 (verify against your CoA).

---

## 4. Operational (non-code) compliance items

These can't be enforced by the website alone — put processes in place:

- **No therapeutic/health claims anywhere**, including social media. Every social post must
  carry the research-only disclaimer (reuse the footer text) and must avoid health/benefit
  language.
- **No B2C social marketing, no affiliate marketing / affiliate discount codes, no
  testimonials, no "Tag Us" campaigns.** (Site-side testimonials, affiliate page, and review
  system are already removed.)
- **No discount pop-ups / exit-intent / on-page promos.** A **one-time first-order discount
  for business purchases is allowed** — implement it as a WooCommerce coupon given privately
  to the customer (Marketing → Coupons), NOT as a site-wide banner/popup. Coupons remain
  enabled for this reason.
- **Zero tolerance for dosing/administration/drug-use instructions** in any customer
  communication (email, chat, phone, DM).
- **No medical/research consultations.**
- **No abbreviations of product consumer names.** Optional research-code renaming is in §6.
- **Monthly audit of all social channels** for human-use language.

---

## 5. Scientific spec reference (VERIFY against your own CoA before entering)

⚠️ These are commonly-published values for reference only. **Confirm every value against the
Certificate of Analysis for the actual batch** before entering — CAS numbers and formulae
vary by salt form/blend and must not be guessed.

| Product | CAS | Molecular formula | MW (g/mol) |
|---------|-----|-------------------|------------|
| BPC-157 | 137525-51-0 | C62H98N16O22 | 1419.5 |
| TB-500 (Thymosin β4 frag / Ac-LKKTETQ) | 885340-08-9 (fragment) | — | — |
| Ipamorelin | 170851-70-4 | C38H49N9O5 | 711.9 |
| Tesamorelin | 218949-48-5 | C221H366N72O67S | 5135.9 |
| Semaglutide | 910463-68-2 | C187H291N45O59 | 4113.6 |
| Tirzepatide | 2023788-19-2 | C225H348N48O68 | 4813.5 |
| Retatrutide | 2381089-83-2 | — | — |
| Cagrilintide | 1415456-99-3 | — | — |
| Melanotan II | 121062-08-6 | C50H69N15O9 | 1024.2 |
| PT-141 (Bremelanotide) | 189691-06-3 | C50H68N14O10 | 1025.2 |
| Sermorelin | 86168-78-7 | C149H246N44O42S | 3357.9 |
| Selank | 129954-34-3 | C33H57N11O9 | 751.9 |
| Semax | 80714-61-0 | C37H51N9O10S | 813.9 |
| GHK-Cu | 89030-95-5 | C14H24N6O4·Cu | 403.9 |
| Epithalon | 307297-39-8 | C14H22N4O9 | 390.3 |

(Purity default `≥98% (HPLC)` and storage default are applied automatically; override per
product if your CoA states otherwise. The client requested purity ≥98%.)

---

## 6. Optional — research-code product renaming (client marked "not required")

Suggested consumer-name → research-code mapping. Apply only if desired. This renames the
displayed product title; it does **not** change the URL slug.

| Current | Suggested |
|---------|-----------|
| Cagrilintide | AM833 |
| Ipamorelin | GHRP-2 |
| Retatrutide | LY3437943 |
| Semaglutide | GLP-1 |
| Tesamorelin | TH9507 |
| Tirzepatide | GLP-1 |

Example SQL (adjust IDs — see current list in admin):
```sql
-- UPDATE vp_posts SET post_title = 'LY3437943' WHERE ID = <retatrutide_id>;
```

---

## 7. Post-deploy verification checklist

- [ ] 21+ age prompt appears on first visit; "Enter" dismisses, "Exit" leaves the site.
- [ ] Footer disclaimer visible on every page (home, shop, product, policy pages).
- [ ] `/checkout/` while logged **out** → redirected to My Account login.
- [ ] Logged **in** checkout shows: Researcher/organization type (required), research-use
      agreement checkbox (required), Terms & Conditions checkbox (required). Order fails if
      any is missing.
- [ ] Admin order screen shows "Researcher type" + "Research-use agreement: Accepted".
- [ ] Product page shows the specification table + research-only notice, **no** ratings,
      reviews, or therapeutic description.
- [ ] Shop has no therapeutic category chips; no testimonials on the homepage.
- [ ] Partner Program page and Bacteriostatic Water products are not reachable (404).
- [ ] Contact page + footer show the correct DBA name, address, and phone.
- [ ] `/auth?view=signup` shows the "Join Vital Peptide Science" card; creating an account
      logs you in and returns you to where you came from (e.g. checkout).
- [ ] `/auth?view=login` signs an existing researcher in; wrong password shows an error.
- [ ] Header shows "Sign In / Create Account" when logged out, "My Account" when logged in.
- [ ] Checkout is a **two-column** layout (details left, sticky order summary right) with a
      gradient "Place order" button and the research-only trust row — not the raw WooCommerce
      style.
- [ ] Guest checkout shows the single styled gate with Create Account / Sign In (no duplicate
      login form).

## Pretty permalinks (server requirement)

The `/auth/`, `/checkout/`, `/shop/`, and product URLs are pretty permalinks — the server
needs URL rewriting. On Apache that means `mod_rewrite` enabled + a WordPress `.htaccess`
at the site root (Settings → Permalinks → Save regenerates it); on nginx, the standard
`try_files $uri $uri/ /index.php?$args;` rule. Production already had this (the site was
live). If any pretty URL returns a server "Not Found", flush permalinks and confirm rewriting
is enabled. After importing this database, always do **Settings → Permalinks → Save** once.

---

## Rollback

- Files: redeploy the previous theme folder.
- To disable ALL code-side compliance behaviour at once: comment out the
  `require_once .../inc/compliance.php;` line in `functions.php` (the age gate, checkout
  gating, spec table, review removal, and prohibited-product filter all live there).
- To disable the signup/login feature: comment out `require_once .../inc/auth.php;` in
  `functions.php` and revert the registration options in §2.4 (the `/auth` page then simply
  falls back to the default WooCommerce My Account flow).
- DB: restore from the pre-deploy backup, or reverse the individual `UPDATE`s above.
