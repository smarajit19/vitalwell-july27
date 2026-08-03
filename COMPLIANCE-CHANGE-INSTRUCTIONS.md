# Compliance change implementation guide

This guide describes the requested changes without applying them to the live
site. It is written for the custom `vitalpeptides` WordPress/WooCommerce theme.

> **Legal review required:** Policy text, business identity, address, return
> periods, and eligibility rules must be supplied or approved by the business's
> counsel before publishing. Do not use the sample wording below as legal
> advice.

## 1. Required inputs before coding

Collect and confirm the following with the client:

1. Exact legal entity name and DBA.
2. Complete US mailing/street address: street, city, state, ZIP.
3. Approved support email and telephone number.
4. Approved Privacy Policy, Refund Policy, Shipping Policy, and Terms &
   Conditions text (including the new Indemnification clause).
5. The email sender address/name and the mail delivery method (transactional
   mail service or verified SMTP). WordPress must reliably deliver emails.
6. The verification-token expiry (recommended: 24 hours) and whether support
   may manually approve an account.

## 2. Current code locations and gap assessment

| Request | Current location | What exists now | Required result |
| --- | --- | --- | --- |
| Gated checkout | `wp-content/themes/vitalpeptides/inc/compliance.php` | Guest checkout is disabled and checkout requires login. | Keep this control and also require a verified email. |
| Account registration | `wp-content/themes/vitalpeptides/inc/auth.php` | Signup calls `wc_create_new_customer()` and immediately calls `wc_set_customer_auth_cookie()`. | Do not sign in a newly created account; send verification mail instead. |
| Checkout UI | `wp-content/themes/vitalpeptides/woocommerce/checkout/form-checkout.php` | Displays a login/account gate to guests. | Display a clear verified-account message for signed-in but unverified users. |
| Privacy | `wp-content/themes/vitalpeptides/page-privacy-policy.php` | Custom policy template exists. | Replace text with approved policy and explain verification-email data handling. |
| Refunds | `wp-content/themes/vitalpeptides/page-returns.php` | Returns/refunds template exists. | Replace with counsel-approved refund policy. |
| Shipping | `wp-content/themes/vitalpeptides/page-shipping.php` | Shipping template exists. | Replace with approved shipping policy. |
| Terms | `wp-content/themes/vitalpeptides/page-terms-of-service.php` | Research-use restriction exists; Indemnification does not. | Retain restriction, add prohibition and approved Indemnification section. |
| Footer address | `wp-content/themes/vitalpeptides/footer.php` | Placeholder address is visible. | Insert final business information. |
| Research Library | `footer.php`, `header.php`, `template-parts/home/features.php`, `page-research.php` | Navigation and page remain available; page has research/health-adjacent content. | Remove links and unpublish or safely redirect the page. |

## 3. Implement verified-email checkout gating

### 3.1 Preserve the existing account gate

Leave the existing WooCommerce options and filters in place:

- WooCommerce → Settings → Accounts & Privacy → disable **Allow customers to
  place orders without an account**.
- Keep the `pre_option_woocommerce_enable_guest_checkout` filter returning
  `no` in `inc/compliance.php`.
- Keep checkout content as the classic `[woocommerce_checkout]` shortcode,
  because the theme's checkout hooks depend on it.

This prevents guests from placing orders, but it does **not** prove ownership
of the account email.

### 3.2 Add user verification state

Create a small, dedicated module such as
`wp-content/themes/vitalpeptides/inc/email-verification.php`, then load it from
`functions.php` after WooCommerce is available.

Use user meta rather than an email address in a query string as the state:

```php
function vp_is_email_verified( $user_id = 0 ) {
    $user_id = $user_id ?: get_current_user_id();
    return $user_id && '1' === get_user_meta( $user_id, '_vp_email_verified', true );
}
```

When an account is created, store:

```php
update_user_meta( $user_id, '_vp_email_verified', '0' );
update_user_meta( $user_id, '_vp_email_verification_hash', wp_hash_password( $raw_token ) );
update_user_meta( $user_id, '_vp_email_verification_expires', time() + DAY_IN_SECONDS );
```

