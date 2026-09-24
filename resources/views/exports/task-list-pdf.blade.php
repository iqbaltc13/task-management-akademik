<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: sans-serif; font-size: 11px; }
  .kop { width: 100%; border-bottom: 2px solid #333; padding-bottom: 8px; margin-bottom: 12px; }
  .kop table { width: 100%; border: none; }
  .kop td { border: none; padding: 0; vertical-align: middle; }
  .kop .logo { width: 70px; }
  .kop .logo img { width: 60px; }
  .kop .institusi { text-align: center; }
  .kop .institusi h1 { font-size: 15px; margin: 0; font-weight: bold; }
  .kop .institusi h2 { font-size: 13px; margin: 2px 0 0; font-weight: bold; }
  .kop .institusi p { font-size: 10px; margin: 4px 0 0; }
  h3 { margin-bottom: 4px; text-align: center; }
  table.data { width: 100%; border-collapse: collapse; margin-top: 12px; }
  table.data th, table.data td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
  table.data th { background: #eee; }
</style>
</head>
<body>
  <div class="kop">
    <table>
      <tr>
        <td class="logo">
          <img src="{{ public_path('images/logo-uinkediri.png') }}">
        </td>
        <td class="institusi">
          <h1>KEMENTERIAN AGAMA REPUBLIK INDONESIA</h1>
          <h2>UNIVERSITAS ISLAM NEGERI SYEKH WASIL KEDIRI</h2>
          <p>Jalan Sunan Ampel Nomor 07 Ngronggo Kota Kediri Kode Pos 64127</p>
          <p>Telepon (0354) 689282 Faksimile (0354) 686564 Website: www.uinkediri.ac.id</p>
        </td>
        <td class="logo"></td>
      </tr>
    </table>
  </div>

  <h3>Daftar Pelayanan</h3>
  <p style="text-align:center; margin-top:-6px;">Dicetak pada {{ now()->format('d M Y H:i') }}</p>

  <table class="data">
    <thead>
      <tr>
        @foreach(($rows->first() ?? []) as $col => $val)
          <th>{{ $col }}</th>
        @endforeach
      </tr>
    </thead>
    <tbody>
      @forelse($rows as $row)
        <tr>
          @foreach($row as $val)
            <td>{{ $val }}</td>
          @endforeach
        </tr>
      @empty
        <tr><td colspan="8">Tidak ada data.</td></tr>
      @endforelse
    </tbody>
  </table>
</body>
</html>