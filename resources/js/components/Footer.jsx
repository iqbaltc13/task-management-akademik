import { Link } from '@inertiajs/react';
import {
  Container,
  SimpleGrid,
  Group,
  Stack,
  Text,
  Title,
  Anchor,
  Divider,
  ActionIcon,
} from '@mantine/core';
import {
  IconBrandTwitter,
  IconBrandFacebook,
  IconBrandInstagram,
  IconBrandYoutube,
  IconArrowUp,
} from '@tabler/icons-react';

const infoLainnya = [
  { label: 'Tentang Kami', href: '/tentang-kami' },
  { label: 'FAQ', href: '/faq' },
  { label: 'News', href: '/news' },
];

export default function Footer() {
  const scrollToTop = () => window.scrollTo({ top: 0, behavior: 'smooth' });

  return (
    <Stack
      gap={0}
      mt={80}
      style={{ backgroundColor: 'var(--mantine-color-dark-8)' }}
    >
      <Container size="xl" py={60}>
        <SimpleGrid cols={{ base: 1, sm: 3 }} spacing={50}>
          {/* Kolom 1: Logo + deskripsi */}
          <Stack align="center" ta="center" gap="xs">
            <img
            src="https://akademik.iainkediri.ac.id/assets/uploads/setting/a72bc10b40b86bd77122c05ca88e9ff9.png"
            alt="Logo"
            width={81}
            height={100}
            />
            <Title order={4} c="white" mt="sm">Tentang Kami</Title>
            <Text size="sm" c="dimmed">
              Menghasilkan Sarjana berkualitas dan professional dalam bidang
              pendidikan Bahasa Inggris yang dilandasi keluhuran akhlak serta
              mampu berperan dalam mengembangkan ilmu kebahasaan di masyarakat.
            </Text>
          </Stack>

          {/* Kolom 2: Info Lainnya */}
          <Stack align="center" gap="xs">
            <Title order={4} c="white">Info Lainnya</Title>
            {infoLainnya.map((item) => (
              <Anchor
                key={item.label}
                component={Link}
                href={item.href}
                c="dimmed"
                size="sm"
              >
                {item.label}
              </Anchor>
            ))}
          </Stack>

          {/* Kolom 3: Kontak Kami */}
          <Stack align="center" gap="xs">
            <Title order={4} c="white">Kontak Kami</Title>
            <Text size="sm" c="dimmed">Sunan Ampel</Text>
            <Text size="sm" c="dimmed">akademik@uinkediri.ac.id</Text>
            <Text size="sm" c="dimmed">0354 - 689282</Text>
          </Stack>
        </SimpleGrid>
      </Container>

      <Divider color="dark.5" />

      <Container size="xl" py="md" w="100%">
        <Group justify="space-between">
          <Text size="sm" c="dimmed">
            © {new Date().getFullYear()} Akademik UIN Syekh Wasil Kediri. All rights reserved.
          </Text>

          <Group gap="sm">
            <ActionIcon component="a" href="#" variant="subtle" color="gray" radius="xl">
              <IconBrandTwitter size={18} />
            </ActionIcon>
            <ActionIcon component="a" href="#" variant="subtle" color="gray" radius="xl">
              <IconBrandFacebook size={18} />
            </ActionIcon>
            <ActionIcon component="a" href="#" variant="subtle" color="gray" radius="xl">
              <IconBrandInstagram size={18} />
            </ActionIcon>
            <ActionIcon component="a" href="#" variant="subtle" color="gray" radius="xl">
              <IconBrandYoutube size={18} />
            </ActionIcon>
            <ActionIcon
              onClick={scrollToTop}
              variant="outline"
              color="orange"
              radius="xl"
            >
              <IconArrowUp size={18} />
            </ActionIcon>
          </Group>
        </Group>
      </Container>
    </Stack>
  );
}