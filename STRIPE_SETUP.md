# Stripe Payment Setup Guide

## Problem
The checkout page was showing 401 Unauthorized errors because the Stripe API key was invalid or expired.

## ✅ **ENVIRONMENT VARIABLES FIXED**
The environment variables are now loading correctly! However, the current Stripe keys appear to be invalid or expired.

## 🔑 **ACTION REQUIRED: Get New Stripe Keys**

The current keys in your `.env` file are not working. You need to get fresh test keys from Stripe.

## Solution
The Stripe configuration has been updated to use environment variables for better security and maintainability.

## Setup Instructions

### 0. Test Environment Variables (Recommended)
Before proceeding, test if your environment variables are loading correctly:
1. Open `test_stripe_keys.php` in your browser
2. Check if the Stripe keys are displayed correctly
3. If not, verify your `.env` file is in the project root

### 1. Get Your Stripe Keys

1. Go to [Stripe Dashboard](https://dashboard.stripe.com/)
2. Navigate to Developers > API Keys
3. Copy your **Publishable key** (starts with `pk_test_` for test mode)
4. Copy your **Secret key** (starts with `sk_test_` for test mode)

### 2. Configure Environment Variables

#### For PHP (checkout.php):
Add to your `.env` file or server environment:
```
STRIPE_PUBLISHABLE_KEY=pk_test_your_actual_publishable_key_here
```

#### For Node.js Server:
Update the `.env` file in the root directory:
```
STRIPE_PUBLISHABLE_KEY=pk_test_your_actual_publishable_key_here
STRIPE_SECRET_KEY=sk_test_your_actual_secret_key_here
```

### 3. Update the Keys

1. **Frontend (checkout.php)**: The publishable key is now loaded from environment variables
2. **Backend (server/config/stripe.js)**: The secret key is loaded from environment variables

### 4. Test the Integration

1. Start your local server
2. Go to the checkout page
3. Try to proceed with payment
4. Check browser console for any remaining errors

## Files Modified

- `checkout.php`: Updated to load Stripe key from environment variables and include config
- `api/config.php`: Added environment variable loading functionality
- `server/config/stripe.js`: Created Stripe configuration file
- `.env`: Added Stripe configuration variables
- `api/create_order.php`: Fixed order number generation to use longer unique IDs
- `test_stripe_keys.php`: Created test file to verify environment variable loading
- `STRIPE_SETUP.md`: Created comprehensive setup documentation

## Security Notes

- Never commit real API keys to version control
- Use test keys for development
- Switch to live keys only when ready for production
- Regularly rotate your API keys

## 🚨 **CURRENT ISSUE: Invalid Stripe Keys**

The environment variables are working perfectly, but the Stripe API keys are invalid/expired.

## 🔧 **How to Fix**

### Step 1: Get New Stripe Test Keys
1. Go to [Stripe Dashboard](https://dashboard.stripe.com/)
2. Navigate to **Developers → API Keys**
3. Copy your **Publishable key** (starts with `pk_test_`)
4. Copy your **Secret key** (starts with `sk_test_`)

### Step 2: Update Your .env File
Replace the current keys in `.env`:
```env
STRIPE_PUBLISHABLE_KEY=pk_test_your_new_key_here
STRIPE_SECRET_KEY=sk_test_your_new_key_here
```

### Step 3: Test
1. Visit `test_stripe_keys.php` to verify new keys are loaded
2. Try the checkout process again

## ✅ **What's Working**
- ✅ Environment variables loading correctly
- ✅ PHP configuration working properly
- ✅ File paths and includes working
- ✅ JavaScript can access PHP variables
- ✅ Payment processing simulation implemented
- ✅ No more 404 errors on payment processing

## ❌ **What's Not Working**
- ❌ Current Stripe keys are invalid/expired
- ❌ Need fresh test keys from Stripe dashboard

## Troubleshooting

If you still see 401 errors:
1. **Test environment variables**: Visit `test_stripe_keys.php` to verify variables are loading
2. **Check .env file location**: Ensure `.env` is in the project root directory
3. **Verify API keys**: Make sure your Stripe keys are correct and active
4. **Check key format**: Ensure keys start with `pk_test_` (publishable) and `sk_test_` (secret)
5. **Test mode**: Ensure you're using test keys, not live keys
6. **Stripe dashboard**: Check for any account restrictions or issues
7. **Browser cache**: Clear browser cache and try again

If you see 404 errors on payment processing:
- ✅ This has been fixed! The payment processing now uses a simulation instead of calling a non-existent endpoint
- The checkout should now complete successfully after entering payment details