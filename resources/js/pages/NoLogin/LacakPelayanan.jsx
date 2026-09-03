import { useState } from 'react';
import { TextInput, Button, Stepper, Group, Paper, Title } from '@mantine/core';
import GuestLayoutWithHeaderMenu from '@/Layouts/GuestLayoutWithHeaderMenu';

// urutan status harus sama dengan urutan Stepper.Step di bawah
const STATUS_STEPS = ['diterima', 'diproses', 'selesai'];

export default function LacakLayanan() {
  const [nomor, setNomor] = useState('');
  const [tracking, setTracking] = useState(null); // null = belum submit sama sekali
  const [loading, setLoading] = useState(false);

  const handleLacak = async () => {
    if (!nomor) return;
    setLoading(true);
    try {
      // Ganti bagian ini dengan request ke backend Laravel kamu, contoh:
      // const res = await axios.get(`/api/pelayanan/lacak/${nomor}`);
      // const data = res.data;

      // dummy sementara, hapus setelah endpoint asli siap:
      const data = { status: 'diproses' };

      setTracking(data);
    } finally {
      setLoading(false);
    }
  };

  // index step aktif, -1 kalau belum ada data (Stepper jadi semua inactive)
  const activeStep = tracking ? STATUS_STEPS.indexOf(tracking.status) : -1;

  return (
    <GuestLayoutWithHeaderMenu title="Lacak Pelayanan">
      <Paper maw={600} mx="auto" mt={50} p="lg" withBorder radius="md">
        <Title order={3} mb="md">Lacak Status Pelayanan</Title>

        <TextInput
          size="lg"
          placeholder="Masukkan nomor pelayanan/permintaan"
          value={nomor}
          onChange={(e) => setNomor(e.currentTarget.value)}
        />

        <Group justify="flex-end" mt="md">
          <Button size="md" loading={loading} onClick={handleLacak}>
            Lacak
          </Button>
        </Group>

        {tracking && (
          <Stepper active={activeStep} mt={40} allowNextStepsSelect={false}>
            <Stepper.Step label="Diterima" description="Permintaan diterima" />
            <Stepper.Step label="Diproses" description="Sedang diproses" />
            <Stepper.Step label="Selesai" description="Pelayanan selesai" />
          </Stepper>
        )}
      </Paper>
    </GuestLayoutWithHeaderMenu>
  );
}