<?php

function loadEnv($file)
{
    if (file_exists($file)) {
        $lines = file($file, FILE_IGNORE_NEW_LINES | FILE_SKIP_EMPTY_LINES);
        
        foreach ($lines as $line) {
            // Ignore comments and empty lines
            if (strpos(trim($line), '#') === 0) continue;
            
            // Parse key=value pairs
            list($key, $value) = explode('=', $line, 2);
            putenv("$key=$value"); // Set the environment variable
            $_ENV[$key] = $value; // Optionally, also populate $_ENV
        }
    } else {
        throw new Exception("Env file not found.");
    }
}

// Load .env file
loadEnv(__DIR__ . '/.env');

// Access the variables
$clientId = getenv('CLIENT_ID');
$clientSecret = getenv('CLIENT_SECRET');
$subscriptionKey = getenv('OCP_APIM_SUBSCRIPTION_KEY');
$apiUrl = getenv('API_URL');

// Prepare the data
$data = array(
    'client_id' => $clientId,
    'client_secret' => $clientSecret,
    'grant_type' => 'client_credentials',
    'resource' => 'api://Corporate-Website-Careers'
);

$options = array(
    CURLOPT_URL => $apiUrl,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($data),
    CURLOPT_HTTPHEADER => array(
        'Ocp-Apim-Subscription-Key: ' . $subscriptionKey,
        'Content-Type: application/json'
    )
);

$curl = curl_init();
curl_setopt_array($curl, $options);
 
$response = curl_exec($curl);
$responsedecode = json_decode($response, true);
$auth_key = $responsedecode['access_token'];
 
curl_close($curl);
 
?>