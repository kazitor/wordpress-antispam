# wordpress-antispam

This is a simple plugin to prevent spam from particular IP ranges (that must be added to the file manually).

If a client wishes to write a comment from one of the IPs or ranges you specify, the client must write "please" in a separate text field with explicit instructions to do that. The idea is that most of the spam you receive is not specifically targetting your site.

## Installation
Download or clone the repository (Use the "clone or download" button, select "Download ZIP" if you don't have special programs) and place kazantispam.php in your plugins directory (basedir/wp-content/plugins by default).

## Configuration
There is a set of lines like (lines 25–29 as of writing)
```php
$badips = array(
    '46.161.9.0/25',
    '77.120.125.23',
    '178.159.37.0/24'
);
```
These IPs are just the more recent ones that had been targetting my site until I wrote the plugin, just replace them with whatever IPs and ranges you're seeing writing lots of spam.

You'll probably also want to change the form output to match your theme. It's all under the `kazantispam_field` function.

## Acknowledgements
The function used to determine if an IP is in a CIDR range was written by tott: https://gist.github.com/tott/7684443
