<?php
// 1. koneksi ke db 
$mysqli = mysqli_connect(
	"YOUR_DB_HOST",
	"YOUR_DB_USER",
	"YOUR_DB_PASSWORD",
	"DB_NAME"
);


function execute($sql)
{
	global $mysqli;
	if ($result = $mysqli->query($sql))
		return $result;
	else {
		die($mysqli->error);
	}
}

function select($table, $where = '')
{
	$sql = "SELECT * FROM $table " . ($where == '' ? '' : ' WHERE ' . $where);
	if (strtolower(substr($table, 0, 6)) == 'select') {
		$sql = $table;
	}
	$result = execute($sql);
	return $result->fetch_all(MYSQLI_ASSOC);
}

function selectOne($sql, $where = '')
{
	$data = select($sql, $where);
	if (isset($data[0])) {
		return $data[0];
	} else {
		return [];
	}
}

function save($table, $data, $where = '')
{
	if ($where == '')
		$sql = "INSERT INTO $table SET ";
	else
		$sql = "UPDATE $table SET ";
	$field = [];
	foreach ($data as $k => $y) {
		$field[] = "$k = '$y'";
	}
	$sql .= join(',', $field) . ($where == '' ? '' : ' WHERE ' . $where);
	//echo $sql;
	execute($sql);
}

function hapus($table, $where = "")
{
	$sql  = "DELETE FROM $table";
	$sql .= ($where == '' ? '' : ' WHERE ' . $where);
	execute($sql);
}

function sanitizeInput($value)
{
	return htmlspecialchars($value, ENT_QUOTES);
}
