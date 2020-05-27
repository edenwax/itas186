<?php

require_once "ActiveRecord.php";
require_once "Database.php";


class Boat implements ActiveRecord {
	private $id;
	private $name;
	private $reg_num;
	private $length;
	private $image;
	private $user_id;

	function getId() { return $this->id; }
	function setId($id) { $this->id = $id; }
	function getName() { return $this->name; }
	function setName($name) { $this->name = $name; }
	function getRegNum() { return $this->reg_num; }
	function setRegNum($reg_num) { $this->reg_num = $reg_num; }
	function getLength() { return $this->length; }
	function setLength($length) { $this->length = $length; }
	function getImage() { return $this->image; }
	function setImage($image) { $this->image = $image; }
	function getUserId() { return $this->user_id; }
	function setUserId($user_id) { $this->user_id = $user_id; }


	public function save(): int {
		try {
			$db = Database::connect();

			$db->query("INSERT INTO boat (name,reg_num,length,image,user_id) 
            VALUES ('$this->name','$this->reg_num','$this->length','$this->image','$this->user_id')");

            $id = $db->lastInsertId();
            return $id;
		} catch (PDOException $e) {
			echo '<br />Error saving Boat: ' . $e->getMessage();
		}
	}

	public function delete(): void {
		try {
			$db = Database::connect();
			$id = $this->id;

			$query = "DELETE FROM `boat` WHERE `id` = :id";
			$stmt = $db->prepare($query);
			$stmt->bindParam('id', $id);
			$stmt->execute();
		} catch (PDOException $e) {
			echo '<br />Error deleting boat: ' . $e->getMessage();
		}
	}

	public function update(): void {
		try {
			$db = Database::connect();

            //echo '<br/>update() has been called.';

            $query = "UPDATE `boat` SET `name` = :name, `reg_num` = :reg_num, `length` = :length, `image` = :image, `user_id` = :user_id WHERE `id` = :id";
            $stmt = $db->prepare($query);

            //echo '<br/>Query passed';
            $stmt->bindParam('id', $this->id);
            $stmt->bindParam('name', $this->name);
            $stmt->bindParam('reg_num', $this->reg_num);
            $stmt->bindParam('length', $this->length);
            $stmt->bindParam('image', $this->image);
            $stmt->bindParam('user_id', $this->user_id);

            //echo $this->id . ' ' . $this->name . ' ' . $this->reg_num . ' ' . $this->length . ' ' . $this->image . ' ' . $this->user_id;

            if($stmt->execute()) {
            	echo '<br/>Boat has been updated. <a href="edit_boat.php?id=' . $this->id . '">Refresh to show changes.</a>';
            } else {
            	echo '<br/>Something failed';
            }

		} catch (PDOException $e) {
			echo '<br />Error saving Boat: ' . $e->getMessage();
		}
	}

	public static function find(int $id): ActiveRecord {
		try {
			$db = Database::connect();
			$result = $db->query("SELECT * FROM `boat` WHERE `id`= $id");
			if ($result->rowCount() > 0) {
				$boat = new Boat();
				$row = $result->fetch(PDO::FETCH_OBJ);

				$boat->setId($row->id);
				$boat->setName($row->name);
				$boat->setRegNum($row->reg_num);
				$boat->setLength($row->length);
				$boat->setImage($row->image);
				$boat->setUserId($row->user_id);

				return $boat;
			}
		} catch (PDOException $e) {
			echo '<br /><strong>Error finding boat</strong>' . $e->getMessage();
		}

		$boat = new Boat();
		$boat->setName('You have no boats currently docked.');
		return $boat;
	}

	public static function findAll(): array {
		try {
			$db = Database::connect();

			$result = $db->query("SELECT * FROM `boat`");
			$boats = $result->fetchAll(PDO::FETCH_CLASS, 'Boat');

			return $boats;
		} catch (PDOException $e) {
			echo '<br /><strong>Error finding boats</strong>' . $e->getMessage();
		}
	}
}