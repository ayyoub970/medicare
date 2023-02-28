<!DOCTYPE html>
<html>

<head>
  <link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/css/bootstrap.min.css" integrity="sha384-Gn5384xqQ1aoWXA+058RXPxPg6fy4IWvTNh0E263XmFcJlSAwiGgFAW/dAiS6JXm" crossorigin="anonymous">
  <script src="https://code.jquery.com/jquery-3.2.1.slim.min.js" integrity="sha384-KJ3o2DKtIkvYIK3UENzmM7KCkRr/rE9/Qpg6aAZGJwFDMVNA/GpGFF93hXpG5KkN" crossorigin="anonymous"></script>
  <script src="https://cdnjs.cloudflare.com/ajax/libs/popper.js/1.12.9/umd/popper.min.js" integrity="sha384-ApNbgh9B+Y1QKtv3Rn7W3mgPxhU9K/ScQsAP7hUibX39j7fakFPskvXusvfa0b4Q" crossorigin="anonymous"></script>
  <script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.0.0/js/bootstrap.min.js" integrity="sha384-JZR6Spejh4U02d8jOt6vLEHfe/JQGiRRSQQxSfFWpi1MquVdAyjUar5+76PVCmYl" crossorigin="anonymous"></script>

  <title>Liste des patients</title>
  <style>
    /* Ajout de styles pour la mise en forme de la page */
    body {
      font-family: Arial, sans-serif;
      margin: 0;
      padding: 0;
    }

    /* Styles pour la table */
    table {
      width: 100%;
      border-collapse: collapse;
    }

    th,
    td {
      border: 1px solid #ccc;
      padding: 10px;
    }

    th {
      background-color: #f2f2f2;
    }

    /* Styles pour le bouton de suppression */
    .delete-btn {
      background-color: #f44336;
      color: white;
      padding: 5px 10px;
      border: none;
      cursor: pointer;
    }

    .delete-btn:hover {
      background-color: #ff0000;
    }

    .edit-btn {
      background-color: green;
      color: white;
      padding: 5px 10px;
      border: none;
      cursor: pointer;
    }

    .edit-btn:hover {
      background-color: green;
    }
    .modal {
  display: none; /* Hidden by default */
  position: fixed; /* Stay in place */
  z-index: 1; /* Sit on top */
  left: 0;
  top: 0;
  width: 100%; /* Full width */
  height: 100%; /* Full height */
  overflow: auto; /* Enable scroll if needed */
  background-color: rgba(0,0,0,0.4); /* Black w/ opacity */
}

.modal-content {
  background-color: #fefefe;
  margin: 15% auto; /* 15% from the top and centered */
  padding: 20px;
  border: 1px solid #888;
  width: 80%; /* Could be more or less, depending on screen size */
}

.close-btn {
  color: #aaa;
  float: right;
  font-size: 28px;
  font-weight: bold;
}

.close-btn:hover,
.close-btn:focus {
  color: black;
  text-decoration: none;
  cursor: pointer;
}
  </style>
</head>

<body>
  <table>
    <tr>
      <th>ID</th>
      <th>Nom</th>
      <th>Prénom</th>
      <th>Actions</th>
    </tr>
    <?php
    // Utilisation de cURL pour envoyer une requête GET à l'API
    $curl = curl_init();
    curl_setopt_array($curl, array(
      CURLOPT_URL => "https://hapi.fhir.org/baseDstu3/Patient?_sort=-_lastUpdated",
      CURLOPT_RETURNTRANSFER => true,
    ));
    $response = curl_exec($curl);
    curl_close($curl);

    // Décodage de la réponse JSON
    $patients = json_decode($response);

    // Boucle pour afficher les patients
    foreach ($patients->entry as $patient) {
      echo "<tr class='patient_div'>";
      echo "<td>" . $patient->resource->id . "</td>";
      if (isset($patient->resource->name[0]->family)) {
        $nname = $patient->resource->name[0]->family;
         
        } else {
          $nname ="N/A" ;
          
        }
        echo "<td>" . $nname . "</td>";
        if (isset($patient->resource->name[0]->given[0])) {
          $lname = $patient->resource->name[0]->given[0];
        } else {
          $lname ="N/A" ;
        }
        echo "<td>" . $lname . "</td>"; 


      
      echo "<td>

          <button class='edit-btn'  data-patient-lname='" . $nname . "'  data-patient-name='" . $lname . "'   data-patient-id='" . $patient->resource->id . "' onclick='editPatient(event)'>Modifier</button>
          <button class='delete-btn' data-patient-id='" . $patient->resource->id . "' onclick='deletePatient(event)'>Supprimer</button></td></td>";

      echo "</tr>";
    }
    ?>
  </table>
  <p id="show"></p>

  <div id="editModal" class="modal">
    <div class="modal-content">
      <span class="close-btn">&times;</span>
      <h3>Edit Patient</h3>
      <form>
      <input type="text" id="patientID">
        <label>Name:</label>
        <input type="text" id="patientName">
        <label>First Name:</label>
        <input type="text" id="patientFirstName">
        <button id="editPatientBtn" onclick="updatePatient()">Valider</button>
      </form>
    </div>
  </div>




