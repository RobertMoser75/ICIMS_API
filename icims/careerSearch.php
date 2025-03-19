<?php
//Loads all CSS and JS for current component.  No need to modify this code. 
require_once get_template_directory()."/mainincludes/findcomponentincludes.php";
recursiveGlob(__DIR__, 'css');
recursiveGlob(__DIR__, 'js');
?>

<?php

// Set the API endpoint URL
$url = 'https://cadmus-apim.azure-api.net/sql/query';

// Start building the SQL query
$query = "SELECT * FROM [dbo].[ods_iCIMS_JobPortal]";


// Add the WHERE clause to the query

// Set the request payload with the updated query
$request_payload = [
    'statement' => $query
];
// Set the request headers
$headers = [
    'Content-Type: application/json',
    "Authorization: Bearer {$auth_key}"
];
// Initialize cURL
$curl = curl_init();
// Set cURL options
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($request_payload),
    CURLOPT_HTTPHEADER => $headers,
));
// Execute the request
$response = curl_exec($curl);
// Check for errors
if ($response === false) {
    $error = curl_error($curl);
    // Handle the error appropriately
    // For example, you could log the error or display a user-friendly message
    die("cURL Error: " . $error);
}
// Close the cURL session
curl_close($curl);
// Decode the response
$dataDecode = json_decode($response);
?>
<?php
$dataDecode; // Initialize the variable
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
$keywords = $_POST['search_query'];
$locationfun = $_POST['locationfun'];
$category = $_POST['category'];
// Set the API endpoint URL
$url = 'https://cadmus-apim.azure-api.net/sql/query';

// Start building the SQL query
// Start building the SQL query
$query = "SELECT * FROM [dbo].[ods_iCIMS_JobPortal] WHERE ";

// Initialize an array to store the conditions for the WHERE clause
$conditions = array();

// Check if the search_query is provided and not empty
if (!empty($keywords)) {
$conditions[] = "Job_Title LIKE '%$keywords%'";
}

// Check if the location is provided and not empty
if (!empty($locationfun)) {
// Use LIKE operator for pattern matching
$conditions[] = "Job_locations LIKE '%$locationfun%'";
}

// Check if the category is provided and not empty
if (!empty($category)) {
$conditions[] = "Category LIKE '%$category%'";
}

// Combine the conditions with 'OR' to create the WHERE clause
$where_clause = implode(" OR ", $conditions);

// Add the WHERE clause to the query
$query .= $where_clause;

// Set the request payload with the updated query
$request_payload = [
    'statement' => $query
];
// Set the request headers
$headers = [
    'Content-Type: application/json',
    "Authorization: Bearer {$auth_key}"
];
// Initialize cURL
$curl = curl_init();
// Set cURL options
curl_setopt_array($curl, array(
    CURLOPT_URL => $url,
    CURLOPT_RETURNTRANSFER => true,
    CURLOPT_ENCODING => '',
    CURLOPT_MAXREDIRS => 10,
    CURLOPT_TIMEOUT => 0,
    CURLOPT_FOLLOWLOCATION => true,
    CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_2TLS,
    CURLOPT_CUSTOMREQUEST => 'POST',
    CURLOPT_POSTFIELDS => json_encode($request_payload),
    CURLOPT_HTTPHEADER => $headers,
));
// Execute the request
$response = curl_exec($curl);
// Check for errors
if ($response === false) {
    $error = curl_error($curl);
    // Handle the error appropriately
    // For example, you could log the error or display a user-friendly message
    die("cURL Error: " . $error);
}
// Close the cURL session
curl_close($curl);
// Decode the response
$dataDecode = json_decode($response);
}
?>
<section class="cadmusJobs">
    <div class="container">


        <?php
session_start(); // Start the PHP session

// Check if the form is submitted
if ($_SERVER["REQUEST_METHOD"] == "POST") {
// Retrieve the form data
$last_search_query = $_POST["search_query"];
$last_location = $_POST["locationfun"];
$last_category = $_POST["category"];

// Store the form data in session variables
$_SESSION["last_search_query"] = $last_search_query;
$_SESSION["last_location"] = $last_location;
$_SESSION["last_category"] = $last_category;
}


