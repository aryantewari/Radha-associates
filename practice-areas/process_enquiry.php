<?php
// MongoDB PHP library load karo
require 'vendor/autoload.php'; 

if ($_SERVER["REQUEST_METHOD"] == "POST") {
    try {
        // Tumhare local database se connection
        $client = new MongoDB\Client("mongodb://localhost:27017");
        $collection = $client->legal_audience_db->enquiries;

        // Form fields ko extract karna
        $fullName = $_POST['fullName'] ?? '';
        $phone = $_POST['phone'] ?? '';
        $email = $_POST['email'] ?? '';
        $practiceArea = $_POST['practiceArea'] ?? 'Property';
        $location = $_POST['location'] ?? 'Not provided';
        $requirement = $_POST['requirement'] ?? 'Not provided';
        $description = $_POST['message'] ?? '';

        // Location aur Requirement ko message mein append kar rahe hain taaki MongoDB mein ek hi field (message) mein sab save ho jaye
        $fullMessage = "Requirement: " . $requirement . " | Location: " . $location . " | Details: " . $description;

        // Document structure exactly jaisa tumhare Compass screenshot mein hai
        $document = [
            'fullName' => $fullName,
            'phone' => $phone,
            'email' => $email,
            'practiceArea' => $practiceArea,
            'message' => $fullMessage,
            'submittedAt' => new MongoDB\BSON\UTCDateTime(),
            '__v' => 0
        ];

        // Database mein insert karna
        $collection->insertOne($document);

        // Success ke baad alert dekar wapas page pe bhej do
        echo "<script>
                alert('Verification Request Submitted Successfully!');
                window.location.href = 'property.html'; 
              </script>";
        exit;

    } catch (Exception $e) {
        echo "Error saving data: " . $e->getMessage();
    }
}
?>