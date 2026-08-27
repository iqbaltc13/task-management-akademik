import { useState } from 'react';
import GuestLayoutWithHeaderMenu from '@/layouts/GuestLayoutWithHeaderMenu';
import {
  IconBook,
  IconCalendarStats,
  IconChartPie3,
  IconChevronDown,
  IconCode,
  IconCoin,
  IconDeviceDesktopAnalytics,
  IconFingerprint,
  IconGauge,
  IconHome2,
  IconNotification,
  IconSettings,
  IconUser,
} from '@tabler/icons-react';
import { Anchor, Box, Burger,  Button,
  Center,Container, Divider, Drawer, Group, HoverCard,
  ScrollArea, SimpleGrid, ScrollAreaTitle,  Text, Tooltip, UnstyledButton, useMantineTheme } from '@mantine/core';

import { useDisclosure } from '@mantine/hooks';

import classes from "./css/HeaderMegaMenu.module.css";

const mainLinksMockdata = [
  { icon: IconHome2, label: 'Home' },
  { icon: IconGauge, label: 'Dashboard' },
  { icon: IconDeviceDesktopAnalytics, label: 'Analytics' },
  { icon: IconCalendarStats, label: 'Releases' },
  { icon: IconUser, label: 'Account' },
  { icon: IconFingerprint, label: 'Security' },
  { icon: IconSettings, label: 'Settings' },
];


const linksMockdata = [
  'Security',
  'Settings',
  'Dashboard',
  'Releases',
  'Account',
  'Orders',
  'Clients',
  'Databases',
  'Pull Requests',
  'Open Issues',
  'Wiki pages',
];

const mockdata = [
  {
    icon: IconCode,
    title: 'Open source',
    description: 'This Pokémon’s cry is very loud and distracting',
  },
  {
    icon: IconCoin,
    title: 'Free for everyone',
    description: 'The fluid of Smeargle’s tail secretions changes',
  },
  {
    icon: IconBook,
    title: 'Documentation',
    description: 'Yanma is capable of seeing 360 degrees without',
  },
  {
    icon: IconFingerprint,
    title: 'Security',
    description: 'The shell’s rounded shape and the grooves on its.',
  },
  {
    icon: IconChartPie3,
    title: 'Analytics',
    description: 'This Pokémon uses its flying ability to quickly chase',
  },
  {
    icon: IconNotification,
    title: 'Notifications',
    description: 'Combusken battles with the intensely hot flames it spews',
  },
]

const CreateTaskn= ({}) => {
 return ();
}

CreateTask.layout = (page) => <GuestLayoutWithHeaderMenu title="Create Task" children="" >{page}</GuestLayoutWithHeaderMenu>;
export default Login;