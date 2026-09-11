import { useState } from 'react';
import { TextInput, Button, Stepper, Group, Paper, Title, Text, Stack } from '@mantine/core';
import { IconX } from '@tabler/icons-react';
import axios from 'axios';
import GuestLayoutWithHeaderMenu from '@/layouts/GuestLayoutWithHeaderMenu';

const STATUS_STEPS = ['diterima', 'diproses', 'selesai'];

export default function LacakLayanan() {
  const [nomor, setNomor] = useState('');
  const [tracking, setTracking] = useState(null);
  const [loading, setLoading] = useState(false);
  const [error, setError] = useState(null);

  const handleLacak = async () => {
    if (!nomor) return;
    setLoading(true);
    setError(null);
    try {
      const res = await axios.post(route('lacak-pelayanan'), { code: nomor });
      setTracking(res.data);
    } catch (e) {
      setTracking(null);
      setError(e.response?.data?.message || 'Terjadi kesalahan, coba lagi.');
    } finally {
      setLoading(false);
    }
  };

  const handleDownload = () => {
    if (!tracking?.link_file_result) return;
    window.location.href = tracking.link_file_result;
  };

  const isDitolak = tracking?.status === 'ditolak';
  const activeStep = tracking
    ? (isDitolak ? 2 : STATUS_STEPS.indexOf(tracking.status))
    : -1;
  const showDownload = tracking && (tracking.status === 'selesai' || tracking.status === 'ditolak');

  return (
    <GuestLayoutWithHeaderMenu title="Lacak Pelayanan">
      <Paper maw={900} mx="auto" mt={50} p="lg" withBorder radius="md">
        <Title order={3} mb="md">Lacak Status Pelayanan</Title>

        <TextInput
          size="lg"
          placeholder="Masukkan nomor pelayanan/permintaan"
          value={nomor}
          onChange={(e) => setNomor(e.currentTarget.value)}
          error={error}
        />

        <Group justify="flex-end" mt="md">
          <Button size="md" loading={loading} onClick={handleLacak}>
            Lacak
          </Button>
        </Group>

        {tracking && (
          <Stack mt={40} gap="lg">
            <Stepper active={activeStep} allowNextStepsSelect={false} color={isDitolak ? 'red' : 'blue'}>
              <Stepper.Step label="Diterima" description="Permintaan diterima" />
              <Stepper.Step label="Diproses" description="Sedang diproses" />
              <Stepper.Step
                label={isDitolak ? 'Ditolak' : 'Selesai'}
                description={isDitolak ? 'Permintaan ditolak' : 'Pelayanan selesai'}
                color={isDitolak ? 'red' : undefined}
                completedIcon={isDitolak ? <IconX size={18} /> : undefined}
              />
            </Stepper>

            <div>
              <Text fw={500} size="sm" mb={4}>Keterangan</Text>
              <Text size="sm" c="dimmed">
                {tracking.final_feedback || 'Belum ada keterangan untuk permintaan ini.'}
              </Text>
            </div>

            {showDownload && (
              <Button
                fullWidth
                size="md"
                variant="light"
                disabled={!tracking.link_file_result}
                onClick={handleDownload}
              >
                Download Berkas
              </Button>
            )}
          </Stack>
        )}
      </Paper>
    </GuestLayoutWithHeaderMenu>
  );
}