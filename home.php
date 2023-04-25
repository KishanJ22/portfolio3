<?php
require_once("connectdb.php");
session_start();
?>
<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <!-- title for the page -->
    <title>AProject</title>
    <!-- Bootstrap CSS Framework -->
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Custom CSS code -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
    <link rel="stylesheet" href="style.css">
</head>
<body>
<header id="main-header">
    <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
        <div class="container-fluid">
            <a href="home.php" class="navbar-brand">
                AProject
            </a>
            <span class="navbar-text">
                <?php echo "Logged in as: " . $_SESSION['username']?>
            </span>
            <ul class="nav nav-pills gap-2">
                <li class="nav-item">
                    <a href="home.php" class="btn btn-outline-light" aria-current="page">Home</a>
                </li>
                <form action="home.php" method="post">
                <button class="btn btn-outline-light" id="logoutButton" name="logoutButton" type="submit">Logout</button>
                </form>
            </ul>
        </div>
    </nav>
</header>
<div class="container">
        <h1 class="projectsHeading">Your Projects</h1>
        <table class="table table-striped">
            <thead>
                <tr>
                    <th>Title</th>
                    <th>Start Date</th>
                    <th>Description</th>
                    <th></th>
                </tr>
            </thead>
            <tbody>
                <?php
                $projects_sql = "SELECT projects.pid, projects.title, projects.start_date, projects.end_date, projects.phase, projects.description, users.username, users.email
                                FROM projects
                                INNER JOIN users ON projects.uid = users.uid";
                try {
                    $result = mysqli_query($connection, $projects_sql);
                    while ($row = $result->fetch_assoc()) {
                        echo "<tr>
                    <td>" . $row["title"] . "</td>
                    <td>" . $row["start_date"] . "</td>
                    <td>" . $row["description"] . "</td>
                    <td>
                        <button type='button' class='btn btn-primary' data-bs-toggle='modal' data-bs-target='#projectInfo'>More Information</button>
                    </td>
                    </tr>";
                    ?>
                    <div class="modal fade" tabindex="-1" id="projectInfo" aria-labelledby="projectModal" aria-hidden="true">
                    <div class="modal-dialog">
                        <div class="modal-content rounded-4 shadow">
                            <div class="modal-header p-5 pb-4 border-bottom-0">
                                <h1 class="fw-bold mb-0 fs-2">Project Information</h1>
                                <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                            </div>
                            <div class="modal-body p-5 pt-0">
                                <?php
                                    echo "<ul class='list-unstyled'>
                                    <li>" . "Project Title: " . $row["title"] . "</li>
                                    <li>" . "Project Description: " . $row["description"] . "</li>
                                    <li>" . "Start Date: " . $row["start_date"] . "</li>
                                    <li>" . "End Date: " . $row["end_date"] . "</li>
                                    <li>" . "Phase: " . $row["phase"] . "</li>
                                    <li>" . "Username: " . $row["username"] . "</li>
                                    <li>" . "User Email: " . $row["email"] . "</li>
                                    </ul>
                                    ";
                                ?>
                                <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" data-bs-dismiss="modal" type="button">Close</button>
                            </div>
                        </div>
                    </div>
                </div>
                <?php
                    }
                } catch (mysqli_sql_exception) {
                    echo "<script>alert('Unable to load projects. Please try again later.')</script>";
                }
                ?>
            </tbody>
        </table>
    </div>

</body>
</html>
<?php
    if(isset($_POST["logoutButton"])) {
        session_destroy();
        header("Location: index.php");
    }
?>