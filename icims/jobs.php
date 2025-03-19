<?php
//Loads all CSS and JS for current component.  No need to modify this code. 
require_once get_template_directory()."/mainincludes/findcomponentincludes.php";
recursiveGlob(__DIR__, 'css');
recursiveGlob(__DIR__, 'js');
?>

<style>
/* This is sued for <p> with white space */
.emptyP {
    line-height: 0;
}
</style>
<?php


// Initialize cURL
$curl = curl_init();

// Set the API endpoint URL
$url = 'https://cadmus-apim.azure-api.net/sql/query';

// Set the job ID parameter
$job_id = $_GET['job_id'];

// Set the request payload
$request_payload = [
    'statement' => "SELECT * FROM [dbo].[ods_iCIMS_JobPortal] WHERE Job_ID = '$job_id'"
];

// Set the request headers
$headers = [
    'Content-Type: application/json',
    "Authorization: Bearer {$auth_key}"
];

// Set curl options
curl_setopt_array($curl, [
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
]);

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

<section class="cadmusJobs JBP">
    <div class="container">
        <div class="row">
            <?php foreach ($dataDecode->ResultSets->Table1 as $job): ?>
            <?php
                $jobTitle = $job->Job_Title;
                $jobLocations = $job->Job_locations;
                $jobID = $job->Job_ID;
                $numOpenings = $job->Num_of_Openings;
                $jobDescription = $job->Description_Overview;
                $jobResponsibilities = $job->Description_Responsibilities;
                $jobQualifications = $job->Description_Qualifications;
                $jobPostDate = $job->Posted_Date;

                // Process job URL and ID
                $splitString = explode("-", $jobID);
                $jobUrlID = $splitString[1] ?? '';
                $hyphenatedString = str_replace(' ', '-', $jobTitle);

                // Creates an array of individual job locations
                $locationsArray = explode("\n", $jobLocations);
             
                ?>

            <div class="col-md-3 col-lg-2">
                <div id="genwrap" class="jobWrapper JP">
                    <h3 class="jb-SB">
                        <span>Job Post Date:</span>
                        <?php echo $jobPostDate; ?>
                    </h3>
                    <h3 class="jb-SB">
                        <span>Job ID:</span>
                        <?php echo $jobID; ?>
                    </h3>

                    <ul class="flexRow NM">
                        <li>
                            <h3>Location:</h3>
                        </li>
                        <?php foreach ($locationsArray as $location): ?>
                        <li>
                            <p><?php echo trim($location); ?></p>
                        </li>
                        <?php endforeach; ?>
                    </ul>
                    <h3 class="jb-SB TM">
                        <span>Job Openings:</span>
                        <?php echo $numOpenings; ?>
                    </h3>
                    <div class="jobs-Wrap-apply">
                        <a class="btn jobbtn "
                            href="https://careers-cadmusgroup.icims.com/jobs/<?php echo $jobUrlID; ?>/<?php echo $hyphenatedString; ?>/login">
                            Apply for job
                        </a>
                    </div>
                </div>
            </div>

            <div id="borderlft" class="col-lg-10 col-md-9">
                <div class="returnBTN"><a href="/careers/search-careers">
                        << Return to Jobs Search Page</a>
                </div>
                <h2><?php echo $jobTitle; ?></h2>
                <div class="jobWrapper JP JBD">
                    <h3>Overview</h3>
                    <div class="descriptionWrap">
                        <?php echo $jobDescription; ?>
                    </div>
                    <div class="responsibilitiesWrap">
                        <h3>Responsibilities</h3>
                        <?php echo $jobResponsibilities;?>
                    </div>
                    <div class="qualificationswrap">
                        <h3>Qualifications:</h3>
                        <?php echo $jobQualifications; ?>
                    </div>

                </div>
            </div>
            <?php endforeach; ?>
        </div>
    </div>
</section>


<script>


document.addEventListener('DOMContentLoaded', function() {

    // Get all <p> elements
    var paragraphs = document.getElementsByTagName("p");
    // Loop through each <p> element
    for (var i = 0; i < paragraphs.length; i++) {
        var paragraph = paragraphs[i];

        // Check if the innerHTML of the <p> element is '&nbsp;'
        if (paragraph.innerHTML === "&nbsp;") {
            // Add the class 'emptyP' to the <p> element
            paragraph.classList.add("emptyP");
        }
    }
    var spans = document.getElementsByTagName("span");
    // Loop through each <p> element
    for (var i = 0; i < spans.length; i++) {
        var span = spans[i];

        // Check if the innerHTML of the <p> element is '&nbsp;'
        if (span.innerHTML === "&nbsp;") {
            // Add the class 'emptyP' to the <p> element
            span.classList.add("emptyP");
        }
    }
});
</script>