<?php
/**
 * User Model
 */

class UserModel
{
    private $db;

    public function __construct()
    {
        $this->db = Database::getInstance()->getConnection();
    }

    /**
     * Find user by email
     */
    public function findByEmail($email)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE email = ?");
        $stmt->execute([$email]);
        return $stmt->fetch();
    }

    /**
     * Find user by ID
     */
    public function findById($id)
    {
        $stmt = $this->db->prepare("SELECT * FROM users WHERE id = ?");
        $stmt->execute([$id]);
        return $stmt->fetch();
    }

    /**
     * Create a new user
     */
    public function create($data)
    {
        $stmt = $this->db->prepare(
            "INSERT INTO users (email, password, full_name, company_name, phone, role) 
             VALUES (?, ?, ?, ?, ?, ?)"
        );
        $stmt->execute([
            $data['email'],
            password_hash($data['password'], PASSWORD_DEFAULT),
            $data['full_name'],
            $data['company_name'] ?? null,
            $data['phone'] ?? null,
            $data['role']
        ]);
        return $this->db->lastInsertId();
    }

    /**
     * Update user profile
     */
    public function update($id, $data)
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET full_name = ?, company_name = ?, phone = ?, updated_at = NOW() WHERE id = ?"
        );
        return $stmt->execute([
            $data['full_name'],
            $data['company_name'] ?? null,
            $data['phone'] ?? null,
            $id
        ]);
    }

    /**
     * Get all users (admin)
     */
    public function getAll($role = null)
    {
        $sql = "SELECT * FROM users";
        $params = [];
        if ($role) {
            $sql .= " WHERE role = ?";
            $params[] = $role;
        }
        $sql .= " ORDER BY created_at DESC";
        $stmt = $this->db->prepare($sql);
        $stmt->execute($params);
        return $stmt->fetchAll();
    }

    /**
     * Verify password
     */
    public function verifyPassword($password, $hash)
    {
        return password_verify($password, $hash);
    }

    /**
     * Toggle user active status
     */
    public function toggleActive($id)
    {
        $stmt = $this->db->prepare("UPDATE users SET is_active = NOT is_active WHERE id = ?");
        return $stmt->execute([$id]);
    }

    /**
    * Update a single field
    */
    public function updateSingleField($id, $field, $value)
    {
        $allowed = ['full_name', 'email', 'phone', 'company_name'];

        if (!in_array($field, $allowed)) {
            return false;
        }

        $sql = "UPDATE users SET $field = ?, updated_at = NOW() WHERE id = ?";
        $stmt = $this->db->prepare($sql);

        return $stmt->execute([$value, $id]);
    }

    /**
     * Update user password
     */
    public function updatePassword($id, $hashedPassword)
    {
        $stmt = $this->db->prepare(
            "UPDATE users SET password = ?, updated_at = NOW() WHERE id = ?"
        );

        return $stmt->execute([$hashedPassword, $id]);
    }
}
