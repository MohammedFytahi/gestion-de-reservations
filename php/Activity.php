<?php
class Activity {
    private $id;
    private $name;
    private $type;
    private $description;

    public function __construct($id, $name, $type, $description) {
        $this->id = $id;
        $this->name = $name;
        $this->type = $type;
        $this->description = $description;
    }

    public function getId() {
        return $this->id;
    }

    public function getName() {
        return $this->name;
    }

    public function getType() {
        return $this->type;
    }

    public function getDescription() {
        return $this->description;
    }
}
?>
