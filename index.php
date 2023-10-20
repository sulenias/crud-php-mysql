<?php

// Koneksi Database
$server = "localhost";
$user = "root";
$password = "";
$database = "dbcrud2022";

// buat koneksi
$koneksi = mysqli_connect($server, $user, $password, $database) or die(mysqli_error($koneksi));

// kode ototmatis
$q = mysqli_query($koneksi,"SELECT kode FROM tbarang order by kode desc limit 1 ");
$datax = mysqli_fetch_array($q);
if($datax)
{
  $no_terakhir = substr($datax['kode'], -3);
  $no = $no_terakhir + 1;
  
  if($no > 0 and $no < 10)
  {
    $kode = "00".$no;
  }else if($no > 10 and $no < 100)
  {
    $kode = "0".$no;
  }else if($no > 100)
  {
    $kode = $no;
  }
}else{
  $kode = "001";
}

$tahun = date('Y');
$vkode = "INV-" . $tahun .'-' . $kode;
// INV-2023-001

// jika tombol simpan diklik / simpan data ke database
if (isset($_POST['bsimpan'])) {

  // pengujian apakah data akan diedit atau disimpan baru
  if (isset($GET['hal']) == "edit") {
    // data akan diedit
    $edit = mysqli_query($koneksi, "UPDATE tbarang SET
                                      nama = '$_POST[nama]',
                                      asal = '$_POST[tasal]',
                                      jumlah = '$_POST[tjumlah]',
                                      satuan = '$_POST[tsatuan]',
                                      tanggal_diterima = '$_POST[ttanggal_diterima]'
                                    WHERE id_barang = '$_GET[id]'
                                    ");
    // uji jika simpan data sukses
    if ($edit) {
      echo "<script>
                alert('Edit data Sukses!');
                document.location='index.php';
              </script>";
    } else {
      echo "<script>
                alert('Edit data Gagal!');
                document.location='index.php';
              </script>";
    }
  } else {
    // data akan disimpan baru
    $simpan = mysqli_query($koneksi, "INSERT INTO tbarang (kode, nama, asal, jumlah, satuan, tanggal_diterima)
                                      VALUE ( '$_POST[tkode]',
                                              '$_POST[tnama]',  
                                              '$_POST[tasal]',  
                                              '$_POST[tjumlah]',  
                                              '$_POST[tsatuan]',  
                                              '$_POST[ttanggal_diterima]' )
                                          ");

    // uji jika simpan data sukses
    if ($simpan) {
      echo "<script>
          alert('Simpan data Sukses!');
          document.location='index.php';
        </script>";
    } else {
      echo "<script>
          alert('Simpan data Gagal!');
          document.location='index.php';
        </script>";
    }
  }
}


// deklarasi variabel untuk menampung data yang akan diedit
$vnama = "";
$vasal = "";
$vjumlah = "";
$vsatuan = "";
$vtanggal_diterima = "";


// pengujian jika tombol edit / hapus diklik
if (isset($_GET['hal'])) {

  // pengujian jika edit data
  if ($_GET['hal'] == "edit") {
    // tampilkan data yang akan diedit
    $tampil = mysqli_query($koneksi, "SELECT * FROM tbarang WHERE id_barang = '$_GET[id]' ");
    $data = mysqli_fetch_array($tampil);
    if ($data) {
      // jika data ditemukan, maka data ditampung ke dalam variabel
      $vkode = $data['kode'];
      $vnama = $data['nama'];
      $vasal = $data['asal'];
      $vjumlah = $data['jumlah'];
      $vsatuan = $data['satuan'];
      $vtanggal_diterima = $data['tanggal_diterima'];
    }
  } else if ($_GET['hal'] == "hapus") 
  {
    // persiapan hapus data
    $hapus = mysqli_query($koneksi, "DELETE FROM tbarang WHERE id_barang = '$_GET[id]' ");

    // uji jika hapus data sukses
    if ($hapus) {
      echo "<script>
          alert('Hapus data Sukses!');
          document.location='index.php';
        </script>";
    } else {
      echo "<script>
          alert('Hapus data Gagal!');
          document.location='index.php';
        </script>";
    }
  }
}





?>




<!doctype html>
<html lang="en">

<head>
  <meta charset="utf-8">
  <meta name="viewport" content="width=device-width, initial-scale=1">
  <title>CRUD PHP & MySQL + bootstrap 5.x</title>
  <link href="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/css/bootstrap.min.css" rel="stylesheet" integrity="sha384-T3c6CoIi6uLrA9TneNEoa7RxnatzjcDSCmG1MXxSR1GAsXEV/Dwwykc2MPK8M2HN" crossorigin="anonymous">
</head>

<body>

  <!-- awal container -->
  <div class="container py-4">

    <h2 class="text-center fw-bolder">Data Inventaris</h2>
    <p class="text-center">👨‍💻Kantor NgodingPintar</p>

    <!-- awal row -->
    <div class="row">
      <!-- awal col -->
      <div class="col-md-8 mx-auto">

        <!-- awal card -->
        <div class="card mt-4">
          <!-- awal card header -->
          <div class="card-header bg-info text-light fw-bold">
            Form Input Data Barang
          </div>
          <!-- akhir card header -->

          <!-- awal card body -->
          <div class="card-body">
            <!-- awal form -->
            <form method="POST">
              <div class="mb-3">
                <label class="form-label">Kode Barang</label>
                <input type="text" name="tkode" value="<?= $vkode ?>" class="form-control" placeholder="Input kode barang">
              </div>

              <div class="mb-3">
                <label class="form-label">Nama Barang</label>
                <input type="text" name="tnama" value="<?= $vnama ?>" class="form-control" placeholder="Input nama barang">
              </div>

              <div class="mb-3">
                <label class="form-label">Asal Barang</label>
                <select class="form-select" name="tasal">
                  <option value="<?= $vasal ?>"><?= $vasal ?></option>
                  <option value="Pembelian">Pembelian</option>
                  <option value="Hibah">Hibah</option>
                  <option value="Sumbangan">Sumbangan</option>
                  <option value="Bantuan">Bantuan</option>
                </select>
              </div>


              <div class="row">

                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Jumlah</label>
                    <input type="number" name="tjumlah" value="<?= $vjumlah ?>" class="form-control" placeholder="Input jumlah barang">
                  </div>
                </div>

                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Satuan</label>
                    <select class="form-select" name="tsatuan">
                      <option value="<?= $vsatuan ?>"><?= $vsatuan ?></option>
                      <option value="Unit">Unit</option>
                      <option value="Kotak">Kotak</option>
                      <option value="Pcs">Pcs</option>
                      <option value="Pak">Pak</option>
                    </select>
                  </div>
                </div>

                <div class="col">
                  <div class="mb-3">
                    <label class="form-label">Tanggal diterima</label>
                    <input type="date" name="ttanggal_diterima" value="<?= $vtanggal_diterima ?>" class="form-control" placeholder="Input jumlah barang">
                  </div>
                </div>

                <div class="text-center">
                  <hr>
                  <button class="btn btn-primary" name="bsimpan" type="submit">Simpan</button>
                  <button class="btn btn-danger" name="bkosongkan" type="reset">Kosongkan</button>
                </div>

              </div>

            </form>
            <!-- akhir form -->
          </div>
          <!-- akhir card body -->

          <!-- awal card footer -->
          <div class="card-footer text-body-secondary">

          </div>
          <!-- akhir card footer -->

        </div>
        <!-- akhir card -->

      </div>
      <!-- akhir col -->
    </div>
    <!-- akhir row -->



    <!-- awal card -->
    <div class="card mt-4">
      <!-- awal card header -->
      <div class="card-header bg-info text-light fw-bold">
        Data Barang
      </div>
      <!-- akhir card header -->

      <!-- awal card body -->
      <div class="card-body">
        <div class="col-md-6 mx-auto">
          <form method="POST">
            <div class="input-group mb-3">
              <input type="text" name="tcari" value="<?= @$_POST['tcari'] ?>" class="form-control" placeholder="Masukan kata kunci">
              <button class="btn btn-primary" name="bcari" type="submit">Cari</button>
              <button class="btn btn-danger" name="breset" type="submit">Reset</button>
            </div>
          </form>
        </div>
        <table class="table table-striped table-hover table-bordered">
          <tr class="text-center">
            <th>No.</th>
            <th>Kode Barang</th>
            <th>Nama Barang</th>
            <th>Asal Barang</th>
            <th>Jumlah</th>
            <th>Tanggal diterima</th>
            <th>Aksi</th>
          </tr>

          <?php
          // menampilkan data dari database
          $no = 1;

          // UNTUK PENCARIAN DATA
          // jika tombol cari diklik
          if(isset($_POST['bcari']))
          {
            // tampilkan data yang dicari
            $keyword = $_POST['tcari'];
            $q = "SELECT * FROM tbarang WHERE kode like '%$keyword%' or nama like '%$keyword%'  or asal like '%$keyword%' order by id_barang desc ";
          } else {
            $q = "SELECT * FROM tbarang order by id_barang desc ";
          }

          $tampil = mysqli_query($koneksi, $q);
          while ($data = mysqli_fetch_array($tampil)) :
          ?>
            <tr>
              <td><?= $no++ ?></td>
              <td><?= $data['kode'] ?></td>
              <td><?= $data['nama']  ?></td>
              <td><?= $data['asal']  ?></td>
              <td><?= $data['jumlah']  ?> <?= $data['satuan'] ?></td>
              <td><?= $data['tanggal_diterima']  ?></td>
              <td class="text-center">

                <a href="index.php?hal=edit&id=<?= $data['id_barang'] ?>" class="btn btn-warning pb-sm-2">Edit</a>

                <a href="index.php?hal=hapus&id=<?= $data['id_barang'] ?>" class="btn btn-danger mt-md-2 mt-lg-0 mt-sm-2" onclick="return confirm('Apakah anda yakin akan menghapus Data ini?')">Hapus</a>

              </td>
            </tr>
          <?php endwhile; ?>

        </table>
      </div>
      <!-- akhir card body -->

      <!-- awal card footer -->
      <div class="card-footer text-body-secondary">

      </div>
      <!-- akhir card footer -->

    </div>
    <!-- akhir card -->

  </div>
  <!-- akhir container -->






  <script src="https://cdn.jsdelivr.net/npm/bootstrap@5.3.2/dist/js/bootstrap.bundle.min.js" integrity="sha384-C6RzsynM9kWDrMNeT87bh95OGNyZPhcTNXj1NW7RuBCsyN/o0jlpcV8Qyq46cDfL" crossorigin="anonymous"></script>
</body>

</html>