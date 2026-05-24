    <?php
    class config{
        private $host = 'localhost';
        private $dbName = 'db_peliculas';
        private $user = 'root';
        private $password = '';
        
        function getHost(){
            return $this->host;
        }

        function getDbName(){   
            return $this->dbName;
        }

        function getUser(){
            return $this->user;
        }

        function getPassword(){
            return $this->password;
        }        
    }

    ?>