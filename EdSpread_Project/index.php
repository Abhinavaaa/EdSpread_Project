<?php
  // Connect to the database
  $conn = mysqli_connect("localhost", "root", "", "notes");
  
  // Set the page title
  $page_title = "Notes";

  $degree_id = "";
  $department_id= "";
  
  // Check if the form has been submitted
  if (isset($_POST['degree'])) {
    // Retrieve the selected degree, department, semester, syllabus, and subject from the form
    $degree_id = mysqli_real_escape_string($conn, $_POST['degree']);
    $department_id = (isset($_POST['department'])) ? mysqli_real_escape_string($conn, $_POST['department']) : "";
    $semester_id = (isset($_POST['semester'])) ? mysqli_real_escape_string($conn, $_POST['semester']) : "";
    $syllabus_id = (isset($_POST['syllabus'])) ? mysqli_real_escape_string($conn, $_POST['syllabus']) : "";
    $subject_id = (isset($_POST['subject'])) ? mysqli_real_escape_string($conn, $_POST['subject']) : "";
    
    // Retrieve the list of notes for the selected subject from the database
    if (!empty($subject_id)) {
      $result = mysqli_query($conn, "SELECT note_id, note_text FROM Notes WHERE subject_id = '$subject_id' ORDER BY note_id ASC");
    }
  }
  
?>

<!DOCTYPE html>
<html>
<head>
  <meta charset="utf-8">
  <title><?php echo $page_title; ?></title>
  <style>
    /* Add your CSS styles here */
    <style>
	title {
		font-family: Trebuchet MS;
      	text-align: center;
}
	.parallax {

  		background-image: url("Images/Notes.jpg");

  	
  		min-height: 500px;

  	
  		background-attachment: fixed;
  		background-position: center;
  		background-repeat: no-repeat;
  		background-size: cover;
}
	label {
		font-family: Trebuchet MS; 
}
	.header {
   		background: #385529;
		padding: 15px;
    		border-top: 5px solid #a16b15;
		border-bottom: 5px solid #a16b15;
	}
	.border {
		border-bottom: 5px solid #a16b15;
		padding-top: 5px;
}
  </style>
  </style>
</head>
<body>
<header class="header-image">
    <div class="header"></div>
    <img src="Images/CbitLogo.png" style="width: 20%; height: 20%;">
    <div class="border"></div>
</header>
  <div class="parallax"></div>
  <h1 class="title"><?php echo $page_title; ?></h1>
  
  <!-- Form for selecting a degree, department, semester, syllabus, and subject -->
  
<?php
  // Connect to the database
  $db = new PDO('mysql:host=localhost;dbname=notes', 'root', '');

  // Initialize variables for storing the selected values
  $degreeId = 0;
  $departmentId = 0;
  $semesterId = 0;
  $syllabusId = 0;
  $subjectId = 0;

  // Check if the form has been submitted
  if (isset($_POST['submit'])) {
    // Retrieve the selected values from the form
    $degreeId = intval($_POST['degree_select']);
    $departmentId = intval($_POST['department_select']);
    $semesterId = intval($_POST['semester_select']);
    $syllabusId = intval($_POST['syllabus_select']);
    $subjectId = intval($_POST['subject_select']);
  }

  // Generate the HTML for the form
  echo '<form method="post">';

  // Generate the degree select menu
  echo '<select name="degree_select" id="degree_select" onchange="this.form.submit()">';
  echo '<option value="">--- Select ---</option>';
  $query = "SELECT * FROM Degrees";
  $stmt = $db->prepare($query);
  $stmt->execute();
  while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
     echo '<option value="'.$row['degree_id'].'"';
     if ($row['degree_id'] == $degreeId) {
       echo ' selected';
     }
     echo '>'.$row['degree_name'].'</option>';
  }
  echo '</select>';

  // Generate the department select menu if a degree has been selected
  if ($degreeId > 0) {
    echo '<select name="department_select" id="department_select" onchange="this.form.submit()">';
    echo '<option value="">--- Select ---</option>';
    $query = "SELECT * FROM Departments WHERE degree_id = $degreeId";
    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.$row['department_id'].'"';
       if ($row['department_id'] == $departmentId) {
         echo ' selected';
       }
       echo '>'.$row['department_name'].'</option>';
    }
    echo '</select>';
  }

  // Generate the semester select menu if a department has been selected
  if ($departmentId > 0) {
    echo '<select name="semester_select" id="semester_select" onchange="this.form.submit()">';
    echo '<option value="">--- Select ---</option>';
    $query = "SELECT * FROM Semesters WHERE department_id = $departmentId";
    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.$row['semester_id'].'"';
       if ($row['semester_id'] == $semesterId) {
         echo ' selected';
       }
       echo '>'.$row['semester_name'].'</option>';
    }
    echo '</select>';
  }

  // Generate the syllabus select menu if a semester has been selected
  if ($semesterId > 0) {
    echo '<select name="syllabus_select" id="syllabus_select" onchange="this.form.submit()">';
    echo '<option value="">--- Select ---</option>';
    $query = "SELECT * FROM Syllabuses WHERE semester_id = $semesterId";
    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.$row['syllabus_id'].'"';
       if ($row['syllabus_id'] == $syllabusId) {
         echo ' selected';
       }
       echo '>'.$row['syllabus_name'].'</option>';
       echo "syllabusid=$syllabusId";
    }
    echo '</select>';
  }

  // Generate the subject select menu if a syllabus has been selected
  if ($syllabusId > 0) {
    echo '<select name="subject_select" id="subject_select" onchange="this.form.submit()">';
    echo '<option value="">--- Select ---</option>';
    $query = "SELECT * FROM Subjects WHERE syllabus_id = $syllabusId";
    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
       echo '<option value="'.$row['subject_id'].'"';
       if ($row['subject_id'] == $subjectId) {
         echo ' selected';
       }
       echo '>'.$row['subject_name'].'</option>';
    }
    echo '</select>';
  }

  echo '<input type="submit" name="submit" value="Go">';
  echo '</form>';

  // If a subject has been selected, display the download links for the notes
  if ($subjectId > 0) {
    echo '<div id="note_links">';
    $query = "SELECT * FROM Notes WHERE subject_id = $subjectId";
    $stmt = $db->prepare($query);
    $stmt->execute();
    while ($row = $stmt->fetch(PDO::FETCH_ASSOC)) {
      echo '<a href="' . $row['notes_ref'] . '">View</a><br>';
    }
    echo '</div>';
  }
  
?>





</body>
</html>

