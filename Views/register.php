<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 22:38
 */
?>

<html class="no-js" lang="zh_CN">

    <?php include 'header.php'; ?>

    <body>

        <div class="row">
            <div class="small-10 small-offset-1 large-6 large-offset-3 columns login-box">
                <div class="row">

                    <div class="small-12 columns">
                        <h3 class="text-center login-text">Sign up</h3>
                    </div>
                    <div class="small-12 columns">
                        <div class="input-group">
                            <span class="input-group-label">Username</span>
                            <input type="text" class="input-group-field" id="user" value="" title="user">
                        </div>
                    </div>
                    <div class="small-12 columns">
                        <div class="input-group">
                            <span class="input-group-label">Password</span>
                            <input type="password" class="input-group-field" id="pwd" value="">
                        </div>
                    </div>

                    <div class="small-6 small-offset-3 columns">
                        <button id='reg' type="button" class="button expanded">Join!</button>
                        <button id='login' type="button" class="button expanded">Log in!</button>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>

        <script>
            $('#reg').click(function () {

                var Username = $('#user').val();
                var Pwd = $('#pwd').val();

                $.ajax({
                    url: '<?=$SiteUrl?>Register/Register',
                    data: {
                        Username: Username,
                        Password: Pwd
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        if (data.err == 0) {
                            alert(data.msg);
                            window.location.href = '/Login';
                        }
                        else alert(data.msg);
                    },
                    error: function (data) {
                        alert(data);
                    }
                });
            });

            $('#login').click(function (){window.location.href='/Login';});
        </script>
    </body>
</html>
