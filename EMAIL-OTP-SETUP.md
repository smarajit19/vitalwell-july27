# Email OTP and Gmail SMTP setup

## What is now built

The customer journey is now:

`Cart drawer` → `Create account or sign in` → `Email code page` → `Checkout`

New accounts must enter a six-digit code, and every successful sign-in sends a new code that must be entered before the login session is verified.

The code expires after 10 minutes. A customer can try five incorrect codes and can request another code once every minute. Only a secure password hash of the code is saved; the actual code is never stored in the database.

## Step 1: configure Gmail SMTP

The code can send email only after SMTP is configured. Install and activate the **WP Mail SMTP** plugin in WordPress Admin:

1. Go to **Plugins → Add New**.
2. Search for **WP Mail SMTP by WPForms**.
3. Click **Install Now**, then **Activate**.
4. Open **WP Mail SMTP → Settings**.
5. Select **Other SMTP** as the mailer.
6. Enter these values:

| Field | Value |
| --- | --- |
| SMTP Host | `smtp.gmail.com` |
| Encryption | `TLS` |
| SMTP Port | `587` |
| Authentication | On / Yes |
| SMTP Username | Your full Gmail address |
| SMTP Password | Your 16-character Gmail App Password |

7. Set **From Email** to the same Gmail address and enable **Force From Email**.
8. Set a clear **From Name**, for example `Vital Peptide Science`.
9. Click **Save Settings**.

Do not use your normal Gmail password. The Gmail account must have two-step verification enabled, then create an App Password in **Google Account → Security → 2-Step Verification → App passwords**. Select “Mail” and create it; Google displays the 16-character value once. Paste it into WP Mail SMTP and keep it private.

## Step 2: verification page

No page needs to be created in WordPress Admin. The theme serves the verification screen automatically at:

```text
/verify-email/
```

You can open that URL only after logging in; unauthenticated visitors are sent to the sign-in page.

## Step 3: send a test email

1. Go to **WP Mail SMTP → Tools → Email Test**.
2. Send a test to an email address you can check.
3. Do not continue until this email arrives successfully. Check spam/junk as well.

## Step 4: test the customer journey

Use a private/incognito browser window to avoid old login and cart cookies:

1. Add a product to the cart.
2. Open the cart drawer and select **Checkout**.
3. Create an account with a new email address.
4. Confirm that the browser opens `/verify-email/`.
5. Enter the six-digit email code.
6. Confirm that you are taken to checkout and your item remains in the cart.
7. Log out, log in again, and confirm that verified users go directly to checkout.

## Troubleshooting

- **No email arrives:** confirm WP Mail SMTP’s test email works first. The OTP form will show an SMTP configuration error when WordPress cannot send mail.
- **The page is blank or 404:** open the exact address `/verify-email/`, then clear the site cache and hard-refresh the browser.
- **Code is rejected:** request a new code; old codes expire after 10 minutes and are replaced on resend.
- **Too many incorrect attempts:** wait for the resend button, request a new code, then enter the new one.

## Security notes

Never enter the Gmail App Password into theme files, Git, or this document. Store it only in the WP Mail SMTP settings page. If it is exposed, revoke it in the Google Account immediately and create a new App Password.
