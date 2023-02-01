<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <title>CodePen - Passport Login</title>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <link rel='stylesheet' href='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.98.0/css/materialize.min.css'>
    <link rel='stylesheet' href='https://fonts.googleapis.com/icon?family=Material+Icons'>
    <link rel="stylesheet" href="/assets/auth/style.css">

</head>

<body>
    <!-- partial:index.partial.html -->
    <div class="login-background">
        <!-- Loader Bar -->
        <div class="progress" id="loader" style="position: absolute; display:none;">
            <div class="indeterminate"></div>
        </div>
        <div class="login-col">
            <div class="login-container">
                <img src="/assets/img/logo.png" class="login-logo login-transitions" id="logo"
                    style="background-color:#212121">

                <div id="login-inputs" class="white-text login-inputs  login-transitions">
                    <div class="input-field col s1" id="back-button" style="display: none;">
                        <a href="#" class="waves-effect waves-circle waves-light"><i
                                class="material-icons white-text ">arrow_back</i></a>
                    </div>
                    <div class="input-field col s5" id="email-div" style="display: none;">
                        <label for="email">Email</label>
                    </div>
                    <div class="input-field col s5" style="display: none;" id="password-div">
                        <label for="password">Password</label>
                    </div>

                    <div class="col s5" style="display: none;" id="login-btn-container">
                        <a class="waves-effect waves-light btn" href="/login/redirect"><i
                                class="material-icons left">cloud</i>Login With Google</a>
                    </div>
                    <div id="alt-login-options">
                        <div class="col s5">
                            <a class="waves-effect waves-light btn" href="/login/redirect"><i
                                    class="material-icons left">cloud</i>Login With Google</a>

                        </div>

                    </div>

                    <div class="col s5" style="display: none;" id="reset-password-submit">
                        <a class="waves-effect waves-light btn" onclick="sudoSendResetEmail()"><i
                                class="material-icons left">cloud</i>Send Reset Email</a>
                    </div>
                    <div class="col s5" id="notification" style="display: none;">
                        <h5>Failed To send Message</h5>
                        <p>See the console (F12) for full error logs.</p>
                    </div>
                </div>
            </div>

        </div>

    </div>
    <!-- partial -->
    <script src='https://cdnjs.cloudflare.com/ajax/libs/jquery/3.2.1/jquery.min.js'></script>
    <script src='https://cdnjs.cloudflare.com/ajax/libs/materialize/0.100.2/js/materialize.min.js'></script>
    <script src="/assets/auth/script.js"></script>

</body>

</html>