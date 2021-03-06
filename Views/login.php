<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 20:00
 */
?>

<html class="no-js" lang="zh_CN">

    <?php include 'header.php'; ?>

    <body>

        <div class="row">
            <div class="small-10 small-offset-1 large-6 large-offset-3 columns login-box">
                <div class="row">

                    <div class="small-12 columns">
                        <h3 class="text-center login-text">Login</h3>
                    </div>
                    <div class="small-12 columns">
                        <div class="input-group">
                            <span class="input-group-label">Username</span>
                            <input type="text" class="input-group-field" id="user" value="">
                        </div>
                    </div>
                    <div class="small-12 columns">
                        <div class="input-group">
                            <span class="input-group-label">Password</span>
                            <input type="password" class="input-group-field" id="pwd" value="">
                        </div>
                    </div>

                    <div class="small-6 small-offset-3 columns">
                        <button id='login' type="button" class="button expanded">Login</button>
                        <button id='reg' type="button" class="button expanded">Sign up</button>
                    </div>
                </div>
            </div>
        </div>
        <?php include 'footer.php'; ?>

        <script>
            $('#login').click(function () {

                var Username = $('#user').val();
                var Pwd = $('#pwd').val();

                $.ajax({
                    url: '<?=$SiteUrl?>Login/Login',
                    data: {
                        Username: Username,
                        Password: Pwd
                    },
                    type: 'POST',
                    dataType: 'json',
                    success: function (data) {
                        if (data.err == 0) {
                            alert(data.msg);
                            window.location.href = '/Index';
                        }
                        else alert(data.msg);
                    },
                    error: function (data) {
                        alert(data);
                    }
                });
                //alert('User is ' + Username + '  Pwd is ' + Pwd);
            });

            $('#reg').click(function (){window.location.href='/Register';});
        </script>
    </body>
</html>
