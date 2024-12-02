<?php

    include 'functions.php'; 

    $checkStudent = isset($_SESSION['user_name']) ? $_SESSION['user_name'] : 'Student';
    $studentName = $_SESSION['user_name'];

    $checkId = isset($_SESSION['idNumber']) ? $_SESSION['idNumber'] : 'Unknown ID';
    $studentID = $_SESSION['idNumber'];

    $studentInfo = $studentID ? getStudentInfo($studentID) : null;
    $clearanceData = getClearanceStatus($studentID);
    $approvedCount = getApprovedCount($studentID); 
    $comment = getDepartmentComment($studentID);
    $approvedCount *= 20;

    $sex = $studentInfo['Sex'] ?? 'Not Specified';
    $civilStatus = $studentInfo['Civil_Status'] ?? 'Not Specified';
    $dob = $studentInfo['Date_of_Birth'] ?? 'Not Specified';
    $pob = $studentInfo['Place_of_Birth'] ?? 'Not Specified';
    $religion = $studentInfo['Religion'] ?? 'Not Specified';
    $nationality = $studentInfo['Nationality'] ?? 'Not Specified';
    $address = $studentInfo['Address'] ?? 'Not Specified';
    $contactNumber = $studentInfo['Contact_Number'] ?? 'Not Specified';
    $course = $studentInfo['Course'] ?? 'Not Specified';
    $section = $studentInfo['Section'] ?? 'Not Specified';

    $result = [];
    $errors = [];  // Array to hold errors

    $oldPassword = getOldPassword($studentID);
    
    if (isset($_POST['changePasswordButton'])) {
        $oldPassword = $_POST['oldPass'];
        $newPassword = $_POST['newPass'];
        
        // Validate old password (ensure it's correct and not empty)
        $errors = validatePasswordCredentials($studentID, $oldPassword);  // Validate old password
        
        // Check if validation passes
        if (empty($errors)) {
            // Check if the new password is not the same as the old password
            if ($oldPassword === $newPassword) {
                $errors['password'] = "New password cannot be the same as the old password.";
            }
    
            // If no errors, proceed with the password change
            if (empty($errors)) {
                // Proceed to change the password
                $result = changePassword($studentID, $oldPassword, $newPassword);
            }
        }
    }

?>

<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="stylesheet" href="styles/dashboard-student.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-QWTKZyjpPEjISv5WaRU9OFeRpok6YctnYmDr5pNlyT2bRjXh0JMhjY6hW+ALEwIH" crossorigin="anonymous">
    <link href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap" rel="stylesheet">
    <link rel="icon" href="images/logo.png">
    <script src="https://kit.fontawesome.com/a082745512.js" crossorigin="anonymous"></script>
    <title>Student Dashboard</title>
