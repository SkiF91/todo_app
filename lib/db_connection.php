<?php
  class DbConnection {
    public $connection;

    function __construct() {
      $this->connection = new PDO("mysql:host=localhost;dbname=todo_app;charset=utf8",
                                  'root',
                                  'xTp68zw73',
                                  [PDO::ATTR_ERRMODE => PDO::ERRMODE_EXCEPTION,
                                   PDO::ATTR_DEFAULT_FETCH_MODE => PDO::FETCH_LAZY]);
    }

    function find_user_by_id($id) {
      $st = $this->connection->prepare('SELECT * FROM users WHERE id = ? LIMIT 1');
      $st->execute([$id]);
      $row = $st->fetch();
      if (!$row) { return false; }
      return $row;
    }
    function find_user_by_login($login) {
      $st = $this->connection->prepare('SELECT * FROM users WHERE LOWER(login) = ? LIMIT 1');
      $st->execute([strtolower($login)]);
      $row = $st->fetch();
      if (!$row) { return false; }
      return $row;
    }
    function set_login_attempts($user_login, $delay = 300) {
      $user_login = strtolower($user_login);
      $st = $this->connection->prepare('DELETE FROM login_attempts WHERE attempt < ?');
      $st->execute([date("Y-m-d H:i:s", strtotime("-$delay seconds"))]);
      $st = $this->connection->prepare('INSERT INTO login_attempts (user_login, attempt) VALUES (?, ?)');
      $st->execute([$user_login, date('Y-m-d H:i:s')]);
      $st = $this->connection->prepare('SELECT COUNT(1) as cnt FROM login_attempts WHERE user_login = ?');
      $st->execute([$user_login]);
      $row = $st->fetch();
      if (!$row) {
        return 0;
      }
      return $row->cnt;
    }

    function create_user($login, $password) {
      $st = $this->connection->prepare("INSERT INTO users (login, password) VALUES(?, ?)");
      return $st->execute([$login, $password]);
    }

    function paginated_todos($user_id, $page=1) {
      $st = $this->connection->prepare("SELECT COUNT(1) as cnt FROM todos WHERE user_id = ?");
      $st->execute([$user_id]);
      $row = $st->fetch();
      $count = 0;
      if ($row) { $count = $row->cnt; }

      if ($page < 1) { $page = 1; }
      $offset = ($page - 1) * 10;
      if ($offset > $count) {
        $offset = $count - ($count % 10);
        $page = ($offset / 10) + 1;
      }
      $st = $this->connection->prepare("SELECT * FROM todos WHERE user_id = ? LIMIT 10 OFFSET $offset");
      $st->execute([$user_id]);
      return ['rows' => $st, 'offset' => $offset, 'page' => $page, 'count' => $count];
    }

    function find_todo_by_id($id) {
      $st = $this->connection->prepare('SELECT * FROM todos WHERE id = ? LIMIT 1');
      $st->execute([$id]);
      $row = $st->fetch();
      if (!$row) { return false; }
      return $row;
    }

    function delete_todo_by_id($id) {
      $st = $this->connection->prepare('DELETE FROM todos WHERE id = ?');
      return $st->execute([$id]);
    }

    /// hmm, transaction... hmmm, NOOOOO, lets hardcore!!! we are strong!
    function create_or_update_todo($name, $todo, $items, $user) {
      if ($items) {
        foreach($items as $it) {
          if (!isset($it['name']) || !$it['name'] || !preg_match('/^[a-zA-Z0-9\x{0430}-\x{044F}\x{0410}-\x{042F}\s]{3,50}$/u', $it['name'])) {
            return ['Имя должно состоять только из букв и цифр и должно быть не короче 3 и не длиньше 50 символов', var_dump($items)];
          }
        }
      }
      if ($todo) {
        $st = $this->connection->prepare('UPDATE todos SET name = ? WHERE id = ?');
        $st->execute([$name, $todo->id]);
        $st = $this->connection->prepare('DELETE FROM todo_items WHERE todo_id = ?');
        $st->execute([$todo->id]);

        if ($items) {
          $sql = [];
          $params = [];
          foreach ($items as $it) {
            $params[] = $it['name'];
            $params[] = $todo->id;
            $params[] = $it['completed'];
            $sql[] = ' (?, ?, ?)';
          }
          $st = $this->connection->prepare('INSERT INTO todo_items (name, todo_id, completed) VALUES ' . join(',', $sql));
          $st->execute($params);
        }
        return $todo->id;
      }
      $st = $this->connection->prepare('INSERT INTO todos (name, user_id) VALUES (?, ?)');
      $st->execute([$name, $user->id]);
      return $this->connection->lastInsertId();
    }

    function find_todo_items_by_id($id) {
      $st = $this->connection->prepare('SELECT * FROM todo_items WHERE todo_id = ?');
      $st->execute([$id]);
      return $st;
    }
  }