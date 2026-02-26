import { Alert } from "@mantine/core";
import {
  IconInfoCircle,
  IconAlertTriangle,
  IconExclamationCircle,
} from "@tabler/icons-react";

export default function LoginNotification({ notify }) {
  return (
    <div style={{ marginTop: "25px" }}>
      {notify === "password-reset" && (
        <Alert radius="md" title="Reset Password Sukses" icon={<IconInfoCircle />}>
          Password Anda berhasil direset, Anda dapat menggunakan password baru tersebut untuk login.
        </Alert>
      )}
      {notify === "social-login-user-not-found" && (
        <Alert
          radius="md"
          title="Login Gagal"
          icon={<IconAlertTriangle />}
          color="orange"
        >
          Tidak ada pengguna yang ditemukan dengan alamat email Google Anda.
        </Alert>
      )}
      {notify === "social-login-failed" && (
        <Alert
          radius="md"
          title="Login Gagal"
          icon={<IconExclamationCircle />}
          color="red"
        >
          Terjadi kesalahan yang tidak terduga, silakan coba login dengan email dan password Anda.
        </Alert>
      )}
    </div>
  );
}
