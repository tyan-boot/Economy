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
        <!--<div class="top-bar">
            <div class="top-bar-title"><span class="bar-title">Economy</span></div>
        </div>-->


        <div class="mdl-layout mdl-js-layout mdl-layout--fixed-header">
            <header class="mdl-layout__header">
                <div class="mdl-layout__header-row">
                    <!-- Title -->
                    <span class="mdl-layout-title">Economy</span>
                    <!-- Add spacer, to align navigation to the right -->
                    <div class="mdl-layout-spacer"></div>
                    <!-- Navigation. We hide it in small screens. -->
                    <nav class="mdl-navigation mdl-layout--large-screen-only">
                        <a class="mdl-navigation__link" id="SignOut" href="">Sign out</a>
                    </nav>
                </div>
            </header>

            <div class="mdl-layout__drawer">
                <span class="mdl-layout-title">Menu</span>
                <nav class="mdl-navigation">
                    <a class="mdl-navigation__link" href="">Link</a>
                    <a class="mdl-navigation__link" href="">Link</a>
                    <a class="mdl-navigation__link" href="">Link</a>
                    <a class="mdl-navigation__link" href="">Link</a>
                </nav>
            </div>
            <main class="mdl-layout__content">
                <div class="page-content">
                    <div class="row">
                        <div class="small-12 medium-10 medium-offset-1">

                            <div class="money-overview column">
                                <div class="row">
                                    <div class="small-4 columns money-in callout">
                                        <p id="Income"></p>
                                        <p class="InOutText">Income</p>
                                    </div>

                                    <div class="small-4 columns money-balance callout">
                                        <p id="Balance"></p>
                                        <p class="InOutText">Balance</p>
                                    </div>

                                    <div class="small-4 columns money-out callout">
                                        <p id="Expenses"></p>
                                        <p class="InOutText">Expenses</p>
                                    </div>
                                </div>

                            </div>

                            <div class="money-details">
                            </div>
                        </div>

                        <div class="floatbutton">
                            <button class="button large add-button" id="addbutton">+</button>
                        </div>
                    </div>

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
                            <button id='Edit' type="button" class="button expanded is-hidden">Edit!</button>
                            <button id='Del' type="button" class="button expanded is-hidden">Delete</button>
                            <button id='TClose' type="button" class="button expanded">Close</button>

                        </div>
                    </div>

                    <?php include 'footer.php'; ?>

                </div>
            </main>
        </div>


        <!-- add -->




        <script>
            var records;
            var Income;
            var Expenses;
            $(document).ready(function () {
                $.ajax({
                    'url': '<?=$SiteUrl?>API/GetRecords',
                    dataType: 'json',
                    contentType: 'application/json',
                    type: 'GET',
                    success: function (data) {
                        $('#Income').text('$' + data.Income);
                        var balance = data.Income - data.Expenses;
                        $('#Balance').text('$' + balance);
                        $('#Expenses').text('$' + data.Expenses);
                        records = data.data;
                        Income = data.Income;
                        Expenses = data.Expenses;

                        for (var key in records) {
                            var record = records[key];
                            var mdetails = $('.money-details');
                            mdetails.append("<div class=\"callout money-box\"> </div>");
                            var mbox = mdetails.children(":last");
                            mbox.append("<span class=\"money-title\">" + record.title + "</span>");
                            mbox.append("<span class=\"money-content\">" + record.details + "</span>");

                            if (record.type == "1")
                                mbox.append("<span class='money-subtract stat'>" + "-$" + record.number + "</span>");
                            else mbox.append("<span class='money-add stat'>" + "+$" + record.number + "</span>");
                            var date = new Date(record.time * 1000);
                            mbox.append("<span class='money-time small'>" + date.toLocaleString() + "</span>");
                            mbox.append("<input type='number' class='is-hidden' id='record-id' value='" + record.id + "'>");
                            mbox.append("<input type='number' class='is-hidden' id='record-index-in-array' value='" + key + "'>");
                        }
                    }
                });
            });
        </script>

        <script>
            $('#addbutton').click(function () {
                var AddItem = $('#AddItem');

                AddItem.foundation('open');

                AddItem.find('#Add').removeClass('is-hidden');
                AddItem.find('#Edit').addClass('is-hidden');
                AddItem.find('h1.text-center').html('Add a record');
                AddItem.find('#Del').addClass('is-hidden');
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

            $('body').on('click', '#Add', function () {
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
                var ss = new Date().getSeconds();
                var date = new Date(year, month, day, hour, minute, ss);

                var timestamp = date.getTime() / 1000;

                var postdata = {
                    title: title,
                    number: number,
                    type: type,
                    details: details,
                    time: timestamp,
                };
                $.ajax({
                    url: '<?=$SiteUrl?>API/AddRecord',
                    dataType: 'json',
                    contentType: 'application/json',
                    type: 'POST',
                    data: JSON.stringify(postdata),
                    success: function (data) {
                        if (data.err == 0) {
                            alert('Add Success');
                            $('#AddItem').foundation('close');
                            InsertBox(data.id, title, number, type, details, timestamp);
                        } else {
                            alert('Add failed: ');
                        }
                    },
                    error: function (XMLHttpRequest, TextStatus) {
                        alert(XMLHttpRequest.status);
                    }
                })
            });

            $('body').on('click', '.money-box', function (event) {
                //init info
                var index = $(this).find('#record-index-in-array').val();
                var thisBox = this;

                var record = records[index];
                var id = record['id'];

                $('#number').val(record.number);
                $('#details').val(record.details);
                $('#title').val(record.title);
                var date = new Date(record.time * 1000);

                $('#year').val(date.getFullYear());
                $('#month').val(date.getMonth() + 1);
                $('#day').val(date.getDate());
                $('#hour').val(date.getHours());
                $('#minute').val(date.getMinutes());

                //show
                $('#AddItem').foundation('open');
                var AddBox = $('#AddItem');
                AddBox.find('#Add').addClass('is-hidden');
                AddBox.find('#Edit').removeClass('is-hidden');
                AddBox.find('#Del').removeClass('is-hidden');

                AddBox.find('h1.text-center').html('Change a record');

                $('#Edit').click(function () {
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
                        uid: id,
                        title: title,
                        number: number,
                        type: type,
                        details: details,
                        time: timestamp
                    };

                    $.ajax({
                        url: '<?=$SiteUrl?>API/EditRecord',
                        dataType: 'json',
                        contentType: 'application/json',
                        type: 'POST',
                        data: JSON.stringify(postdata),
                        success: function (data) {
                            if (data.err == 0) {
                                alert('Edit Success');
                                $('#AddItem').foundation('close');
                                window.location.reload();
                            } else {
                                alert('Edit failed: ');
                            }
                        },
                        error: function (XMLHttpRequest, TextStatus) {
                            alert(XMLHttpRequest.status);
                        }
                    });

                    $('#TClose').click(function () {
                        $('#AddItem').foundation('close');
                    });
                });

                $('#Del').click(function () {
                    $.ajax({
                        url: '<?=$SiteUrl?>API/DelRecord/' + id,
                        dataType: 'json',
                        contentType: 'application/json',
                        type: 'GET',
                        success: function (data) {
                            if (data.err == 0) {
                                alert('Del Success');
                                $('#AddItem').foundation('close');
                                window.location.reload();
                            } else {
                                alert('Del failed: ');
                            }
                        },
                        error: function (XMLHttpRequest, TextStatus) {
                            alert(XMLHttpRequest.status);
                        }
                    });
                });
            });

            $('#SignOut').click(function(){

                function removeItem(sKey, sPath, sDomain) {
                    document.cookie = encodeURIComponent(sKey) +
                        "=; expires=Thu, 01 Jan 1970 00:00:00 GMT" +
                        (sDomain ? "; domain=" + sDomain : "") +
                        (sPath ? "; path=" + sPath : "");
                }

                removeItem('LOGIN');
            });
        </script>
    </body>
</html>
