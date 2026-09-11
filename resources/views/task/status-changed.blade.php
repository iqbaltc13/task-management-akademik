<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1.0">
</head>
<body style="margin:0; padding:0; background-color:#141517; font-family: -apple-system, BlinkMacSystemFont, 'Segoe UI', Roboto, Helvetica, Arial, sans-serif;">
<table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="background-color:#141517; padding: 32px 16px;">
  <tr>
    <td align="center">
      <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="max-width: 480px; background-color:#1A1B1E; border-radius: 12px; overflow: hidden; border: 1px solid #2C2E33;">

        <tr>
          <td style="padding: 28px 32px 20px; border-bottom: 1px solid #2C2E33;">
            <table role="presentation" width="100%" cellpadding="0" cellspacing="0">
              <tr>
                <td>
                  <table role="presentation" cellpadding="0" cellspacing="0">
                    <tr>
                      <td style="width: 32px; height: 32px; background-color:#228BE6; border-radius: 8px; text-align:center; vertical-align:middle;">
                        <span style="color:#ffffff; font-weight:700; font-size:14px;">LA</span>
                      </td>
                      <td style="padding-left: 10px; color:#C1C2C5; font-size:15px; font-weight:600;">
                        LSP Akademik
                      </td>
                    </tr>
                  </table>
                </td>
              </tr>
            </table>
          </td>
        </tr>

        <tr>
          <td style="padding: 32px;">
            <p style="margin:0 0 4px; color:#909296; font-size:13px;">
                Status permintaan diperbarui &middot;
                <span style="color:#74C0FC; font-weight:600;">#{{ $task->code }}</span>
            </p>
            <h1 style="margin:0 0 24px; color:#C1C2C5; font-size:19px; font-weight:600; line-height:1.4;">
              {{ $task->name }}
            </h1>

            <table role="presentation" width="100%" cellpadding="0" cellspacing="0" style="margin-bottom: 8px;">
              <tr>
                @if($oldGroup)
                <td align="center" style="width: 42%;">
                  <span style="display:inline-block; background-color:#25262B; color:#909296; font-size:13px; font-weight:600; padding: 8px 14px; border-radius: 999px; border: 1px solid #373A40;">
                    {{ $oldGroup->name }}
                  </span>
                </td>
                <td align="center" style="width: 16%; color:#5C5F66; font-size:16px;">&rarr;</td>
                @endif
                <td align="center" style="width: {{ $oldGroup ? '42%' : '100%' }};">
                  <span style="display:inline-block; background-color:#0C2A45; color:#74C0FC; font-size:13px; font-weight:600; padding: 8px 14px; border-radius: 999px; border: 1px solid #1971C2;">
                    {{ $newGroup->name }}
                  </span>
                </td>
              </tr>
            </table>

            <p style="margin: 24px 0 0; color:#909296; font-size:14px; line-height:1.6;">
              @if($oldGroup)
                Status permintaan kamu telah berubah dari <strong style="color:#C1C2C5;">{{ $oldGroup->name }}</strong> menjadi <strong style="color:#74C0FC;">{{ $newGroup->name }}</strong>.
              @else
                Permintaan kamu telah diterima dan berada pada status <strong style="color:#74C0FC;">{{ $newGroup->name }}</strong>.
              @endif
            </p>
          </td>
        </tr>

        <tr>
          <td style="padding: 20px 32px; border-top: 1px solid #2C2E33;">
            <p style="margin:0; color:#5C5F66; font-size:12px; line-height:1.6;">
              Email ini dikirim otomatis oleh sistem LSP Akademik. Mohon tidak membalas email ini.
            </p>
          </td>
        </tr>

      </table>
    </td>
  </tr>
</table>
</body>
</html>