<!DOCTYPE html>
<html lang="en-US">

<body>
    <p>Hello.</p>

    <hr>

    <p>let's try php + mysql</p>
    <?php
        // Determine credentials, connect
        if ($_SERVER['REMOTE_ADDR'] == '127.0.0.1') {
            $conn = new mysqli("127.0.0.1", "root", "password", "php");
        } else {
            $conn = new mysqli("sql212.epizy.com", "epiz_26007529", "bLRm14uzdNSFMM", "epiz_26007529_test");
        }

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        } else {
            echo "<p>Successfully connected to database</p>";
        }

        // sql to create table
        $make_table = "CREATE TABLE melody_data (
        id INT(8) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        melody VARCHAR(60) NOT NULL,
        title VARCHAR(60) NOT NULL,
        artist VARCHAR(30),
        genre VARCHAR(30),
        youtube_id VARCHAR(11)
        )";

        if ($conn->query($make_table)) {
            echo "<p>made database!</p>";
        } else {
            echo "couldn't create database: ", $conn->error;
        }

        // insert dummy data
        $data = $conn->query("SELECT id FROM melody_data");
        if ($data->num_rows < 2) {
            $dummy_data = array(array('-1 1 -1 1 -5 3 -2 -3', 'Fur Elise', 'Ludwig van Beethoven', 'Classical', '_mVW8tgGY_w'),
                                array());
            $insert_data = $conn->prepare("INSERT INTO melody_data (melody, title, artist, genre, youtube_id) VALUES (?,?,?,?,?)");

            foreach ($dummy_data as $row) {
                $melody = $title = $artist = $genre = $youtube_id = $row;

                $insert_data->execute();
            }
            // $new_data->execute(array('-1 1 -1 1 -5 3 -2 -3', 'Fur Elise', 'Ludwig van Beethoven', 'Classical', '_mVW8tgGY_w'));

            echo "<p>Inserted dummy data</p>";
        } else {
            echo "<p>Did not insert more data</p>";
        }

        $conn->close();
    ?>
</body>

</html>