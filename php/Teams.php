<?php

class Teams {

    static public function findAll()
    {
        $result = pg_query('SELECT * FROM ' . Config::$TBL . ' ORDER BY num');

        $teams = array();
        while($row = pg_fetch_object($result)) {
            $teams[] = new Team($row->id, $row->num, $row->name, $row);
        }

        return $teams;
    }

    static public function findAllByRank($order, $untill = 0)
    {
        if ($untill == 0)
            $untill = key(array_slice(Config::$ROUNDS, -1, 1, TRUE));

        $orderQuery = '';
        foreach(Config::$ROUNDS as $num => $round) {
            if ($num > $untill)
                break;
            $orderQuery .= ' round' . $num . ' + ';
        }
        $orderQuery = trim($orderQuery, ' + ');

        $separation = Config::$HASSEPARATION ? ', ABS(separation - ' . Config::$SEPARATIONQUESTION . ') ' . ($order == 'DESC' ? 'ASC' : 'DESC') : '';

        $result = pg_query('SELECT * FROM ' . Config::$TBL . ' ORDER BY (' . $orderQuery . ') ' . $order . ' ' . $separation . ' ');

        $teams = array();
        while($row = pg_fetch_object($result)) {
            $teams[] = new Team($row->id, $row->num, $row->name, $row);
        }

        return $teams;
    }

    static public function findOneById($id)
    {
        $result = pg_query('SELECT * FROM ' . Config::$TBL . ' WHERE id = ' . $id);

        while($row = pg_fetch_object($result)) {
            $team = new Team($row->id, $row->num, $row->name, $row);
        }

        return $team;
    }

    static public function add(Team $team)
    {
        pg_query('INSERT INTO ' . Config::$TBL . ' (num, name) VALUES(' . $team->getNumber() . ', \'' . pg_escape_string($team->getName()) . '\')');
    }

    static public function remove(Team $team)
    {
        pg_query('DELETE FROM ' . Config::$TBL . ' WHERE id = ' . $team->getId());
    }

    static public function save(Team $team)
    {
        pg_query('UPDATE ' . Config::$TBL . ' SET num=' . $team->getNumber() . ', name=\'' . pg_escape_string($team->getName()) . '\' WHERE id = ' . $team->getId());
    }
}