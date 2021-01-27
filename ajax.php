<?php

include_once('./library/db_func.php');

if (isset($_POST['q'])) {
    $keywords = htmlspecialchars($_POST['q'], ENT_QUOTES);

    if ($keywords == '') $data = select('barang');
    else $data = select('barang', "nama LIKE '" . $keywords . "%'");


    if (!empty($data)) {
        foreach ($data as $key => $value) {
?>

            <tr>
                <td><?= $key + 1 ?></td>
                <td><?= $value['nama'] ?></td>
                <td><?= $value['harga'] ?></td>
                <td><?= $value['stok'] ?></td>
                <td>
                    <?= $value['stok'] <= 0 ? '<span class="tag is-danger">Habis</span>' : '<span class="tag is-success">Ready</span>' ?>
                </td>
            </tr>

<?php
        }
    } else {
        echo '<td colspan="5" class="has-text-centered">Tidak ada data yang ditemukan</td>';
    }
}
?>