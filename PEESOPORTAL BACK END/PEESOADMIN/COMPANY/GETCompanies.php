<?php
class CompanyRetriever {
    private $pdo;
    private $companyTable;

    public function __construct($pdo, $companyTable = 'tblcompany') {
        $this->pdo = $pdo;
        $this->companyTable = $companyTable;
    }

    public function getCompaniesByLoginID() {
        $query = "SELECT *,concat('http://10.0.1.26:82/PEESOPORTAL/REGISTRATION/ADMIN/Logos/',company_name,'/',company_logo) as logo FROM " . $this->companyTable ;
        $stmt = $this->pdo->prepare($query);
       
        $stmt->execute();
        
        return $stmt->fetchAll();
    }
}

// Usage example
try {
    $results = [];
    $dsn = 'mysql:host=localhost;dbname=PEESO';
    $username = 'root';
    $password = '';
    $options = [
        PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
        PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_OBJ,
    ];

    $pdo = new PDO($dsn, $username, $password, $options);
    $companyRetriever = new CompanyRetriever($pdo);

    // Retrieve the LoginID from the form data
    // $loginID = filter_input(INPUT_POST, 'LoginID', FILTER_VALIDATE_INT);
    // if ($loginID === null || $loginID === false) {
    //     throw new InvalidArgumentException("LoginID input is missing or invalid.");
    // }

    $companyInfo = $companyRetriever->getCompaniesByLoginID();
    $results['success'] = true;
    $results['data'] = $companyInfo;
    // $results['data']['Company_Logo']='C:/xampp/htdocs/PEESOPORTAL/' . $companyInfo['Company_name'];
    // echo $results['data']['Company_Logo'];
} catch (PDOException $e) {
    $results['success'] = false;
    $results['message'] = "Database connection failed: " . $e->getMessage();
} catch (InvalidArgumentException $e) {
    $results['success'] = false;
    $results['message'] = $e->getMessage();
}

echo json_encode($results);
?>