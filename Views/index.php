<?php
/**
 * Created by PhpStorm.
 * User: TyanBoot
 * Date: 2016/9/17
 * Time: 23:31
 */
?>


<!doctype html>

<html class="no-js" lang="zh_CN">

    <?php include 'header.php'; ?>

    <body>
        <div class="top-bar">
            <div class="top-bar-title"><span class="bar-title">Economy</span></div>
        </div>
        <div class="row">
            <div class="small-12 medium-10 medium-offset-1">

                <div class="money-details">
                    <div class="callout money-box">
                        <span class="money-title">SFW</span>
                        <span class="money-content">Buy 4 sfw books</span>
                        <span class="money-subtract stat">-$100</span>
                        <span class="money-time small">2016-09-18</span>
                    </div>

                    <div class="callout money-box">
                        <span class="money-title">Add</span>
                        <span class="money-content">Arathi gave $9999</span>
                        <span class="money-add stat">+$9999</span>
                        <span class="money-time small">2016-09-18</span>
                    </div>
                    <div class="callout money-box">
                        <span class="money-title">Add</span>
                        <span class="money-content">Arathi gave $9999</span>
                        <span class="money-add stat">+$9999</span>
                        <span class="money-time small">2016-09-18</span>
                    </div>
                </div>

            </div>

            <div class="floatbutton">
                <button class="button large add-button" id="addbutton">+</button>
            </div>
        </div>

        <!-- add -->

        <div class="reveal" id="AddItem" data-reveal data-animation-out="fade-out">
            <button class="close-button" data-close type="button">
                <span>&times;</span>
            </button>

            <div class="AddForm">
                <h1 class="text-center">Add a record</h1>
                <div class="input-group">
                    <span class="input-group-label">Number</span>
                    <input type="text" class="input-group-field" id="number" value="">
                </div>

                <div class="switch large">
                    <input class="switch-input" id="InOut" type="checkbox" name="InOut" checked>
                    <label class="switch-paddle" for="InOut">
                        <span class="switch-active">Output</span>
                        <span class="switch-inactive">Input</span>
                    </label>
                </div>

                <div class="input-group">
                    <span class="input-group-label">Details</span>
                    <input type="text" class="input-group-field" id="pwd" value="">
                </div>
                <div class="input-group">
                    <span class="input-group-label">Time</span>
                    <input type="text" class="input-group-field" id="pwd" value="">
                </div>

                <button id='Add' type="button" class="button expanded">Add!</button>

            </div>
        </div>

        <?php include 'footer.php'; ?>


        <script>
            $('#addbutton').click(function () {
                var AddElm = new Foundation.Reveal($('#AddItem'), {"data-animation-in": "fade-in"});
                AddElm.open();
                //$('#AddItem').foundation('open');
            });
        </script>
    </body>
</html>
