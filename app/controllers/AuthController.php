<?php
require_once "app/models/UserModel.php";
require_once "app/models/TokenModel.php";
session_start();

class AuthController
{
    private $userModel;
    private $tokenModel;

    public function __construct()
    {
        $this->userModel = new UserModel();
        $this->tokenModel = new TokenModel();
    }


    public function register()
    {
        $errors = [];
        $oldInput = [];
        if (isset($_POST["username"])) {
            $oldInput = $_POST;
            $username = $_POST["username"];
            $email = $_POST["email"];

            if ($this->userModel->checkUsernameExists($username)) {
                $errors["message"] = "** Username already exists, please type again! **";
            }

            if ($this->userModel->checkEmailExists($email)) {
                $errors["message"] = "** Email already exists, please type again! **";
            }

            if (empty($errors)) {
                if ($this->userModel->register($_POST)) {
                    $_SESSION['success_message'] = "Registration successfully!";
                    header("Location: /webprogramming_assignment_242/login");
                    exit();
                } else {
                    $errors["message"] = "** Registration failed, please try again! **";
                }
            }
        }
        require_once "app/views/user/register/register.php";
    }

    public function login()
    {
        if ($this->userModel->checkUsernameExists("admin") == false) {
            $admin = [];
            $admin["username"] = "admin";
            $admin["password"] = "123";
            $admin["email"] = "admin@gmail.com";
            $admin["role"] = "admin";
            $this->userModel->register($admin);
        }
        $errors = [];
        $oldInput = [];
        if (isset($_POST["usernameEmail"])) {
            $oldInput = $_POST;
            $usernameEmail = $_POST["usernameEmail"];
            $checkUsernameExists = $this->userModel->checkUsernameExists($usernameEmail);
            $checkEmailExists = $this->userModel->checkEmailExists($usernameEmail);
            $user = $checkUsernameExists ? $checkUsernameExists : ($checkEmailExists ? $checkEmailExists : false);

            if (!$user) {
                $errors["message"] = "** Username or email doesn't exists, please type again! **";
            }

            if (empty($errors)) {
                $userID = $user["UserID"];

                // Kiểm tra nếu đã quá 5 lần sai và chưa hết 10 phút thì chặn đăng nhập
                if ($user["loginAttempts"] >= 5) {
                    date_default_timezone_set("Asia/Ho_Chi_Minh");
                    $timePassed = time() - strtotime($user["lastAttemptTime"]);
                    if ($timePassed < 600) {
                        $errors["message"] = "** You've entered the wrong password too many times, try again after 10 minutes since " . $user["lastAttemptTime"] . " **";
                    } else {
                        $this->userModel->updateLoginAttempts(true, $userID);
                    }
                } else {
                    if ($this->userModel->login($_POST)) {
                        $_SESSION["success_message"] = "Login successfully!";
                        $_SESSION["mySession"] = $usernameEmail;
                        $_SESSION["userid"] = $user["UserID"];
                        //cập nhật lại login attempt là 0
                        $this->userModel->updateLoginAttempts(true, $userID);


                        if (isset($_POST["remember"])) {
                            $token = bin2hex(random_bytes(32));
                            $expiresAt = date('Y-m-d H:i:s', strtotime('+7 days'));
                            setcookie("usernameEmail", $token, time() + (86400 * 7), "/");
                            $this->tokenModel->add($userID, $token, $expiresAt);
                        }

                        if ($usernameEmail == "admin" || $usernameEmail == "admin@gmail.com") {
                            header("Location: /webprogramming_assignment_242/admin");
                        } else {
                            header("Location: /webprogramming_assignment_242");
                        }
                        exit();
                    } else {
                        date_default_timezone_set("Asia/Ho_Chi_Minh");

                        $errors["message"] = $user["loginAttempts"] == 4 ? "** You've entered the wrong password too many times, try again after 10 minutes since " . date("Y-m-d H:i:s", time()) . " **" :  "** Password is not correct, please type again! **";

                        //cập nhật login attempt và last attempt time trong db
                        $this->userModel->updateLoginAttempts(false, $userID);
                    }
                }
            }
        }
        require_once "app/views/user/login/login.php";
    }

    public function logout()
    {
        if (isset($_SESSION["mySession"])) {
            session_unset();
            session_destroy();

            $token = $_COOKIE["usernameEmail"];
            if (isset($token)) {
                setcookie("usernameEmail", "", time() - 3600, "/");
                $this->tokenModel->delete($token);
            }
        }

        header("location: /webprogramming_assignment_242/");
    }
}