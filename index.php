<?php

if (isset($_POST['form'])) {
    if ($_POST['form'] == 'reservation') {
        $fp = fopen('calendar.csv', 'a');
        fputcsv($fp, [
            uniqid(),
            $_POST['name'],
            $_POST['from'],
            $_POST['to'],
            preg_replace("/[\n\r]/", "", nl2br($_POST['note']))
        ]);
        fclose($fp);
    }

    if ($_POST['form'] == 'notes') {
        file_put_contents('notes.txt', $_POST['notes']);
    }

    header('Location: https://' . $_SERVER['SERVER_NAME']);
    exit();
}

$admin = isset($_GET['admin']) ? true : false;

if (isset($_GET['delete'])) {

    $calendar = fopen('calendar.csv', 'r');
    $temp_calendar = fopen('calendar_temp.csv', 'w');

    while (($data = fgetcsv($calendar, 1000)) !== false) {
        if (reset($data) == $_GET['delete']) {
            continue;
        }
        fputcsv($temp_calendar, $data);
    }
    fclose($calendar);
    fclose($temp_calendar);
    rename('calendar_temp.csv', 'calendar.csv');

    header('Location: https://' . $_SERVER['SERVER_NAME']);
    exit();

}

?>

<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport"
          content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
    <meta http-equiv="X-UA-Compatible" content="ie=edge">
    <title>Le Grillon</title>

    <link rel="stylesheet" href="/assets/clndr.css">
    <link rel="stylesheet" href="/assets/daterangepicker.css">
    <link rel="stylesheet" href="/assets/quill.snow.css">

    <link rel="stylesheet" href="/app.css">

</head>
<body>

<div class="wrapper">
    <h1>Le Grillon</h1>
    <h2>Aperçu</h2>
    <div class="calendar">
        <script type="text/template" class="calendar-template">
            <div class="clndr-controls">
                <div class="clndr-previous-button">‹</div>
                <div class="clndr-next-button">›</div>
                <div class="current-month"><%= month %> <%= year %></div>

            </div>
            <div class="clndr-grid">
                <div class="days-of-the-week clearfix">
                    <% _.each(daysOfTheWeek, function(day) { %>
                    <div class="header-day"><%= day %></div>
                    <% }); %>
                </div>
                <div class="days clearfix">
                    <% _.each(days, function(day) { %>
                    <div class="<%= day.classes %>" id="<%= day.id %>">
                        <span class="day-number"><%= day.day %></span>
                    </div>
                    <% }); %>
                </div>
            </div>
            <div class="event-listing">
                <div class="event-listing-title">List</div>
                <% _.each(eventsThisMonth, function(event) { %>
                <div class="event-item" data-uid="<%= event.uid %>">
                    <div class="event-item-name"><%= event.name %></div>
                    <div class="event-item-dates">
                        <%= event.startDate.split('-')[2] %>.<%= event.startDate.split('-')[1] %>.<%=
                        event.startDate.split('-')[0] %>
                        -
                        <%= event.endDate.split('-')[2] %>.<%= event.endDate.split('-')[1] %>.<%=
                        event.endDate.split('-')[0] %>
                    </div>
                    <div class="event-item-location"><%= event.note %></div>
                    <?php if ($admin): ?>
                        <a href="/?delete=<%= event.uid %>" class="delete button">Delete</a>
                    <?php endif; ?>
                </div>
                <% }); %>
            </div>
        </script>
    </div>
    <div class="more clearfix">
        <div class="reservation">
            <br><br>
            <h2>Nouvelle Reservation</h2>
            <form action="" method="post">
                <input type="hidden" name="form" value="reservation">
                <input type="text" name="name" placeholder="Nom" required>
                <input type="text" name="dates" placeholder="Dates" required>
                <input type="hidden" name="from">
                <input type="hidden" name="to">
                <textarea name="note" placeholder="Note"></textarea>
                <input type="submit" value="Réservez">
            </form>
        </div>
        <div class="notes">
            <br><br>
            <h2>Memos</h2>
            <form action="" method="post">
                <input type="hidden" name="form" value="notes">
                <input type="hidden" name="notes" value="<?php echo file_get_contents('notes.txt'); ?>">
                <div class="notes-editor"></div>
                <?php if(!$admin): ?>
                    <div class="notes-display"></div>
                <?php else: ?>
                <input type="submit" value="Sauvegarder">
                <?php endif; ?>
            </form>
        </div>
    </div>
</div>

<script src="/assets/jquery-3.3.1.min.js" defer></script>
<script src="/assets/underscore-min.js" defer></script>
<script src="/assets/moment.min.js" defer></script>
<script src="/assets/clndr.min.js" defer></script>
<script src="/assets/daterangepicker.js" defer></script>
<script src="/assets/quill.min.js" defer></script>

<script>

    var reservations = [

        <?php

        if (($handle = fopen("calendar.csv", "r")) !== false) {

            while (($data = fgetcsv($handle, 1000, ",")) !== false) {

                echo '{' . PHP_EOL;
                echo 'uid: "' . $data[0] . '",' . PHP_EOL;
                echo 'name: "' . $data[1] . '",' . PHP_EOL;
                echo 'startDate: "' . $data[2] . '",' . PHP_EOL;
                echo 'endDate: "' . $data[3] . '", ' . PHP_EOL;
                echo 'note: "' . $data[4] . '"' . PHP_EOL;
                echo '},' . PHP_EOL;
            }

        }

        ?>

    ];

</script>

<script src="/app.js" defer></script>

</body>
</html>