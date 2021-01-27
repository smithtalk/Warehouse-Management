<?php
session_start();
include_once('../library/db_func.php');

if (!isset($_SESSION['name'])) header('Location: login.php');
$data = select('barang');

if (isset($_POST)) {
    if (isset($_POST['logout'])) {
        session_destroy();
        header('Location: index.php');
    }


    $id_barang = $_POST['id_barang'];
    if (isset($_POST['hapus'])) {
        hapus('barang', 'id_barang=' . sanitizeInput($id_barang));
        header('Location: index.php');
        exit;
    }

    if (isset($_POST['edit'])) {
        $editdata = select('barang', 'id_barang=' . sanitizeInput($id_barang));
    }

    if (isset($_POST['tambah'])) {

        $submitdata = [];
        foreach ($_POST['data'] as  $key => $value) {
            $submitdata[$key] = sanitizeInput($value);
        }

        save('barang', $submitdata, ($submitdata['id_barang'] ? 'id_barang=' . $submitdata['id_barang'] : ''));
        header('Location: index.php');
        exit;
    }
}
?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wehaus!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link href='https://cdn.jsdelivr.net/npm/css.gg/icons/all.css' rel='stylesheet'>
    <link rel="stylesheet" href="../public/css/style.css">
</head>

<body>
    <nav class="navbar p-3" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="">
                    <span class="is-size-3" style="font-weight: 700;">Werhaus<span class="has-text-warning">.</span> Dashboard</span>
                </a>

                <a role="button" class="navbar-burger" aria-label="menu" aria-expanded="false" data-target="navbarBasicExample">
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                    <span aria-hidden="true"></span>
                </a>
            </div>

            <div id="navbarBasicExample" class="navbar-menu">
                <div class="navbar-end">
                    <div class="navbar-item">
                        <div class="buttons">
                            <small>Selamat Datang, <?= $_SESSION['name'] ?> | <form class="is-inline-block" action="" method="POST">
                                    <button name="logout" style="border: 0; padding: 5px 10px; border-radius: 3px" class="has-background-warning has-text-white">Logout</button>
                                </form></small>
                        </div>
                    </div>
                </div>
            </div>
        </div>
    </nav>

    <div class="container">
        <div class="columns">
            <div class="column">
                <table class="table my-5">
                    <thead>
                        <tr>
                            <th>No</th>
                            <th>Nama Barang</th>
                            <th>Harga Barang</th>
                            <th>Stok</th>
                            <th>Status</th>
                        </tr>
                    </thead>
                    <tbody>
                        <?php if (count($data)) : ?>
                            <?php foreach ($data as $key => $value) : ?>
                                <form action="" method="POST">
                                    <tr>
                                        <td><?= $key + 1 ?></td>
                                        <td><?= $value['nama'] ?></td>
                                        <td>Rp. <?= $value['harga'] ?></td>
                                        <td><?= $value['stok'] ?></td>
                                        <td>
                                            <input type="hidden" name="id_barang" value="<?= $value['id_barang'] ?>">
                                            <button name="edit" class="tag is-warning has-text-white" style="border: 0;">✎</button>
                                            <button name="hapus" class="tag is-danger" style="border: 0;">✖</button>
                                        </td>
                                    </tr>
                                </form>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="has-text-centered">Belum ada barang yang diinputkan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="column right-form">
                <form action="" method="POST">
                    <div class="field">
                        <label class="label">Nama Barang</label>
                        <?= !empty($editdata) ? '<input type="hidden" name="data[id_barang]" value="' . $editdata[0]['id_barang'] . '">' : '' ?>
                        <div class="control">
                            <input class="input" type="text" name="data[nama]" <?= !empty($editdata) ? 'value="' . $editdata[0]['nama'] . '"' : '' ?>>
                        </div>
                        <p class="help">Nama barang hanya abjad yang diterima</p>
                    </div>
                    <div class="field">
                        <label class="label">Harga Barang</label>
                        <div class="control">
                            <input class="input" type="text" name="data[harga]" <?= !empty($editdata) ? 'value="' . $editdata[0]['harga'] . '"' : '' ?>>
                        </div>
                        <p class="help">Harga barang hanya angka yang diterima</p>
                    </div>
                    <div class="field">
                        <label class="label">Stok</label>
                        <div class="control">
                            <input class="input" type="number" name="data[stok]" <?= !empty($editdata) ? 'value="' . $editdata[0]['stok'] . '"' : '' ?>>
                        </div>
                        <p class="help">Jumlah stok yang ingin dimasukkan</p>
                    </div>
                    <div class="field">
                        <button name="tambah" class="button is-warning has-text-white"><i class="mr-2 gg-add-r"></i> Tambah</button>
                    </div>
                </form>
            </div>
        </div>
    </div>
</body>

</html>

<?php



?>