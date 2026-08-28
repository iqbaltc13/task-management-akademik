import { Link } from '@inertiajs/react';
import FlashNotification from "@/components/FlashNotification";
import { Head } from "@inertiajs/react";
import {
  IconBook,
  IconChartPie3,
  IconChevronDown,
  IconCode,
  IconCoin,
  IconFingerprint,
  IconNotification,
} from '@tabler/icons-react';
import {
  Anchor,
  Autocomplete,
  Box,
  Burger,
  Button,
  Center,
  Container,
  Collapse,
  Divider,
  Drawer,
  Group,
  HoverCard,
  ScrollArea,
  SimpleGrid,
  Title,
  Stack,
  Text,
  ThemeIcon,
  UnstyledButton,
  useMantineTheme,
  rem,
} from '@mantine/core';
import { useDisclosure } from '@mantine/hooks';
import { IconSearch } from '@tabler/icons-react';
import classes from './css/HeaderMegaMenu.module.css';

const mockdata = [ /* ...tetap sama... */ ];

export default function GuestLayoutWithHeaderMenu({ title, children }) {
  const [drawerOpened, { toggle: toggleDrawer, close: closeDrawer }] = useDisclosure(false);
  const [linksOpened, { toggle: toggleLinks }] = useDisclosure(false);
  const theme = useMantineTheme();

  const links = mockdata.map((item) => (
    <UnstyledButton className={classes.subLink} key={item.title}>
      <Group wrap="nowrap" align="flex-start">
        <ThemeIcon size={34} variant="default" radius="md">
          <item.icon size={22} color={theme.colors.blue[6]} />
        </ThemeIcon>
        <div>
          <Text size="sm" fw={500}>{item.title}</Text>
          <Text size="xs" c="dimmed">{item.description}</Text>
        </div>
      </Group>
    </UnstyledButton>
  ));

  return (
    <>
      <Head title={title} />
      <FlashNotification />
      <header className={classes.header}>
        <Group justify="space-between" h="100%">
          <Text fw={700}>Worklane Akademik</Text>
          <Group h="100%" gap={0} visibleFrom="sm">
            <Link href="/" className={classes.link}>Home</Link>
          </Group>
          <Burger opened={drawerOpened} onClick={toggleDrawer} hiddenFrom="sm" />
        </Group>
      </header>

      <main>{children}</main>
    </>
  );
}