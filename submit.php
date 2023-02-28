<?php
if (isset($_POST['submit'])) {
    $name = $_POST['newpatientFirstName'];
    $lastname = $_POST['newpatientName'];

    $patient = array(
        "resourceType" => "Patient",
        "name" => array(
            array(
                "family" => $name,
                "given" => array($lastname)
            )
        )
    );
    $data_string = json_encode($patient);

    $ch = curl_init('http://hapi.fhir.org/baseDstu3/Patient');
    curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "POST");
    curl_setopt($ch, CURLOPT_POSTFIELDS, $data_string);
    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);
    curl_setopt($ch, CURLOPT_HTTPHEADER, array(
            'Content-Type: application/json',
            'Content-Length: ' . strlen($data_string))
    );

    $result = curl_exec($ch);
    curl_close($ch);

    echo $result;
}
?>
