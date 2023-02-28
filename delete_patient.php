<?php

// Récupération de l'ID du patient à supprimer
$patient_id = $_POST['patient_id'];

// URL de l'API HAPI FHIR
$api_url = 'http://hapi.fhir.org/baseDstu3/Patient/' . $patient_id;

// Initialisation de la session cURL
$curl = curl_init();

// Configuration de la requête cURL
curl_setopt($curl, CURLOPT_URL, $api_url);
curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
curl_setopt($curl, CURLOPT_RETURNTRANSFER, true);

// Exécution de la requête cURL
$response = curl_exec($curl);

// Fermeture de la session cURL
curl_close($curl);

// Affichage de la réponse de l'API
echo $response;

?>