</head>
<body> 
    <header class="navbar sticky-top bg-dark flex-md-nowrap p-0 shadow" data-bs-theme="dark">
        <div class="head">
            <a class="navbar-brand col-md-3 col-lg-2 me-0 px-3 fs-10 text-white " href="#"><?php echo $_SESSION['user_name'] . " - Dashboard"; ?></a>
        </div>
    </header>

    <div class="sidebar">
        <div class="container position-fixed start-0 sidebar-shadow" style="height: 100vh; width:250px;">
            <div class="d-flex pt-5 ps-3 text-center">
                <i class="fa-solid fa-unlock"></i>
                <a href="" class="text-decoration-none ps-2" data-bs-toggle="modal" data-bs-target="#changePassModal">
                    <p  class="text-dark fw-semibold">Change Password</p>
                </a>
            </div>
            <br>
            <hr>
            <div class="ps-3 pt-3">
            <a href="logout.php" class="text-danger">               
                <i class="fa-solid fa-right-from-bracket">Logout</i>               
            </a>
            
        </div>
        </div>
    </div>

    <div class="container pt-3 ps-5 mt-4 custom-shadow position-relative">
        <p class="" style="font-size: 40px;">
        <?php echo $_SESSION['user_name']; ?>
        </p>
        <div class="d-flex justify-content-between custom-info">
        <div class="col-md-3">   
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Student ID:</p>
            <p><?php echo htmlspecialchars($studentID) ?></p>
        </div>
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Sex:</p>
            <p><?php echo htmlspecialchars($sex) ?></p>
        </div>
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Civil Status:</p>
            <p><?php echo htmlspecialchars($civilStatus) ?></p>
        </div>
        </div> 
        <div class="col-md-4">
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Date of Birth:</p>
            <p><?php echo htmlspecialchars($dob) ?></p>
        </div>
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Place of Birth:</p>
            <p><?php echo htmlspecialchars($pob) ?></p>
        </div>
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Religion:</p>
            <p><?php echo htmlspecialchars($religion) ?></p>
        </div>
        </div>
        <div class="col-md-4">
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Nationality:</p>
            <p><?php echo htmlspecialchars($nationality) ?></p>
        </div>
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Permanent Address:</p>
            <p><?php echo htmlspecialchars($address) ?></p>
        </div>
        <div style="display: flex; gap:10px;">
            <p class="fw-bold">Contact No:</p>
            <p><?php echo htmlspecialchars($contactNumber) ?></p>
        </div>
        </div>
        <div class="position-absolute end-0 me-5  top-0 mt-4">
            <img src="images/user-stud.png" alt="" height="200px">
        </div>
        </div>
    </div>
    <div class="container d-flex mt-5">
    <div class="container custom-shadow pt-3 pb-5 ps-4" style="width: 30%;">
            <p class=" fw-semibold fs-5 text-center">
                Progress
            </p>
            <div class="skill ps-5">
                <div class="outer ps-3">
                    <div class="inner ps-3">
                        <div id="number">
                            
                        </div>
                    </div>
                </div>
                <svg xmlns="http://www.w3.org/2000/svg" version="1.1" width="160px" height="160px">
                    <defs>
                        <linearGradient id="GradientColor">
                        <stop offset="0%" stop-color="#e91e63" />
                        <stop offset="100%" stop-color="#673ab7" />
                        </linearGradient>
                    </defs>
                    <circle cx="80" cy="80" r="70" stroke-linecap="round" id="progress-circle"/>
                </svg>
            </div>
        </div>
        <div class="container">
            <table class="table table-striped custom-shadow">
                <thead>
                    <tr class="table-dark">
                        <th>Dept ID</th>
                        <th>Dept Name</th>
                        <th>Status</th>
                        <th>Comment</th>
                    </tr>
                </thead>
                <tbody>
    <?php foreach ($clearanceData as $data): ?>
        <tr>
            <th>2009998887</th>
            <th>Library</th>
            <th class="<?php echo ($data['Library'] == 0 ? 'text-danger' : 'text-success'); ?>">
                <?php echo ($data['Library'] == 0 ? 'Decline' : 'Approve'); ?>
            </th>
            <th>
                <?php if ($data['Library'] == 0): ?>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">View Comment</button>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <th>100045768</th>
            <th>OSA</th>
            <th class="<?php echo ($data['OSA'] == 0 ? 'text-danger' : 'text-success'); ?>">
                <?php echo ($data['OSA'] == 0 ? 'Decline' : 'Approve'); ?>
            </th>
            <th>
                <?php if ($data['OSA'] == 0): ?>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">View Comment</button>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <th>122345342</th>
            <th>Cashier</th>
            <th class="<?php echo ($data['Cashier'] == 0 ? 'text-danger' : 'text-success'); ?>">
                <?php echo ($data['Cashier'] == 0 ? 'Decline' : 'Approve'); ?>
            </th>
            <th>
                <?php if ($data['Cashier'] == 0): ?>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">View Comment</button>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <th>200987836</th>
            <th>Student Council</th>
            <th class="<?php echo ($data['Student Council'] == 0 ? 'text-danger' : 'text-success'); ?>">
                <?php echo ($data['Student Council'] == 0 ? 'Decline' : 'Approve'); ?>
            </th>
            <th>
                <?php if ($data['Student Council'] == 0): ?>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">View Comment</button>
                <?php endif; ?>
            </th>
        </tr>
        <tr>
            <th>300908645</th>
            <th>Dean</th>
            <th class="<?php echo ($data['Dean'] == 0 ? 'text-danger' : 'text-success'); ?>">
                <?php echo ($data['Dean'] == 0 ? 'Decline' : 'Approve'); ?>
            </th>
            <th>
                <?php if ($data['Dean'] == 0): ?>
                    <button class="btn btn-danger" data-bs-toggle="modal" data-bs-target="#exampleModal">View Comment</button>
                <?php endif; ?>
            </th>
        </tr>
    <?php endforeach; ?>
</tbody>

            </table>
        </div>
    </div>

    <div class="modal" tabindex="-1" id="exampleModal">
        <div class="modal-dialog">
            <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Comment</h5>
                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
            </div>
            <div class="modal-body">
                <p><?php echo $comment ?></p>
            </div>
            <div class="modal-footer">
                <button type="button" class="btn btn-secondary" data-bs-dismiss="modal">Close</button>
            </div>
            </div>
        </div>
    </div>

    <!-- Modal Form to Change Password -->
