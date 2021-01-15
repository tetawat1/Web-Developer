<?php
class dbconnect
{
    public $servername = '202.28.34.197';
    public $username = 'stu62011212109';
    public $password = '62011212109@csmsu';
    public $dbname = 'stu62011212109';
    public $conn;
    public $pass;

    public function db_start()
    {
        $this->conn = new mysqli($this->servername, $this->username, $this->password, $this->dbname);
        if ($this->conn->connect_error) {
            die($this->console_log('Connect fail' . $this->conn->connect_error));
        } else {
            $this->console_log('Connect completed');
        }
    }

    public function login($username, $password)
    {
        $stmt = $this->conn->prepare("select * from admin where username=?");
        $stmt->bind_param('s', $username);
        $stmt->execute();

        $result = $stmt->get_result();
        if ($result->num_rows == 1) {
            $row = $result->fetch_assoc();
            $this->pass = $row['password'];
        } else {
            $this->pass =  "";
        }

        if (password_verify($password, $this->pass)) {
            header("Location: edit.php");
        } else {
            echo "<script>alert('ชื่อผู้ใช้หรือรหัสผ่านผิด');</script>";
            header("Location: index.html");
            $this->console_log("Login Faile");
        }
    }

    public function show()
    {
        $sql = "select * from member_book";
        $result = $this->conn->query($sql);

        if ($result->num_rows >= 0) {
?>
            <?php
            while ($row = $result->fetch_assoc()) {
            ?>
                <tr>
                    <td>
                    </td>
                    <td><?= $row["ID"] ?></td>
                    <td><?= $row["fristName"] ?></td>
                    <td><?= $row["lastName"] ?></td>
                    <td><?= $row["nickName"] ?></td>
                    <td><?= $row["phone"] ?></td>
                    <td><?= $row["facebook_url"] ?></td>
                    <td><button id='<?php $row["ID"] ?>'></button></td>
                    <td><button id='<?php $row["ID"] ?>'><a href="delete.php? id=<?= $row["ID"] ?>">ลบ</a></button></td>
                    <!-- <form action="insert.php" method="POST">
                        <td>
                            <a href="#editEmployeeModal" class="edit" name='ID' id='<?php $row["ID"] ?>' data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Edit">&#xE254;</i></a>
                            <a href="#deleteEmployeeModal" class="delete" name='ID' id='<?php $row["ID"] ?>' data-toggle="modal"><i class="material-icons" data-toggle="tooltip" title="Delete">&#xE872;</i></a>
                        </td>
                    </form> -->

                </tr>
<?php
            }
        }
    }

    public function update($fname, $lname, $nname, $phone, $fb)
    {
    }
    public function delete($ID)
    {
        $stmt = $this->conn->prepare('delete FROM member_book WHERE ID=?');
        $stmt->bind_param('i', $ID);
        $stmt->execute();
        $res = $stmt->affected_rows;
        echo $res . '';
        if ($res <= 0) {
            echo "<script>alert('Insert FAIL');</script>";
        } else {
            echo "<script>alert('Insert Succes');</script>";
        }
    }

    public function insert($fname, $lname, $nname, $phone, $fb)
    {
        $stmt = $this->conn->prepare('insert into member_book(fristName, lastName, nickName, phone, facebook_url) VALUES (?,?,?,?,?)');
        $stmt->bind_param('sssis', $fname, $lname, $nname, $phone, $fb);
        $stmt->execute();
        $res = $stmt->affected_rows;
        echo $res . '';
        if ($res <= 0) {
            echo "<script>alert('Insert FAIL');</script>";
        } else {
            header("Location: edit.php");
            echo "<script>alert('Insert Succes');</script>";
        }
    }

    public function console_log($output, $with_script_tags = true)
    {
        $js_code = 'console.log(' . json_encode($output, JSON_HEX_TAG) .
            ');';
        if ($with_script_tags) {
            $js_code = '<script>' . $js_code . '</script>';
        }
        echo $js_code;
    }
}



?>