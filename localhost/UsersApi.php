<?php
require_once 'Api.php';
require_once 'Db.php';
require_once 'Users.php';

class UsersApi extends Api
{
    public $apiName = 'users';
    /**
     * Метод POST
     * http://ДОМЕН/users/ + параметры запроса name, email
     */
    public function createAction()
    {
        $mysqli = new mysqli("localhost", "root", "root", "restdb");
        $firstName = $this->requestParams['firstName'] ?? '';
        $lastName = $this->requestParams['lastName'] ?? '';
        $password = password_hash($this->requestParams['password'] ?? '', PASSWORD_DEFAULT);
        $email = $this->requestParams['email'] ?? '';

        $sql = "INSERT INTO users (email,password, first_name, last_name ) VALUES ('$email', '$password', '$firstName', '$lastName')";
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if ($mysqli->connect_error) 
            {
                echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }

            if($mysqli->query($sql)===TRUE)
            {
                return $this->response('User created.', 200);
            }
            else 
            {
                return $this->response('Create error.', 200);
            }
        }
        else
        {
            return $this->response('Invalid Email', 400);
        }
    }

    /**
     * Метод PUT
     * http://ДОМЕН/users/userId/ + параметры запроса name, email
     */
    public function updateAction()
    {
        $mysqli = new mysqli("localhost", "root", "root", "restdb");
        $parse_url = parse_url($this->requestUri[0]);
        $userId = $parse_url['path'] ?? null;
        $firstName = $this->requestParams['firstName'] ?? '';
        $lastName = $this->requestParams['lastName'] ?? '';
        $password = password_hash($this->requestParams['password'] ?? '', PASSWORD_DEFAULT);
        $email = $this->requestParams['email'] ?? '';
        $sql = "UPDATE users SET email='$email', password='$password', first_name='$firstName', last_name='$lastName' WHERE id='$userId'";

        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if ($mysqli->connect_error)
            {
                echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }

            if($mysqli->query($sql)===TRUE)
            {
                return $this->response('User updated.', 200);
            }
            else 
            {
                return $this->response("Update error", 400);
            }
        }
        else
        {
            return $this->response('Invalid Email', 400);
        }
        
    }

    /**
     * Метод DELETE
     * http://ДОМЕН/users/userId
     */
    public function deleteAction()
    {
        $mysqli = mysqli_connect("localhost", "root", "root");
        $parse_url = parse_url($this->requestUri[0]);
        $userId = $parse_url['path'] ?? null;
        $sql = "DELETE FROM users WHERE id='$userId'";
        
        if(filter_var($email, FILTER_VALIDATE_EMAIL))
        {
            if ($mysqli->connect_error) 
            {
                echo "Не удалось подключиться к MySQL: (" . $mysqli->connect_errno . ") " . $mysqli->connect_error;
            }

            if($mysqli->query($sql)===TRUE)
            {
                return $this->response('User deleted.', 200);
            }
            else
            {
                return $this->response("Delete error", 500);
            }
        }
        else
        {
            return $this->response('Invalid Email', 400);
        }    
    }
}
?>