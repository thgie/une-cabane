<?php

    require __DIR__ . '/vendor/autoload.php';

    use League\Csv\Writer;

    if(isset($_POST['form'])){
        if($_POST['form'] == 'reservation'){
            $calendar = Writer::createFromPath('calendar.csv', 'a');
            $calendar->getNewline();
            $calendar->insertOne([
                $_POST['name'],
                $_POST['from'],
                $_POST['to'],
                $_POST['note']
            ]);
        }

        if($_POST['form'] == 'notes') {
            file_put_contents('notes.txt', $_POST['notes']);
        }

        header('Location: ' . $_SERVER['HTTP_HOST']);
        exit();
    }

?>

<!doctype html>
<html lang="de">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, user-scalable=no, initial-scale=1.0, maximum-scale=1.0, minimum-scale=1.0">
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
        <h2>Enquête / Übersicht</h2>
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
                    <div class="event-listing-title">EVENTS THIS MONTH</div>
                    <% _.each(eventsThisMonth, function(event) { %>
                    <div class="event-item">
                        <div class="event-item-name"><%= event.name %></div>
                        <div class="event-item-dates">
                            <%= event.startDate.split('-')[2] %>.<%= event.startDate.split('-')[1] %>.<%= event.startDate.split('-')[0] %>
                            -
                            <%= event.endDate.split('-')[2] %>.<%= event.endDate.split('-')[1] %>.<%= event.endDate.split('-')[0] %>
                        </div>
                        <div class="event-item-location"><%= event.note %></div>
                    </div>
                    <% }); %>
                </div>
            </script>
        </div>
        <div class="more clearfix">
            <div class="reservation">
                <br><br>
                <h2>Nouveau reservation / Neue Reservation</h2>
                <form action="" method="post">
                    <input type="hidden" name="form" value="reservation">
                    <input type="text" name="name" placeholder="Nom / Name" required>
                    <input type="text" name="dates" placeholder="Données / Daten" required>
                    <input type="hidden" name="from">
                    <input type="hidden" name="to">
                    <textarea name="note" placeholder="Notiz"></textarea>
                    <input type="submit" value="Reservieren">
                </form>
            </div>
            <div class="notes">
                <br><br>
                <h2>Notes / Notizen</h2>
                <form action="" method="post">
                    <input type="hidden" name="form" value="notes">
                    <input type="hidden" name="notes" value="<?php echo file_get_contents('notes.txt'); ?>">
                    <div class="notes-editor"></div>
                    <input type="submit" value="Abschicken">
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

            use League\Csv\Reader;

            $calendar = Reader::createFromPath('calendar.csv', 'r');
            $calendar->setHeaderOffset(0);
            $reservations = $calendar->getRecords(['name', 'from', 'to', 'note']);

            foreach ($reservations as $key => $reservation) {
                echo '{' . PHP_EOL;
                echo 'name: "' . $reservation['name'] . '",' . PHP_EOL;
                echo 'startDate: "' . $reservation['from'] . '",' . PHP_EOL;
                echo 'endDate: "' . $reservation['to'] . '", ' . PHP_EOL;
                echo 'note: "' . $reservation['note'] . '"' . PHP_EOL;
                echo '},' . PHP_EOL;
            }

        ?>

        ];

    </script>

    <script src="/app.js" defer></script>

</body>
</html>