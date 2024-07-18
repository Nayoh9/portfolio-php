    <?php
    require_once __DIR__ . '../../../vendor/autoload.php';

    $error_db = "error_no_results_found";

    // Dynamic url 
    $dashboard_url = "/dashboard/";
    $template_url = "/portfolio/";


    // **Dotenv config**
    $dotenvPath = __DIR__ . '/../../.env';
    $dotenv = Dotenv\Dotenv::createImmutable(dirname($dotenvPath));
    $loaded = $dotenv->load();

    // Tiny mce env vars
    $tiny_mce_key = $_ENV["TINY_MCE_KEY"];

    // PDO env vars
    $db_user = $_ENV["DB_USER"];
    $db_pass = $_ENV["DB_PASS"];
    $db_host = $_ENV["DB_HOST"];
    $db_name = $_ENV["DB_NAME"];




    // **DB connexion** //
    try {
        $db = new PDO("mysql:host=$db_host;dbname=$db_name", $db_user, $db_pass);
    } catch (PDOException $e) {
        // echo"Error connexion to DB";
        var_dump($e->getMessage());
        die();
    }

    // AWS S3 config 

    use Aws\S3\S3Client;

    $AWS_CREDENTIALS = serialize(array(
        "key" => $_ENV["API_PUBLIC"],
        "secret" => $_ENV["API_SECRET"]
    ));


    //Create a S3Client
    $s3Client = new S3Client([
        'region' => 'eu-north-1',
        'version' => 'latest',
        'credentials' => unserialize($AWS_CREDENTIALS)
    ]);

    $bucket = "pixeven";

    //Listing all S3 Bucket
    // $buckets = $s3Client->listBuckets();
    // foreach ($buckets['Buckets'] as $bucket) {
    //     echo $bucket['Name'] . "\n";
    // }
