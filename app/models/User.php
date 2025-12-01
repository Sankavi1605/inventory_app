<?php

class User
{
    private $db;

    public function __construct()
    {
        $this->db = new Database();
    }

    public function register($data)
    {
        $this->db->query('INSERT INTO users (username, email, password, first_name, last_name, phone)
                  VALUES (:username, :email, :password, :first_name, :last_name, :phone)');

        $this->db->bind(':username', $data['username']);
        $this->db->bind(':email', $data['email']);
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':phone', $data['phone']);

        if ($this->db->execute()) {
            return $this->db->lastInsertId();
        }

        return false;
    }

    public function login($username, $password)
    {
        $this->db->query('SELECT * FROM users WHERE username = :username OR email = :username');
        $this->db->bind(':username', $username);
        $row = $this->db->single();

        if ($row && password_verify($password, $row->password)) {
            return $row;
        }
        return false;
    }

    public function findUserByEmailOrUsername($email, $username)
    {
        $this->db->query('SELECT * FROM users WHERE email = :email OR username = :username');
        $this->db->bind(':email', $email);
        $this->db->bind(':username', $username);
        $row = $this->db->single();
        return $this->db->rowCount() > 0 ? $row : false;
    }

    public function getUserById($id)
    {
        $this->db->query('SELECT * FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->single();
    }

    public function getAllUsers()
    {
        $this->db->query('SELECT id, username, email, first_name, last_name, phone, last_login, created_at
                          FROM users ORDER BY created_at DESC');
        return $this->db->resultSet();
    }

    public function updateUser($data)
    {
        $this->db->query('UPDATE users SET
                  email = :email,
                  first_name = :first_name,
                  last_name = :last_name,
                  phone = :phone
                  WHERE id = :id');

        $this->db->bind(':email', $data['email']);
        $this->db->bind(':first_name', $data['first_name']);
        $this->db->bind(':last_name', $data['last_name']);
        $this->db->bind(':phone', $data['phone']);
        $this->db->bind(':id', $data['id']);

        return $this->db->execute();
    }

    public function updatePassword($data)
    {
        $this->db->query('UPDATE users SET password = :password WHERE id = :id');
        $this->db->bind(':password', $data['password']);
        $this->db->bind(':id', $data['id']);
        return $this->db->execute();
    }

    public function deleteUser($id)
    {
        $this->db->query('DELETE FROM users WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateLastLogin($id)
    {
        $this->db->query('UPDATE users SET last_login = NOW() WHERE id = :id');
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function setPasswordResetToken($email, $token, $expires)
    {
        $this->db->query('UPDATE users SET password_reset_token = :token, password_reset_expires = :expires WHERE email = :email');
        $this->db->bind(':token', $token);
        $this->db->bind(':expires', $expires);
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }

    public function getUserByResetToken($token)
    {
        $this->db->query('SELECT * FROM users WHERE password_reset_token = :token AND password_reset_expires > NOW()');
        $this->db->bind(':token', $token);
        return $this->db->single();
    }

    public function clearPasswordResetToken($email)
    {
        $this->db->query('UPDATE users SET password_reset_token = NULL, password_reset_expires = NULL WHERE email = :email');
        $this->db->bind(':email', $email);
        return $this->db->execute();
    }
}