</body>
<script>
  function deletePatient(event) {
    // Get the patient ID from the button
    var patientId = event.target.dataset.patientId;

    // Send a DELETE request to the API using fetch
    fetch("https://hapi.fhir.org/baseDstu3/Patient/" + patientId, {
        method: 'DELETE'
      })
      .then(response => {
        if (response.ok) {
          // Get the parent <tr> element of the button and remove it
          var parentDiv = event.target.closest(".patient_div");
          parentDiv.remove();
        } else {
          // Handle error
        }
      })
      .catch(error => {
        // Handle error
      });
  }
</script>
<script>
  // Get the modal element
let modal = document.getElementById("editModal");

// Get the "edit" button
let editBtn = document.getElementsByClassName("edit-btn");

// Get the "x" button
let closeBtn = document.getElementsByClassName("close-btn")[0];

// Get the input fields for name and first name
let patientNameInput = document.getElementById("patientName");
let patientFirstNameInput = document.getElementById("patientFirstName");
let patientIDInput= document.getElementById("patientID");

// Loop through all "edit" buttons
for(let i = 0; i < editBtn.length; i++) {
  // Add a click event listener to each button
  editBtn[i].addEventListener("click", function(event) {
    event.preventDefault();
    modal.style.display = "block";

    // Get the patient ID from the button's data attribute
    let patientId = this.getAttribute("data-patient-id");
    let patientName = this.getAttribute("data-patient-lname");
    let patientLName = this.getAttribute("data-patient-name");
    
    // Do something with the patient ID (e.g. make an API call to retrieve the patient's information)
    // ...

    // Set the input fields with the patient's name and first name
    patientNameInput.value = patientName;
    patientFirstNameInput.value = patientLName;
    patientIDInput.value=patientId;

  });
}

// When the user clicks on the "x" button, close the modal
closeBtn.onclick = function() {
  modal.style.display = "none";
}

// When the user clicks anywhere outside of the modal, close it
window.onclick = function(event) {
  if (event.target == modal) {
    modal.style.display = "none";
  }
}

// Update patient function
function updatePatient() {
    // Get the patient's name and first name from the input fields
    let patientName = patientNameInput.value;
    let patientFirstName = patientFirstNameInput.value;
    let patientID = patientIDInput.value;

    // Do something with the patient's name and first name (e.g. make an API call to update the patient's information)
  

var id = patientID;
var newFname = patientName;
var newLname = patientFirstName;

fetch(`https://hapi.fhir.org/baseDstu3/Patient/${id}`, {
  method: 'PUT',
  headers: {
    'Content-Type': 'application/json'
  },
  body: JSON.stringify({
    resourceType: "Patient",
    id: `${id}`,
    name: [{
      family: `${newLname}`,
      given: [`${newFname}`]
    }]
  })
}).then(response => {
    if(response.ok) {
        console.log("Patient updated successfully");
    }
    else {
        console.log("Error updating patient: " + response.status);
    }
}).catch(error => {
    console.log("Error updating patient: " + error);
});


    // ...
    // Close the modal
    modal.style.display = "none";
}

</script>

</html>