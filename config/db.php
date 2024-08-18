<?php
function getDbConnection() {
    return new PDO('mysql:host=localhost;dbname=problemone', 'root', '');
}
