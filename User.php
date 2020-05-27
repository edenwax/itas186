<?php

require_once "Database.php";
require_once "ActiveRecord.php";
require_once "Boat.php";

class User implements ActiveRecord {
	private $id;
	public $first_name;
	public $last_name;
	public $address;
	public $phone_num;
	public $username;
	public $password;
	public $user_type;

	public function getId() { return $this->id; }
	public function setId($id) { $this->id = $id; }

	public function getFirstName() { return $this->first_name; }
	public function setFirstName($first_name) { $this->first_name = $first_name; }

	public function getLastName() { return $this->last_name; }
	public function setLastName($last_name) { $this->last_name = $last_name; }

	public function getAddress() { return $this->address; }
	public function setAddress($address) { $this->address = $address; }

	public function getPhoneNum() { return $this->phone_num; }
	public function setPhoneNum($phone_num) { $this->phone_num = $phone_num; }

	public function getEmail() { return $this->email; }
	public function setEmail($email) { $this->email = $email; }

	public function getUsername() { return $this->username; }
	public function setUsername($username) { $this->username = $username; }

	public function getPassword() { return $this->password; }
	public function setPassword($password) { $this->password = $password; }

	public function getUserType() { return $this->user_type; }
	public function setUserType($user_type) { $this->user_type = $user_type; }

	public function save(): int {
        try {
            $db = Database::connect();

            $checkusr = $db->prepare("SELECT `username` FROM `user` WHERE `username` = :username");
            $checkusr->bindParam('username', $this->username);
            $checkusr->execute();

            if($checkusr->rowCount() > 0) {
            	echo 'Username already exists.';
            	$error = 1;
            	return $error;
            } else {
                $db->query("INSERT INTO user (first_name,last_name,address,phone_num,email,username,password,user_type) 
                VALUES ('$this->first_name','$this->last_name','$this->address','$this->phone_num', '$this->email', '$this->username', '$this->password', '$this->user_type')");

                echo '<h3>Account created.</h3>';

                $id = $db->lastInsertId();
                return $id;
            }
        } catch (PDOException $e) {
            echo "<br>Error saving Owner: " . $e->getMessage();
        }

    }

	public static function checkUser($username, $password) {
		try {
			$db = Database::connect();
	 		$db->setAttribute(PDO::ATTR_ERRMODE, PDO::ERRMODE_EXCEPTION);

	   		$md5password = md5($password);

	   		$result = $db->query("SELECT * FROM user WHERE username='$username' AND password='$md5password'");
	   		if ($result->rowCount() > 0) {
	    		$user = new User();

	  			$row = $result->fetch(PDO::FETCH_OBJ);
	  			$user->setId($row->id);
	  			$user->setFirstName($row->first_name);
	  			$user->setLastName($row->last_name);
	  			$user->setAddress($row->address);
	  			$user->setPhoneNum($row->address);
	  			$user->username = $row->username;
	  			$user->user_type = $row->user_type;

	 			return $user;
	 		}

	 		return NULL;
		} catch (PDOException $e) {
			echo "<br>Error checking username and password combo: " . $e->getMessage();
	 	}
	}

	public function delete(): void {
		try {
			$db = Database::connect();
			$id = $this->id;

			$query = "DELETE FROM `user` WHERE `id` = :id";
			$stmt = $db->prepare($query);
			$stmt->bindParam('id', $id);
			$stmt->execute();
		} catch (PDOException $e) {
			echo '<br />Error deleting user: ' . $e->getMessage();
		}
	}

	public function update(): void {
		try {
			$db = Database::connect();

			$boat = $db->exec("UPDATE `user` SET first_name='$this->first_name', last_name='$this->last_name', address='$this->address', phone_num='$this->phone_num', email='$this->email', username='$this->username', password='$this->password', user_type='$this->user_type' WHERE id=$this->id");
			echo 'User has been updated.';
			date_default_timezone_set("America/Vancouver");
			echo '<br/><span class="mini-date">' . date("m/d/Y h:ia") . '</span><br/>';
			echo '<br/><i class="fas fa-sync"></i> <a href="profile.php"> Refresh the page to show changes</a>';

		} catch (PDOException $e) {
			echo '<br />Error deleting user: ' . $e->getMessage();
		}
	}

	public static function find(int $id): ActiveRecord {
        try {
            $db = Database::connect();
            $result = $db->query("SELECT * FROM `user` WHERE `id` = $id");
            if ($result->rowCount() > 0) {
                $user = new User();

                $row = $result->fetch(PDO::FETCH_OBJ);
                $user->setId($row->id);
                $user->setFirstName($row->first_name);
                $user->setLastName($row->last_name);
                $user->setAddress($row->address);
                $user->setPhoneNum($row->phone_num);
                $user->setEmail($row->email);
                $user->setUsername($row->username);
                $user->setPassword($row->password);
                $user->setUserType($row->user_type);

                return $user;
            }
        } catch (PDOException $e) {
            echo "<br>Error saving customer: " . $e->getMessage();
        }
		// return an empty new user
		$user = new User();
		$user->setFirstName('User not found');
		return $user;
    }

	public static function findAll(): array {
		try {
			$db = Database::connect();

			$result = $db->query("SELECT * FROM `user`");
			$user = $result->fetchAll(PDO::FETCH_CLASS, 'User');

			return $user;
		} catch (PDOException $e) {
			echo '<br /><strong>Error finding users</strong>' . $e->getMessage();
		}
	}

}