Generate `$raw_token` with `wp_generate_password( 32, false, false )`. Never
store the raw token in user meta or logs. Send a URL containing the user ID and
raw token, for example `/verify-email/?uid=123&token=...`; verify it server-side
with `wp_check_password()`.

### 3.3 Change registration behavior

In `inc/auth.php`, replace the post-registration block:

```php
wc_set_customer_auth_cookie( $customer_id );
wp_safe_redirect( $redirect );
```

with this process:

1. Initialize verification meta and send a verification email.
2. Redirect to a neutral “Check your email” page or `/auth/?view=verify-pending`.
3. Do not create an authenticated session.
4. Do not reveal whether an arbitrary email address already has an account.

Use `wp_mail()` only after SMTP/transactional email is configured and tested.
Add a nonce-protected resend endpoint with rate limiting (for example, one send
per account per five minutes) and an audit timestamp such as
`_vp_email_verification_sent_at`.

### 3.4 Add the verification endpoint

Implement one page template or a `template_redirect` handler that:

1. Validates and sanitizes `uid` and `token`.
2. Loads the matching user and checks that the token has not expired.
3. Uses `wp_check_password( $token, $stored_hash )`.
4. On success, sets `_vp_email_verified` to `1`, deletes the token hash and
   expiry, then sends the user to `/auth/?view=login` (or logs them in only if
   that behavior is approved).
5. On failure or expiry, shows a generic invalid/expired-link message and a
   resend option; do not disclose account details.

### 3.5 Enforce verification on the server

UI hiding is insufficient. Add both checks in the verification module:

```php
add_action( 'woocommerce_checkout_process', function () {
    if ( ! vp_is_email_verified() ) {
        wc_add_notice(
            __( 'Verify your email address before placing an order.', 'vitalpeptides' ),
            'error'
        );
    }
} );

add_filter( 'woocommerce_available_payment_gateways', function ( $gateways ) {
    if ( is_checkout() && ! vp_is_email_verified() ) {
        return array();
    }
    return $gateways;
} );
```

Also use a `template_redirect` check on `is_checkout()` to send logged-in,
unverified users to the pending-verification page. Preserve the intended
checkout URL only after validating it with `wp_validate_redirect()`.

The `woocommerce_checkout_process` validation remains necessary because it
protects checkout requests that bypass the page UI.

### 3.6 Update checkout/auth messaging

In `woocommerce/checkout/form-checkout.php`, distinguish three states:

1. Guest: sign in or create an account.
2. Signed in but unverified: verify email or resend the verification email; no
   order form/payment options.
3. Signed in and verified: show normal checkout.

In `page-auth.php`, add a `verify-pending` view showing only the check-email
message and resend form. Update the signup success message accordingly.

## 4. Update compliant policy pages and repair policy links

### 4.1 Privacy Policy

Replace the section data in `page-privacy-policy.php` with the approved policy.
It should accurately cover information collected at registration and checkout,
verification-email/token processing, payment processors, cookies/analytics,
service providers, retention, security, consumer privacy rights, contact
details, and effective/last-updated date.

### 4.2 Refund and Shipping Policy

Maintain separate source files unless counsel provides one combined policy:

- `page-returns.php`: approved eligibility, exclusions, authorization process,
  return destination/instructions, refund timing/method, damaged or incorrect
  shipments, and contact information.
- `page-shipping.php`: processing times, carrier/methods, rates, destinations,
  tracking, delivery estimates/disclaimers, lost/damaged shipments, and contact
  information.

The current footer has links to `/shipping/` and `/returns/`; they should work
only after corresponding published WordPress pages have these exact slugs.

In WordPress admin, confirm:

1. Pages → Shipping Information has slug `shipping` and template **Shipping**.
2. Pages → Returns & Refunds has slug `returns` and template **Returns**.
3. Pages → Privacy Policy has slug `privacy-policy` and template **Privacy
   Policy**.
