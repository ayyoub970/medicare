<!DOCTYPE html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>MediCare | Patients</title>
  <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/5.15.1/css/all.min.css">
  <link rel="stylesheet" href="https://fonts.googleapis.com/css?family=Source+Sans+Pro:300,400,400i,700&display=fallback">

  <link rel="stylesheet" href="./folder/css/all.min.css">

  <link rel="stylesheet" href="./folder/css/dataTables.bootstrap4.min.css">
  <link rel="stylesheet" href="./folder/css/responsive.bootstrap4.min.css">
  <link rel="stylesheet" href="./folder/css/buttons.bootstrap4.min.css">

  <link rel="stylesheet" href="./folder/css/adminlte.min.css">

<body class="hold-transition sidebar-mini">
  <div class="wrapper">




    <aside class="main-sidebar sidebar-dark-primary elevation-4">

      <a href="../../index3.html" class="brand-link">
        <img src="./folder/logo.png" alt="AdminLTE Logo" class="brand-image img-circle elevation-3" style="opacity: .8">
        <span class="brand-text font-weight-light">MediCare</span>
      </a>

      <div class="sidebar">




        <nav class="mt-2">
          <ul class="nav nav-pills nav-sidebar flex-column" data-widget="treeview" role="menu" data-accordion="false">


            <li class="nav-item">
              <a class="">

                <button class='nav-link' data-toggle='modal' data-target='#addPatient'>
                  <i class="nav-icon fas fa-plus"></i>
                  Ajouter un patient
                </button>
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="">

                <button class='nav-link' data-toggle='modal' data-target='#findPatient'>
                  <i class="nav-icon fas fa-search"></i>
                  Trouver un patient
                </button>
              </a>
            </li>
            <li class="nav-item">
              <a href="" class="">

                <button class='nav-link' data-toggle='modal' data-target='#'>
                  <i class="nav-icon fas fa-list"></i>
                  Liste des patients
                </button>
              </a>
            </li>




          </ul>
        </nav>

      </div>

    </aside>



    <div class="content-wrapper">
      <section class="content-header">
        <div class="container-fluid">



        </div>
      </section>
      <section class="content">
        <div class="card card-primary">

          <div class="card-header" style="padding: 0.15rem 0.55rem;">
            <h3 class="card-title"></h3>
          </div>
          <div class="card-body table-responsive p-1">
            <table id="example1" class="table table-bordered table-striped dataTable dtr-inline">
              <thead>
                <tr>
                  <td>ID</td>
                  <td>Prénom</td>
                  <td>Nom</td>

                  <td width="20%">Action </td>
                </tr>
              </thead>
              <tbody>

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

                foreach ($patients->entry as $patient) {
                  echo "<tr class='patient_div'>";
                  echo "<td>" . $patient->resource->id . "</td>";
                  if (isset($patient->resource->name[0]->family)) {
                    $nname = $patient->resource->name[0]->family;
                  } else {
                    $nname = "N/A";
                  }
                  echo "<td>" . $nname . "</td>";
                  if (isset($patient->resource->name[0]->given[0])) {
                    $lname = $patient->resource->name[0]->given[0];
                  } else {
                    $lname = "N/A";
                  }
                  echo "<td>" . $lname . "</td>";



                  echo "<td>

                  <button class='edit-btn' data-toggle='modal' data-target='#editModal'     data-patient-id='" . $patient->resource->id . "' onclick='Details(event)'>Details</button>
       
                  <button class='edit-btn' data-toggle='modal' data-target='#editModal'  data-patient-lname='" . $nname . "'  data-patient-name='" . $lname . "'   data-patient-id='" . $patient->resource->id . "' onclick='editPatient(event)'>Modifier</button>
          <button class='delete-btn' data-patient-id='" . $patient->resource->id . "' onclick='deletePatient(event)'>Supprimer</button></td></td>";
                  echo "</tr>";
                }
                ?>


              </tbody>
            </table>



            <div class="modal" id="editModal">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Modifier un Patient</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col-sm-6">
                          <input type="hidden" id="patientID">
                          <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" id="patientFirstName" class="form-control" placeholder="">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Nom</label>
                            <input type="text" id="patientName" class="form-control" placeholder="">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button type="submit" id="editPatientBtn" onclick="updatePatient()" class="btn btn-primary">Save changes</button>
                      </div>
                    </form>
                  </div>

                </div>
              </div>
            </div>
            <div class="modal" id="addPatient">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Ajouter un nouveau Patient</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form method="post" action="submit.php">
                      <div class="row">
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Prénom</label>
                            <input type="text" name="newpatientFirstName" class="form-control" placeholder="">
                          </div>
                        </div>
                        <div class="col-sm-6">
                          <div class="form-group">
                            <label>Nom</label>
                            <input type="text" name="newpatientName" class="form-control" placeholder="">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <input type="submit" name="submit" class="btn btn-primary" value="Créer">
                      </div>
                    </form>
                  </div>

                </div>
              </div>
            </div>
            <div class="modal" id="findPatient">
              <div class="modal-dialog">
                <div class="modal-content">
                  <div class="modal-header">
                    <h4 class="modal-title">Trouver un Patient</h4>
                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                      <span aria-hidden="true">&times;</span>
                    </button>
                  </div>
                  <div class="modal-body">
                    <form>
                      <div class="row">
                        <div class="col-sm-12">
                          <div class="form-group">
                            <label>ID du Patient</label>
                            <input type="text" id="idPatientsearch" name="idPatientsearch" class="form-control" placeholder="">
                          </div>
                        </div>
                      </div>
                      <div class="modal-footer justify-content-between">
                        <button type="button" class="btn btn-default" data-dismiss="modal">Close</button>
                        <button onclick="getPatient(event)" class="btn btn-primary">chercher</button>
                      </div>
                    </form>

                  </div>

                </div>
              </div>
            </div>


            <div id="modalshow" style="display: none;">
              <div id="modal-content"></div>
            </div>

          </div>

        </div>

      </section>

    </div>





    <footer class="main-footer">
      <div class="float-right d-none d-sm-block">
        <b>Version</b> 3.2.0
      </div>
      <strong>Created By &copy; Ayyoub MAACHE<a href=""></a>.</strong> All rights reserved.
    </footer>

    <aside class="control-sidebar control-sidebar-dark">

    </aside>

  </div>


  <script src="./folder/js/jquery.min.js"></script>

  <script src="./folder/js/bootstrap.bundle.min.js"></script>

  <script src="./folder/js/jquery.dataTables.min.js"></script>
  <script src="./folder/js/dataTables.bootstrap4.min.js"></script>
  <script src="./folder/js/dataTables.responsive.min.js"></script>
  <script src="./folder/js/responsive.bootstrap4.min.js"></script>
  <script src="./folder/js/dataTables.buttons.min.js"></script>
  <script src="./folder/js/buttons.bootstrap4.min.js"></script>
  <script src="./folder/js/jszip.min.js"></script>
  <script src="./folder/js/pdfmake.min.js"></script>
  <script src="./folder/js/vfs_fonts.js"></script>
  <script src="./folder/js/buttons.html5.min.js"></script>
  <script src="./folder/js/buttons.print.min.js"></script>
  <script src="./folder/js/buttons.colVis.min.js"></script>

  <script src="./folder/js/adminlte.min.js?v=3.2.0"></script>



  <script>
    function getPatient(event) {
        event.preventDefault();
        var patientId = document.getElementById("idPatientsearch").value;
        fetch("https://hapi.fhir.org/baseDstu3/Patient/" + patientId)
            .then(response => response.json())
            .then(patient => {
                var patientName = patient.name[0].given[0] + " " + patient.name[0].family;
                var patientGender = patient.gender;
                var patientBirthDate = patient.birthDate;

                // Créer un nouvel URL avec les données du patient
                var patientUrl = "patient.html?name=" + patientName + "&gender=" + patientGender + "&birthdate=" + patientBirthDate;

                // Ouvrir la nouvelle page avec les données du patient
                window.open(patientUrl, "_blank");
            })
            .catch(error => console.error(error));
    }
</script>


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
    let patientIDInput = document.getElementById("patientID");

    // Loop through all "edit" buttons
    for (let i = 0; i < editBtn.length; i++) {
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
        patientIDInput.value = patientId;

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
        if (response.ok) {
          console.log("Patient updated successfully");
        } else {
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












  <script>
    $(function() {
      $("#example1").DataTable({
        "responsive": true,
        "lengthChange": false,
        "autoWidth": false,
        "buttons": ["copy", "csv", "excel", "pdf", "print"]
      }).buttons().container().appendTo('#example1_wrapper .col-md-6:eq(0)');
      $('#example2').DataTable({
        "paging": true,
        "lengthChange": false,
        "searching": false,
        "ordering": true,
        "info": true,
        "autoWidth": false,
        "responsive": true,
      });
    });
  </script>
</body>

</html>