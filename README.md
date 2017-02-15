# webhook-api

A small API for handling webhooks from GitHub.

This API handles:

 * Checking a request genuinely came from GitHub
 * Pulling the latest code to the specified directory
 
## How to use
 
First, you'll need to clone this repo and set up a virtual host to point to it.
 
> I recommend that you use SSL for this API. You can get free SSL certificates from http://letsencrypt.org
 
Next, set up your webhook in GitHub. Use the same API endpoint for all your repositories. 
 
You also need to set a *secret*. This will allow you to ensure a request to your API was made by GitHub. Use a password generator such as http://passwordsgenerator.net to create a secure secret.
 
Now you'll need to create a `RepoLinks.php` file. This file will contain the secret for each repository along with the path to the repo. There's a template to get you started named `RepoLinks.template.php`. Rename this to `RepoLinks.php` and fill in your information.

Here's an example of how your RepoLinks.php file should look

````php
<?php
return [
    'danjohnson95/zappem' => [
        'secret' => 'supER!Secr3t<P4ssW0RD',
        'path' => '/var/www/zappem'
    ],
    'danjohnson95/devflow' => [
        'secret' => '@&9dqKtNZ{Z9$fm5',
        'path' => '/var/www/devflow'
    ]
];
````
