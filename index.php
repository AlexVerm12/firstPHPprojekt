<!DOCTYPE html>
<html lang="en">

<head>
    <meta charset="UTF-8">
    <meta http-equiv="X-UA-Compatible" content="IE=edge">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <link rel="preconnect" href="https://fonts.googleapis.com">
    <link rel="preconnect" href="https://fonts.gstatic.com" crossorigin>
    <link
        href="https://fonts.googleapis.com/css2?family=Roboto:ital,wght@0,100;0,300;0,400;0,500;0,700;0,900;1,100;1,300;1,400;1,500;1,700;1,900&display=swap"
        rel="stylesheet">
    <title>Document</title>

    <style>
        body {
            margin: 0px;
            font-family: 'Roboto', sans-serif;
            background-color: #EAEDF8;
        }

        .footer {
            text-align: center;
            width: 100%;
            padding: 50px 0px;
            background-color: #343434;
            color: white;
            position: fixed;
            bottom: 0;
            left: 0;
        }

        .main {
            display: flex;
        }

        .menu {
            width: 20%;
            background-color: #746cf5;
            margin-right: 32px;
            padding-top: 150px;
            height: 100vh;
        }

        .menu a {
            display: block;
            text-decoration: none;
            color: #000000;
            padding: 8px;
            display: flex;
            align-items: center;
        }

        .menu img {
            width: 32px;
            margin-right: 8px;
        }

        .menu a:hover {
            background-color: rgba(255, 255, 255, 0.1);
        }

        .content {
            width: 80%;
            margin-top: 120px;
            margin-right: 32px;
            background-color: white;
            border-radius: 8px;
            padding: 16px;

        }

        .menubar {
            background-color: white;
            position: absolute;
            left: 0;
            right: 0;
            top: 0;
            height: 80px;
            box-shadow: 2px 2px 2px rgba(0, 0, 0, 0.1);
            padding-left: 50px;
            display: flex;
            justify-content: space-between;

        }

        .avatar {
            border-radius: 100%;
            background-color: yellowgreen;
            padding: 16px;
            width: 16px;
            height: 16px;
            display: flex;
            justify-content: center;
            align-items: center;
            margin-right: 16px;
        }

        .menubarleft {
            display: flex;
            align-items: center;
            margin-right: 50px;
        }

        .profile-picture {
            position: absolute;
            left: 16px;
            bottom: 4px;
        }

        .card {
            position: relative;
            background-color: rgba(0, 0, 0, 0.05);
            margin-bottom: 16px;
            border-radius: 8px;
            padding: 8px;
            padding-left: 70px;
        }

        .deletebtn {
            background-color: red;
            padding: 4px;
            color: white;
            text-decoration: none;
            border-radius: 4px;
            position: absolute;
            bottom: 0px;
            width: 60px;
            right: 0px;
        }

        .deletebtn:hover {
            background-color: #FF3333;
        }
    </style>
</head>

<body>
    <div class="menubar">
        <h1>Mein Kontaktbuch</h1>

        <div class="menubarleft">
            <div class="avatar">AV</div> Alex Vermeersch
        </div>

    </div>
    <div class="main">
        <div class="menu">
            <a href="index.php?page=start"><img src="img/icon-home.png">Start</a>
            <a href="index.php?page=contacts"><img src="img/icon-contacts.png">Kontakte</a>
            <a href="index.php?page=addcontact"><img src="img/icon-add.png">Kontakt hinzufügen</a>
            <a href="index.php?page=legal"><img src="img/icon-legal.png">Impressum</a>
        </div>

        <div class="content">

            <?php
            $headline = 'Herzlich willkommen';
            $contacts = [];

            if (file_exists('contacts.txt')) {
                $text = file_get_contents('contacts.txt', true);
                $contacts = json_decode($text, true);
            }

            if (isset($_POST['name']) && isset($_POST['phone'])) {
                echo 'Kontakt <b>' . $_POST['name'] . '</b> wurde hinzugefügt';
                $newContact = [
                    'name' => $_POST['name'],
                    'phone' => $_POST['phone']
                ];
                array_push($contacts, $newContact);
                file_put_contents('contacts.txt', json_encode($contacts, JSON_PRETTY_PRINT));
            }

            if ($_GET['page'] == 'delete') {
                $headline = 'Kontakt gelöscht';
            }

            if ($_GET['page'] == 'contacts') {
                $headline = 'Deine Kontakte';
            }

            if ($_GET['page'] == 'addcontact') {
                $headline = 'Kontakt hinzufügen';
            }

            if ($_GET['page'] == 'legal') {
                $headline = 'Impressum';
            }

            echo '<h1>' . $headline . '</h1>';

            if ($_GET['page'] == 'delete') {
                echo '<p>Dein Kontakt wurde gelöscht</p>';
                # Wir laden die Nummer der Reihe aus den URL Parametern
                $index = $_GET['delete'];

                # Wir löschen die Stelle aus dem Array 
                unset($contacts[$index]);

                # Tabelle erneut speichern in Datei contacts.txt
                file_put_contents('contacts.txt', json_encode($contacts, JSON_PRETTY_PRINT));
            } else if ($_GET['page'] == 'contacts') {
                echo "
                    <p>Auf dieser Seite hast du einen Überblick über deine <b>Kontakte</b></p>
            ";

                foreach ($contacts as $index => $row) {
                    $name = $row['name'];
                    $phone = $row['phone'];

                    echo "<div class='card'>
                            <img class='profile-picture' src='img/profile.png'>
                            <b>$name</b> </br>
                            <span><a style='color:grey; text-decoration:none;' href='tel:$phone'>$phone</a></span>
                            <a class='deletebtn' href='?page=delete&delete=$index'>Löschen</a>
                        </div>";
                }
            } else if ($_GET['page'] == 'legal') {
                echo "
                    Hier kommt das Impressum hin
            ";
            } else if ($_GET['page'] == 'addcontact') {
                echo "
                    <div>Hier kannst du einen Kontakt hinzufügen.</div>
                    <form action ='?page=contacts' method='post'>
                        <div>
                            <input placeholder='Namen eingeben' name='name'>
                        </div>
                        <div>
                            <input type='number' placeholder='Telefonnummer eingeben' name='phone'>
                        </div>

                        <button type='submit'>Absenden</button>
                    </form>
            ";
            } else {
                echo 'Du bist auf der Startseite';
            }
            ?>

        </div>
    </div>

    <div class="footer">
        (c) 2023 Developer Akademie GmbH
    </div>

</body>

</html>