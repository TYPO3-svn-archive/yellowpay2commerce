
yellowpay2commerce

30.10.2008
Cedric Spindler <cs@cabag.ch>, cab services ag

This extension provides a commerce payment-provider to the yellowpay payment 
gateway. The yellowpay-provider will be added to the existing list of providers 
in commerce.

Version 0.0.3 introduces the possibility to use the "dynamic template" option
that PostFinance provides in its gateway. You need a html-template with the 
marker "$$$PAYMENT ZONE$$$". Important: The payment gateway uses SSL, if you
aren't using SSL for your shop it breaks the secure environment (The browser
complains about "non-SSL content"). Solution: Use SSL, too or host the content
at PostFinance (service provided).

Please put any bug reports or feature requests on forge.typo3.org project page.
Patches are welcome when tested and commented properly (always 
with documentation text please).

IMPORTANT!
Manual checking in the Postfinance Backoffice by the seller is strongly recommended.

Don't check this option in the yellowpay backend:
"5.2: Display a ticket to the client if a redirection to your website is
detected just after the post-payment process".
Otherwise the client could break up the process by clicking away the
ticket window.

Take a look at the screenshot postfinance.jpg and the red marks to
set the right Yellowpay options.
