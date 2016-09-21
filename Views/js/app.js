function InsertBox(id, title, number, type, details, time) {
    if (type == "1") {
        Expenses = parseFloat(Expenses) + parseFloat(number);
        $('#Expenses').text('$' + Expenses);
    } else {
        Income = parseFloat(Income)+parseFloat(number);
        $('#Income').text('$' + Income);
    }
    records.push({
        "id": id,
        "userid": "",
        "title": title,
        "type": type,
        "number": number,
        "time": time,
        "details": details
    });

    var balance = Income - Expenses;
    $('#Balance').text('$' + balance);

    var mdetails = $('.money-details');
    mdetails.prepend("<div class=\"callout money-box\"> </div>");
    var mbox = mdetails.children(":first");
    mbox.append("<span class=\"money-title\">" + title + "</span>");
    mbox.append("<span class=\"money-content\">" + details + "</span>");

    if (type == "1")
        mbox.append("<span class='money-subtract stat'>" + "-$" + number + "</span>");
    else mbox.append("<span class='money-add stat'>" + "+$" + number + "</span>");
    var date = new Date(time * 1000);
    mbox.append("<span class='money-time small'>" + date.toLocaleString() + "</span>");
    mbox.append("<input type='number' class='is-hidden' id='record-id' value='" + id + "'>");
    var index = records.length - 1;
    mbox.append("<input type='number' class='is-hidden' id='record-index-in-array' value='" + index + "'>");

}