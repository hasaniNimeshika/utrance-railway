{{main}}

<?php
    if($_REQUEST['url'] !== 'resetPassword' && $_REQUEST['url'] !== 'forgotPassword' && $_REQUEST['url'] !== 'login' && $_REQUEST['url'] !== 'register') :?>
        {{header}}
    <?php endif;?>

<?php
    if(App::$APP->activeUser()['role'] === 'admin' && $_REQUEST['url'] !== 'home' && $_REQUEST['url'] !== 'myBookings' && $_REQUEST['url'] !== 'booked-tour' && $_REQUEST['url'] !== 'booking-train' && $_REQUEST['url'] !== 'freight-booking-train' && $_REQUEST['url'] !== 'resetPassword' && $_REQUEST['url'] !== 'forgotPassword' && $_REQUEST['url'] !== 'search' && $_REQUEST['url'] !== 'book-seats' && $_REQUEST['url'] !== 'login' && $_REQUEST['url'] !== 'register' && $_REQUEST['url'] !== 'freight-search' && $_REQUEST['url'] !== 'book-freights' && $_REQUEST['url'] !== 'view-train')  : ?>
    {{adminSideNav}}
<?php endif;?>

<?php
    if(App::$APP->activeUser()['role'] === 'user' && $_REQUEST['url'] !== 'home' && $_REQUEST['url'] !== 'resetPassword' && $_REQUEST['url'] !== 'forgotPassword') : ?>
    {{userSideNav}}
<?php endif;?>

{{content}}

{{footer}}
