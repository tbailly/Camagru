<?php

/* START If logged on user */
if (isset($_SESSION['logged_on_user'])) {
$headerContent = <<<HTML
<header id="header">
    <nav class="navbar navbar-primary bg-primary justify-content-between">
    <div>
        <button id="header-home" class="btn btn-primary">Camagru</button>
        <button id="header-my-pictures" class="btn btn-outline-dark ml-3" type="submit">My pictures</button>
        <button id="header-take-picture" class="btn btn-outline-dark ml-3" type="submit">Take a picture</button>
    </div>
        <div>
            <button id="header-logout" class="btn btn-outline-dark" type="submit">Log out</button>
            <button id="go-to-profile-page" class="btn btn-primary ml-5">
                <span class="mr-2">{$_SESSION['logged_on_user']['username']}</span>
HTML;
    if ($_SESSION['logged_on_user']['profile_img'] === '1') {
$headerContent .= <<<HTML
                <img class="rounded-circle" src="../pictures/profiles/{$_SESSION['logged_on_user']['id_user']}.jpg"/>
            </button>
        </div>
    </nav>
HTML;
    } else {
$headerContent .= <<<HTML
                <img class="rounded-circle" src="./img/user-placeholder.png"/>
            </button>
        </div>
    </nav>
HTML;
    }
}
/* END If logged on user */
/* START If NOT logged on user */
else
{
$headerContent = <<<HTML
<header id="header">
    <nav class="navbar navbar-primary bg-primary justify-content-between">
    <div>
        <button id="header-home" class="btn btn-primary">Camagru</button>
    </div>
        <div>
            <button id="header-signup" class="btn btn-outline-dark" type="submit">Sign up</button>
            <button id="header-login" class="btn btn-outline-dark" type="submit">Sign in</button>
        </div>
    </nav>
HTML;
}
/* END If NOT logged on user */


/* START modal if logged on user  */
if (!isset($_SESSION['logged_on_user']))
{
$headerContent .= <<<HTML
<div id="signup-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Sign up</h5>
                <button type="button" class="close close-signup-modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" id="signup-firstname" placeholder="First name">
                    </div>
                     <div class="form-group">
                        <input type="text" class="form-control" id="signup-lastname" placeholder="Last name">
                    </div>
                    <div class="form-group">
                        <input type="text" class="form-control" id="signup-username" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="email" class="form-control" id="signup-mail" placeholder="E-mail">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="signup-password" placeholder="Password">
                    </div>
                    <button type="submit" id="signup-send" class="btn btn-primary">Sign up</button>
                </form>
            </div>
        </div>
    </div>
</div>
<div id="login-modal" class="modal fade">
    <div class="modal-dialog">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title">Log in</h5>
                <button type="button" class="close close-login-modal">
                    <span>&times;</span>
                </button>
            </div>
            <div class="modal-body">
                <form>
                    <div class="form-group">
                        <input type="text" class="form-control" id="login-username-mail" placeholder="Username">
                    </div>
                    <div class="form-group">
                        <input type="password" class="form-control" id="login-password" placeholder="Password">
                    </div>
                    <a>Confirmation e-mail not received ?</a>
                    <a href="./reset-password.php">Forgotten password</a>
                    <button type="submit" id="login-send" class="btn btn-primary">Log in</button>
                </form>
            </div>
        </div>
    </div>
</div>
HTML;
}
/* END modal if logged on user  */

$headerContent .= <<<HTML

</header>
<div id="notifications"></div>
HTML;