<form method="post">
    <div class="modal" tabindex="-1" id="changePassModal">
        <div class="modal-dialog">
            <div class="modal-content">
                <div class="modal-header">
                    <h5 class="modal-title">Change Password</h5>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body">
                    <?php if (!empty($result)): ?>
                        <div class="alert <?php echo $result['status'] === 'success' ? 'alert-success' : 'alert-danger'; ?>" role="alert">
                            <?php echo htmlspecialchars($result['message']); ?>
                        </div>
                    <?php endif; ?>
                    <?php if (!empty($errors)): ?>
                        <?php echo displayErrors($errors); ?>
                    <?php else: ?>
                        <div>
                            <label for="password" class="form-label">Old Password</label>
                            <input id="oldPassword" type="password" name="oldPass" class="form-control" required>
                            <i class="fa-solid fa-eye position-absolute" style="right: 24px; top: 60px; cursor:pointer" id="toggleOldPassword"></i>
                        </div>
                        <div class="pt-4">
                            <label for="newpassword" class="form-label">New Password</label>
                            <input id="newPassword" type="password" name="newPass" class="form-control" required>
                            <i class="fa-solid fa-eye position-absolute" style="right: 24px; top: 155px; cursor:pointer" id="toggleNewPassword"></i>
                        </div>
                        <div class="pt-4">
                            <label for="conNewPassword" class="form-label">Confirm New Password</label>
                            <input id="conNewPassword" type="password" name="confirmPass" class="form-control" required>
                            <i class="fa-solid fa-eye position-absolute" style="right: 24px; top: 250px; cursor:pointer" id="toggleConNewPassword"></i>
                        </div>
                    <?php endif; ?>
                </div>
                <div class="modal-footer">
                    <button type="button" class="btn btn-danger" data-bs-dismiss="modal">Cancel</button>
                    <button type="submit" class="btn btn-success" name="changePasswordButton">Save</button>
                </div>
            </div>
        </div>
    </div>
</form>

<script>
    // Ensure the modal is shown if errors exist
    <?php if (!empty($errors) || !empty($result)): ?>
        // Trigger the modal to be shown if errors or results are set
        var myModal = new bootstrap.Modal(document.getElementById('changePassModal'));
        myModal.show();
    <?php endif; ?>
</script>

    <script>
       let number = document.getElementById("number");
        let progressCircle = document.getElementById("progress-circle");

        let counter = 0; // Start from 0 or any initial value
        let targetValue = <?php echo $approvedCount ?>; // Desired end value
        let maxCounter = 100; // Maximum percentage (100%)
        let maxDashOffset = 472; // Full circle dasharray value (circumference of the circle)

        // Set the initial state of the progress circle
        number.innerHTML = counter + "%";
        progressCircle.style.strokeDashoffset = maxDashOffset; // Start from 100% hidden

        // Animate the circle and counter to the target value
        let interval = setInterval(() => {
            if (counter >= targetValue) { // Stop the animation at the target value
                clearInterval(interval);
            } else {
                counter += 1; // Increment the counter
                number.innerHTML = counter + "%"; // Update the percentage text

                // Gradually reduce the stroke-dashoffset
                let dashOffset = maxDashOffset - (maxDashOffset * counter) / maxCounter;
                progressCircle.style.strokeDashoffset = dashOffset; // Apply the new offset
            }
        }, 30); // Adjust the speed of animation


        // Add toggle functionality for each field
            document.getElementById('toggleOldPassword').addEventListener('click', () => togglePassword('oldPassword', 'toggleOldPassword'));
            document.getElementById('toggleNewPassword').addEventListener('click', () => togglePassword('newPassword', 'toggleNewPassword'));
            document.getElementById('toggleConNewPassword').addEventListener('click', () => togglePassword('conNewPassword', 'toggleConNewPassword'));

            // Reusable function for toggling password visibility
            function togglePassword(inputId, iconId) {
                const passwordInput = document.getElementById(inputId);
                const toggleIcon = document.getElementById(iconId);

                // Toggle input type
                const isPassword = passwordInput.type === 'password';
                passwordInput.type = isPassword ? 'text' : 'password';

                // Toggle icon class
                toggleIcon.classList.toggle('fa-eye');
                toggleIcon.classList.toggle('fa-eye-slash');
            }

    </script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.3/dist/js/bootstrap.bundle.min.js" integrity="sha384-YvpcrYf0tY3lHB60NNkmXc5s9fDVZLESaAA55NDzOxhy9GkcIdslK1eN7N6jIeHz" crossorigin="anonymous"></script>
</body>
</html>