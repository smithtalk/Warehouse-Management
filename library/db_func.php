<?php
// 1. koneksi ke db 
$mysqli = mysqli_connect(
	"localhost",   //host db
	"admin", 	   //user db
	"admin", 	   //pass db
	"otoko"		   //nama db
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

function table($title, $a, $link, $pk)
{
	echo '<table class="table" data-role="table" 
				data-search-min-length="3"
				data-cls-table-top="row flex-nowrap"
				data-cls-search="cell-md-8"
				data-cls-rows-count="cell-md-4"
		><thead><tr>';
	echo '<th>No</th>';
	foreach ($title as $l) {
		echo '<th>' . $l . '</th>';
	}
	echo '<th>Aksi</th>';
	echo '</tr></thead><tbody>';
	$c = 1;
	foreach ($a as $k => $value) {
		echo '<tr><td>' . ($c++) . '</td>';
		foreach ($value as $s => $v) {
			if ($s != $pk)
				echo '<td>' . $v . '</td>';
		}

		echo '<td>';
		$link_template = '<a href="%s&id=%s" class="button primary" style="margin-left:4px;">%s</a>'; //template untuk link
		foreach ($link as $sl => $vl) {
			echo sprintf($link_template, $vl['url'], $value[$pk], $vl['label']);
		}
		echo '</td>';
		echo '</tr>';
	}
	echo '</tbody></table>';
}

function simpan_file(
	$nama_attr, 	// nama attribute
	$lokasi_simpan, // folder lokasi file
	$filter			// filter extension, array  
) {
	$nama_file = '';
	if (isset($_FILES[$nama_attr])) {
		// tentukan foldernya:
		$lokasi_simpan = $lokasi_simpan;
		// ambil nama file 
		$file = basename($_FILES[$nama_attr]["name"]);
		//nama extension file 
		$ext = strtolower(pathinfo($file, PATHINFO_EXTENSION));
		// jika mp3, simpan, jika tidak, tolak
		if (in_array($ext, $filter)) {
			$tmp_name = $_FILES[$nama_attr]["tmp_name"];
			move_uploaded_file($tmp_name, "$lokasi_simpan/$file");
			// jika ada filenya, baru disimpan						
			$nama_file = "$lokasi_simpan/$file";
			//print_r($_FILES);
		}
	}

	return $nama_file;
}

function form_data($label, $name, $data, $type = 'text')
{
	$value = isset($data[$name]) ? $data[$name] : '';
	return form_input($label, 'data[' . $name . ']', $value, $type);
}

function form_input($label, $name, $value, $type = 'text')
{
	$type = $type == '' ? 'text' : $type;
	if ($type == "text") {
		$string = '
		<input type="%s" name="%s" value="%s" data-role="materialinput"
		data-label="%s"
		placeholder="Harap Masukkan Data %s">
		<br/>
		';
		return sprintf($string, $type, $name, $value, $label, $label);
	} else if ($type == "submit") {
		$string = '
		<input type="%s" name="%s" value="%s" class="button primary" data-label="%s %s" style="margin-top:10px;">
		<br/>
		';
		return sprintf($string, $type, $name, $value, $label, $label);
	} else {
		$string = '
		<input type="%s" data-role="%s" name="%s" value="%s" data-label="%s" style="margin-top:10px;">
		<br/>
		';
		return sprintf($string, $type, $type, $name, $value, $label);
	}
}

function form_file($label, $name, $type = 'text')
{
	// ekstrak nama dari data[namaField]
	$name = substr($name, strpos($name, '[') + 1, -1);
	$string = '
	<div style="margin-top:10px">
		<input type="file" data-role="file" data-prepend="Pilih %s:" name="%s">
	</div>
	';
	return sprintf($string, $label, $name);
}

/* ****************************************************** *
 * fungsi form_select dipakai untuk menampilkan drop down *
 * $label : nama label 									  *
 * $name  : attribut pada name  						  *
 * $data  : nama field tabel     						  *
 * $params: data yang ditampilkan bila array, 			  *
 *          bila string, akan diambil dari query SQL      *
 *          SQL memiliki sintaks sbb:					  *
 *          SELECT [pk] as value,[textfield] as name	  *
 *          FROM [table]                             	  *
 * ****************************************************** */
function form_select($label, $name, $data, $params)
{
	$xid = $name;
	if (substr($name, -1) == ']') {
		$xid = substr($name, strpos($name, '[') + 1, -1);
	}
	$value = isset($data[$xid]) ? $data[$xid] : '';

	$select  = '<select name="' . $name . '">';
	$opsi = is_array($params['data']) ? $params['data'] : select($params['data']);
	foreach ($opsi as $k) {
		$select .= '<option value="' . $k['value'] . '" '
			. ($k['value'] == $value ? 'selected="selected"' : '')
			. '>' . $k['name'] . '</option>';
	}
	$select .= '</select>';
	$string = '
	<div style="margin-top:10px">
		<label>%s</label>
		%s
	</div>
	';
	return sprintf($string, $label, $select);
}

function sanitizeInput($value)
{
	return htmlspecialchars($value, ENT_QUOTES);
}
