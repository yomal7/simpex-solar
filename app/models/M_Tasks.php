<?php
class M_Tasks
{
    private $db;
    public function __construct()
    {
        $this->db = new Database();
    }

    // public function getPosts(){
    //     $this->db->query('SELECT * FROM v_posts');
    //     $results= $this->db->resultSet();
    //     return $results;
    // }

    // public function getAllTasks(){
    //     $this->db->query('SELECT * FROM tasks ORDER BY created_at DESC');
    //     $results= $this->db->resultSet();
    //     return $results;
    // }


    public function getAllTasks()
    {
        $this->db->query('SELECT t.*, u.name as employee_name 
                      FROM tasks t 
                      LEFT JOIN employees e ON t.employee_id = e.employee_id
                      LEFT JOIN users u ON e.user_id = u.user_id
                      ORDER BY t.id');
        return $this->db->resultSet();
    }

    public function getTaskById($taskId) {
        $this->db->query('SELECT t.*, u.name as employee_name, u.phone, u.profile_picture, e.role as employee_role 
                  FROM tasks t
                  JOIN employees e ON t.employee_id = e.employee_id
                  JOIN users u ON e.user_id = u.user_id
                  WHERE t.id = :id');
        $this->db->bind(':id', $taskId);
        return $this->db->single();
    }

    // public function getPostById($postId){
    //     $this->db->query('SELECT * FROM v_posts WHERE post_id = :id');
    //     $this->db->bind(':id', $postId);
    //     $row= $this->db->single();
    //     return $row;
    // }

    // public function getTaskById($taskId)
    // {
    //     $this->db->query('SELECT * FROM tasks WHERE id = :id');
    //     $this->db->bind(':id', $taskId);
    //     $row = $this->db->single();
    //     return $row;
    // }

    public function create($data)
    {
        $this->db->query('INSERT INTO tasks (title, description, start_date, end_date, project_id, employee_id, status) VALUES(:title, :description, :start_date, :end_date, :project_id, :employee_id, :status)');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':status', $data['status']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // public function edit($data)
    // {
    //     $this->db->query('UPDATE posts SET title = :title, body = :body WHERE id = :id');
    //     $this->db->bind(':title', $data['title']);
    //     $this->db->bind(':body', $data['body']);
    //     $this->db->bind(':id', $data['post_id']);

    //     if ($this->db->execute()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function edit($data)
    {
        $this->db->query('UPDATE tasks SET title = :title, description = :description, start_date = :start_date, end_date = :end_date, project_id = :project_id, employee_id = :employee_id, status = :status WHERE id = :id');
        $this->db->bind(':title', $data['title']);
        $this->db->bind(':description', $data['description']);
        $this->db->bind(':start_date', $data['start_date']);
        $this->db->bind(':end_date', $data['end_date']);
        $this->db->bind(':project_id', $data['project_id']);
        $this->db->bind(':employee_id', $data['employee_id']);
        $this->db->bind(':status', $data['status']);
        $this->db->bind(':id', $data['id']);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    // public function delete($postId) {
    //     $this->db->query('DELETE FROM posts WHERE id = :id');
    //     $this->db->bind(':id', $postId);

    //     if ($this->db->execute()) {
    //         return true;
    //     } else {
    //         return false;
    //     }
    // }

    public function delete($taskId)
    {
        $this->db->query('DELETE FROM tasks WHERE id = :id');
        $this->db->bind(':id', $taskId);

        if ($this->db->execute()) {
            return true;
        } else {
            return false;
        }
    }

    public function getTasks($employee_id, $limit = 10, $offset = 0)
    {
        $this->db->query('SELECT * FROM tasks 
        WHERE employee_id = :employee_id
        ORDER BY end_date DESC
        LIMIT :limit OFFSET :offset');

        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);
        $this->db->bind('employee_id', $employee_id);

        return $this->db->resultSet();
    }

    public function getTotalProjectTasks($employee_id)
    {
        $this->db->query('SELECT COUNT(*) AS total FROM tasks WHERE employee_id = :employee_id');
        $this->db->bind(':employee_id', $employee_id);
        return $this->db->single()->total;
    }

    /***get tasks for technician***/

    public function getProjectTasks($employee_id, $limit = 10, $offset = 0)
    {
        $this->db->query('SELECT id, project_id, title, description, start_date, end_date, status, comment 
    FROM tasks 
    WHERE employee_id = :employee_id
    ORDER BY end_date ASC
    LIMIT :limit OFFSET :offset');

        $this->db->bind(':employee_id', $employee_id);
        $this->db->bind(':limit', $limit);
        $this->db->bind(':offset', $offset);

        return $this->db->resultSet();
    }

    public function getTotalProjectTasksById($employee_id)
    {
        $this->db->query('SELECT id, project_id, title, description, start_date, end_date, status, comment 
    FROM tasks 
    WHERE employee_id = :employee_id
    AND status != "completed"	
    ORDER BY end_date ASC');

        $this->db->bind(':employee_id', $employee_id);


        return $this->db->resultSet();
    }

    public function getProjectTasksById($taskId)
    {
        $this->db->query('SELECT * FROM tasks WHERE id = :id');
        $this->db->bind(':id', $taskId);

        return $this->db->single();
    }


    // public function getTotalProjectTasks($employee_id)
    // {
    //     $this->db->query('SELECT COUNT(*) AS total FROM tasks WHERE employee_id = :employee_id');
    //     $this->db->bind(':employee_id', $employee_id);
    //     return $this->db->single()->total;
    // }


    public function updateTaskComment($id, $comment)
    {
        $this->db->query('UPDATE tasks SET comment = :comment WHERE id = :id');
        $this->db->bind(':comment', $comment);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function updateTaskStatus($id, $status)
    {
        $this->db->query('UPDATE tasks SET status = :status WHERE id = :id');
        $this->db->bind(':status', $status);
        $this->db->bind(':id', $id);
        return $this->db->execute();
    }

    public function deleteTaskComment($taskId)
    {
        $this->db->query('UPDATE tasks SET comment = NULL WHERE id = :id');
        $this->db->bind(':id', $taskId);
        return $this->db->execute();
    }

    public function getTaskByStatus($status) {
        $this->db->query('SELECT * FROM tasks WHERE status = :status');
        $this->db->bind(':status', $status);
        return $this->db->resultSet();
    }

    public function getTaskCountByStatusForEmployee($status, $employeeId) {
        $this->db->query('SELECT COUNT(*) as count FROM tasks WHERE status = :status AND employee_id = :employee_id');
        $this->db->bind(':employee_id', $employeeId);
        $this->db->bind(':status', $status);
        return $this->db->single()->count;
    }

    public function getTaskStatus($taskId) {
        $this->db->query('SELECT status FROM tasks WHERE id = :id');
        $this->db->bind(':id', $taskId);
        return $this->db->single()->status;
    }
}
