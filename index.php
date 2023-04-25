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
    <link rel="stylesheet" href="style.css">
    <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/css/bootstrap.min.css" rel="stylesheet">
    <!-- Bootstrap JS Framework -->
    <script src="https://cdn.jsdelivr.net/npm/@popperjs/core@2.11.6/dist/umd/popper.min.js" integrity="sha384-oBqDVmMz9ATKxIep9tiCxS/Z9fNfEXiDAYTujMAeBAsjFuCZSmKbSSUnQlmh/jp3" crossorigin="anonymous"></script>
    <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.0-alpha1/dist/js/bootstrap.min.js" integrity="sha384-mQ93GR66B00ZXjt0YO5KlohRA5SY2XofN4zfuZxLkoj1gXtW8ANNCe9d5Y3eG5eD" crossorigin="anonymous"></script>
</head>
<body>
    <header id="main-header">
        <nav class="navbar navbar-expand-lg bg-dark navbar-dark">
            <div class="container-fluid">
                <a href="index.php" class="navbar-brand">
                    AProject
                </a>
                <ul class="nav nav-pills gap-2">
                    <li class="nav-item">
                        <a href="index.php" class="btn btn-outline-light" aria-current="page">Home</a>
                    </li>
                    <button class="btn btn-outline-light" id="RegisterButton" data-bs-toggle="modal" data-bs-target="#modalSignup" type="button">Register</button>
                    <button class="btn btn-outline-light" id="loginButton" data-bs-toggle="modal" data-bs-target="#modalLogin" type="button">Login</button>
                </ul>
            </div>

        </nav>
    </header>
    <div class="modal fade" tabindex="-1" id="modalSignup" aria-labelledby="signupModal" aria-hidden="true">
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["signup"])) {
                $signupEmail = filter_input(INPUT_POST, "signupEmail", FILTER_SANITIZE_EMAIL);
                $signupEmail = filter_input(INPUT_POST, "signupEmail", FILTER_VALIDATE_EMAIL);
                $signupUsername = filter_input(INPUT_POST, "signupUsername", FILTER_SANITIZE_SPECIAL_CHARS);
                $signupPassword = $_POST["signupPassword"];
            
            
                if(empty($signupEmail)) {
                    echo "<script>alert('Please enter an email address');</script>";
                }
                else if(empty($signupUsername)) {
                    echo "<script>alert('Please enter a username');</script>";
                }
                else if(empty($signupPassword)) {
                    echo "<script>alert('Please enter a password');</script>";
                }
                else {
                    $passwordHash = password_hash($signupPassword, PASSWORD_DEFAULT);
                    $signup_sql = "INSERT INTO users (username, password, email)
                    VALUES ('$signupUsername', '$passwordHash', '$signupEmail')";
                    try {
                        mysqli_query($connection, $signup_sql);
                    } catch(mysqli_sql_exception) {
                        echo "<script>alert('Could not register user. Please try again');</script>";
                    }
                }
            }
        ?>
        <div class="modal-dialog">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2">Sign up</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 pt-0">
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                        <div class="form-floating mb-3">
                            <input type="email" class="form-control rounded-3" id="signupEmail" name="signupEmail" placeholder="name@example.com" required>
                            <label for="signupEmail">Email address</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="signupUsername" name="signupUsername" placeholder="Username"required>
                            <label for="signupUsername">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="signupPassword" name="signupPassword" placeholder="Password" required>
                            <label for="signupPassword">Password</label>
                        </div>
                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" name="signup" type="submit">Sign Up</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="modal fade" tabindex="-1" id="modalLogin" aria-labelledby="loginModal" aria-hidden="true">
        <?php
            if($_SERVER["REQUEST_METHOD"] == "POST" && isset($_POST["login"])) {
                $loginUsername = filter_input(INPUT_POST, "loginUsername", FILTER_SANITIZE_SPECIAL_CHARS);
                $loginPassword = $_POST["loginPassword"];
            
                $login_sql = "SELECT * FROM users WHERE username = '$loginUsername'";
                $result = mysqli_query($connection, $login_sql);
                    try {
                    if(mysqli_num_rows($result) > 0) {
                        $row = mysqli_fetch_assoc($result);
                        if(password_verify($loginPassword, $row["password"])) {
                            $_SESSION["username"] = $loginUsername;
                            $_SESSION["password"] = $loginPassword;
                            header("Location: home.php");
                        } else {
                            echo "<script>alert('The password is incorrect. Please try again');</script>";
                        }
                    } else {
                        echo "<script>alert('No user found. Please try again');</script>";
                    }
                } catch(mysqli_sql_exception) {
                    echo "<script>alert('Error finding user in database');</script>";
                }
            }
        ?>
        <div class="modal-dialog">
            <div class="modal-content rounded-4 shadow">
                <div class="modal-header p-5 pb-4 border-bottom-0">
                    <h1 class="fw-bold mb-0 fs-2">Login</h1>
                    <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                </div>
                <div class="modal-body p-5 pt-0">
                    <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
                        <div class="form-floating mb-3">
                            <input type="text" class="form-control rounded-3" id="loginUsername" name="loginUsername" placeholder="Username" required>
                            <label for="loginUsername">Username</label>
                        </div>
                        <div class="form-floating mb-3">
                            <input type="password" class="form-control rounded-3" id="loginPassword" name="loginPassword" placeholder="Password" required>
                            <label for="loginPassword">Password</label>
                        </div>
                        <button class="w-100 mb-2 btn btn-lg rounded-3 btn-primary" name="login" type="submit">Login</button>
                    </form>
                </div>
            </div>
        </div>
    </div>

    <div class="container">
        <form action="<?php htmlspecialchars($_SERVER["PHP_SELF"]) ?>" method="post">
            <div class="input-group mb-3 pt-3">
                <input type="text" class="form-control" name="searchField" placeholder="Search Projects" aria-label="Search Projects" aria-describedby="searchButton">
                <button class="btn btn-outline-secondary" type="submit" id="searchButton" name="searchButton">Search</button>
            </div>
        </form>
        <h1 class="projectsHeading">Projects</h1>
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