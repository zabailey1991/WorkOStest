<?php

require __DIR__ . "/vendor/autoload.php";

use Twig\Environment;
use Twig\Loader\FilesystemLoader;

//Set API Key, ClientID, and Connection
$WORKOS_API_KEY = "sk_test_a2V5XzAxRlNRWjc4RFM4UTY3OFo4Vk5IWENZSk44LEpiRFBPSEFMU3c4dW1FdUtRZ1NxSVNDSWE";
$WORKOS_CLIENT_ID = "client_01FSQZ78E0633E0RYEDGBTJZ8G";
$WORKOS_CONNECTION_ID = "";


// Setup html templating library
$loader = new FilesystemLoader(__DIR__ . '/templates');
$twig = new Environment($loader);

// Configure WorkOS with API Key and Client ID 
\WorkOS\WorkOS::setApiKey($WORKOS_API_KEY);
\WorkOS\WorkOS::setClientId($WORKOS_CLIENT_ID);

// Convenient function for throwing a 404
function httpNotFound() {
    header($_SERVER["SERVER_PROTOCOL"] . " 404");
    return true;
}

// Routing
switch (strtok($_SERVER["REQUEST_URI"], "?")) {
    case (preg_match("/\.css$/", $_SERVER["REQUEST_URI"]) ? true: false): 
        $path = __DIR__ . "/static/css" .$_SERVER["REQUEST_URI"];
        if (is_file($path)) {
            header("Content-Type: text/css");
            readfile($path);
            return true;
        }
        return httpNotFound();

    case (preg_match("/\.png$/", $_SERVER["REQUEST_URI"]) ? true: false): 
        $path = __DIR__ . "/static/images" .$_SERVER["REQUEST_URI"];
        if (is_file($path)) {
            header("Content-Type: image/png");
            readfile($path);
            return true;
        }
        return httpNotFound();

// /auth page is what will run the getAuthorizationUrl function

/* There are 6 parameters for the GetAuthorizationURL Function
Domain (deprecated), Redirect URI, State, Provider, Connection and Organization
These can be read about here: https://workos.com/docs/reference/sso/authorize/get
We recommend using Connection (pass a connectionID) */

    case ("/auth"):
        $authorizationUrl = (new \WorkOS\SSO())
            ->getAuthorizationUrl(
                null, //domain is deprecated, use organization instead
                'http://localhost:8000/callback', //redirectURI
                [], //state array, also empty
                null, //Provider which can remain null unless being used
                $WORKOS_CONNECTION_ID, //connection which is the WorkOS Connection ID,
                null //organization ID, to identify connection based on organization ID
            );
            
        header('Location: ' . $authorizationUrl, true, 302);
        return true;

    // /callback page is what will run the getProfileAndToken function and return it
    case ("/callback"):
        $profile = (new \WorkOS\SSO())->getProfileAndToken($_GET["code"]);
        $first_name = $profile->raw['profile']['first_name'];
        
        echo $twig->render("login_successful.html.twig", ['raw_profile' => json_encode($profile), 'first_name' => $first_name]);
        return true;
 

    // home and /login will display the login page       
    case ("/"):
    case ("/login"):
        echo $twig->render("login.html.twig");
        return true;

    default:
        return httpNotFound();
}