?>

        <div class="search-form">
            <h2> Job Listings</h2>
            <p> You can view all open job positions or use the following search form to find jobs that suit your
                specific career interests.</p>
            <form method="post" action="<?php echo esc_url(get_permalink()); ?>">

                <div class="input-wrapper">
                    <div class="selectWrap">
                        <label class="SeaSE">Location</label>
                        <select name="locationfun">
                            <option value="">All</option>
                            <?php
                $locationdata = $dataDecode;
                if (isset($locationdata)) {
                    $locationsarray = [];
                    foreach ($locationdata->ResultSets->Table1 as $job) {
                        $job_locations = $job->Job_locations;

                        if (strpos($job_locations, "\n") !== false) {
                            $location_parts = preg_split('/[\n]+/', $job_locations);
                            foreach ($location_parts as $location) {
                                $locationsarray[] = trim($location);
                            }
                        } else {
                            $locationsarray[] = trim($job_locations);
                        }
                    }

                    $locationsarray = array_unique($locationsarray);

                    foreach ($locationsarray as $location) {
                        $selected = isset($_SESSION['last_location']) && $_SESSION['last_location'] == $location ? 'selected' : '';
                        echo "<option value='$location' $selected>" . $location . "</option>";
                    }
                }
                ?>
                        </select>
                    </div>

                    <div class="selectWrap">
                        <label class="SeaSE">Category</label>
                        <select name="category">
                            <option value="">All</option>
                            <?php
                $categorydata = $dataDecode;
                if (isset($categorydata)) {
                    $categories = [];
                    foreach ($categorydata->ResultSets->Table1 as $category) {
                        $categories[] = $category->Category;
                    }

                    $categories = array_unique($categories);

                    foreach ($categories as $category) {
                        $selected = isset($_SESSION['last_category']) && $_SESSION['last_category'] == $category ? 'selected' : '';
                        echo "<option value='$category' $selected>" . $category . "</option>";
                    }
                }
                ?>
                        </select>
                    </div>
                </div>
                <label class="SeaSE">Job search</label>
                <div class="input-wrapperSF">
                    <input type="text" name="search_query" placeholder="Start your job search here"
                        value="<?php echo isset($_SESSION['last_search_query']) ? $_SESSION['last_search_query'] : ''; ?>">
                    <button type="submit">Search</button>
                </div>
            </form>
        </div>

        <div class="jobsearch-row">
            <?php
            // Check if search results are available
            if (isset($dataDecode)) {
                foreach ($dataDecode->ResultSets->Table1 as $job) {
                    // Job details
                    $jobTitle = $job->Job_Title;
                    $jobLocations = $job->Job_locations;
                    $jobid = $job->Job_ID;
                    $numopening = $job->Num_of_Openings;
                    $jobDescription = $job->Description_Overview;
                    $jobpostDate = $job->Posted_Date;
                    $jobcategory = $job->Category;

                    // Construct the URL with the job ID as a query parameter
                    $buttonUrl = '/job/?job_id=' . $jobid;

        // Creates an array of individual job locations
        $locationsArray = explode("\n", $jobLocations);
        ?>

            <div class="search-wrapper">
                <div class="jobswrapper sec">

                    <div class="genwrap">
                        <a href="<?php echo $buttonUrl; ?>">
                            <h2><?php echo $jobTitle; ?></h2>
                        </a>
                        <p class="jd-search">
                            <span>
                                <strong>Date:</strong>
                                <?php echo $jobpostDate; ?>
                            </span>
                            <span>
                                <strong>Job ID:</strong>
                                <?php echo $jobid; ?>
                            </span>
                            <span>
                                <strong>Category:</strong>
                                <?php echo $jobcategory; ?>
                            </span>
                        </p>
                        <ul class="flexRow locResults">
                            <li class="BLD">Location:</li>
                            <?php foreach ($locationsArray as $location): ?>
                            <li><?php echo trim($location); ?></li>
                            <?php endforeach; ?>
                        </ul>
                    </div>
                    <div id="ICIMSdesc" class="descriptionWrap">
                        <p id="myParagraph"><?php echo substr(strip_tags($jobDescription), 0, 850); ?>...</p>
                    </div>
                </div>
                <?php
        }
    } else {
        // No search results found
        echo '<p>No matching jobs found.</p>';
    }
    ?>
            </div>
        </div>
</section>
<script>
// This script should be placed after the HTML content you provided

document.addEventListener('DOMContentLoaded', function() {
    // Get all elements with class "descriptionWrap"
    var descriptionElements = document.querySelectorAll('.descriptionWrap');

    // Iterate through each description element
    descriptionElements.forEach(function(element) {
        // Get the inner HTML of the element
        var description = element.innerHTML;

        // Remove the specific phrase
        description = description.replace('What You’ll Be Doing', '');

        // Remove &nbsp;
        description = description.replace(/&nbsp;/g, '');

        // Trim leading space from the first word
        description = description.replace(/^\s+/, '');

        // Set the modified content back to the element
        element.innerHTML = description;
    });
});
</script>