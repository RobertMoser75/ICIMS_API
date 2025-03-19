# ICIMS_API
 PHP Script for Making API Requests to ICIMS ATS by Loading Environment Variables.
Overview

This PHP code interacts with an external API to retrieve job listings from a job portal database and display them on a website. It provides a search functionality where users can filter job listings based on job title, location, and category. The results are displayed in a user-friendly format, allowing users to easily navigate and view job opportunities.
Key Features:

    Search Functionality: Allows users to search for jobs by title, location, and category.
    Job Display: Displays job listings with key information such as job title, location, category, and description.
    Session Persistence: Retains user search criteria across page refreshes.
    Dynamic Job Listings: Fetches data from an external API endpoint and displays the results in a structured format.
    Job Detail Links: Each job title is a clickable link that redirects users to a page for more detailed information.

Code Breakdown
API Interaction

    API Request: The code sends a POST request to an API endpoint (https://cadmus-apim.azure-api.net/sql/query) to fetch job listings from the database. The query sent is a SQL query that retrieves all rows from the ods_iCIMS_JobPortal table, or a filtered version based on user inputs.

    Request Payload:
        The payload for the POST request includes a SQL query string ($query) wrapped in a statement key.
        The headers include the content type (application/json) and authorization (Bearer {$auth_key}) for API access.

    Response Handling:
        The response from the API is JSON-decoded (json_decode($response)) and saved in the $dataDecode variable for further processing.

Job Search Form

    User Input:
        A form is provided to let users search for jobs based on Job_Title, Location, and Category.
        Users can type a keyword, select a location, or select a category from drop-down lists.
    Form Persistence:
        When the form is submitted, the search criteria (search_query, locationfun, and category) are stored in PHP session variables ($_SESSION["last_search_query"], $_SESSION["last_location"], $_SESSION["last_category"]). This allows the page to remember the user's previous inputs even after a page refresh.

Job Listings Display

    Job Information:
        The script loops through each job listing returned in the API response ($dataDecode->ResultSets->Table1).
        For each job, it extracts key details such as the job title, locations, category, description, job ID, and posting date.

    Location Handling:
        If a job listing has multiple locations (delimited by new lines), the locations are split and displayed separately.

    Job Link:
        Each job title is a clickable link, which redirects users to a detailed job description page (/job/?job_id={$jobid}).

    Description Formatting:
        A truncated version of the job description is displayed (up to 850 characters) for an overview. A script strips certain unwanted text ("What You’ll Be Doing") and HTML entities (&nbsp;) from the description.

Search Logic

    WHERE Clause Creation:
        When the form is submitted, the code builds a dynamic SQL WHERE clause based on the user’s inputs (keywords, location, and category). If any of these values are provided, they are added to the WHERE clause using the LIKE operator for pattern matching.
    Dynamic SQL Query:
        The query is built dynamically, adding conditions for Job_Title, Job_locations, and Category only if the respective values are provided. The conditions are joined using OR.

Error Handling and Debugging

    cURL Error Handling:
        If the API request fails, the code checks for errors using curl_error($curl) and terminates the script with an error message.

Frontend Display

    HTML Structure:
        The HTML structure consists of a search form and a list of job results. The search form allows users to filter jobs based on their criteria, and the results are displayed below the form.

    CSS Classes:
        The code uses specific CSS classes (search-form, jobswrapper, flexRow, etc.) to structure and style the page elements, making the display of jobs visually appealing and responsive.

    JavaScript:
        A small script is included at the end of the HTML to manipulate the job descriptions, removing unwanted text and adjusting formatting.

Setup Instructions

    Server Requirements:
        PHP 7.x or later.
        cURL enabled on the server to make API requests.
        Access to the external job portal API with a valid auth_key.

    Configuration:
        Ensure that the $auth_key variable is set to a valid API token for authenticating requests.

    Session Setup:
        PHP sessions should be enabled to store user input between page reloads.

    Customizations:
        Customize the search filters (locationfun, category, etc.) and the API endpoint as necessary to fit the specific needs of your job portal application.

Conclusion

This PHP script enables dynamic job searching and filtering based on user inputs. It interacts with an external API to retrieve and display job listings, allowing users to search by job title, location, and category. The job data is displayed in a clean and responsive manner with links to more detailed job descriptions.