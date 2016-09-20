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

                <div class="money-overview column">
                    <div class="row">
                        <div class="small-4 columns money-in callout">
                            <p>
                                $<?= $Income ?>
                            </p>
                            <p class="InOutText">Income</p>
                        </div>

                        <div class="small-4 columns money-balance callout">
                            <p>
                                $<?= $Balance ?>
                            </p>
                            <p class="InOutText">Balance</p>
                        </div>

                        <div class="small-4 columns money-out callout">
                            <p>
                                $<?= $Outcome ?>
                            </p>
                            <p class="InOutText">Expenses</p>
                        </div>
                    </div>

                </div>

                <div class="money-details">
                    <?php foreach ($Records as $key => $r): ?>

                        <div class="callout money-box">
                            <span class="money-title"><?= $r['title'] ?></span>
                            <span class="money-content"><?= $r['details'] ?></span>
                            <?php if ($r['type'] == 1): ?>
                                <span class="money-subtract stat">-$<?= $r['number'] ?></span>
                            <?php else: ?><span class="money-add stat">+$<?= $r['number'] ?></span>
                            <?php endif; ?>
                            <span class="money-time small"><?= date("Y-m-d H:i:s", $r['time']); ?></span>
                            <input type="number" class="is-hidden" id="record-id" value="<?= $r['id'] ?>">
                        </div>

                    <?php endforeach; ?>
                </div>

            </div>

            <div class="floatbutton">
                <button class="button large add-button" id="addbutton">+</button>
            </div>
        </div>

        <!-- add -->

        <div class="reveal" id="AddItem" data-reveal data-animation-out="fade-out" data-reset-on-close="true">
            <button class="close-button" data-close type="button">
                <span>&times;</span>
            </button>

            <div class="AddForm">
                <h1 class="text-center">Add a record</h1>
                <div class="row collapse">
                    <div class="small-12 columns">
                        <div class="input-group">
                            <span class="input-group-label">Number</span>
                            <input type="number" class="input-group-field" id="number" value="">
                        </div>
                    </div>

                    <div class="small-12 columns">
                        <div class="switch large">
                            <input class="switch-input" id="InOut" type="checkbox" name="InOut" checked>
                            <label class="switch-paddle" for="InOut">
                                <span class="switch-active">Output</span>
                                <span class="switch-inactive">Input</span>
                            </label>
                        </div>
                    </div>

                    <div class="small-12 columns">
                        <div class="input-group">
                            <span class="input-group-label">Title</span>
                            <input type="text" class="input-group-field" id="title" value="">
                        </div>
                    </div>

                    <div class="small-12 columns">
                        <div class="input-group">
                            <span class="input-group-label">Details</span>
                            <input type="text" class="input-group-field" id="details" value="">
                        </div>
                    </div>

                    <div class="small-12 medium-3 columns">
                        <div class="input-group">
                            <span class="input-group-label">Year</span>
                            <input type="number" class="input-group-field" id="year" value="">
                        </div>
                    </div>

                    <div class="small-12 medium-3 columns">
                        <div class="input-group">
                            <span class="input-group-label">Month</span>
                            <input type="number" class="input-group-field" id="month" value="">
                        </div>
                    </div>

                    <div class="small-12 medium-6 columns">
                        <div class="input-group">
                            <span class="input-group-label">Day</span>
                            <input type="number" class="input-group-field" id="day" value="">
                        </div>
                    </div>

                    <div class="small-12 medium-4 columns">
                        <div class="input-group">
                            <span class="input-group-label">Hour</span>
                            <input type="number" class="input-group-field" id="hour" value="">
                        </div>
                    </div>

                    <div class="small-12 medium-6 columns">
                        <div class="input-group">
                            <span class="input-group-label">Minute</span>
                            <input type="number" class="input-group-field" id="minute" value="">
                        </div>
                    </div>
                </div>

                <button id='Add' type="button" class="button expanded">Add!</button>

                <button id='TClose' type="button" class="button expanded">Close</button>

            </div>
        </div>

        <?php include 'footer.php'; ?>


        <script>
            $('#addbutton').click(function () {
                $('#AddItem').foundation('open');

                $('#AddItem').find('#Add').html('Add');
                $('#AddItem').find('h1.text-center').html('Add a record');

                var date = new Date();
                var year = date.getFullYear();
                var month = date.getMonth() + 1;
                var day = date.getDate();
                var hour = date.getHours();
                var minute = date.getMinutes();

                $('#year').val(year);
                $('#month').val(month);
                $('#day').val(day);
                $('#hour').val(hour);
                $('#minute').val(minute);
                //bind function
                $('#TClose').click(function () {
                    $('#AddItem').foundation('close');
                });
            });

            $('#Add').click(function () {
                var number = $('#number').val();
                if ($('#InOut').is(':checked'))
                //out
                    var type = 1;
                else var type = 2; //in

                var details = $('#details').val();
                var title = $('#title').val();
                //var time = new Da)

                var year = $('#year').val();
                var month = $('#month').val();
                var day = $('#day').val();
                var hour = $('#hour').val();
                var minute = $('#minute').val();
                var date = new Date(year, month, day, hour, minute);

                var timestamp = date.getTime() / 1000;

                var postdata = {
                    title: title,
                    number: number,
                    type: type,
                    details: details,
                    time: timestamp
                };
                $.ajax({
                    url: 'http://eco.boot.pw/Index/AddRecord',
                    dataType: 'json',
                    contentType: 'application/json',
                    type: 'POST',
                    data: JSON.stringify(postdata),
                    success: function (data) {
                        if (data.err == 0) {
                            alert('Add Success');
                            $('#AddItem').foundation('close');
                        }
                    }
                })
            });

            $('.money-box').click(function (event) {
                console.log($(this).find('#record-id').val());
                $('#AddItem').foundation('open');
                var AddBox = $('#AddItem');
                AddBox.find('#Add').html('Change');
                AddBox.find('h1.text-center').html('Change a record');
                alert('Sorry, change operation is not allow now');
                $('#TClose').click(function () {
                    $('#AddItem').foundation('close');
                });
            });
        </script>
    </body>
</html>