4. Pages → Terms of Service has slug `terms-of-service` and template **Terms
   of Service**.
5. Settings → Permalinks → Save Changes, then test every footer URL in an
   incognito window. Fix a 404 by correcting the page slug/assignment, not by
   adding a static URL workaround.

If the client wants one combined “Refunds and Shipping Policy” page, create a
new page with a permanent slug such as `refunds-shipping-policy`, add its
template/link deliberately, and remove the separate links to avoid duplicate
or contradictory policies.

### 4.3 Terms & Conditions

Edit `page-terms-of-service.php` after receiving approved text. Retain and
strengthen the existing **Research Use Only** section so it expressly says:

- products are for laboratory/scientific research only;
- they are not for human or animal consumption;
- they are not drugs, foods, dietary supplements, cosmetics, or medical
  devices; and
- products may not be used for diagnosis, treatment, cure, prevention, or
  clinical/veterinary purposes.

Add a standalone **Indemnification** section before Limitation of Liability.
Counsel must supply the final jurisdiction-specific language. It should be
consistent with the research-use restriction, buyer representations, and the
company's actual legal entity/DBA. Do not copy a generic indemnity clause
without review.

Then set this page in WooCommerce → Settings → Advanced → Page setup → **Terms
and conditions page**, so WooCommerce renders its required agreement checkbox
at checkout.

## 5. Complete business information in the footer

In `wp-content/themes/vitalpeptides/footer.php`, replace the literal placeholder
line:

```php
'[Business street address], [City], [State] [ZIP], USA'
```

with the client-approved, complete address. Also confirm the displayed entity,
DBA, phone, and support email are correct. Update the matching address in
`page-contact.php` and WooCommerce → Settings → General → Store Address so all
customer-facing and transactional information agrees.

Do not publish the literal bracketed placeholder.

## 6. Remove “Research Library” and the research-content entry points

1. Remove the **Research Library** link from the Resources list in `footer.php`.
2. Remove the `/research/` navigation links in `header.php` (desktop and mobile).
3. Remove the “Explore Research Library” CTA and associated feature card in
   `template-parts/home/features.php`.
4. In WordPress admin, unpublish/delete the Research page, which is rendered by
   `page-research.php`, or change it to a 301 redirect to a reviewed neutral
   page such as `/research-use-only/`. Use a redirect plugin or server rule;
   do not leave the old URL returning claim-based content.
5. Search the active theme, WordPress menus, posts, product descriptions, and
   SEO metadata for `Research Library`, `/research/`, clinical terms, health
   claims, and B2C wording. Review each result with the client/counsel before
   removing or rewriting it.

Suggested repository check:

```powershell
rg -n -i "research library|/research/|clinical|treat|cure|human consumption" wp-content/themes/vitalpeptides
```

## 7. Verification checklist

- [ ] A visitor can browse but cannot place an order as a guest.
- [ ] New registration sends exactly one verification email and does not log
      the user in.
- [ ] An unverified account cannot reach a payment-capable checkout or create
      an order through a direct request.
- [ ] A valid verification link works once; expired/reused/invalid tokens do
      not verify an account.
- [ ] Resend is rate-limited and verified emails are delivered in production.
- [ ] A verified user can sign in and complete checkout only after accepting
      the research-use and Terms checkboxes.
- [ ] Privacy, Shipping, Refunds, and Terms URLs return HTTP 200 and policy
      text matches counsel-approved copy.
- [ ] Terms include research-only restrictions, the human-consumption
      prohibition, and the approved Indemnification section.
- [ ] Footer and Contact page show the same complete business identity/address.
- [ ] “Research Library” and `/research/` are absent from navigation, homepage,
      sitemap/menus, and public search results; the former URL is unpublished or
      redirects safely.

## 8. Deployment notes

Test in staging first with a real external test mailbox. Back up the database
before changing pages or WooCommerce settings. After deployment, clear page,
object, and CDN caches; then repeat the checklist in a logged-out browser and
with a new email address.
