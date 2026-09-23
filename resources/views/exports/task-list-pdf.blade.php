<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<style>
  body { font-family: sans-serif; font-size: 11px; }
  h2 { margin-bottom: 4px; }
  table { width: 100%; border-collapse: collapse; margin-top: 12px; }
  th, td { border: 1px solid #999; padding: 6px 8px; text-align: left; }
  th { background: #eee; }
</style>
</head>
<body>
  <h2>Daftar Pelayanan</h2>
  <p>Dicetak pada {{ now()->format('d M Y H:i') }}</p>
  <table>
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