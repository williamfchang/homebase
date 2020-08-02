<!DOCTYPE html>
<html lang="en-US">

<body>
    <p>Hello.</p>

    <!-- <form action="php-test-post.php" method="post">
    Name: <input type="text" name="name"><br>
    E-mail: <input type="text" name="email"><br>
    <input type="submit">
    </form> -->

    <form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
        <p>First Name: <input type="text" name="firstname" value="<?php echo $firstname;?>"></p>
        <p>Last Name: <input type="text" name="lastname" value="<?php echo $lastname;?>"></p>

        <p>E-mail: <input type="text" name="email" value="<?php echo $email;?>"></p>

        <p>Website: <input type="text" name="website" value="<?php echo $website;?>"></p>

        <p>Comment: <textarea name="comment" rows="5" cols="40"><?php echo $comment;?></textarea></p>

        <p>Gender:
        <input type="radio" name="gender"
        <?php if (isset($gender) && $gender=="female") echo "checked";?>
        value="female">Female
        <input type="radio" name="gender"
        <?php if (isset($gender) && $gender=="male") echo "checked";?>
        value="male">Male
        <input type="radio" name="gender"
        <?php if (isset($gender) && $gender=="other") echo "checked";?>
        value="other">Other</p>

        <input type="submit" name="submit" value="Submit">
    </form>

    <?php
    if(isset($_POST['submit'])) {
        echo "<p>", $_POST["firstname"], "</p>";
        echo "<p>hmm</p>";
    } else {
        echo "<p>nah fam</p>";
    }
    ?>

    <hr>

    <p>let's try php + mysql</p>
    <?php
        $servername = "127.0.0.1";
        $username = "root";
        $password = "password";
        $dbname = "php";

        // Create connection
        $conn = new mysqli($servername, $username, $password, $dbname);

        // Check connection
        if ($conn->connect_error) {
            die("Connection failed: " . $conn->connect_error);
        }

        // // sql to create table
        // $sql = "CREATE TABLE MyGuests (
        // id INT(6) UNSIGNED AUTO_INCREMENT PRIMARY KEY,
        // firstname VARCHAR(30) NOT NULL,
        // lastname VARCHAR(30) NOT NULL,
        // email VARCHAR(50),
        // reg_date TIMESTAMP DEFAULT CURRENT_TIMESTAMP ON UPDATE CURRENT_TIMESTAMP
        // )";

        // if ($conn->query($sql) === TRUE) {
        //     echo "made database!";
        // } else {
        //     echo "couldn't create database: ", $conn->error;
        // }

        // echo "<br>";

        // insert data FROM FORM!!
        if (isset($_POST['submit'])) {
            $new_data = $conn->prepare("INSERT INTO MyGuests (firstname, lastname, email) VALUES (?, ?, ?)");
            $new_data->bind_param('sss', $_POST["firstname"], $_POST["lastname"], $_POST["email"]);
            
            if ($new_data->execute() === TRUE) {
                echo "inserted data";
            } else {
                echo "couldn't insert data: ", $new_data->error;
            }

            $new_data->close();
        }

        // delete data
        // $delete_cmd = "delete from MyGuests where firstname='John'";
        // if ($conn->query($delete_cmd)) {
        //     echo "Record(s) deleted successfully";
        // } else {
        //     echo "Error deleting record: " . $conn->error;
        // }

        // update data
        $update_cmd = "UPDATE MyGuests SET firstname='Willie' WHERE firstname='William'";
        if ($conn->query($update_cmd)) {
            echo "Record(s) updated successfully";
        } else {
            echo "Error updating record: " . $conn->error;
        }

        // display data
        $display_data = "SELECT id, firstname, lastname, email FROM MyGuests order by id desc";
        $table_data = $conn->query($display_data);

        if ($table_data->num_rows > 0) {
            echo "<ul>";
            while ($row = $table_data->fetch_assoc()) {
                echo "<li>[", $row['id'], '] ', $row['firstname'], ' ', $row['lastname'], ' (', $row['email'], ")</li>";
            }
            echo "</ul>";
        } else {
            echo "<p>0 results</p>";
        }

        $conn->close();
    ?>
</body>

</html>