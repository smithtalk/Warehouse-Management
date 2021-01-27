<?php

include_once('./library/db_func.php');
$data = select('barang');

?>

<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>Wehaus!</title>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bulma@0.9.1/css/bulma.min.css">
    <link href='https://cdn.jsdelivr.net/npm/css.gg/icons/all.css' rel='stylesheet'>
    <link rel="stylesheet" href="public/css/style.css">
</head>

<body>
    <nav class="navbar p-3" role="navigation" aria-label="main navigation">
        <div class="container">
            <div class="navbar-brand">
                <a class="navbar-item" href="">
                    <span class="is-size-3" style="font-weight: 700;">Werhaus<span class="has-text-warning">.</span></span>
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
                            <a class="button is-warning has-text-white" href="admin">
                                Log in
                            </a>
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
                    <tbody id="table-body">
                        <?php if (count($data) > 0) : ?>
                            <?php foreach ($data as $key => $value) : ?>
                                <tr>
                                    <td><?= $key + 1 ?></td>
                                    <td><?= $value['nama'] ?></td>
                                    <td>Rp. <?= $value['harga'] ?></td>
                                    <td><?= $value['stok'] ?></td>
                                    <td>
                                        <?= $value['stok'] <= 0 ? '<span class="tag is-danger">Habis</span>' : '<span class="tag is-success">Ready</span>' ?>
                                    </td>
                                </tr>
                            <?php endforeach; ?>
                        <?php else : ?>
                            <tr>
                                <td colspan="5" class="has-text-centered">Belum ada barang yang diinputkan</td>
                            </tr>
                        <?php endif; ?>
                    </tbody>
                </table>
            </div>
            <div class="column right-form index-r-f">
                <div class="field">
                    <label class="label">Cari Barang</label>
                    <div class="control">
                        <input class="input" id="cari" name="username" type="text" placeholder="">
                    </div>
                </div>
            </div>
        </div>
    </div>

    <script src="https://code.jquery.com/jquery-3.5.1.min.js" integrity="sha256-9/aliU8dGd2tb6OSsuzixeV4y/faTqgFtohetphbbj0=" crossorigin="anonymous"></script>
    <script>
        $('#cari').keyup((e) => {
            let keywords = e.target.value;
            if (keywords !== '') {
                $.ajax({
                    type: 'POST',
                    url: 'ajax.php',
                    data: {
                        q: keywords
                    },
                    success: function(html) {
                        $('#table-body').html(html).show();
                    }
                })
            }
        })
    </script>
</body>

</html>