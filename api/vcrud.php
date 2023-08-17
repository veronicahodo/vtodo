<?php

// vcrud.php

// Basic class for handing CRUD database interactions.  Designed to
// be extended for whatever the intended use would be

// Version 1.0.0

/* Changelog:
	1.0.1: updated read() to have an optional parameter to use the OR operator rathern than AND
	1.0.0: Initial fully "working" release
*/
class VCRUD
{
	private $connection;	// Stores the PDO connection so we don't have to pass it every time
	private $maxRows = 20000;

	public function __construct($dbUser, $dbPass, $dbHost, $dbName)
	{
		// Just calls the connect function. Did it this way in case I ever wanted to change
		// databases
		$this->connect($dbUser, $dbPass, $dbHost, $dbName);
	}

	private function connect($dbUser, $dbPass, $dbHost, $dbName)
	{
		// connects to a mysql/mariadb database
		$dsn = "mysql:host={$dbHost};dbname={$dbName}";
		$this->connection  = new PDO($dsn, $dbUser, $dbPass);
	}

	private function conditionsToStrings($conditions)
	{
		// turns the datasets [column,operand,value] into the SQL string formatted
		// markup
		$working = [];
		foreach ($conditions as $condition) {
			[$column, $operator, $value] = $condition;
			$workingStr = "{$column} {$operator} ";
			// if it's a LIKE operand we have to add the % to either side
			if (strtolower($operator) === 'like') {
				$workingStr .= "%{$value}%";
			} else {
				$workingStr .= $value;
			}
			$working[] = $workingStr;
		}
		return $working;
	}

	public function create($table, $fields)
	{
		// inserts a single row into the database. Perhaps in the future it will
		// support multi row but for now until I need it, this is fine
		$columns = array_keys($fields);
		$placeholders = ':' . implode(',:', $columns);
		$sql = "INSERT INTO `{$table}` (" . implode(',', $columns) . ") VALUES ({$placeholders})";
		$stmt = $this->connection->prepare($sql);
		$stmt->execute($fields);
		return $this->connection->lastInsertId();
	}

	public function read($table, $conditions,$orOperand = false)
	{
		// reads up to 20000 rows and returns them based on conditions. 
		// Conditions are formatted [column,operand,value]
		$strConditions = $this->conditionsToStrings($conditions);
		if ($orOperand) {
			$sql = "SELECT * FROM `{$table}` WHERE (" . implode(' OR ', $strConditions) . ") LIMIT ". $this->maxRows;
		} else {
			$sql = "SELECT * FROM `{$table}` WHERE (" . implode(' AND ', $strConditions) . ") LIMIT " . $this->maxRows;
		}
		$stmt = $this->connection->prepare($sql);
		$stmt->execute();
		$return = [];
		while ($line = $stmt->fetch(PDO::FETCH_ASSOC)) {
			$return[] = $line;
		}
		return $return;
	}

	public function update($table, $fields, $conditions)
	{
		// upates up to 20000 rows with the data from fields.
		// Conditions are formated based on [column,operand,value]
		$strConditions = $this->conditionsToStrings($conditions);
		$frames = [];
		foreach (array_keys($fields) as $column) {
			$frames[] = "{$column}=:{$column}";
		}
		$sql = "UPDATE `{$table}` SET " . implode(',', $frames) . " WHERE (" . implode(' AND ', $strConditions) . ") LIMIT " . $this->maxRows;
		$stmt = $this->connection->prepare($sql);
		$stmt->execute($fields);
	}

	public function delete($table, $conditions)
	{
		// deletes up to 20000 rows that meet the conditions
		// Conditions are formatted based on [colum,operand,value]
		$strConditions = $this->conditionsToStrings($conditions);
		$sql = "DELETE FROM `{$table}` WHERE (" . implode(' AND ', $strConditions) . ") LIMIT " . $this->maxRows;
		$stmt = $this->connection->prepare($sql);
		$stmt->execute();
	}

	public function close()
	{
		// as for good cleanup,this should be called before exiting
		$this->connection = null;
	}
}
