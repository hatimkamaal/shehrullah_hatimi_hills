<?php

class ErrorlogCtrl extends Ctrl {
    public function get(Dao $dao) {
        $content = file_get_contents('error_log');
        echo '<form action="" method="post">
                <input type="submit" value="DELETE LOG">
            </form><br/><br/><hr/>';
        echo "<p>$content</p>";
    }

    public function post(Dao $dao) {
        unlink('error_log');
        $this->get($dao);
    }
}