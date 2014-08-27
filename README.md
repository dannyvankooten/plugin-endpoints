# EDD Software Licensing Endpoint

This class creates and endpoint for the Easy Digital Downloads Softare Licensing add-on. It dramatically increases the performance of all (remote) API requests.

Results depend on many factors (active plugins, ..) but it is not unusual to experience a 50% gain in speed and a decrease in memory consumption of about 25-30%.

### Usage 

1. Add the plugin file to your `/wp-content/mu-plugins` folder of the site running Easy Digital Downloads & EDD Software Licensing.
1. In your products, point all API requests to `yoursite.com/edd-sl-api`.

*Example API URL's*
```
https://youreddstore.com/edd-sl-api/?edd_action=get_version....
https://youreddstore.com/edd-sl-api/?edd_action=activate_license....
https://youreddstore.com/edd-sl-api/?edd_action=deactivate_license....
```