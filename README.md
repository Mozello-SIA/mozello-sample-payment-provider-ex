# Mozello sample payment provider with visual shop enhancements

This repository provides a sample payment provider for [Mozello](https://www.mozello.com) websites. It adds a new payment method and also adds visual enhancements on the catalog product view page, cart, and checkout form using the Apps API. This can be used to display "pay later" options or other payment-related information automatically.

A basic demo without the visual enhancements can be found here: [mozello-sample-payment-provider](https://github.com/Mozello-SIA/mozello-sample-payment-provider)

# Quick start
1. Create Mozello account if you haven't already.
2. The payment provider needs to be hosted in a public location. You will need to have the public URL to complete the next steps.
3. Modify `meta/manifest.json` and replace the sample values with your data.
4. Submit the manifest via [Payment integration setup portal](https://www.mozello.com/apps/api/payments/) and save the resulting API key. You can also re-submit your manifest later if it is changed.
5. Modify `config.php`. Fill in API key from the integration setup portal.
6. Modify other scripts and files to add the required functionality.
7. Upload `public` to your host.
8. The payment provider will be available in your Mozello website.


# Mozello API documentation

[Payment API](https://www.mozello.com/developers/payment-api/)

[Apps API](https://www.mozello.com/developers/apps-api/)
