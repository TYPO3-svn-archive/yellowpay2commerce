
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
This extension does not check, if the payment was successful! Manual checking 
in the Postfinance Backoffice by the seller is strongly recommended.
TYPO3 does not provide the required security level to handle fully trusded 
payments!
