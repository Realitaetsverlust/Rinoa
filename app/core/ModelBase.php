<?php

/**
 * Class ModelBase
 *
 * The base for all models. Contains almost all functionality like creating tables,
 * updating tables, selects, inserts - everything.
 *
 */
class ModelBase extends SuperConfig {
    protected $_tableName;
    protected $_columnNames;
    protected $_columns;
    protected $_db;

    const FETCH_ALL = 1;
    const FETCH_ROW = 2;
    const FETCH_COL = 3;
    const FETCH_ONE = 4;

    public function __construct() {
        parent::__construct();
        $this->_db = (new Database())->getDb();
    }

    /**
     * Executes a query and returns the result
     * @param string $sql
     * @return bool|array Returns the fetched array, or false on failure
     */
    public function query(string $sql, $mode = self::FETCH_ALL):array {
        try {
            switch($mode) {
                case self::FETCH_ALL:
                    return $this->_db->query($sql)->fetchAll();
                    break;
                case self::FETCH_ROW:
                    return $this->_db->query($sql)->fetchRow();
                    break;
                case self::FETCH_COL:
                    return $this->_db->query($sql)->fetchAll();
                    break;
                case self::FETCH_ONE:
                    return $this->_db->query($sql)->fetch();
                    break;
                default:
                    return $this->_db->query($sql)->fetchAll();
                    break;
            }
        } catch(Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Executes a simple SELECT-Statement
     *
     * @param string $where The where condition. If not set, no WHERE condition is used
     * @param array $cols The cols requests. If not set, it uses all cols (SELECT * FROM)
     * @return bool|array Returns the result of the select, or false on failure
     */
    public function select(array $where = array(), array $cols = array('*'), int $limit = null):array {
        if(empty($where)) {
            $where = '1';
        }

        $fieldPlaceholder = ':' . key($where);
        $fieldName = key($where);

        $sql = 'SELECT '.implode(', ', $cols).' FROM ' . $this->getTableName().' WHERE  {$fieldName} = {$fieldPlaceholder}';

        if($limit !== null) {
            $sql .= ' LIMIT {$limit}';
        }

        try {
            $stmt = $this->_db->prepare($sql);
            $stmt->execute($where);
            return $stmt->fetchAll();
        } catch(Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Executes a simple INSERT query.
     *
     * @param array $data The data that should be inserted
     * @param array|null $columns The cols that should be affected. If not set, it uses all cols
     * @return bool True on success, false on failure
     */
    public function insert(array $data):bool {
        $columnNames = array();
        $placeholders = array();

        foreach($data as $columnName => $d) {
            $columnNames[] = str_replace(':', '', $columnName);
        }

        foreach($data as $key => $value) {
            $placeholders[] = $key;
        }

        $columnNames = implode(',', $columnNames);
        $placeholders = implode(',', $placeholders);

        try {
            $pdoStatement = $this->_db->prepare("INSERT INTO {$this->_tableName} ({$columnNames}) VALUES ({$placeholders})");
            $pdoStatement->execute($data);
            return true;
        } catch(Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    public function update($data, $id) {
        $setSql = '';

        foreach($data as $key => $value) {
            if(!is_numeric($value)) {
                $value = $this->_db->quote($value, PDO::PARAM_STR);
            }
            $setSql[] = ' {$key} = {$value}';
        }

        $setSql = implode(',', $setSql);

        try {
            $this->_db->exec("UPDATE {$this->getTableName()} SET {$setSql} WHERE id = {$id};");
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    /**
     *
     * @param $where
     */
    public function delete($where) {
        try {
            $this->_db->exec("DELETE FROM {$this->_tableName} WHERE {$where}");
        } catch(Exception $e) {
            echo $e->getMessage();
        }
    }

    protected function _load($whereClause) {
        $result = json_decode(json_encode($this->select($whereClause)))[0];

        if(count($result) !== 0) {
            foreach(array_keys($this->getExpectedFieldNames()) as $fieldName) {
                $setterToBeCalled = 'set'.ucfirst($fieldName);
                $this->$setterToBeCalled($result->$fieldName);
            }

            $this->_existsInDatabase = true;
        }
    }

    public function save() {
        foreach(array_keys($this->getExpectedFieldNames()) as $fieldName) {
            if($fieldName === 'id') {
                continue;
            }
            $getterToBeCalled = 'get'.$fieldName;
            if($this->$getterToBeCalled() !== null) {
                $data[':'.$fieldName] = $this->$getterToBeCalled();
            }
        }

        if($this->_existsInDatabase) {
            return $this->update($data, $this->getId());
        } else {
            return $this->insert($data);
        }
    }

    public function truncateTable() {

    }

    protected function _getCurrentColNamesForTable():array {
        $pdoStatement = $this->_db->query("DESCRIBE {$this->_tableName};");
        return $pdoStatement->fetchAll();
    }

    /**
     * Returns the table name
     * @return string
     */
    public function getTableName():string {
        return $this->_tableName;
    }

    /**
     * Main function responsible for creating or updating tables in SetupTablesController
     * Does not return anything, since it's the executor
     */
    public function createTableForModel() {
        $tableName = $this->getTableName();

        if($this->_doesTableExists()) {
            $this->_applyChangesToTable();
        } else {
            $this->_createNewTable($this->getTableName());
        }
    }

    /**
     * Executes a simple SELECT statement to check if a table exists
     * @return bool True if table exists, false if not
     */
    protected function _doesTableExists():bool {
        try {
            $this->_db->query("SELECT 1 FROM {$this->getTableName()} LIMIT 1");
            return true;
        } catch (Exception $e) {
            echo $e->getMessage();
            return false;
        }
    }

    /**
     * Applies all the changes made to the table like dropping, adding and modifying cols
     * @return bool True on success, false on failure
     */
    protected function _applyChangesToTable():bool {
        //create a new dummy table
        $this->_createNewTable($dummyTableName = $this->getTableName().'_cloned');
        //get all the rows from the dummy table
        $dummyTableRows = $this->_db->query("DESCRIBE {$dummyTableName}")->fetchAll();
        //get allthe rows from the live table
        $liveTableRows = $this->_getCurrentColNamesForTable();
        //delete the dummy table right after since we don't need it anymore
        $this->_db->exec('DROP TABLE {$dummyTableName}');
        $dummyTableColNames = [];
        $liveTableColNames = [];
        $sql = '';

        //@TODO: Make this better. This looks like shit
        foreach($dummyTableRows as $dummyTableRow) {
            array_push($dummyTableColNames, $dummyTableRow['Field']);
        }

        foreach($liveTableRows as $liveTableRow) {
            array_push($liveTableColNames, $liveTableRow['Field']);
        }

        //Drop all the cols not in the dummy table anymore
        foreach($colsToDelete = array_diff($liveTableColNames, $dummyTableColNames) as $colToDelete) {
            try {
                $this->_db->exec("ALTER TABLE {$this->getTableName()} DROP {$colToDelete}");
            } catch(Exception $e) {
                echo "Failed while dropping column {$colToDelete}";
                echo $e->getMessage();
            }
        }

        foreach($dummyTableRows as $key => $dummyTableRow) {
            //First, check if the field exists in the live table
            if(($positionInLiveTable = $this->_getPositionOfFieldInLiveTable($dummyTableRow['Field'])) >= 0 ) {
                if($this->_isRowUnchanged($dummyTableRow, $positionInLiveTable)) {
                    //Check if the row is unchanged. If yes, do nothing
                    $operation = null;
                } else {
                    //if it is changed, set the operation to MODIFY
                    $operation = 'MODIFY';
                }
            } else {
                //if the position in live table returns -1, it does not exist there. Therefore, ADD the column
                $operation = 'ADD';
            }

            if($operation !== null) {
                try {
                    $sql = "ALTER TABLE {$this->getTableName()} {$operation} COLUMN {$dummyTableRow['Field']}";
                    $sql .= " {$dummyTableRow['Type']} " . (isset($dummyTableRow['Default']) ? "DEFAULT {$dummyTableRow['Default']} " : '') . $dummyTableRow['Extra'] ;
                    $this->_db->exec($sql);
                } catch (Exception $e) {
                    echo "{$operation} for {$dummyTableRow['Field']} failed!<br>";
                    echo "Query used: {$sql}";
                    return false;
                }
            }
        }

        return true;
    }

    /**
     * Returns the position
     * @param $fieldName
     * @return int
     */
    protected function _getPositionOfFieldInLiveTable($fieldName):int {
        $liveTableRows = $this->_getCurrentColNamesForTable();

        foreach($liveTableRows as $position => $liveTableRow) {
            if($liveTableRow['Field'] === $fieldName) {
                return $position;
            }
        }

        return -1;
    }

    protected function _isRowUnchanged($dummyRow, $positionInLiveTable):bool {
        $liveTableRows = $this->_getCurrentColNamesForTable();

        if(count(array_diff($dummyRow, $liveTableRows[$positionInLiveTable])) >= 1) {
            return false;
        }
        return true;
    }

    protected function _doesFieldStillExistInDummyTable($fieldName) {
        $liveTableRows = $this->_getCurrentColNamesForTable();
        foreach($liveTableRows as $liveTableRow) {
            if($liveTableRow['Field'] === $fieldName) {
                return true;
            }
        }
        return false;
    }

    protected function _createNewTable($tableName):bool {
        $sql = 'CREATE TABLE {$tableName} (';

        foreach($this->getExpectedFieldNames() as $fieldName => $fieldSettings) {
            $sql .= "{$fieldName} {$fieldSettings['datatype']} {$fieldSettings['attribute']} {$fieldSettings['index']}";
            $sql .= ($fieldSettings['auto_increment'] === true ? ' AUTO_INCREMENT ' : '');
            $sql .= (isset($fieldSettings['default']) ? " DEFAULT {$fieldSettings['default']}" : '');
            $sql .= ',';
        }

        //pop the comma from the string since it throws an sql error
        //way easier than checking if the current element is the last
        $sql = rtrim($sql,',');
        $sql .= ')';

        try {
            $this->_db->exec($sql);
            return true;
        } catch(Exception $e) {
            echo 'Creation of {$tableName} failed!';
            echo $e->getMessage();
            return false;
        }
    }

    protected function _alterExistingTable():bool
    {

    }

    public function getExpectedFieldNames() {
        return $this->_expectedFieldNames;
    }

    protected function _checkIfTableExists(string $tableName):bool {
        try {
            $this->_db->query('SELECT 1 FROM {$tableName} LIMIT 1');
            return true;
        } catch(Exception $e) {
            echo 'Table {$tableName} does not exist!';
            echo $e->getCode().': '.$e->getMessage();
            return false;
        }
    }
}